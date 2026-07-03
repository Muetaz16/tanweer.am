@extends('layouts.admin')

@section('title', 'إضافة محتوى')

@section('content')
    <div class="tw-toolbar">
        <h2>إضافة محتوى جديد</h2>
        <a href="{{ route('admin.posts.index') }}" class="tw-btn tw-btn-ghost">رجوع</a>
    </div>

    @if ($errors->any())
        <div class="tw-alert tw-alert-error" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form class="tw-form" method="post" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.posts._form', ['post' => null])
        <button type="submit" class="tw-btn tw-btn-primary">حفظ</button>
    </form>
@endsection
