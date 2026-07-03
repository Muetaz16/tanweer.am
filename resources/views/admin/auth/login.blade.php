<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>دخول الإدارة — تنوير</title>
    @vite(['resources/css/tanweer.css', 'resources/js/app.js'])
</head>
<body class="tw-body">
    <div class="tw-login">
        <div class="tw-login-card">
            <div class="tw-brand" style="margin-bottom: 1.25rem;">
                <span class="tw-logo-wrap"><img src="{{ asset('branding/tanweer-logo.svg') }}" alt="شعار تنوير" class="tw-logo"></span>
                <div>
                    <h1 class="tw-brand-title" style="font-size: 1.25rem;">تنوير</h1>
                    <span>لوحة التحكم</span>
                </div>
            </div>
            <h2>تسجيل الدخول</h2>
            <p>أدخل بيانات حساب المدير للمتابعة.</p>

            @if ($errors->any())
                <div class="tw-alert tw-alert-error" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="post" action="{{ route('admin.login') }}" class="tw-form">
                @csrf
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <p class="tw-muted">سيتم تسجيل دخولك تلقائياً بحساب المدير.</p>
                </div>
                <button type="submit" class="tw-btn tw-btn-primary" style="width: 100%; padding: 0.75rem;">دخول إلى لوحة التحكم</button>
            </form>

            <p class="tw-muted" style="margin-top: 1.25rem; font-size: 0.85rem;">
                <a href="{{ route('home') }}" class="tw-btn tw-btn-ghost" style="width:100%;">العودة للموقع</a>
            </p>
        </div>
    </div>
</body>
</html>
