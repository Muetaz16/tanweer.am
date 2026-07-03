<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'تنوير') — صحيفة إلكترونية</title>
    @vite(['resources/css/tanweer.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="tw-body">
    <header class="tw-header">
        <div class="tw-header-top">
            <div class="tw-wrap tw-header-inner-top">
                <div class="tw-social-links">
                    <a href="#" aria-label="Facebook"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M22.675 0h-21.35C.597 0 0 .597 0 1.325v21.351C0 23.403.597 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.323-.597 1.323-1.324V1.325C24 .597 23.403 0 22.675 0z"/></svg></a>
                    <a href="#" aria-label="Twitter"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.016 10.016 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg></a>
                    <a href="#" aria-label="Nabd">نبض</a>
                </div>
                <div class="tw-date">{{ \Carbon\Carbon::now()->locale('ar')->translatedFormat('l j F Y') }}</div>
            </div>
        </div>
        <div class="tw-header-main">
            <div class="tw-wrap tw-header-inner-main">
                <a href="{{ route('home') }}" class="tw-brand">
                    <span class="tw-logo-wrap"><img src="{{ asset('branding/Logo_Icon_Option_01.svg') }}" alt="شعار تنوير" class="tw-logo"></span>
                    <div>
                        <h1 class="tw-brand-title">تنوير</h1>
                        <span class="tw-brand-subtitle">منصة إعلامية شاملة</span>
                    </div>
                </a>

                <button
                    type="button"
                    class="tw-nav-trigger-btn"
                    id="drawer-trigger"
                    aria-expanded="false"
                    aria-controls="public-drawer"
                    aria-label="فتح القائمة الرئيسية"
                >
                    <span class="tw-nav-trigger-icon" aria-hidden="true"></span>
                    <span>القائمة</span>
                </button>
            </div>
        </div>
    </header>

    <main class="tw-main-content">
        @yield('content')
    </main>

    <footer class="tw-footer">
        <div class="tw-wrap tw-footer-inner">
            <div class="tw-footer-about">
                <h2>تنوير</h2>
                <p>تنوير منصة شبابية مستقلة، وُجدت لإعادة الاعتبار للفكر في زمن الاستهلاك السريع للمحتوى. 

نناقش الإنسان قبل الظاهرة، والوعي قبل الرأي، عبر مقالات، حوارات، بودكاست، ومحتوى بصري يطرح الأسئلة التي تستحق التفكير . ..                .</p>
            </div>
            <div class="tw-footer-links">
                <h3>أقسام الموقع</h3>
                <ul>
                    @foreach (\App\Models\Post::navPrimaryLinks() as $link)
                        @if ($link['key'] !== 'home')
                            <li><a href="{{ $link['href'] }}">{{ $link['label'] }}</a></li>
                        @endif
                    @endforeach
                </ul>
                <h3 style="margin-top: 1.25rem;">الفيديوهات</h3>
                <ul>
                    @foreach (\App\Models\Post::navVideoLinks() as $link)
                        <li><a href="{{ $link['href'] }}">{{ $link['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="tw-footer-bottom">
                <p>© {{ date('Y') }} تنوير — جميع الحقوق محفوظة</p>
                <!-- <a href="{{ route('admin.login') }}" class="tw-btn tw-btn-ghost tw-btn-sm">دخول الإدارة</a> -->
            </div>
        </div>
    </footer>

    @include('partials.public-nav')
    @yield('scripts')
</body>
</html>
