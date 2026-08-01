<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->query('type');

        $posts = Post::query()
            ->with(['user', 'images'])
            ->when(
                $type && array_key_exists($type, Post::types()),
                fn ($q) => $q->where('type', $type)
            )
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.posts.index', compact('posts', 'type'));
    }

    public function create(): View
    {
        return view('admin.posts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Post::makeUniqueSlug($data['title']);
        $data['user_id'] = $request->user()->id;

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        // Handle Investigation Persons
        if ($request->input('type') === Post::TYPE_INVESTIGATION) {
            $data['investigation_persons'] = $this->processInvestigationPersons($request);
        } else {
            $data['investigation_persons'] = null;
        }

        $post = Post::create($data);

        // Upload Multiple Images (Infographics / Reports)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('post_gallery', 'public');
                    $post->images()->create([
                        'image_path' => $path,
                        'sort_order' => $index,
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'تم إنشاء المحتوى بنجاح.');
    }

    public function edit(Post $post): View
    {
        $post->load('images');
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $this->validated($request, $post);

        if ($request->boolean('regenerate_slug')) {
            $data['slug'] = Post::makeUniqueSlug($data['title'], $post->id);
        }

        if ($request->hasFile('cover_image')) {
            if ($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        // Handle Investigation Persons
        if ($request->input('type') === Post::TYPE_INVESTIGATION) {
            $data['investigation_persons'] = $this->processInvestigationPersons($request, $post->investigation_persons);
        } else {
            $data['investigation_persons'] = null;
        }

        $post->update($data);

        // 1. Delete requested images
        if ($request->filled('delete_images') && is_array($request->delete_images)) {
            $imagesToDelete = $post->images()->whereIn('id', $request->delete_images)->get();
            foreach ($imagesToDelete as $img) {
                Storage::disk('public')->delete($img->image_path);
                $img->delete();
            }
        }

        // 2. Update order of existing images
        if ($request->filled('image_order') && is_array($request->image_order)) {
            foreach ($request->image_order as $imageId => $order) {
                $post->images()->where('id', $imageId)->update([
                    'sort_order' => (int) $order
                ]);
            }
        }

        // 3. Store new uploaded images
        if ($request->hasFile('images')) {
            $maxOrder = $post->images()->max('sort_order');
            $startOrder = $maxOrder !== null ? $maxOrder + 1 : 0;
            foreach ($request->file('images') as $index => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('post_gallery', 'public');
                    $post->images()->create([
                        'image_path' => $path,
                        'sort_order' => $startOrder + $index,
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'تم تحديث المحتوى بنجاح.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }

        foreach ($post->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }

        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'تم حذف المحتوى.');
    }

    private function processInvestigationPersons(Request $request, ?array $existingPersons = null): ?array
    {
        $personsInput = $request->input('persons');

        if (!is_array($personsInput) || empty($personsInput)) {
            return null;
        }

        $processed = [];

        foreach ($personsInput as $index => $p) {
            if (!is_array($p)) continue;

            $name = trim($p['name'] ?? '');
            $title = trim($p['title'] ?? '');
            $bio = trim($p['bio'] ?? '');
            $body = trim($p['body'] ?? '');
            $url = trim($p['external_url'] ?? '');
            $imagePath = $p['existing_image'] ?? null;

            if ($request->hasFile("person_image_{$index}")) {
                $file = $request->file("person_image_{$index}");
                if ($file && $file->isValid()) {
                    $imagePath = $file->store('persons', 'public');
                }
            }

            if ($name !== '' || $body !== '' || $imagePath !== null || $bio !== '') {
                $processed[] = [
                    'name' => $name,
                    'title' => $title,
                    'bio' => $bio,
                    'body' => $body,
                    'image' => $imagePath,
                    'external_url' => $url,
                ];
            }
        }

        return !empty($processed) ? $processed : null;
    }

    private function validated(Request $request, ?Post $existing = null): array
    {
        $types = implode(',', array_keys(Post::types()));
        $type = $request->input('type');

        $rules = [
            'type' => ['required', 'in:'.$types],
            'title' => ['required', 'string', 'max:255'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:2000'],
            'body' => [
                function ($attribute, $value, $fail) use ($type, $request) {
                    if (in_array($type, [Post::TYPE_NEWS, Post::TYPE_REPORTS, Post::TYPE_ARTICLE])) {
                        if (trim((string) $value) === '') {
                            $fail('النص الكامل مطلوب لهذا القسم.');
                        }
                    }
                }
            ],
            'external_url' => ['nullable', 'string', 'max:2048', 'url'],
            'video_url' => [
                'nullable', 
                'string', 
                'max:2048', 
                'url',
                function ($attribute, $value, $fail) use ($type) {
                    if (in_array($type, Post::videoTypes(), true)) {
                        if (!$value) {
                            $fail('رابط الفيديو مطلوب لهذا القسم.');
                        } elseif (!Post::extractYoutubeId($value)) {
                            $fail('يجب إدخال رابط يوتيوب صحيح (فيديو عادي أو Shorts).');
                        }
                    }
                }
            ],
            'is_published' => ['sometimes', 'boolean'],
            'cover_image' => ['nullable', 'image', 'max:5120'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:10240'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer'],
            'image_order' => ['nullable', 'array'],
            'regenerate_slug' => ['sometimes', 'boolean'],
        ];

        $data = $request->validate($rules, [
            'type.required' => 'نوع المحتوى مطلوب.',
            'title.required' => 'العنوان مطلوب.',
            'cover_image.image' => 'الملف يجب أن يكون صورة.',
            'cover_image.max' => 'حجم صورة الغلاف يتجاوز 5 ميجابايت.',
            'images.*.image' => 'يجب رفع صور صحيحة فقط.',
            'images.*.max' => 'حجم كل صورة يجب ألا يتجاوز 10 ميجابايت.',
            'video_url.url' => 'رابط الفيديو غير صالح.',
            'external_url.url' => 'الرابط الخارجي غير صالح.',
        ]);

        unset($data['regenerate_slug'], $data['images'], $data['delete_images'], $data['image_order'], $data['persons']);

        if (!isset($data['body']) || $data['body'] === null) {
            $data['body'] = '';
        }

        $data['is_published'] = $request->boolean('is_published');
        if ($data['is_published']) {
            $data['published_at'] = $existing?->published_at ?? now();
        } else {
            $data['published_at'] = null;
        }

        if (! in_array($data['type'] ?? null, Post::videoTypes(), true)) {
            $data['video_url'] = null;
            $data['youtube_video_id'] = null;
        } else {
            $data['youtube_video_id'] = Post::extractYoutubeId($data['video_url'] ?? null);
        }

        return $data;
    }
}
