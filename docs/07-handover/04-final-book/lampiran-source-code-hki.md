# LAMPIRAN: CUPLIKAN KODE SUMBER (SOURCE CODE)
## Program Komputer: Portal Informasi Desa Bendung

---

**Nama Program**  : Portal Informasi Desa Bendung  
**Jenis Ciptaan** : Program Komputer  
**Bahasa Pemrograman** : PHP 8.x (Framework Laravel 12), Blade Template Engine, JavaScript (Leaflet.js), CSS  
**Basis Data**    : MySQL / MariaDB  
**Penyusun**      : Tim KKN Universitas Muhammadiyah Gresik — Prodi Informatika, Tahun 2026  

---

> **Catatan Lampiran**  
> Lampiran ini menyajikan cuplikan representatif kode sumber (*source code*) dari program komputer "Portal Informasi Desa Bendung". Kode yang ditampilkan merupakan bagian-bagian inti yang merepresentasikan orisinalitas arsitektur, logika bisnis, dan struktur basis data sistem. Kode lengkap tidak disertakan secara penuh mengingat keterbatasan halaman; cuplikan ini mencakup modul-modul utama yang mencerminkan keseluruhan karya cipta program.

---

## L-1. Arsitektur & Struktur Folder Aplikasi

Program ini dibangun menggunakan arsitektur **MVC (Model-View-Controller)** berbasis framework Laravel. Berikut struktur folder utama proyek:

```
portal-desa-bendung/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── LoginController.php       ← Autentikasi multi-role
│   │   │   ├── Public/
│   │   │   │   ├── HomeController.php         ← Halaman beranda & peta desa
│   │   │   │   ├── DusunController.php        ← Halaman profil per-dusun
│   │   │   │   ├── UmkmController.php         ← Detail UMKM publik
│   │   │   │   ├── FasilitasController.php    ← Detail fasilitas publik
│   │   │   │   ├── AgendaController.php       ← Detail agenda publik
│   │   │   │   ├── PengumumanController.php   ← Detail pengumuman publik
│   │   │   │   └── PengumumanArchiveController.php ← Arsip pengumuman
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php    ← Dashboard Admin Dusun
│   │   │   │   ├── UmkmController.php         ← CRUD UMKM (Admin Dusun)
│   │   │   │   ├── FasilitasController.php    ← CRUD Fasilitas (Admin Dusun)
│   │   │   │   ├── AgendaKegiatanController.php ← CRUD Agenda (Admin Dusun)
│   │   │   │   ├── PengumumanController.php   ← CRUD Pengumuman (Admin Dusun)
│   │   │   │   ├── KontakPelayananController.php ← CRUD Kontak (Admin Dusun)
│   │   │   │   └── ProfilDusunController.php  ← Edit Profil Dusun
│   │   │   └── SuperAdmin/
│   │   │       ├── DashboardController.php    ← Dashboard Super Admin
│   │   │       ├── DesaController.php         ← Kelola identitas desa
│   │   │       ├── DusunController.php        ← Kelola 6 dusun (activate/deactivate)
│   │   │       ├── AdminAccountController.php ← Kelola akun operator dusun
│   │   │       ├── UmkmController.php         ← Monitoring UMKM lintas dusun
│   │   │       ├── FasilitasController.php    ← Monitoring fasilitas lintas dusun
│   │   │       ├── KategoriFasilitasController.php ← Kelola kategori fasilitas
│   │   │       ├── AgendaKegiatanController.php ← Warta desa (agenda global)
│   │   │       ├── PengumumanController.php   ← Pengumuman desa global
│   │   │       ├── KontakPelayananController.php ← Kontak global lintas dusun
│   │   │       └── DataPetaController.php     ← Monitoring spasial/peta
│   │   ├── Middleware/
│   │   │   ├── EnsureRole.php                 ← Guard berbasis peran (role-based)
│   │   │   └── EnsureAdminAccountActive.php   ← Guard status akun aktif
│   │   └── Requests/                          ← Form Request Validation
│   ├── Models/
│   │   ├── Desa.php          ← Entitas Desa (singleton)
│   │   ├── Dusun.php         ← Entitas Dusun (6 dusun tetap)
│   │   ├── AdminAccount.php  ← Entitas Akun Operator (multi-role)
│   │   ├── Umkm.php          ← Entitas UMKM + Soft Delete + Koordinat
│   │   ├── Fasilitas.php     ← Entitas Fasilitas + Soft Delete + Koordinat
│   │   ├── KontakPelayanan.php ← Entitas Kontak Pelayanan + Koordinat
│   │   ├── AgendaKegiatan.php  ← Entitas Agenda/Kegiatan + Soft Delete
│   │   ├── Pengumuman.php    ← Entitas Pengumuman + Scope + Soft Delete
│   │   ├── KategoriFasilitas.php ← Entitas Kategori Fasilitas
│   │   ├── ProdukUmkm.php    ← Entitas Produk UMKM (child dari Umkm)
│   │   └── AgendaMedia.php   ← Entitas Media/Foto Agenda
│   └── Services/
│       └── MediaService.php  ← Layanan pengelolaan file media (upload/delete)
├── database/
│   ├── migrations/           ← 12 file migrasi skema basis data
│   └── seeders/              ← Data awal (desa, dusun, akun super admin)
├── resources/
│   └── views/                ← Template antarmuka (Blade)
│       ├── public/           ← Tampilan portal publik (masyarakat)
│       ├── admin/            ← Tampilan panel Admin Dusun
│       ├── super-admin/      ← Tampilan panel Super Admin
│       └── auth/             ← Tampilan halaman login
└── routes/
    └── web.php               ← Definisi seluruh rute HTTP aplikasi
```

