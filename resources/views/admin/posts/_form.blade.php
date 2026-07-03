@php
    /** @var \App\Models\Post|null $post */
    $post = $post ?? null;
    $types = \App\Models\Post::types();
@endphp

<div class="tw-field">
    <label for="type">القسم</label>
    <select class="tw-select" id="type" name="type" required>
        @foreach ($types as $value => $label)
            <option value="{{ $value }}" @selected(old('type', $post->type ?? 'news') === $value)>{{ $label }}</option>
        @endforeach
    </select>
</div>

<div class="tw-field">
    <label for="title">العنوان</label>
    <input class="tw-input" type="text" id="title" name="title" required maxlength="255"
        value="{{ old('title', $post->title ?? '') }}">
</div>

<div class="tw-field">
    <label for="excerpt">مقدمة / ملخص (اختياري)</label>
    <textarea class="tw-textarea" id="excerpt" name="excerpt" rows="4" style="min-height: 120px;">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
    <p class="tw-help">يظهر في بطاقات الأخبار وصفحات القوائم.</p>
</div>

<div class="tw-field">
    <label for="body">النص الكامل</label>
    <textarea class="tw-textarea" id="body" name="body" required>{{ old('body', $post->body ?? '') }}</textarea>
</div>

<div class="tw-field">
    <label for="video_url">رابط فيديو يوتيوب (يوتيوب عادي أو Shorts)</label>
    <input class="tw-input" type="url" id="video_url" name="video_url"
        value="{{ old('video_url', $post->video_url ?? '') }}" placeholder="https://www.youtube.com/shorts/... أو https://youtu.be/...">
    <p class="tw-help">مطلوب لقسم الريلز. سيتم استخراج الفيديو والصورة المصغرة تلقائياً.</p>
</div>

<div class="tw-field">
    <label for="cover_image">صورة الغلاف (اختياري للريلز)</label>
    <input class="tw-input" type="file" id="cover_image" name="cover_image" accept="image/*">
    <p class="tw-help">صورة JPG أو PNG أو WebP — حتى 5 ميجابايت.</p>
    @if ($post?->thumbnailUrl())
        <p class="tw-help" style="margin-top: 0.5rem;">
            الحالية:
            <a href="{{ $post->thumbnailUrl() }}" target="_blank" rel="noopener">عرض</a>
            @if(!$post->cover_image && $post->youtube_video_id)
            <br>
            <span style="color: var(--tw-gray-500); font-size: 0.85rem;">تم استخراج الصورة من يوتيوب.</span>
            @endif
        </p>
    @endif
</div>

<div class="tw-field">
    <label class="tw-check">
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post->is_published ?? false))>
        نشر على الموقع فور الحفظ
    </label>
</div>

@if ($post)
    <div class="tw-field">
        <label class="tw-check">
            <input type="checkbox" name="regenerate_slug" value="1" @checked(old('regenerate_slug'))>
            إعادة توليد الرابط (slug) من العنوان
        </label>
        <p class="tw-help">الرابط الحالي: <code style="color: var(--tw-green);">{{ $post->slug }}</code></p>
    </div>
@endif
