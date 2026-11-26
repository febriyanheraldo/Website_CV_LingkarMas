<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <link rel="icon" href="{{ asset('images/logo/logo_lingkarmas.png') }}">
    <title>Login Admin | CV. Lingkar Mas</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700&family=Inter:wght@400;500;600&display=swap">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: #f8fafc;
            color: #0f1b2a;
            min-height: 100vh;
            min-height: 100dvh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }
        .login-wrap {
            min-height: 100vh;
            min-height: 100dvh;
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            background: #f8fafc;
            width: 100%;
        }
        .login-brand-panel {
            background: linear-gradient(145deg, #0b2239 0%, #133a61 100%);
            color: #fff;
            padding: clamp(32px, 5vw, 60px);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .login-brand-panel::before {
            content: "";
            position: absolute;
            top: -120px;
            right: -120px;
            width: 340px;
            height: 340px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.25) 0%, transparent 70%);
            pointer-events: none;
        }
        .login-brand-panel::after {
            content: "";
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }
        .login-brand-top {
            position: relative;
            z-index: 2;
        }
        .login-brand-content {
            position: relative;
            z-index: 2;
            margin: auto 0;
            padding: 24px 0;
        }
        .login-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 99px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            font-size: 13px;
            font-weight: 600;
            color: #fdba74;
            margin-bottom: 20px;
            backdrop-filter: blur(4px);
        }
        .login-brand-title {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(26px, 3vw, 38px);
            font-weight: 700;
            line-height: 1.25;
            margin-bottom: 16px;
            color: #ffffff;
        }
        .login-brand-desc {
            color: #cbd5e1;
            font-size: clamp(13px, 1.15vw, 15px);
            line-height: 1.6;
            max-width: 44ch;
            margin-bottom: 32px;
        }
        .login-features {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .login-feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            color: #e2e8f0;
        }
        .login-feature-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.12);
            display: grid;
            place-items: center;
            font-size: 15px;
            flex-shrink: 0;
        }
        .login-brand-footer {
            position: relative;
            z-index: 2;
            font-size: 13px;
            color: #94a3b8;
        }

        .login-form-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: clamp(20px, 4vw, 40px);
            background: #f8fafc;
            min-height: 100%;
            width: 100%;
        }
        .login-card {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: clamp(26px, 4vw, 40px) clamp(20px, 4vw, 36px);
            box-shadow: 0 10px 30px rgba(11, 34, 57, 0.06);
        }
        .login-mobile-logo {
            display: none;
            margin-bottom: 24px;
            justify-content: center;
            align-items: center;
        }
        .login-mobile-logo .logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .login-card-header {
            margin-bottom: 24px;
        }
        .login-card-header h1 {
            font-size: clamp(22px, 2.5vw, 26px);
            font-weight: 700;
            color: #0b2239;
            margin-bottom: 6px;
        }
        .login-card-header p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.5;
        }
        .err {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 13.5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            line-height: 1.4;
        }
        .err svg {
            flex-shrink: 0;
            color: #ef4444;
        }
        .ok {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 13.5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            line-height: 1.4;
        }
        .ok svg {
            flex-shrink: 0;
            color: #22c55e;
        }
        .input-group {
            margin-bottom: 18px;
        }
        .input-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 7px;
        }
        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }
        .input-wrap input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s;
            background: #ffffff;
            color: #0f1b2a;
        }
        .input-wrap.has-toggle input {
            padding-right: 44px;
        }
        .input-wrap input:focus {
            border-color: #f97316;
            outline: none;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
        }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            display: flex;
            align-items: center;
            pointer-events: none;
            z-index: 1;
        }
        .btn-toggle-password {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            padding: 6px;
            border-radius: 6px;
            color: #94a3b8;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s, background-color 0.2s;
            z-index: 2;
        }
        .btn-toggle-password:hover {
            color: #334155;
            background: #f1f5f9;
        }
        .btn-toggle-password:focus-visible {
            outline: 2px solid #f97316;
            outline-offset: 2px;
        }
        .btn-login-submit {
            width: 100%;
            min-height: 46px;
            padding: 13px 20px;
            border-radius: 10px;
            background: #f97316;
            color: #ffffff;
            border: none;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 12px;
            box-shadow: 0 4px 14px rgba(249, 115, 22, 0.3);
        }
        .btn-login-submit:hover {
            background: #ea580c;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(249, 115, 22, 0.35);
        }
        .btn-login-submit:active {
            transform: translateY(0);
        }
        .btn-login-submit:focus-visible {
            outline: 2px solid #ea580c;
            outline-offset: 2px;
        }
        .login-back-link {
            margin-top: 22px;
            text-align: center;
            padding-top: 18px;
            border-top: 1px solid #f1f5f9;
        }
        .login-back-link a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #64748b;
            font-size: 13px;
            font-weight: 500;
            transition: color 0.2s;
        }
        .login-back-link a:hover {
            color: #0b2239;
        }

        /* RESPONSIVE BREAKPOINT */
        @media (max-width: 960px) {
            .login-wrap {
                grid-template-columns: 1fr;
            }
            .login-brand-panel {
                display: none;
            }
            .login-mobile-logo {
                display: flex;
            }
            .login-form-panel {
                padding: 40px 20px;
                min-height: 100vh;
                min-height: 100dvh;
            }
        }
        @media (max-width: 768px) {
            /* Mencegah auto-zoom Safari iOS pada field input */
            .input-wrap input {
                font-size: 16px;
            }
        }
        @media (max-width: 480px) {
            .login-form-panel {
                padding: 24px 14px;
            }
            .login-card {
                padding: 24px 18px;
                border-radius: 16px;
                box-shadow: 0 4px 16px rgba(11, 34, 57, 0.05);
            }
            .login-card-header {
                margin-bottom: 20px;
            }
            .login-card-header h1 {
                font-size: 21px;
            }
            .login-card-header p {
                font-size: 13px;
            }
            .btn-login-submit {
                min-height: 48px;
                font-size: 14.5px;
            }
        }
        @media (max-width: 360px) {
            .login-card {
                padding: 20px 14px;
            }
            .input-wrap input {
                padding-left: 38px;
            }
            .input-icon {
                left: 12px;
            }
        }
    </style>
