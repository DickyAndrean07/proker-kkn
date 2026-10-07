<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dusun') — Portal Informasi Desa Bendung</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="admin-sidebar" id="adminSidebar" aria-label="Navigasi Utama Admin">
            <div class="admin-sidebar-header">
                <div class="admin-brand">
                    <img
                        src="{{ asset('images/logo-desa-bendung.png') }}"
                        alt="Logo Desa Bendung"
                        class="admin-brand-logo"
                        width="36"
                        height="44"
                    >
                    <div>
                        <div class="admin-brand-title">Portal Desa Bendung</div>
                        <div class="admin-brand-subtitle">Admin Dusun</div>
                    </div>
                </div>
                <button type="button" class="admin-sidebar-close" id="sidebarCloseBtn" aria-label="Tutup Menu">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            <!-- Dusun Context Card -->
            @php
                $currentDusun = auth()->user()?->dusun;
            @endphp
            @if($currentDusun)
                <div class="admin-context-card">
                    <div class="admin-context-label">DUSUN TERDAFTAR</div>
                    <div class="admin-context-name">{{ $currentDusun->nama_dusun }}</div>
                    <div class="admin-context-badge {{ $currentDusun->status_dusun === 'ACTIVE' ? 'badge-active' : 'badge-inactive' }}">
                        {{ $currentDusun->status_dusun === 'ACTIVE' ? 'Aktif Publik' : 'Nonaktif Publik' }}
                    </div>
                </div>
            @endif

            <!-- Quick Action: Akses Website Publik (Di bagian atas agar langsung terlihat di HP) -->
            <div class="admin-sidebar-public-wrap">
                <a href="{{ route('home') }}" target="_blank" class="admin-public-link" rel="noopener">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                        <polyline points="15 3 21 3 21 9"/>
                        <line x1="10" y1="14" x2="21" y2="3"/>
                    </svg>
                    <span>Lihat Website Publik</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="admin-nav">
                <a href="{{ route('admin-dusun.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin-dusun.dashboard') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    <span class="nav-text">Dashboard</span>
                </a>
                <a href="{{ route('admin-dusun.profil.edit') }}" class="admin-nav-item {{ request()->routeIs('admin-dusun.profil.*') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    <span class="nav-text">Profil Dusun</span>
                </a>
                <a href="{{ route('admin-dusun.kontak.index') }}" class="admin-nav-item {{ request()->routeIs('admin-dusun.kontak.*') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    <span class="nav-text">Kontak Pelayanan</span>
                </a>
                <a href="{{ route('admin-dusun.umkm.index') }}" class="admin-nav-item {{ request()->routeIs('admin-dusun.umkm.*') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                        <path d="M3 6h18"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                    <span class="nav-text">Kelola UMKM</span>
                </a>
                <a href="{{ route('admin-dusun.fasilitas.index') }}" class="admin-nav-item {{ request()->routeIs('admin-dusun.fasilitas.*') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <span class="nav-text">Kelola Fasilitas</span>
                </a>
                <a href="{{ route('admin-dusun.agenda.index') }}" class="admin-nav-item {{ request()->routeIs('admin-dusun.agenda.*') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <span class="nav-text">Agenda & Kegiatan</span>
                </a>
                <a href="{{ route('admin-dusun.pengumuman.index') }}" class="admin-nav-item {{ request()->routeIs('admin-dusun.pengumuman.*') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                    <span class="nav-text">Kelola Pengumuman</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div class="admin-main">
            <!-- Admin Top Header -->
            <header class="admin-topbar">
                <div class="admin-topbar-left">
                    <button type="button" class="admin-menu-toggle" id="sidebarToggleBtn" aria-label="Buka Menu">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"/>
                            <line x1="3" y1="6" x2="21" y2="6"/>
                            <line x1="3" y1="18" x2="21" y2="18"/>
                        </svg>
                    </button>
                    <div class="admin-breadcrumb">
                        @yield('breadcrumb', 'Admin Dusun')
                    </div>
                </div>

                <div class="admin-topbar-right">
                    <div class="admin-user-pill">
                        <div class="admin-user-avatar">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <span class="admin-user-name">{{ auth()->user()?->username }}</span>
                        <span class="admin-role-tag">Admin Dusun</span>
                    </div>

                    <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" class="btn-logout" title="Keluar">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </header>

            <!-- Alert Notice if Dusun is INACTIVE -->
            @if($currentDusun && $currentDusun->status_dusun === 'INACTIVE')
                <div class="admin-banner-notice banner-warning" role="alert">
                    <div class="banner-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </div>
                    <div class="banner-content">
                        <strong>Dusun Berstatus Nonaktif Publik</strong>
                        <p>Dusun ini saat ini berstatus NONAKTIF di portal publik. Data yang Anda kelola tetap tersimpan dan dapat diedit, namun tidak ditampilkan kepada publik hingga diaktifkan kembali oleh Super Admin.</p>
                    </div>
                </div>
            @endif

            <!-- Flash Success Message -->
            @if(session('success'))
                <div class="admin-flash flash-success" role="status">
                    <span class="flash-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </span>
                    <span class="flash-message">{{ session('success') }}</span>
                    <button type="button" class="flash-close" onclick="this.parentElement.remove();" aria-label="Tutup notifikasi">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Global Validation Error Summary -->
            @if($errors->any())
                <div class="admin-flash flash-error" role="alert">
                    <span class="flash-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </span>
                    <div class="flash-message">
                        <strong>Terdapat kesalahan pada isian form:</strong>
                        <ul class="error-list">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Main Page View Content -->
            <main class="admin-content" id="mainContent">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Confirmation Modal Dialog for Nonaktifkan / Soft Delete -->
    <div class="admin-modal" id="deactivateModal" role="dialog" aria-modal="true" aria-labelledby="deactivateModalTitle" aria-hidden="true">
        <div class="admin-modal-backdrop" id="modalBackdrop"></div>
        <div class="admin-modal-dialog">
            <div class="admin-modal-header">
                <h3 class="admin-modal-title" id="deactivateModalTitle">Konfirmasi Nonaktifkan</h3>
                <button type="button" class="admin-modal-close" id="modalCloseBtn" aria-label="Tutup modal">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="admin-modal-body">
                <p>Data <strong id="deactivateItemName"></strong> akan dinonaktifkan dan tidak lagi tampil di halaman publik.</p>
            </div>
            <div class="admin-modal-footer">
                <button type="button" class="btn btn-secondary" id="modalCancelBtn">Batal</button>
                <form id="deactivateForm" method="POST" action="" class="inline-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="modalConfirmBtn">Nonaktifkan</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Admin Drawer & Modal Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('adminSidebar');
            const toggleBtn = document.getElementById('sidebarToggleBtn');
            const closeBtn = document.getElementById('sidebarCloseBtn');

            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('open');
                });
            }

            if (closeBtn && sidebar) {
                closeBtn.addEventListener('click', () => {
                    sidebar.classList.remove('open');
                });
            }

            const modal = document.getElementById('deactivateModal');
            const modalBackdrop = document.getElementById('modalBackdrop');
            const modalCloseBtn = document.getElementById('modalCloseBtn');
            const modalCancelBtn = document.getElementById('modalCancelBtn');
            const deactivateForm = document.getElementById('deactivateForm');
            const deactivateItemName = document.getElementById('deactivateItemName');

            window.openDeactivateModal = function(actionUrl, itemName = '') {
                deactivateForm.action = actionUrl;
                deactivateItemName.textContent = itemName || 'ini';
                modal.classList.add('show');
                modal.setAttribute('aria-hidden', 'false');
            };

            function closeModal() {
                modal.classList.remove('show');
                modal.setAttribute('aria-hidden', 'true');
            }

            if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeModal);
            if (modalCancelBtn) modalCancelBtn.addEventListener('click', closeModal);
            if (modalBackdrop) modalBackdrop.addEventListener('click', closeModal);

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modal.classList.contains('show')) {
                    closeModal();
                }
            });
        });
    </script>
    <x-admin.ai-modal />
    @stack('scripts')
</body>
</html>
