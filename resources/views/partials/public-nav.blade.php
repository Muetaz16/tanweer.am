@php
    $navActiveKey = $navActiveKey ?? null;
    $videoTypes = \App\Models\Post::navVideoTypes();
    $isVideoSectionActive = \App\Models\Post::isNavVideoType($navActiveKey);
@endphp

<!-- Backdrop Overlay -->
<div class="tw-drawer-backdrop" id="drawer-backdrop" data-drawer-close></div>

<!-- Main Drawer Panel -->
<div class="tw-drawer" id="public-drawer" role="dialog" aria-modal="true" aria-label="القائمة الرئيسية" aria-hidden="true" tabindex="-1">
    <!-- Header inside drawer -->
    <div class="tw-drawer-header">
        <a href="{{ route('home') }}" class="tw-brand">
            <span class="tw-logo-wrap" style="width: 42px; height: 42px; border-radius: 10px; box-shadow: none; overflow: hidden;"><img src="{{ asset('branding/Logo_Icon_Option_01.svg') }}" alt="شعار تنوير" class="tw-logo" style="width: 100%; height: 100%; object-fit: cover;"></span>
            <div>
                <h2 class="tw-brand-title" style="font-size: 1.25rem; margin: 0;">تنوير</h2>
            </div>
        </a>

        <button type="button" class="tw-drawer-close" data-drawer-close aria-label="إغلاق القائمة">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <!-- Body / Content -->
    <div class="tw-drawer-body">
        <nav class="tw-drawer-nav" aria-label="التنقل الجانبي">
            @foreach (\App\Models\Post::navPrimaryLinks() as $link)
                <div class="tw-drawer-nav-item">
                    <a
                        href="{{ $link['href'] }}"
                        @class(['tw-drawer-link', 'is-active' => $navActiveKey === $link['key']])
                        @if ($navActiveKey === $link['key']) aria-current="page" @endif
                    >{{ $link['label'] }}</a>
                </div>
            @endforeach

            <!-- Video Section Dropdown inside Drawer -->
            <div class="tw-drawer-nav-item">
                <div class="tw-drawer-dropdown @if($isVideoSectionActive) is-open @endif" data-drawer-dropdown>
                    <button
                        type="button"
                        class="tw-drawer-dropdown-trigger @if($isVideoSectionActive) is-active @endif"
                        data-drawer-dropdown-trigger
                        aria-expanded="{{ $isVideoSectionActive ? 'true' : 'false' }}"
                        aria-controls="drawer-videos-menu"
                    >
                        <span>الفيديوهات</span>
                        <svg class="tw-drawer-dropdown-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>

                    <div class="tw-drawer-dropdown-menu" id="drawer-videos-menu">
                        <div class="tw-drawer-dropdown-menu-inner">
                            @foreach (\App\Models\Post::navVideoLinks() as $link)
                                <a
                                    href="{{ $link['href'] }}"
                                    @class(['is-active' => $navActiveKey === $link['key']])
                                    @if ($navActiveKey === $link['key']) aria-current="page" @endif
                                >{{ $link['label'] }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Footer of the drawer -->
    <div class="tw-drawer-footer">
        <div class="tw-drawer-footer-social">
            <a href="#" aria-label="Facebook"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M22.675 0h-21.35C.597 0 0 .597 0 1.325v21.351C0 23.403.597 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.323-.597 1.323-1.324V1.325C24 .597 23.403 0 22.675 0z"/></svg></a>
            <a href="#" aria-label="Twitter"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.016 10.016 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.085 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg></a>
            <a href="#" style="font-weight: 700; font-size: 0.9rem; display: flex; align-items: center;">نبض</a>
        </div>
        <div class="tw-drawer-footer-copy">
            <span>© {{ date('Y') }} تنوير — جميع الحقوق محفوظة</span>
        </div>
    </div>
</div>