---

## L-2. Definisi Rute Sistem (`routes/web.php`)

File ini mendefinisikan seluruh jalur URL aplikasi beserta middleware pengaman akses. Terdapat tiga kelompok rute utama: rute publik, rute Admin Dusun (terproteksi role `ADMIN_DUSUN`), dan rute Super Admin (terproteksi role `SUPER_ADMIN`).

```php
<?php

use App\Http\Controllers\Admin\AgendaKegiatanController as AdminAgendaController;
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
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\DataPetaController;
use App\Http\Controllers\SuperAdmin\DesaController;
use Illuminate\Support\Facades\Route;

// ════════════════════════════════════════════════════════════════════════════
// RUTE PUBLIK — tidak memerlukan autentikasi
// ════════════════════════════════════════════════════════════════════════════

Route::get('/', HomeController::class)->name('home');
Route::get('/dusun/{id}', [DusunController::class, 'show'])->whereNumber('id')->name('dusun.show');
Route::get('/pengumuman/arsip', [PengumumanArchiveController::class, 'index'])->name('pengumuman.arsip');
Route::get('/umkm/{id}', [UmkmController::class, 'show'])->whereNumber('id')->name('umkm.show');
Route::get('/fasilitas/{id}', [FasilitasController::class, 'show'])->whereNumber('id')->name('fasilitas.show');
Route::get('/agenda/{id}', [AgendaController::class, 'show'])->whereNumber('id')->name('agenda.show');
Route::get('/pengumuman/{id}', [PengumumanController::class, 'show'])->whereNumber('id')->name('pengumuman.show');

// ════════════════════════════════════════════════════════════════════════════
// RUTE AUTENTIKASI
// ════════════════════════════════════════════════════════════════════════════

Route::middleware('guest')->group(function (): void {
    Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.submit');
});

Route::middleware(['auth', 'admin.active'])->group(function (): void {
    Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

    // ════════════════════════════════════════════════════════════════════════
    // RUTE PANEL ADMIN DUSUN — terproteksi role ADMIN_DUSUN
    // ════════════════════════════════════════════════════════════════════════
    Route::middleware('role:ADMIN_DUSUN')
        ->prefix('admin-dusun')
        ->name('admin-dusun.')
        ->group(function (): void {
            Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
            Route::get('/profil', [AdminProfilDusunController::class, 'edit'])->name('profil.edit');
            Route::put('/profil', [AdminProfilDusunController::class, 'update'])->name('profil.update');
            Route::resource('kontak', AdminKontakController::class)->except(['show'])->names('kontak');
            Route::resource('umkm', AdminUmkmController::class)->except(['show'])->names('umkm');
            Route::resource('fasilitas', AdminFasilitasController::class)->except(['show'])->names('fasilitas');
            Route::resource('agenda', AdminAgendaController::class)->except(['show'])->names('agenda');
            Route::resource('pengumuman', AdminPengumumanController::class)->except(['show'])->names('pengumuman');
        });

    // ════════════════════════════════════════════════════════════════════════
    // RUTE PANEL SUPER ADMIN — terproteksi role SUPER_ADMIN
    // ════════════════════════════════════════════════════════════════════════
    Route::middleware('role:SUPER_ADMIN')
        ->prefix('super-admin')
        ->name('super-admin.')
        ->group(function (): void {
            Route::get('/dashboard', DashboardController::class)->name('dashboard');
            Route::get('/desa', [DesaController::class, 'edit'])->name('desa.edit');
            Route::put('/desa', [DesaController::class, 'update'])->name('desa.update');
            Route::get('/data-peta', DataPetaController::class)->name('data-peta');
            Route::get('/admin-dusun', [AdminAccountController::class, 'index'])->name('admin-dusun.index');
            Route::post('/admin-dusun/{id}/remove', [AdminAccountController::class, 'remove'])
                ->whereNumber('id')->name('admin-dusun.remove');
            // ... (rute CRUD global untuk UMKM, Fasilitas, Agenda, Pengumuman, Kontak)
        });
});
```

