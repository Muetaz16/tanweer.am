<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') — تنوير</title>
    @vite(['resources/css/tanweer.css', 'resources/js/app.js'])
</head>
<body class="tw-body">
    <div class="tw-admin">
        <aside class="tw-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="tw-brand">
                <span class="tw-logo-wrap"><img src="{{ asset('branding/Logo_Icon_Option_01.svg') }}" alt="شعار تنوير" class="tw-logo"></span>
                <div>
                    <h1 class="tw-brand-title">تنوير</h1>
                    <span>لوحة التحكم</span>
                </div>
            </a>

            <nav class="tw-menu" aria-label="قائمة الإدارة">
                @php($route = request()->route()?->getName())
                <a href="{{ route('admin.dashboard') }}" class="{{ str_starts_with((string) $route, 'admin.dashboard') ? 'is-active' : '' }}">نظرة عامة</a>
                <a href="{{ route('admin.posts.index') }}" class="{{ str_starts_with((string) $route, 'admin.posts') ? 'is-active' : '' }}">المحتوى</a>
                <a href="{{ route('home') }}" target="_blank" rel="noopener">عرض الموقع</a>
            </nav>


        </aside>

        <div class="tw-main">
            @if (session('success'))
                <div class="tw-alert" role="status">{{ session('success') }}</div>
            @endif
            @yield('content')
        </div>
    </div>
</body>
</html>
