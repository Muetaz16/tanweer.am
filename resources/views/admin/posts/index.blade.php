@extends('layouts.admin')

@section('title', 'المحتوى')

@section('content')
    <div class="tw-toolbar">
        <h2>المحتوى</h2>
        <a href="{{ route('admin.posts.create') }}" class="tw-btn tw-btn-primary">إضافة</a>
    </div>

    <div class="tw-filters" style="margin-bottom: 1rem;">
        <a href="{{ route('admin.posts.index') }}" class="{{ ! $type ? 'is-on' : '' }}">الكل</a>
        @foreach (\App\Models\Post::types() as $key => $label)
            <a href="{{ route('admin.posts.index', ['type' => $key]) }}" class="{{ $type === $key ? 'is-on' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="tw-table-wrap">
        <table class="tw-table">
            <thead>
                <tr>
                    <th>العنوان</th>
                    <th>النوع</th>
                    <th>الحالة</th>
                    <th>آخر تحديث</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr>
                        <td>
                            {{ $post->title }}
                            @if($post->images->count() > 0)
                                <span class="tw-badge" style="margin-right: 0.35rem; font-size: 0.7rem; background: var(--tw-green-pale); color: var(--tw-green-dark);">📷 {{ $post->images->count() }}</span>
                            @endif
                        </td>
                        <td><span class="tw-badge">{{ $post->typeLabel() }}</span></td>
                        <td>
                            @if ($post->is_published)
                                <span class="tw-badge tw-badge-ok">منشور</span>
                            @else
                                <span class="tw-badge tw-badge-warn">مسودة</span>
                            @endif
                        </td>
                        <td class="tw-muted">{{ $post->updated_at->translatedFormat('d M Y') }}</td>
                        <td>
                            <div class="tw-row-actions">
                                <a class="tw-btn tw-btn-ghost" href="{{ route('admin.posts.edit', $post) }}">تعديل</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="tw-muted">لا توجد عناصر.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $posts->links('pagination.tanweer') }}
@endsection