---

## L-3. Middleware Kontrol Akses Berbasis Peran (`EnsureRole.php`)

Middleware ini memastikan setiap pengguna yang mengakses panel admin hanya dapat masuk ke halaman yang sesuai dengan peran (*role*) yang dimiliki. Jika peran tidak sesuai, sistem menolak akses dengan kode HTTP 403.

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if ($user === null || $user->role !== $role) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
```

---

## L-4. Logika Autentikasi Multi-Peran (`LoginController.php`)

Controller ini menangani proses login dengan mekanisme *rate limiting* (pembatasan percobaan login) untuk keamanan, serta pengarahan otomatis berdasarkan peran pengguna setelah berhasil masuk.

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\AdminAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user instanceof AdminAccount && $user->isAdminDusun()) {
                return redirect()->route('admin-dusun.dashboard');
            }

            if ($user instanceof AdminAccount && $user->isSuperAdmin()) {
                return redirect()->route('super-admin.dashboard');
            }
        }

        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $username = trim($request->input('username'));
        $throttleKey = Str::transliterate(Str::lower($username).'|'.$request->ip());

        // Batasi maksimal 5 percobaan login gagal dalam 60 detik
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'username' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam '.$seconds.' detik.',
            ]);
        }

        $credentials = [
            'username'   => $username,
            'password'   => $request->input('password'),
            'removed_at' => null, // Akun yang telah dinonaktifkan tidak dapat login
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user instanceof AdminAccount && $user->isAdminDusun()) {
                return redirect()->intended(route('admin-dusun.dashboard'));
            }

            if ($user instanceof AdminAccount && $user->isSuperAdmin()) {
                return redirect()->intended(route('super-admin.dashboard'));
            }
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'username' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
```

---

## L-5. Model Data & Relasi Antar Entitas

### L-5.1. Model Dusun (`Dusun.php`)

