<?php

use App\Http\Controllers\Admin\AgendaKegiatanController as AdminAgendaController;
use App\Http\Controllers\Admin\AiAssistantController;
use App\Http\Controllers\Admin\CoordinateResolverController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FasilitasController as AdminFasilitasController;
use App\Http\Controllers\Admin\KontakPelayananController as AdminKontakController;
use App\Http\Controllers\Admin\PengumumanController as AdminPengumumanController;
use App\Http\Controllers\Admin\ProfilDusunController as AdminProfilDusunController;
use App\Http\Controllers\Admin\UmkmController as AdminUmkmController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Public\AgendaController;
use App\Http\Controllers\Public\DusunController;
use App\Http\Controllers\Public\FasilitasController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PengumumanArchiveController;
use App\Http\Controllers\Public\PengumumanController;
use App\Http\Controllers\Public\UmkmController;
use App\Http\Controllers\SuperAdmin\AdminAccountController;
use App\Http\Controllers\SuperAdmin\AgendaKegiatanController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\DataPetaController;
use App\Http\Controllers\SuperAdmin\DesaController;
use App\Http\Controllers\SuperAdmin\KategoriFasilitasController;
use App\Http\Controllers\SuperAdmin\KontakPelayananController;
use Illuminate\Support\Facades\Route;

// ════════════════════════════════════════════════════════════════════════════
// PUBLIC ROUTES — no auth middleware (7 routes)
// ════════════════════════════════════════════════════════════════════════════

// UX-SCR-001 + UX-SCR-008 — Homepage (Peta Desa is section #peta-desa)
Route::get('/', HomeController::class)->name('home');

// UX-SCR-002 + UX-SCR-009 — Halaman Dusun (Peta Dusun is section #peta-dusun)
Route::get('/dusun/{id}', [DusunController::class, 'show'])
    ->whereNumber('id')
    ->name('dusun.show');

// UX-SCR-003 — Arsip Pengumuman (MUST be declared before /{id} route)
Route::get('/pengumuman/arsip', [PengumumanArchiveController::class, 'index'])
    ->name('pengumuman.arsip');

// UX-SCR-004 — Detail UMKM
Route::get('/umkm/{id}', [UmkmController::class, 'show'])
    ->whereNumber('id')
    ->name('umkm.show');

// UX-SCR-005 — Detail Fasilitas
Route::get('/fasilitas/{id}', [FasilitasController::class, 'show'])
    ->whereNumber('id')
    ->name('fasilitas.show');

// UX-SCR-006 — Detail Agenda/Kegiatan
Route::get('/agenda/{id}', [AgendaController::class, 'show'])
    ->whereNumber('id')
    ->name('agenda.show');

// UX-SCR-007 — Detail Pengumuman
Route::get('/pengumuman/{id}', [PengumumanController::class, 'show'])
    ->whereNumber('id')
    ->name('pengumuman.show');

// ════════════════════════════════════════════════════════════════════════════
// AUTHENTICATION ROUTES — DEV-04 (preserved)
// ════════════════════════════════════════════════════════════════════════════

Route::middleware('guest')->group(function (): void {
    Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.submit');
});

