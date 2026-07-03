@extends('layouts.admin')

@section('title', 'نظرة عامة')

@section('content')
    <div class="tw-toolbar">
        <h2>مرحباً بك في لوحة التحكم</h2>
        <a href="{{ route('admin.posts.create') }}" class="tw-btn tw-btn-primary">إضافة محتوى</a>
    </div>

    <div class="tw-stats">
        @foreach (\App\Models\Post::types() as $type => $label)
            <div class="tw-stat"><strong>{{ $counts[$type] ?? 0 }}</strong><span>{{ $label }}</span></div>
        @endforeach
        <div class="tw-stat"><strong>{{ $counts['published'] }}</strong><span>منشور</span></div>
        <div class="tw-stat"><strong>{{ $counts['draft'] }}</strong><span>مسودات</span></div>
    </div>

    <div class="tw-section-title">
        <h2>آخر التحديثات</h2>
        <a href="{{ route('admin.posts.index') }}" class="tw-btn tw-btn-ghost">كل المحتوى</a>
    </div>

    <div class="tw-table-wrap">
        <table class="tw-table">
            <thead>
                <tr>
                    <th>العنوان</th>
                    <th>النوع</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recent as $post)
                    <tr>
                        <td><a href="{{ route('admin.posts.edit', $post) }}">{{ $post->title }}</a></td>
                        <td><span class="tw-badge">{{ $post->typeLabel() }}</span></td>
                        <td>
                            @if ($post->is_published)
                                <span class="tw-badge tw-badge-ok">منشور</span>
                            @else
                                <span class="tw-badge tw-badge-warn">مسودة</span>
                            @endif
                        </td>
                        <td class="tw-muted">{{ $post->updated_at->translatedFormat('d M Y، H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="tw-muted">لا يوجد محتوى بعد. ابدأ بإضافة خبر أو مقال.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