</head>
<body>
<div class="login-wrap">
    <!-- Panel Kiri: Brand & Informasi (Desktop) -->
    <div class="login-brand-panel">
        <div class="login-brand-top">
            @include('partials.logo', ['dark' => true])
        </div>
        <div class="login-brand-content">
            <span class="login-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Portal Administrator
            </span>
            <h2 class="login-brand-title">Sistem Informasi &amp; Katalog Industri</h2>
            <p class="login-brand-desc">
                Pusat kontrol terpadu CV. Lingkar Mas untuk memantau stok produk, menerbitkan barcode Code 39, dan mengelola pesanan klien secara aman dan real-time.
            </p>
            <div class="login-features">
                <div class="login-feature-item">
                    <span class="login-feature-icon">🏷️</span>
                    <span>Generasi Barcode &amp; QR Code Produk Otomatis</span>
                </div>
                <div class="login-feature-item">
                    <span class="login-feature-icon">📦</span>
                    <span>Monitoring Stok &amp; Peringatan Stok Menipis</span>
                </div>
                <div class="login-feature-item">
                    <span class="login-feature-icon">📊</span>
                    <span>Laporan Rekapitulasi PDF &amp; Excel Ekspor</span>
                </div>
            </div>
        </div>
        <div class="login-brand-footer">
            &copy; {{ date('Y') }} CV. Lingkar Mas Sidoarjo. All rights reserved.
        </div>
    </div>

    <!-- Panel Kanan: Form Login (Responsive) -->
    <div class="login-form-panel">
        <div class="login-card">
            <!-- Logo untuk Tampilan Mobile -->
            <div class="login-mobile-logo">
                @include('partials.logo')
            </div>

            <div class="login-card-header">
                <h1>Masuk ke Dashboard</h1>
                <p>Silakan masukkan kredensial akun administrator Anda.</p>
            </div>

            @if (session('status'))
                <div class="ok">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="err">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="input-group">
                    <label for="email">Alamat Email</label>
                    <div class="input-wrap">
                        <span class="input-icon" aria-hidden="true">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        </span>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="nama@email.com">
                    </div>
                </div>

                <div class="input-group">
                    <label for="password">Kata Sandi</label>
                    <div class="input-wrap has-toggle">
                        <span class="input-icon" aria-hidden="true">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                        <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="••••••••">
                        <button type="button" class="btn-toggle-password" id="togglePassword" aria-label="Lihat kata sandi" title="Lihat kata sandi">
                            <!-- Ikon mata terbuka (saat password tertutup/hidden) -->
                            <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <!-- Ikon mata dicoret (saat password terbuka/visible) -->
                            <svg class="eye-off-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display: none;">
                                <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                <line x1="2" x2="22" y1="2" y2="22"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button class="btn-login-submit" type="submit">
                    <span>Masuk ke Panel</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>

                <div class="login-back-link">
                    <a href="{{ route('home') }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                        Kembali ke Halaman Utama
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toggleBtn = document.getElementById('togglePassword');
        var passwordInput = document.getElementById('password');

        if (toggleBtn && passwordInput) {
            var eyeIcon = toggleBtn.querySelector('.eye-icon');
            var eyeOffIcon = toggleBtn.querySelector('.eye-off-icon');

            toggleBtn.addEventListener('click', function (e) {
                e.preventDefault();
                var isPassword = passwordInput.getAttribute('type') === 'password';

                if (isPassword) {
                    passwordInput.setAttribute('type', 'text');
                    if (eyeIcon) eyeIcon.style.display = 'none';
                    if (eyeOffIcon) eyeOffIcon.style.display = 'block';
                    toggleBtn.setAttribute('aria-label', 'Sembunyikan kata sandi');
                    toggleBtn.setAttribute('title', 'Sembunyikan kata sandi');
                } else {
                    passwordInput.setAttribute('type', 'password');
                    if (eyeIcon) eyeIcon.style.display = 'block';
                    if (eyeOffIcon) eyeOffIcon.style.display = 'none';
                    toggleBtn.setAttribute('aria-label', 'Lihat kata sandi');
                    toggleBtn.setAttribute('title', 'Lihat kata sandi');
                }
            });
        }
    });
</script>
</body>
</html>