Model ini merepresentasikan entitas dusun dan mendefinisikan seluruh relasi *one-to-many* ke entitas turunannya (UMKM, Fasilitas, Kontak, Agenda, Pengumuman). Terdapat *query scope* untuk memfilter dusun yang aktif secara publik.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dusun extends Model
{
    protected $table = 'dusuns';

    // ─── Relasi ke Entitas Induk ───────────────────────────────────────────
    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    // ─── Relasi ke Entitas Turunan ─────────────────────────────────────────
    public function adminAccounts(): HasMany  { return $this->hasMany(AdminAccount::class, 'dusun_id'); }
    public function kontakPelayanans(): HasMany { return $this->hasMany(KontakPelayanan::class, 'dusun_id'); }
    public function umkms(): HasMany           { return $this->hasMany(Umkm::class, 'dusun_id'); }
    public function fasilitas(): HasMany       { return $this->hasMany(Fasilitas::class, 'dusun_id'); }
    public function agendaKegiatans(): HasMany  { return $this->hasMany(AgendaKegiatan::class, 'dusun_id'); }
    public function pengumumans(): HasMany     { return $this->hasMany(Pengumuman::class, 'dusun_id'); }

    // ─── Query Scope: Visibilitas Publik ──────────────────────────────────
    /**
     * Hanya Dusun berstatus ACTIVE yang tampil di portal publik.
     */
    public function scopePublicActive($query)
    {
        return $query->where('status_dusun', 'ACTIVE');
    }
}
```

### L-5.2. Model UMKM (`Umkm.php`)

Model UMKM mengimplementasikan *Soft Deletes* (penghapusan lunak) sehingga data tidak hilang secara permanen, dan dilengkapi *scope* untuk memfilter UMKM berdasarkan status dusun induk dan kelengkapan koordinat peta.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Umkm extends Model
{
    use SoftDeletes;

    protected $table = 'umkms';

    protected function casts(): array
    {
        return [
            'latitude'  => 'decimal:6',
            'longitude' => 'decimal:6',
        ];
    }

    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class, 'dusun_id');
    }

    public function produkUmkms(): HasMany
    {
        return $this->hasMany(ProdukUmkm::class, 'umkm_id');
    }

    // ─── Query Scope: Visibilitas Publik ──────────────────────────────────

    /**
     * Hanya UMKM yang berada di bawah Dusun ACTIVE yang tampil secara publik.
     */
    public function scopeWithinActiveDusun($query)
    {
        return $query->whereHas('dusun', fn ($q) => $q->where('status_dusun', 'ACTIVE'));
    }

    /**
     * Hanya UMKM yang memiliki koordinat GPS yang tampil sebagai marker di peta.
     */
    public function scopeWithCoordinates($query)
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }
}
```

### L-5.3. Model Pengumuman (`Pengumuman.php`)

Model ini mengimplementasikan logika *scope* pengumuman aktif/kedaluwarsa berdasarkan tanggal, serta pembedaan cakupan antara pengumuman tingkat Desa (global) dan tingkat Dusun.

```php
<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengumuman extends Model
{
    use SoftDeletes;

    protected $table = 'pengumumans';

    protected function casts(): array
    {
        return ['tanggal_kedaluwarsa' => 'date'];
    }

    public function desa(): BelongsTo { return $this->belongsTo(Desa::class, 'desa_id'); }
    public function dusun(): BelongsTo { return $this->belongsTo(Dusun::class, 'dusun_id'); }

    /**
     * Memeriksa apakah pengumuman sudah kedaluwarsa pada tanggal bisnis tertentu.
     */
    public function isArchivedFor(CarbonInterface $businessDate): bool
    {
        $timezone = config('app.business_timezone', 'Asia/Jakarta');
        $day = CarbonImmutable::parse(
            CarbonImmutable::instance($businessDate)->setTimezone($timezone)->toDateString(),
            $timezone,
        )->startOfDay();
        $expiresOn = CarbonImmutable::parse(
            $this->tanggal_kedaluwarsa->toDateString(),
            $timezone,
        )->startOfDay();

        return $day->isAfter($expiresOn);
    }

    // ─── Query Scope: Filter Status ───────────────────────────────────────

    /** Pengumuman aktif: tanggal kedaluwarsa >= hari ini (WIB). */
    public function scopeActiveAnnouncements($query, ?string $timezone = 'Asia/Jakarta')
    {
        return $query->whereDate('tanggal_kedaluwarsa', '>=', now($timezone)->toDateString());
    }

    /** Pengumuman arsip: tanggal kedaluwarsa < hari ini (WIB). */
    public function scopeArchivedAnnouncements($query, ?string $timezone = 'Asia/Jakarta')
    {
        return $query->whereDate('tanggal_kedaluwarsa', '<', now($timezone)->toDateString());
    }

    /** Filter cakupan: hanya pengumuman tingkat DESA (global). */
    public function scopeDesaScope($query) { return $query->where('scope_level', 'DESA'); }

    /** Filter cakupan: hanya pengumuman tingkat DUSUN (per-dusun). */
    public function scopeDusunScope($query) { return $query->where('scope_level', 'DUSUN'); }

    /** Hanya tampil pengumuman dusun yang dusun induknya berstatus ACTIVE. */
    public function scopeWithinActiveDusun($query)
    {
        return $query->whereHas('dusun', fn ($q) => $q->where('status_dusun', 'ACTIVE'));
    }
}
```

