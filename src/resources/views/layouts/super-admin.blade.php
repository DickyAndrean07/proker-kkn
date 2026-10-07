<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin') â€” Portal Desa Bendung</title>

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
        <aside class="admin-sidebar" id="adminSidebar" aria-label="Navigasi Super Admin">
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
                        <div class="admin-brand-title">Desa Bendung</div>
                        <div class="admin-brand-subtitle">Super Admin Portal</div>
                    </div>
                </div>
                <button type="button" class="admin-sidebar-close" id="sidebarCloseBtn" aria-label="Tutup Menu">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            <!-- Global Context Card -->
            <div class="admin-context-card">
                <span class="admin-context-label">Hak Akses Administrasi</span>
                <div class="admin-context-name">Super Administrator</div>
                <span class="admin-context-badge badge-sa-global">
                    Akses Global Seluruh Desa
                </span>
            </div>

            <!-- Quick Action: Akses Portal Publik (Di bagian atas agar langsung terlihat di HP) -->
            <div class="admin-sidebar-public-wrap">
                <a href="{{ route('home') }}" target="_blank" class="admin-public-link" rel="noopener">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                        <polyline points="15 3 21 3 21 9"/>
                        <line x1="10" y1="14" x2="21" y2="3"/>
                    </svg>
                    <span>Buka Portal Publik</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="admin-nav" aria-label="Navigasi Super Admin">
                <!-- Desa -->
                <div class="admin-nav-section">
                    <span class="admin-nav-section-label">Desa</span>
                </div>
                <a href="{{ route('super-admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    <span class="nav-text">Dashboard</span>
                </a>
                <a href="{{ route('super-admin.desa.edit') }}" class="admin-nav-item {{ request()->routeIs('super-admin.desa.*') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    </svg>
                    <span class="nav-text">Identitas Desa</span>
                </a>
                <a href="{{ route('super-admin.dusun.index') }}" class="admin-nav-item {{ request()->routeIs('super-admin.dusun.*') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <span class="nav-text">Kelola Dusun</span>
                </a>

                <!-- Informasi -->
                <div class="admin-nav-section">
                    <span class="admin-nav-section-label">Informasi</span>
                </div>
                <a href="{{ route('super-admin.kontak.index') }}" class="admin-nav-item {{ request()->routeIs('super-admin.kontak.*') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    <span class="nav-text">Kontak Pelayanan</span>
                </a>
                <a href="{{ route('super-admin.umkm.index') }}" class="admin-nav-item {{ request()->routeIs('super-admin.umkm.*') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                        <path d="M3 6h18"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                    <span class="nav-text">Kelola UMKM</span>
                </a>
                <a href="{{ route('super-admin.fasilitas.index') }}" class="admin-nav-item {{ request()->routeIs('super-admin.fasilitas.*') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <span class="nav-text">Kelola Fasilitas</span>
                </a>
                <a href="{{ route('super-admin.kategori-fasilitas.index') }}" class="admin-nav-item {{ request()->routeIs('super-admin.kategori-fasilitas.*') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                        <line x1="7" y1="7" x2="7.01" y2="7"/>
                    </svg>
                    <span class="nav-text">Kategori Fasilitas</span>
                </a>

                <!-- Konten -->
                <div class="admin-nav-section">
                    <span class="admin-nav-section-label">Konten</span>
                </div>
                <a href="{{ route('super-admin.agenda.index') }}" class="admin-nav-item {{ request()->routeIs('super-admin.agenda.*') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <span class="nav-text">Agenda & Kegiatan</span>
                </a>
                <a href="{{ route('super-admin.pengumuman.index') }}" class="admin-nav-item {{ request()->routeIs('super-admin.pengumuman.*') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                    <span class="nav-text">Pengumuman</span>
                </a>

                <!-- Sistem -->
                <div class="admin-nav-section">
                    <span class="admin-nav-section-label">Sistem</span>
                </div>
                <a href="{{ route('super-admin.data-peta') }}" class="admin-nav-item {{ request()->routeIs('super-admin.data-peta') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/>
                        <line x1="8" y1="2" x2="8" y2="18"/>
                        <line x1="16" y1="6" x2="16" y2="22"/>
                    </svg>
                    <span class="nav-text">Data / Peta</span>
                </a>
                <a href="{{ route('super-admin.admin-dusun.index') }}" class="admin-nav-item {{ request()->routeIs('super-admin.admin-dusun.*') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <span class="nav-text">Admin Dusun</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <header class="admin-topbar">
                <div class="admin-topbar-left">
                    <button type="button" class="admin-menu-toggle" id="menuToggleBtn" aria-label="Buka Menu">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"/>
                            <line x1="3" y1="6" x2="21" y2="6"/>
                            <line x1="3" y1="18" x2="21" y2="18"/>
                        </svg>
                    </button>
                    <nav class="admin-breadcrumb" aria-label="Breadcrumb">
                        @yield('breadcrumb')
                    </nav>
                </div>
                <div class="admin-topbar-right">
                    <div class="admin-user-pill">
                        <div class="admin-user-avatar">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <span class="admin-user-name">{{ Auth::user()->username ?? 'Super Admin' }}</span>
                        <span class="admin-role-tag badge-sa-tag">Super Admin</span>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}" class="inline-form">
                        @csrf
                        <button type="submit" class="btn-logout" id="logoutBtn">
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

            <!-- Flash Feedback -->
            @if(session('success'))
                <div class="admin-flash flash-success" role="alert">
                    <span class="flash-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </span>
                    <div class="flash-message">{{ session('success') }}</div>
                    <button type="button" class="flash-close" onclick="this.parentElement.remove()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="admin-flash flash-error" role="alert">
                    <span class="flash-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </span>
                    <div class="flash-message">{{ session('error') }}</div>
                    <button type="button" class="flash-close" onclick="this.parentElement.remove()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
            @endif

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
                        <strong>Terdapat kesalahan pada isian formulir:</strong>
                        <ul class="error-list">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="flash-close" onclick="this.parentElement.remove()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
            @endif

            <main class="admin-content">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Universal Action Modals -->
    <!-- 1. Soft Delete / Nonaktifkan Modal -->
    <div class="admin-modal" id="deactivateModal" aria-hidden="true" role="dialog">
        <div class="admin-modal-backdrop" onclick="closeModal('deactivateModal')"></div>
        <div class="admin-modal-dialog">
            <div class="admin-modal-header">
                <h3 class="admin-modal-title">Konfirmasi Nonaktifkan Data</h3>
                <button type="button" class="admin-modal-close" onclick="closeModal('deactivateModal')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="admin-modal-body">
                <p>Apakah Anda yakin ingin menonaktifkan data <strong id="deactivateItemName"></strong>?</p>
                <p class="text-muted text-sm modal-note">
                    Data akan disembunyikan dari portal publik (Soft Delete) dan dapat dipulihkan kapan saja oleh Super Admin.
                </p>
            </div>
            <div class="admin-modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('deactivateModal')">Batal</button>
                <form id="deactivateForm" method="POST" action="" class="inline-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Nonaktifkan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. Restore Modal -->
    <div class="admin-modal" id="restoreModal" aria-hidden="true" role="dialog">
        <div class="admin-modal-backdrop" onclick="closeModal('restoreModal')"></div>
        <div class="admin-modal-dialog">
            <div class="admin-modal-header">
                <h3 class="admin-modal-title">Konfirmasi Pemulihan Data</h3>
                <button type="button" class="admin-modal-close" onclick="closeModal('restoreModal')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="admin-modal-body">
                <p>Apakah Anda yakin ingin memulihkan data <strong id="restoreItemName"></strong>?</p>
                <p class="text-muted text-sm modal-note">
                    Data akan aktif kembali dan dapat ditampilkan di portal publik jika wilayah dusun berstatus aktif.
                </p>
            </div>
            <div class="admin-modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('restoreModal')">Batal</button>
                <form id="restoreForm" method="POST" action="" class="inline-form">
                    @csrf
                    <button type="submit" class="btn btn-primary">Ya, Pulihkan Data</button>
                </form>
            </div>
        </div>
    </div>

    <!-- 3. Hard Delete Modal -->
    <div class="admin-modal" id="forceDeleteModal" aria-hidden="true" role="dialog">
        <div class="admin-modal-backdrop" onclick="closeModal('forceDeleteModal')"></div>
        <div class="admin-modal-dialog modal-danger">
            <div class="admin-modal-header">
                <div class="admin-modal-header-danger">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    <h3 class="admin-modal-title text-danger">Konfirmasi Hapus Permanen</h3>
                </div>
                <button type="button" class="admin-modal-close" onclick="closeModal('forceDeleteModal')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="admin-modal-body">
                <p>PERINGATAN: Tindakan ini akan <strong>MENGHAPUS PERMANEN</strong> data <strong id="forceDeleteItemName"></strong> beserta berkas medianya dari penyimpanan.</p>
                <p class="text-danger font-semibold text-sm modal-note">
                    Data yang sudah dihapus permanen TIDAK DAPAT dipulihkan kembali dengan cara apa pun!
                </p>
            </div>
            <div class="admin-modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('forceDeleteModal')">Batal</button>
                <form id="forceDeleteForm" method="POST" action="" class="inline-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus Permanen Sekarang</button>
                </form>
            </div>
        </div>
    </div>

    <!-- 4. Remove Account Modal -->
    <div class="admin-modal" id="removeAccountModal" aria-hidden="true" role="dialog">
        <div class="admin-modal-backdrop" onclick="closeModal('removeAccountModal')"></div>
        <div class="admin-modal-dialog">
            <div class="admin-modal-header">
                <h3 class="admin-modal-title">Konfirmasi Nonaktifkan Akun Admin</h3>
                <button type="button" class="admin-modal-close" onclick="closeModal('removeAccountModal')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="admin-modal-body">
                <p>Apakah Anda yakin ingin menonaktifkan akun <strong id="removeAccountUsername"></strong>?</p>
                <p class="text-muted text-sm modal-note">
                    Akun ini tidak akan dapat login lagi secara permanen (Logical Removal). Riwayat identitas akun tetap disimpan untuk arsip audit, dan username tidak dapat didaftarkan ulang.
                </p>
            </div>
            <div class="admin-modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('removeAccountModal')">Batal</button>
                <form id="removeAccountForm" method="POST" action="" class="inline-form">
                    @csrf
                    <button type="submit" class="btn btn-danger">Ya, Nonaktifkan Akun</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('adminSidebar');
            const toggleBtn = document.getElementById('menuToggleBtn');
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
        });

        function openDeactivateModal(actionUrl, itemName) {
            document.getElementById('deactivateForm').action = actionUrl;
            document.getElementById('deactivateItemName').textContent = itemName;
            document.getElementById('deactivateModal').classList.add('show');
        }

        function openRestoreModal(actionUrl, itemName) {
            document.getElementById('restoreForm').action = actionUrl;
            document.getElementById('restoreItemName').textContent = itemName;
            document.getElementById('restoreModal').classList.add('show');
        }

        function openForceDeleteModal(actionUrl, itemName) {
            document.getElementById('forceDeleteForm').action = actionUrl;
            document.getElementById('forceDeleteItemName').textContent = itemName;
            document.getElementById('forceDeleteModal').classList.add('show');
        }

        function openRemoveAccountModal(actionUrl, username) {
            document.getElementById('removeAccountForm').action = actionUrl;
            document.getElementById('removeAccountUsername').textContent = username;
            document.getElementById('removeAccountModal').classList.add('show');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
        }
    </script>
    <x-admin.ai-modal />
    @stack('scripts')
</body>
</html>
