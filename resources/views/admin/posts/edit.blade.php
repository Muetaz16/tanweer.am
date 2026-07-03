@extends('layouts.admin')

@section('title', 'تعديل: '.$post->title)

@section('content')
    <div class="tw-toolbar">
        <h2>تعديل المحتوى</h2>
        <div class="tw-row-actions">
            @if ($post->is_published && $post->published_at && $post->published_at->lte(now()))
                <a href="{{ route('posts.show', $post->slug) }}" class="tw-btn tw-btn-ghost" target="_blank" rel="noopener">معاينة</a>
            @endif
            <a href="{{ route('admin.posts.index') }}" class="tw-btn tw-btn-ghost">رجوع</a>
        </div>
    </div>

    @if ($errors->any())
        <div class="tw-alert tw-alert-error" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form class="tw-form" method="post" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.posts._form', ['post' => $post])
        <div class="tw-row-actions" style="margin-top: 1rem;">
            <button type="submit" class="tw-btn tw-btn-primary">تحديث</button>
        </div>
    </form>

    <form method="post" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('حذف هذا المحتوى نهائياً؟');" style="margin-top: 2rem;">
        @csrf
        @method('DELETE')
        <button type="submit" class="tw-btn tw-btn-danger">حذف</button>
    </form>
@endsection