### L-5.4. Model Akun Admin (`AdminAccount.php`)

Model ini mengimplementasikan sistem autentikasi dengan dua peran berbeda: `ADMIN_DUSUN` (operator dusun) dan `SUPER_ADMIN` (administrator utama), beserta mekanisme penonaktifan akun menggunakan kolom `removed_at`.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminAccount extends Authenticatable
{
    public const ROLE_ADMIN_DUSUN = 'ADMIN_DUSUN';
    public const ROLE_SUPER_ADMIN = 'SUPER_ADMIN';

    protected $table  = 'admin_accounts';
    protected $guarded = ['*'];
    protected $hidden  = ['password_hash', 'remember_token'];

    protected function casts(): array
    {
        return ['removed_at' => 'datetime'];
    }

    // ─── Override kolom password untuk kompatibilitas Laravel Auth ────────
    public function getAuthPasswordName(): string { return 'password_hash'; }
    public function getAuthPassword(): string     { return $this->password_hash; }

    // ─── Pemeriksaan Peran ────────────────────────────────────────────────
    public function isAdminDusun(): bool { return $this->role === self::ROLE_ADMIN_DUSUN; }
    public function isSuperAdmin(): bool { return $this->role === self::ROLE_SUPER_ADMIN; }

    /** Akun yang telah dinonaktifkan (removed_at terisi) tidak dapat login. */
    public function isRemoved(): bool    { return $this->removed_at !== null; }

    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class, 'dusun_id');
    }
}
```

---

## L-6. Controller Beranda & Sistem Peta Spasial (`HomeController.php`)

Controller ini menggabungkan data dari tiga entitas berbeda (Fasilitas, UMKM, Kontak Pelayanan) menjadi satu kumpulan *marker* peta GIS yang dikirim ke antarmuka pengguna dalam format JSON untuk dirender oleh pustaka peta Leaflet.js.

```php
<?php

namespace App\Http\Controllers\Public;

