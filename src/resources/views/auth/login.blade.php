@extends('layouts.app')

@section('title', 'Login Admin — Portal Informasi Desa Bendung')
@section('body-class', 'auth-page-body')
@section('shell-class', 'auth-shell')

@section('content')
<div class="auth-viewport-bg" style="--auth-bg-image: url('{{ asset('images/balai-desa-bendung.webp') }}');">
    <div class="auth-overlay"></div>
    
    <div class="auth-card-wrapper">
        <!-- Top Branding (Above Card) -->
        <header class="auth-page-brand">
            <img 
                src="{{ asset('images/logo-desa-bendung.png') }}" 
                alt="Logo Desa Bendung" 
                class="auth-brand-logo"
                width="98"
                height="120"
            >
            <div class="auth-brand-identity">
                <span class="auth-brand-village">DESA BENDUNG</span>
                <span class="auth-brand-subtitle">PORTAL INFORMASI</span>
            </div>
        </header>

        <!-- Glass Card -->
        <div class="auth-glass-card">
            <!-- Decorative Gold Top Flourish Ornament -->
            <div class="auth-card-flourish" aria-hidden="true">
                <svg width="26" height="12" viewBox="0 0 28 14" fill="none">
                    <path d="M14 0C11 5 4 8 0 8C4 8 10 11 14 14C18 11 24 8 28 8C24 8 17 5 14 0Z" fill="#D6A928"/>
                </svg>
            </div>

            <div class="auth-card-header">
                <h1 class="auth-card-title">Selamat datang kembali</h1>
                <p class="auth-card-desc">Silakan masuk untuk melanjutkan <span class="sr-only">(Login Admin)</span></p>
            </div>

            @if ($errors->any())
                <div class="auth-alert-danger" role="alert" aria-live="polite">
                    <div class="auth-alert-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <div class="auth-alert-content">
                        <ul class="auth-error-list">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="auth-form" novalidate>
                @csrf

                <div class="auth-field-group">
                    <div class="auth-input-wrapper">
                        <span class="auth-input-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </span>
                        <input
                            id="username"
                            name="username"
                            type="text"
                            class="auth-input @error('username') is-invalid @enderror"
                            value="{{ old('username') }}"
                            placeholder="Username"
                            required
                            autofocus
                            autocomplete="username"
                            spellcheck="false"
                        >
                    </div>
                </div>

                <div class="auth-field-group">
                    <div class="auth-input-wrapper">
                        <span class="auth-input-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </span>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="auth-input @error('password') is-invalid @enderror"
                            placeholder="Password"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button" class="auth-password-toggle" id="togglePasswordBtn" aria-label="Lihat password" tabindex="-1">
                            <svg id="eyeIcon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="auth-form-options">
                    <label class="auth-checkbox-label">
                        <input type="checkbox" name="remember" value="1" class="auth-checkbox">
                        <span>Ingat saya</span>
                    </label>
                    <span class="auth-forgot-link" title="Silakan hubungi Super Administrator bila lupa password">Lupa password?</span>
                </div>

                <button type="submit" class="auth-submit-btn">
                    <svg class="auth-btn-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                    <span>Masuk</span>
                </button>
            </form>

            <footer class="auth-card-footer">
                <a href="{{ route('home') }}" class="auth-back-link">
                    <svg class="auth-back-icon" viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    <span>Kembali ke beranda desa</span>
                </a>
            </footer>
        </div>

        <footer class="auth-page-footer">
            <p>© 2025 Desa Bendung. Semua hak dilindungi.</p>
        </footer>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function() {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                toggleBtn.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Lihat password');
                toggleBtn.style.opacity = isPassword ? '1' : '0.7';
            });
        }
    });
</script>
@endsection

