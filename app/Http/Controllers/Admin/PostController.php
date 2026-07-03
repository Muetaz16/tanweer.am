<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
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
            ->with('user')
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

        Post::create($data);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'تم إنشاء المحتوى بنجاح.');
    }

    public function edit(Post $post): View
    {
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

        $post->update($data);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'تم تحديث المحتوى.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }
        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'تم حذف المحتوى.');
    }

    private function validated(Request $request, ?Post $existing = null): array
    {
        $types = implode(',', array_keys(Post::types()));

        $rules = [
            'type' => ['required', 'in:'.$types],
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:2000'],
            'body' => ['required', 'string'],
            'video_url' => [
                'nullable', 
                'string', 
                'max:2048', 
                'url',
                function ($attribute, $value, $fail) use ($request) {
                    if (in_array($request->input('type'), [Post::TYPE_REELS])) {
                        if (!$value) {
                            $fail('رابط اليوتيوب مطلوب لقسم الريلز.');
                        } elseif (!Post::extractYoutubeId($value)) {
                            $fail('يجب إدخال رابط يوتيوب صحيح (فيديو عادي أو Shorts) لقسم الريلز.');
                        }
                    }
                }
            ],
            'is_published' => ['sometimes', 'boolean'],
            'cover_image' => ['nullable', 'image', 'max:5120'],
            'regenerate_slug' => ['sometimes', 'boolean'],
        ];

        $data = $request->validate($rules, [
            'type.required' => 'نوع المحتوى مطلوب.',
            'title.required' => 'العنوان مطلوب.',
            'body.required' => 'النص مطلوب.',
            'cover_image.image' => 'الملف يجب أن يكون صورة.',
            'cover_image.max' => 'حجم الصورة يتجاوز 5 ميجابايت.',
            'video_url.url' => 'رابط الفيديو غير صالح.',
        ]);

        unset($data['regenerate_slug']);

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