use App\Models\AgendaKegiatan;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\Fasilitas;
use App\Models\KontakPelayanan;
use App\Models\Pengumuman;
use App\Models\Umkm;
use Illuminate\Support\Js;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda portal desa beserta data peta spasial.
     */
    public function __invoke()
    {
        $desa    = Desa::query()->first();
        $dusuns  = Dusun::publicActive()->orderBy('nama_dusun')->get();

        // Pengumuman & Agenda terbaru (lingkup Desa, 3 item)
        $pengumumans = Pengumuman::desaScope()->activeAnnouncements()->latest()->take(3)->get();
        $agendas     = AgendaKegiatan::desaScope()->orderBy('tanggal_mulai', 'desc')->take(3)->get();

        // ── Pengumpulan Marker Peta Spasial ─────────────────────────────────

        // Marker Fasilitas: hanya yang memiliki koordinat & dusun ACTIVE
        $fasilitasMarkers = Fasilitas::withCoordinates()->withinActiveDusun()
            ->with(['dusun:id,nama_dusun', 'kategoriFasilitas:id,nama_kategori'])
            ->get()->toBase()
            ->map(fn ($f) => [
                'lat'         => (float) $f->latitude,
                'lng'         => (float) $f->longitude,
                'name'        => $f->nama,
                'category'    => $f->kategoriFasilitas?->nama_kategori ?? 'Fasilitas',
                'address'     => $f->alamat,
                'marker_type' => 'DEFAULT',
                'dusun_id'    => $f->dusun_id,
                'detail_url'  => route('fasilitas.show', $f->id),
                'photo_url'   => $f->foto_path ? asset('storage/'.$f->foto_path) : null,
            ]);

        // Marker UMKM: hanya yang memiliki koordinat & dusun ACTIVE
        $umkmMarkers = Umkm::withCoordinates()->withinActiveDusun()
            ->with(['dusun:id,nama_dusun'])->get()->toBase()
            ->map(fn ($u) => [
                'lat'         => (float) $u->latitude,
                'lng'         => (float) $u->longitude,
                'name'        => $u->nama_umkm,
                'category'    => 'UMKM',
                'address'     => $u->alamat,
                'marker_type' => 'UMKM',
                'dusun_id'    => $u->dusun_id,
                'detail_url'  => route('umkm.show', $u->id),
                'photo_url'   => $u->foto_utama_path ? asset('storage/'.$u->foto_utama_path) : null,
            ]);

        // Marker Kontak Pelayanan: hanya yang memiliki koordinat & dusun ACTIVE
        $kontakMarkers = KontakPelayanan::withCoordinates()->withinActiveDusun()
            ->with(['dusun:id,nama_dusun'])->get()->toBase()
            ->map(fn ($k) => [
                'lat'         => (float) $k->latitude,
                'lng'         => (float) $k->longitude,
                'name'        => $k->nama,
                'category'    => 'PELAYANAN',
                'address'     => $k->alamat_pelayanan,
                'marker_type' => 'PELAYANAN',
                'dusun_id'    => $k->dusun_id,
                'detail_url'  => route('dusun.show', $k->dusun_id).'#kontak-pelayanan',
                'photo_url'   => $k->foto_path ? asset('storage/'.$k->foto_path) : null,
            ]);

        $markers = $fasilitasMarkers->concat($umkmMarkers)->concat($kontakMarkers)->values();

        return view('public.home', [
            'desa'               => $desa,
            'dusuns'             => $dusuns,
            'pengumumans'        => $pengumumans,
            'agendas'            => $agendas,
            'dusunFilterOptions' => $dusuns->map(fn ($d) => ['id' => $d->id, 'nama' => $d->nama_dusun]),
            'categoryOptions'    => $markers->pluck('category')->unique()->values(),
            'mapConfigJson'      => Js::from(['center' => [-7.6298, 110.8603], 'zoom' => 13]),
            'mapMarkersJson'     => Js::from($markers->toArray()),
        ]);
    }
}
```

---

## L-7. Controller Manajemen UMKM — Panel Admin Dusun (`Admin/UmkmController.php`)

Controller ini menangani operasi CRUD data UMKM yang dilindungi kebijakan otorisasi (*Policy*), termasuk logika transaksi basis data, pengelolaan file foto, dan rekonsiliasi data produk.

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UmkmRequest;
use App\Models\ProdukUmkm;
use App\Models\Umkm;
use App\Services\MediaService;
use Illuminate\Support\Facades\DB;

class UmkmController extends Controller
{
    /**
     * Menampilkan daftar UMKM milik Dusun yang sedang login.
     */
    public function index(Request $request): View
    {
        $dusun   = $request->user()->dusun;
        $umkmList = Umkm::where('dusun_id', $dusun->id)
            ->with('produkUmkms')->orderBy('nama_umkm')->paginate(15);

        return view('admin.umkm.index', compact('umkmList', 'dusun'));
    }

    /**
     * Menyimpan data UMKM baru beserta produk dan foto ke dalam basis data.
     * Menggunakan transaksi DB untuk menjamin konsistensi data.
     */
    public function store(UmkmRequest $request, MediaService $mediaService): RedirectResponse
    {
        $dusun    = $request->user()->dusun;
        $this->authorize('create', [Umkm::class, $dusun->id]);
        $validated = $request->validated();

        DB::transaction(function () use ($dusun, $validated, $request, $mediaService): void {
            $fotoPath = null;
            if ($request->hasFile('foto_utama')) {
                $fotoPath = $mediaService->storeImage($request->file('foto_utama'), 'umkm');
            }

            $umkm = Umkm::forceCreate([
                'dusun_id'        => $dusun->id,
                'nama_umkm'       => $validated['nama_umkm'],
                'nama_pemilik'    => $validated['nama_pemilik'],
                'jenis_usaha'     => $validated['jenis_usaha'],
                'deskripsi'       => $validated['deskripsi'],
                'alamat'          => $validated['alamat'],
                'nomor_whatsapp'  => $validated['nomor_whatsapp'],
                'jam_operasional' => $validated['jam_operasional'],
                'foto_utama_path' => $fotoPath,
                'latitude'        => $validated['latitude'] ?? null,
                'longitude'       => $validated['longitude'] ?? null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // Simpan daftar produk UMKM
            foreach ($validated['produk'] ?? [] as $p) {
                $namaProduk = trim($p['nama_produk'] ?? '');
                if ($namaProduk !== '') {
                    ProdukUmkm::forceCreate([
                        'umkm_id'     => $umkm->id,
                        'nama_produk' => $namaProduk,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                }
            }
        });

        return redirect()->route('admin-dusun.umkm.index')->with('success', 'Data UMKM berhasil disimpan.');
    }

    /**
     * Menonaktifkan (soft delete) data UMKM. Data tidak dihapus secara permanen.
     */
    public function destroy(Request $request, int $id): RedirectResponse
    {
        $dusun = $request->user()->dusun;
        $umkm  = Umkm::where('dusun_id', $dusun->id)->findOrFail($id);
        $this->authorize('delete', $umkm);

        $umkm->delete(); // Soft delete — file media tetap tersimpan

        return redirect()->route('admin-dusun.umkm.index')->with('success', 'Data berhasil dinonaktifkan.');
    }
}
```