Route::middleware(['auth', 'admin.active'])->group(function (): void {
    Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

    // AI Assistant Endpoint — Shared between Admin Dusun and Super Admin
    Route::post('/admin/ai/generate-draft', [AiAssistantController::class, 'generate'])
        ->middleware('throttle:60,1')
        ->name('admin.ai.generate-draft');

    // Google Maps Link Coordinate Resolver Endpoint — Shared between Admin Dusun and Super Admin
    Route::post('/admin/resolve-coordinate', [CoordinateResolverController::class, 'resolve'])
        ->middleware('throttle:60,1')
        ->name('admin.resolve-coordinate');

    // ════════════════════════════════════════════════════════════════════════════
    // ADMIN DUSUN MANAGEMENT ROUTES — DEV-06 (UX-SCR-011 through UX-SCR-017)
    // ════════════════════════════════════════════════════════════════════════════
    Route::middleware('role:ADMIN_DUSUN')
        ->prefix('admin-dusun')
        ->name('admin-dusun.')
        ->group(function (): void {
            // UX-SCR-011: Dashboard Dusun
            Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');

            // UX-SCR-012: Kelola Profil Dusun
            Route::get('/profil', [AdminProfilDusunController::class, 'edit'])->name('profil.edit');
            Route::put('/profil', [AdminProfilDusunController::class, 'update'])->name('profil.update');

            // UX-SCR-013: Kelola Kontak Pelayanan
            Route::resource('kontak', AdminKontakController::class)
                ->except(['show'])
                ->names('kontak');

            // UX-SCR-014: Kelola UMKM
            Route::resource('umkm', AdminUmkmController::class)
                ->except(['show'])
                ->names('umkm');

            // UX-SCR-015: Kelola Fasilitas
            Route::resource('fasilitas', AdminFasilitasController::class)
                ->except(['show'])
                ->names('fasilitas');

            // UX-SCR-016: Kelola Agenda & Kegiatan
            Route::resource('agenda', AdminAgendaController::class)
                ->except(['show'])
                ->names('agenda');

            // UX-SCR-017: Kelola Pengumuman
            Route::resource('pengumuman', AdminPengumumanController::class)
                ->except(['show'])
                ->names('pengumuman');
        });

    // ════════════════════════════════════════════════════════════════════════════
    // SUPER ADMIN MANAGEMENT ROUTES — DEV-07 (UX-SCR-018 through UX-SCR-028)
    // ════════════════════════════════════════════════════════════════════════════
    Route::middleware('role:SUPER_ADMIN')
        ->prefix('super-admin')
        ->name('super-admin.')
        ->group(function (): void {
            // UX-SCR-018: Dashboard Super Admin
            Route::get('/dashboard', DashboardController::class)->name('dashboard');

            // UX-SCR-019: Kelola Identitas Desa (Singleton)
            Route::get('/desa', [DesaController::class, 'edit'])->name('desa.edit');
            Route::put('/desa', [DesaController::class, 'update'])->name('desa.update');

            // UX-SCR-020: Kelola Dusun (Fixed 6 Dusun)
            Route::get('/dusun', [App\Http\Controllers\SuperAdmin\DusunController::class, 'index'])->name('dusun.index');
            Route::get('/dusun/{id}/edit', [App\Http\Controllers\SuperAdmin\DusunController::class, 'edit'])->whereNumber('id')->name('dusun.edit');
            Route::put('/dusun/{id}', [App\Http\Controllers\SuperAdmin\DusunController::class, 'update'])->whereNumber('id')->name('dusun.update');
            Route::post('/dusun/{id}/activate', [App\Http\Controllers\SuperAdmin\DusunController::class, 'activate'])->whereNumber('id')->name('dusun.activate');
            Route::post('/dusun/{id}/deactivate', [App\Http\Controllers\SuperAdmin\DusunController::class, 'deactivate'])->whereNumber('id')->name('dusun.deactivate');

            // UX-SCR-021: Global Kontak Pelayanan
            Route::get('/kontak', [KontakPelayananController::class, 'index'])->name('kontak.index');
            Route::get('/kontak/create', [KontakPelayananController::class, 'create'])->name('kontak.create');
            Route::post('/kontak', [KontakPelayananController::class, 'store'])->name('kontak.store');
            Route::get('/kontak/{id}/edit', [KontakPelayananController::class, 'edit'])->whereNumber('id')->name('kontak.edit');
            Route::put('/kontak/{id}', [KontakPelayananController::class, 'update'])->whereNumber('id')->name('kontak.update');
            Route::delete('/kontak/{id}', [KontakPelayananController::class, 'destroy'])->whereNumber('id')->name('kontak.destroy');
            Route::post('/kontak/{id}/restore', [KontakPelayananController::class, 'restore'])->whereNumber('id')->name('kontak.restore');
            Route::delete('/kontak/{id}/force', [KontakPelayananController::class, 'forceDelete'])->whereNumber('id')->name('kontak.force-delete');

            // UX-SCR-022: Global UMKM
            Route::get('/umkm', [App\Http\Controllers\SuperAdmin\UmkmController::class, 'index'])->name('umkm.index');
            Route::get('/umkm/create', [App\Http\Controllers\SuperAdmin\UmkmController::class, 'create'])->name('umkm.create');
            Route::post('/umkm', [App\Http\Controllers\SuperAdmin\UmkmController::class, 'store'])->name('umkm.store');
            Route::get('/umkm/{id}/edit', [App\Http\Controllers\SuperAdmin\UmkmController::class, 'edit'])->whereNumber('id')->name('umkm.edit');
            Route::put('/umkm/{id}', [App\Http\Controllers\SuperAdmin\UmkmController::class, 'update'])->whereNumber('id')->name('umkm.update');
            Route::delete('/umkm/{id}', [App\Http\Controllers\SuperAdmin\UmkmController::class, 'destroy'])->whereNumber('id')->name('umkm.destroy');
            Route::post('/umkm/{id}/restore', [App\Http\Controllers\SuperAdmin\UmkmController::class, 'restore'])->whereNumber('id')->name('umkm.restore');
            Route::delete('/umkm/{id}/force', [App\Http\Controllers\SuperAdmin\UmkmController::class, 'forceDelete'])->whereNumber('id')->name('umkm.force-delete');

            // UX-SCR-023: Global Fasilitas
            Route::get('/fasilitas', [App\Http\Controllers\SuperAdmin\FasilitasController::class, 'index'])->name('fasilitas.index');
            Route::get('/fasilitas/create', [App\Http\Controllers\SuperAdmin\FasilitasController::class, 'create'])->name('fasilitas.create');
            Route::post('/fasilitas', [App\Http\Controllers\SuperAdmin\FasilitasController::class, 'store'])->name('fasilitas.store');
            Route::get('/fasilitas/{id}/edit', [App\Http\Controllers\SuperAdmin\FasilitasController::class, 'edit'])->whereNumber('id')->name('fasilitas.edit');
            Route::put('/fasilitas/{id}', [App\Http\Controllers\SuperAdmin\FasilitasController::class, 'update'])->whereNumber('id')->name('fasilitas.update');
            Route::delete('/fasilitas/{id}', [App\Http\Controllers\SuperAdmin\FasilitasController::class, 'destroy'])->whereNumber('id')->name('fasilitas.destroy');
            Route::post('/fasilitas/{id}/restore', [App\Http\Controllers\SuperAdmin\FasilitasController::class, 'restore'])->whereNumber('id')->name('fasilitas.restore');
            Route::delete('/fasilitas/{id}/force', [App\Http\Controllers\SuperAdmin\FasilitasController::class, 'forceDelete'])->whereNumber('id')->name('fasilitas.force-delete');

            // UX-SCR-024: Kelola Kategori Fasilitas
            Route::get('/kategori-fasilitas', [KategoriFasilitasController::class, 'index'])->name('kategori-fasilitas.index');
            Route::get('/kategori-fasilitas/create', [KategoriFasilitasController::class, 'create'])->name('kategori-fasilitas.create');
            Route::post('/kategori-fasilitas', [KategoriFasilitasController::class, 'store'])->name('kategori-fasilitas.store');
            Route::get('/kategori-fasilitas/{id}/edit', [KategoriFasilitasController::class, 'edit'])->whereNumber('id')->name('kategori-fasilitas.edit');
            Route::put('/kategori-fasilitas/{id}', [KategoriFasilitasController::class, 'update'])->whereNumber('id')->name('kategori-fasilitas.update');
            Route::delete('/kategori-fasilitas/{id}', [KategoriFasilitasController::class, 'destroy'])->whereNumber('id')->name('kategori-fasilitas.destroy');

            // UX-SCR-025: Global Agenda & Kegiatan
            Route::get('/agenda', [AgendaKegiatanController::class, 'index'])->name('agenda.index');
            Route::get('/agenda/create', [AgendaKegiatanController::class, 'create'])->name('agenda.create');
            Route::post('/agenda', [AgendaKegiatanController::class, 'store'])->name('agenda.store');
            Route::get('/agenda/{id}/edit', [AgendaKegiatanController::class, 'edit'])->whereNumber('id')->name('agenda.edit');
            Route::put('/agenda/{id}', [AgendaKegiatanController::class, 'update'])->whereNumber('id')->name('agenda.update');
            Route::delete('/agenda/{id}', [AgendaKegiatanController::class, 'destroy'])->whereNumber('id')->name('agenda.destroy');
            Route::post('/agenda/{id}/restore', [AgendaKegiatanController::class, 'restore'])->whereNumber('id')->name('agenda.restore');
            Route::delete('/agenda/{id}/force', [AgendaKegiatanController::class, 'forceDelete'])->whereNumber('id')->name('agenda.force-delete');

            // UX-SCR-026: Global Pengumuman
            Route::get('/pengumuman', [App\Http\Controllers\SuperAdmin\PengumumanController::class, 'index'])->name('pengumuman.index');
            Route::get('/pengumuman/create', [App\Http\Controllers\SuperAdmin\PengumumanController::class, 'create'])->name('pengumuman.create');
            Route::post('/pengumuman', [App\Http\Controllers\SuperAdmin\PengumumanController::class, 'store'])->name('pengumuman.store');
            Route::get('/pengumuman/{id}/edit', [App\Http\Controllers\SuperAdmin\PengumumanController::class, 'edit'])->whereNumber('id')->name('pengumuman.edit');
            Route::put('/pengumuman/{id}', [App\Http\Controllers\SuperAdmin\PengumumanController::class, 'update'])->whereNumber('id')->name('pengumuman.update');
            Route::delete('/pengumuman/{id}', [App\Http\Controllers\SuperAdmin\PengumumanController::class, 'destroy'])->whereNumber('id')->name('pengumuman.destroy');
            Route::post('/pengumuman/{id}/restore', [App\Http\Controllers\SuperAdmin\PengumumanController::class, 'restore'])->whereNumber('id')->name('pengumuman.restore');
            Route::delete('/pengumuman/{id}/force', [App\Http\Controllers\SuperAdmin\PengumumanController::class, 'forceDelete'])->whereNumber('id')->name('pengumuman.force-delete');

            // UX-SCR-027: Data / Peta (Map-centric projection only)
            Route::get('/data-peta', DataPetaController::class)->name('data-peta');

            // UX-SCR-028: Kelola Admin Dusun
            Route::get('/admin-dusun', [AdminAccountController::class, 'index'])->name('admin-dusun.index');
            Route::get('/admin-dusun/create', [AdminAccountController::class, 'create'])->name('admin-dusun.create');
            Route::post('/admin-dusun', [AdminAccountController::class, 'store'])->name('admin-dusun.store');
            Route::get('/admin-dusun/{id}/edit', [AdminAccountController::class, 'edit'])->whereNumber('id')->name('admin-dusun.edit');
            Route::put('/admin-dusun/{id}', [AdminAccountController::class, 'update'])->whereNumber('id')->name('admin-dusun.update');
            Route::get('/admin-dusun/{id}/reset-password', [AdminAccountController::class, 'showResetPasswordForm'])->whereNumber('id')->name('admin-dusun.reset-password');
            Route::put('/admin-dusun/{id}/reset-password', [AdminAccountController::class, 'resetPassword'])->whereNumber('id')->name('admin-dusun.reset-password.submit');
            Route::post('/admin-dusun/{id}/remove', [AdminAccountController::class, 'remove'])->whereNumber('id')->name('admin-dusun.remove');
        });
});
