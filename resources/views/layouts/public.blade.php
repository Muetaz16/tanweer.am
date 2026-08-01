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
                        <span class="tw-brand-subtitle">منصة شبابية مستقلة    </span>
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
                
                <!-- Newsletter Subscription -->
                <div class="tw-footer-newsletter" style="margin-top: 1.5rem;">
                    <h3 style="color: var(--tw-beige); font-size: 1rem; font-weight: 700; margin-bottom: 0.75rem;">النشرة البريدية</h3>
                    <p style="font-size: 0.85rem; color: rgba(240, 240, 200, 0.7); margin-bottom: 0.75rem;">اشترك للحصول على أحدث المقالات والتحليلات</p>
                    <form class="tw-newsletter-form" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        <input 
                            type="email" 
                            placeholder="بريدك الإلكتروني" 
                            style="flex: 1; min-width: 200px; padding: 0.6rem 0.9rem; border-radius: var(--tw-radius-sm); border: 1.5px solid rgba(255, 255, 255, 0.2); background: rgba(255, 255, 255, 0.1); color: #fff; font-family: var(--tw-font); font-size: 0.9rem;"
                            required
                        >
                        <button type="submit" class="tw-btn tw-btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">اشترك</button>
                    </form>
                </div>
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
                <div class="tw-footer-social-links" style="display: flex; gap: 1rem; align-items: center;">
                    <a href="#" aria-label="Facebook" style="color: rgba(240, 240, 200, 0.6); transition: color 0.2s;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M22.675 0h-21.35C.597 0 0 .597 0 1.325v21.351C0 23.403.597 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.323-.597 1.323-1.324V1.325C24 .597 23.403 0 22.675 0z"/></svg>
                    </a>
                    <a href="#" aria-label="Twitter" style="color: rgba(240, 240, 200, 0.6); transition: color 0.2s;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.016 10.016 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                    </a>
                    <a href="#" aria-label="Instagram" style="color: rgba(240, 240, 200, 0.6); transition: color 0.2s;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    @include('partials.public-nav')
    @yield('scripts')
</body>
</html>