---

## L-8. Skema Basis Data (Migration)

### L-8.1. Tabel `dusuns`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dusuns', function (Blueprint $table): void {
            $table->engine    = 'InnoDB';
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->unsignedBigInteger('desa_id');
            $table->string('nama_dusun', 150);
            $table->string('status_dusun', 16)->default('ACTIVE'); // ACTIVE | INACTIVE
            $table->string('banner_path', 512)->nullable();
            $table->text('deskripsi_singkat');
            $table->string('nama_kepala_dusun', 150);
            $table->unsignedSmallInteger('jumlah_rt');
            $table->unsignedSmallInteger('jumlah_rw');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');

            $table->foreign('desa_id', 'fk_dusuns_desa_id')
                ->references('id')->on('desas')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->index(['desa_id', 'status_dusun'], 'idx_dusuns_desa_status');
        });

        // Constraint CHECK: nilai status hanya boleh ACTIVE atau INACTIVE
        DB::statement("ALTER TABLE dusuns ADD CONSTRAINT chk_dusuns_status
            CHECK (status_dusun IN ('ACTIVE', 'INACTIVE'))");
    }
};
```

### L-8.2. Tabel `admin_accounts`

```php
Schema::create('admin_accounts', function (Blueprint $table): void {
    $table->id();
    $table->unsignedBigInteger('dusun_id')->nullable(); // null untuk SUPER_ADMIN
    $table->string('username', 100);
    $table->string('password_hash', 255);
    $table->string('role', 24);             // ADMIN_DUSUN | SUPER_ADMIN
    $table->dateTime('removed_at')->nullable(); // null = akun aktif
    $table->dateTime('created_at');
    $table->dateTime('updated_at');

    $table->unique('username', 'uq_admin_accounts_username');
    $table->index(['dusun_id', 'removed_at'], 'idx_admin_accounts_dusun_removed');
});

// Constraint: role hanya boleh salah satu dari dua nilai
DB::statement("ALTER TABLE admin_accounts ADD CONSTRAINT chk_admin_accounts_role
    CHECK (role IN ('ADMIN_DUSUN', 'SUPER_ADMIN'))");

// Constraint: ADMIN_DUSUN wajib punya dusun_id; SUPER_ADMIN tidak boleh punya dusun_id
DB::statement("ALTER TABLE admin_accounts ADD CONSTRAINT chk_admin_accounts_role_scope
    CHECK ((role = 'ADMIN_DUSUN' AND dusun_id IS NOT NULL)
        OR (role = 'SUPER_ADMIN' AND dusun_id IS NULL))");
```

### L-8.3. Tabel `umkms`

```php
Schema::create('umkms', function (Blueprint $table): void {
    $table->id();
    $table->unsignedBigInteger('dusun_id');
    $table->string('nama_umkm', 200);
    $table->string('nama_pemilik', 150);
    $table->string('jenis_usaha', 150);
    $table->text('deskripsi');
    $table->text('alamat');
    $table->string('nomor_whatsapp', 32);
    $table->string('jam_operasional', 255);
    $table->string('foto_utama_path', 512)->nullable();
    $table->decimal('latitude', 9, 6)->nullable();   // Koordinat GPS
    $table->decimal('longitude', 9, 6)->nullable();  // Koordinat GPS
    $table->dateTime('created_at');
    $table->dateTime('updated_at');
    $table->dateTime('deleted_at')->nullable();       // Soft Delete

    $table->foreign('dusun_id', 'fk_umkms_dusun_id')
        ->references('id')->on('dusuns')->onUpdate('restrict')->onDelete('restrict');
    $table->index(['dusun_id', 'deleted_at'], 'idx_umkms_dusun_deleted');
});

// Constraint: koordinat harus diisi berpasangan (keduanya ada atau keduanya kosong)
DB::statement('ALTER TABLE umkms ADD CONSTRAINT chk_umkms_coordinate_pair
    CHECK ((latitude IS NULL AND longitude IS NULL)
        OR (latitude IS NOT NULL AND longitude IS NOT NULL))');
```

### L-8.4. Tabel `pengumumans`

```php
Schema::create('pengumumans', function (Blueprint $table): void {
    $table->id();
    $table->unsignedBigInteger('desa_id');
    $table->unsignedBigInteger('dusun_id')->nullable(); // null = cakupan DESA
    $table->string('scope_level', 16);      // DESA | DUSUN
    $table->string('judul', 255);
    $table->text('isi');
    $table->date('tanggal_kedaluwarsa');    // Tanggal berakhir aktif
    $table->dateTime('created_at');
    $table->dateTime('updated_at');
    $table->dateTime('deleted_at')->nullable(); // Soft Delete
});

// Constraint: DESA -> dusun_id NULL; DUSUN -> dusun_id NOT NULL
DB::statement("ALTER TABLE pengumumans ADD CONSTRAINT chk_pengumumans_scope
    CHECK ((scope_level = 'DESA' AND dusun_id IS NULL)
        OR (scope_level = 'DUSUN' AND dusun_id IS NOT NULL))");
```

---

## L-9. Ringkasan Statistik Kode Sumber

| Komponen | Jumlah File | Keterangan |
|---|:---:|---|
| Controllers (Public) | 7 | Penanganan rute & logika portal publik |
| Controllers (Admin Dusun) | 8 | CRUD data per-dusun dengan otorisasi |
| Controllers (Super Admin) | 11 | Monitoring & manajemen lintas dusun |
| Controllers (Auth) | 1 | Autentikasi multi-peran |
| Models | 11 | Entitas data dengan relasi & scope |
| Migrations | 12 | Definisi skema 11 tabel basis data |
| Middleware | 2 | Pengaman akses berbasis peran & status |
| Requests (Validasi) | ±10 | Validasi input formulir |
| Views (Blade Templates) | ±80 | Tampilan antarmuka pengguna |
| **Total Rute HTTP** | **±60** | Publik, Admin Dusun, Super Admin |

---

*Lampiran ini merupakan bagian integral dari karya cipta "Portal Informasi Terpadu Desa Bendung" yang didaftarkan sebagai Program Komputer pada Direktorat Jenderal Kekayaan Intelektual (DJKI), Kementerian Hukum dan Hak Asasi Manusia Republik Indonesia.*
