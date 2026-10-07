# As-Built User Manual Audit

## 1. Audit Metadata

| Item | Nilai |
|---|---|
| Project | Portal Informasi Desa Bendung |
| Audit type | As-built user manual audit |
| Audit date | 2026-08-20 |
| Scope | Static inspection implementasi saat ini di `src`, dengan dokumen lama sebagai pembanding |
| Output | `docs/07-handover/00-source-of-truth/as-built-user-manual-audit.md` |
| Rule | Audit saja. Tidak ada perubahan source code, dokumen lama, asset, atau konfigurasi existing. |

Status confidence:

| Confidence | Arti |
|---|---|
| HIGH | Didukung route, controller, view, request validation, middleware/policy, atau model yang saling konsisten. |
| MEDIUM | Didukung source utama tetapi behavior runtime belum diuji melalui browser/manual. |
| LOW | Hanya dapat dipastikan sebagian dari static inspection. |

## 2. Audit Method & Evidence

Metode audit:

1. Mengidentifikasi teknologi dan struktur project melalui `composer.json`, `package.json`, `routes/web.php`, controller, model, request validation, middleware, policy, Blade view, JavaScript, dan migration.
2. Menjalankan `php artisan route:list` dari `src` untuk memverifikasi route aktual. Perintah `--columns` tidak tersedia pada versi artisan project ini, sehingga detail route dibaca dari `routes/web.php`.
3. Menelusuri form dari Blade create/edit dan validation dari `app/Http/Requests`.
4. Menelusuri authorization dari route middleware, custom middleware, policy, controller query scoping, dan FormRequest `authorize`.
5. Membandingkan hasil implementasi dengan dokumen lama: Requirements Baseline, PRD, Sitemap, User Flows, dan Roles/Permissions.

Evidence utama implementasi:

| Area | Evidence |
|---|---|
| Tech stack | `src/composer.json:9-20`, `src/package.json:6-15`, `src/resources/js/map.js:2-345` |
| Route publik/auth/admin | `src/routes/web.php:28-205` |
| Middleware auth/role | `src/bootstrap/app.php:18-37`, `src/app/Http/Middleware/EnsureRole.php:1-19`, `src/app/Http/Middleware/EnsureAdminAccountActive.php:16-26` |
| Auth controller/model | `src/app/Http/Controllers/Auth/LoginController.php:17-88`, `src/app/Models/AdminAccount.php:10-71` |
| Public controllers | `src/app/Http/Controllers/Public/HomeController.php:21-121`, `src/app/Http/Controllers/Public/DusunController.php:22-115`, `src/app/Http/Controllers/Public/*Controller.php` |
| Admin Dusun controllers | `src/app/Http/Controllers/Admin/*Controller.php` |
| Super Admin controllers | `src/app/Http/Controllers/SuperAdmin/*Controller.php` |
| Requests/validation | `src/app/Http/Requests/Admin/*Request.php`, `src/app/Http/Requests/SuperAdmin/*Request.php`, `src/app/Http/Requests/Auth/LoginRequest.php` |
| Policies | `src/app/Policies/*Policy.php` |
| Public views | `src/resources/views/layouts/public.blade.php:54-99`, `src/resources/views/public/*.blade.php` |
| Admin views | `src/resources/views/layouts/admin.blade.php:58-181`, `src/resources/views/admin/**/*.blade.php` |
| Super Admin views | `src/resources/views/layouts/super-admin.blade.php:53-206`, `src/resources/views/super-admin/**/*.blade.php` |
| Map behavior | `src/resources/js/map.js:141-345`, `src/resources/views/components/admin/coordinate-picker.blade.php:12-306` |
| Data lifecycle schema | `src/database/migrations/2026_08_14_000001_create_desas_table.php:17-27`, `2026_08_14_000002_create_dusuns_table.php:19-35`, `2026_08_14_000003_create_admin_accounts_table.php:18-35`, `2026_08_14_000004_create_kontak_pelayanans_table.php:19-32`, `2026_08_14_000005_create_umkms_table.php:19-35`, `2026_08_14_000008_create_fasilitas_table.php:20-36`, `2026_08_14_000009_create_agenda_kegiatans_table.php:19-49`, `2026_08_14_000011_create_pengumumans_table.php:19-44` |

Dokumen lama pembanding:

| Dokumen | Evidence |
|---|---|
| Requirements Baseline | `docs/01-requirements/requirements-baseline.md:1-10`, `:97-109`, `:118-176`, `:182-224`, `:224-324`, `:328-388`, `:416-486`, `:849-906`, `:957-1044` |
| PRD | `docs/02-product/PRD.md:169-183`, `:189-236`, `:242-341`, `:363-437`, `:472-479`, `:602-618` |
| Sitemap | `docs/03-ux/sitemap.md:57-110`, `:117-168`, `:170-240`, `:269-288`, `:290-360`, `:391-412` |
| User Flows | `docs/03-ux/user-flows.md:57-85`, `:219-240`, `:264-321`, `:337-438`, `:492-701`, `:716-740` |
| Roles/Permissions | `docs/04-system/roles-permissions.md:272-311`, `:319-343`, `:349-371`, `:423-456` |

Limitasi:

- Audit ini tidak menjalankan browser end-to-end, tidak melakukan login dengan akun nyata, dan tidak melakukan submit form. Behavior yang memerlukan observasi runtime ditandai `PERLU VERIFIKASI MANUAL`.
- Audit tidak menilai kualitas visual secara screenshot. Perubahan visual hanya dicatat bila terlihat dari struktur view/layout.

## 3. Current System Overview

Project saat ini adalah aplikasi Laravel/PHP dengan Blade server-rendered views, Vite asset pipeline, dan Leaflet/OpenStreetMap untuk peta.

Evidence:

- `src/composer.json:9-20`: PHP `^8.3`, Laravel framework `^13.17`, PHPUnit.
- `src/package.json:6-15`: Vite, Laravel Vite plugin, Leaflet.
- `src/resources/js/map.js:2-345`: inisialisasi peta Leaflet, marker, filter, popup, dan auto-init `[data-map]`.

Struktur area:

| Area | Kondisi aktual | Evidence | Confidence |
|---|---|---|---|
| Public | Tersedia tanpa auth untuk homepage, halaman dusun aktif, arsip pengumuman, detail UMKM, detail fasilitas, detail agenda, detail pengumuman. | `src/routes/web.php:28-61` | HIGH |
| Authentication | Login dan logout admin tersedia. Role redirect membedakan Admin Dusun dan Super Admin. | `src/routes/web.php:67-73`, `src/app/Http/Controllers/Auth/LoginController.php:17-88` | HIGH |
| Admin Dusun | Route group `admin-dusun` dengan dashboard, profil, kontak, UMKM, fasilitas, agenda, pengumuman. Semua memakai auth, akun aktif, role `ADMIN_DUSUN`. | `src/routes/web.php:72-112` | HIGH |
| Super Admin | Route group `super-admin` dengan dashboard, identitas desa, dusun, kontak, UMKM, fasilitas, kategori, agenda, pengumuman, data/peta, akun Admin Dusun. | `src/routes/web.php:118-205` | HIGH |
| Public map | Peta publik memakai marker fasilitas, UMKM, dan kontak pelayanan yang punya koordinat; popup menyediakan detail dan arah Google Maps. | `src/app/Http/Controllers/Public/HomeController.php:42-121`, `src/app/Http/Controllers/Public/DusunController.php:54-115`, `src/resources/js/map.js:141-345` | HIGH |
| Admin coordinate picker | Form lokasi memakai komponen peta untuk input koordinat, smart input, GPS, hapus titik. | `src/resources/views/components/admin/coordinate-picker.blade.php:12-306` | HIGH |

## 4. Public Area Inventory

### 4.1 Homepage Desa

Homepage tersedia pada `/`.

Aktual:

- Menampilkan identitas desa, pilihan dusun aktif, pengumuman desa aktif terbaru, agenda desa terbaru, Peta Desa, dan kontak desa.
- Public nav desktop: Beranda, Dusun, Informasi, Pengumuman, Agenda, Peta, Kontak.
- Mobile nav memiliki link sepadan.
- Tidak ada page builder publik atau halaman "Tentang Desa" terpisah yang terlihat pada route.

Evidence:

- Route: `src/routes/web.php:31-32`.
- Controller: `src/app/Http/Controllers/Public/HomeController.php:21-121`.
- View: `src/resources/views/public/home.blade.php:108-376`.
- Layout nav: `src/resources/views/layouts/public.blade.php:54-99`.

Confidence: HIGH.

### 4.2 Halaman Dusun

Halaman dusun tersedia pada `/dusun/{id}` dan hanya untuk dusun dengan status publik aktif.

Aktual:

- Menampilkan hero/profil dusun, statistik/konteks dusun, peta dusun, kontak pelayanan, UMKM, fasilitas, agenda, pengumuman, dan link arsip pengumuman dusun.
- Jika dusun tidak aktif atau ID tidak valid, controller memakai `firstOrFail`.
- Kontak, UMKM, fasilitas, agenda, dan pengumuman diambil untuk `dusun_id` halaman terkait.

Evidence:

- Route: `src/routes/web.php:34-37`.
- Controller: `src/app/Http/Controllers/Public/DusunController.php:22-115`.
- Model active scope: `src/app/Models/Dusun.php:55`.
- View: `src/resources/views/public/dusun.blade.php:22-377`.

Confidence: HIGH.

### 4.3 Kontak Pelayanan

Aktual:

- Tidak ada detail kontak terpisah.
- Kontak tampil di halaman dusun dan marker pelayanan pada peta mengarah ke section kontak dusun.
- Tombol WhatsApp tersedia untuk nomor kontak.
- Kontak dengan koordinat dapat menjadi marker peta.

Evidence:

- Dusun controller kontak: `src/app/Http/Controllers/Public/DusunController.php:28-32`, marker `:88-100`.
- Home marker pelayanan: `src/app/Http/Controllers/Public/HomeController.php:85-98`.
- WhatsApp button: `src/resources/views/partials/whatsapp-btn.blade.php:1-14`.
- View dusun: `src/resources/views/public/dusun.blade.php:187`.

Confidence: HIGH.

### 4.4 UMKM

Aktual:

- UMKM tampil pada halaman dusun.
- Detail UMKM tersedia pada `/umkm/{id}`.
- Detail menampilkan WhatsApp dan tombol arah jika koordinat tersedia.
- UMKM tanpa koordinat tetap dapat tampil di direktori, tetapi tidak menjadi marker peta karena marker memakai scope `hasCoordinates`.

Evidence:

- Route detail: `src/routes/web.php:44-46`.
- Public controller: `src/app/Http/Controllers/Public/UmkmController.php:17-23`.
- Dusun list: `src/app/Http/Controllers/Public/DusunController.php:32-35`.
- Marker: `src/app/Http/Controllers/Public/HomeController.php:68-81`, `src/app/Http/Controllers/Public/DusunController.php:73-86`.
- Model coordinate scope: `src/app/Models/Umkm.php:41-49`.
- Detail view: `src/resources/views/public/umkm-detail.blade.php:12-80`.

Confidence: HIGH.

### 4.5 Fasilitas

Aktual:

- Fasilitas tampil pada halaman dusun.
- Detail fasilitas tersedia pada `/fasilitas/{id}`.
- Detail menyediakan arah Google Maps bila koordinat ada dan WhatsApp bila nomor tersedia.
- Fasilitas wajib memiliki koordinat dari request dan schema, sehingga semestinya selalu dapat menjadi marker jika data tidak soft-deleted dan dusunnya aktif.

Evidence:

- Route detail: `src/routes/web.php:49-51`.
- Public controller: `src/app/Http/Controllers/Public/FasilitasController.php:17-30`.
- Dusun list/marker: `src/app/Http/Controllers/Public/DusunController.php:37-43`, `:58-71`.
- Request required coordinate: `src/app/Http/Requests/Admin/FasilitasRequest.php:29-35`, `src/app/Http/Requests/SuperAdmin/FasilitasRequest.php:17-25`.
- Detail view: `src/resources/views/public/fasilitas-detail.blade.php:12-84`.

Confidence: HIGH.

### 4.6 Agenda/Kegiatan

Aktual:

- Agenda Desa terbaru tampil di homepage; Agenda Dusun tampil di halaman dusun.
- Detail agenda tersedia pada `/agenda/{id}`.
- Status efektif dihitung dari tanggal mulai/selesai atau manual override.
- Media agenda mendukung poster awal dan dokumentasi.

Evidence:

- Route detail: `src/routes/web.php:54-56`.
- Home agenda: `src/app/Http/Controllers/Public/HomeController.php:36-40`.
- Dusun agenda: `src/app/Http/Controllers/Public/DusunController.php:43-47`.
- Detail controller: `src/app/Http/Controllers/Public/AgendaController.php:19-38`.
- Status model: `src/app/Models/AgendaKegiatan.php:43-65`.
- Detail view: `src/resources/views/public/agenda-detail.blade.php:13-38`.

Confidence: HIGH.

### 4.7 Pengumuman

Aktual:

- Pengumuman Desa aktif tampil di homepage; Pengumuman Dusun aktif tampil di halaman dusun.
- Detail pengumuman tersedia pada `/pengumuman/{id}`.
- Arsip publik tersedia pada `/pengumuman/arsip`, dengan query optional `dusun`.
- Arsip adalah pengumuman kedaluwarsa, bukan soft-deleted.

Evidence:

- Routes: `src/routes/web.php:40-41`, `:59-61`.
- Archive controller: `src/app/Http/Controllers/Public/PengumumanArchiveController.php:18-56`.
- Detail controller: `src/app/Http/Controllers/Public/PengumumanController.php:18-37`.
- Model active/archive: `src/app/Models/Pengumuman.php:52-69`.
- Views: `src/resources/views/public/pengumuman-arsip.blade.php:28-62`, `src/resources/views/public/pengumuman-detail.blade.php:13-18`.

Confidence: HIGH.

### 4.8 Peta

Aktual:

- Peta Desa tersedia sebagai section homepage dengan filter dusun dan kategori.
- Peta Dusun tersedia sebagai section halaman dusun dengan filter kategori.
- Marker berasal dari fasilitas, UMKM, dan kontak pelayanan yang memiliki koordinat.
- Popup marker dibuat oleh JavaScript, termasuk URL arah Google Maps.
- Base map memakai OpenStreetMap tiles; arah eksternal memakai Google Maps.
- Tidak ditemukan pencarian nama lokasi atau batas wilayah dusun.

Evidence:

- Homepage map view: `src/resources/views/public/home.blade.php:260-376`.
- Dusun map view: `src/resources/views/public/dusun.blade.php:113-377`.
- Map JS: `src/resources/js/map.js:141-345`.
- Directions URL: `src/resources/js/map.js:141-143`, `src/resources/views/partials/directions-btn.blade.php:5-9`.

Confidence: HIGH.

## 5. Authentication Inventory

| Fitur | Kondisi aktual | Evidence | Confidence |
|---|---|---|---|
| Login page | Tersedia di `/admin/login`, hanya untuk guest. | `src/routes/web.php:67-69`, `src/resources/views/auth/login.blade.php:60-127` | HIGH |
| Field login | Username, password, remember checkbox, toggle lihat password. | `src/resources/views/auth/login.blade.php:60-120`, `src/app/Http/Requests/Auth/LoginRequest.php:16-26` | HIGH |
| Login gagal | Jika akun tidak ditemukan atau password salah, kembali ke form dengan error username. | `src/app/Http/Controllers/Auth/LoginController.php:34-76` | HIGH |
| Akun removed | Login hanya memakai credential dengan `removed_at => null`; middleware juga logout akun yang sudah removed. | `src/app/Http/Controllers/Auth/LoginController.php:49-55`, `src/app/Http/Middleware/EnsureAdminAccountActive.php:16-26` | HIGH |
| Redirect Admin Dusun | Role `ADMIN_DUSUN` diarahkan ke dashboard Admin Dusun. | `src/app/Http/Controllers/Auth/LoginController.php:59-63`, `src/bootstrap/app.php:26-34` | HIGH |
| Redirect Super Admin | Role `SUPER_ADMIN` diarahkan ke dashboard Super Admin. | `src/app/Http/Controllers/Auth/LoginController.php:64-67`, `src/bootstrap/app.php:26-34` | HIGH |
| Logout | POST `/admin/logout`, invalidate session, regenerate token, redirect login. | `src/routes/web.php:72-73`, `src/app/Http/Controllers/Auth/LoginController.php:81-88` | HIGH |
| Self-service password reset | Tidak ditemukan route/view forgot password atau recovery. | `src/routes/web.php:67-205` | HIGH |

Behavior yang perlu diverifikasi manual:

- Copy error login yang benar-benar tampil di browser dan interaction toggle password.
- Behavior `remember` di sesi nyata.

## 6. Admin Dusun Inventory

Admin Dusun route group:

- Auth guard: `auth`.
- Akun harus aktif: `admin.active`.
- Role harus `ADMIN_DUSUN`.
- Prefix route: `/admin-dusun`.

Evidence: `src/routes/web.php:72-112`, `src/bootstrap/app.php:18-37`.

Menu aktual Admin Dusun:

| Menu | Route | Action utama | Evidence | Confidence |
|---|---|---|---|---|
| Dashboard | `admin-dusun/dashboard` | Melihat ringkasan jumlah data dan quick action. | `src/resources/views/layouts/admin.blade.php:58-112`, `src/resources/views/admin/dashboard.blade.php:37-114` | HIGH |
| Profil Dusun | `admin-dusun/profil` | Edit profil dusun sendiri. | `src/routes/web.php:86-87`, `src/app/Http/Controllers/Admin/ProfilDusunController.php:14-48` | HIGH |
| Kontak Pelayanan | resource `admin-dusun/kontak` | List, create, edit, soft delete. | `src/routes/web.php:90-92`, `src/app/Http/Controllers/Admin/KontakPelayananController.php:15-110` | HIGH |
| Kelola UMKM | resource `admin-dusun/umkm` | List, create, edit, soft delete, produk dinamis. | `src/routes/web.php:95-97`, `src/app/Http/Controllers/Admin/UmkmController.php:17-178` | HIGH |
| Kelola Fasilitas | resource `admin-dusun/fasilitas` | List, create, edit, soft delete, koordinat wajib. | `src/routes/web.php:100-102`, `src/app/Http/Controllers/Admin/FasilitasController.php:16-122` | HIGH |
| Agenda & Kegiatan | resource `admin-dusun/agenda` | List, create, edit, soft delete, upload media. | `src/routes/web.php:105-107`, `src/app/Http/Controllers/Admin/AgendaKegiatanController.php:18-168` | HIGH |
| Pengumuman | resource `admin-dusun/pengumuman` | List, create, edit, soft delete. | `src/routes/web.php:110-112`, `src/app/Http/Controllers/Admin/PengumumanController.php` | HIGH |
| Logout | POST `/admin/logout` | Keluar dari sesi admin. | `src/resources/views/layouts/admin.blade.php:155-163` | HIGH |

Hak dan batas aktual:

- Admin Dusun tidak dapat memilih dusun pada form modul operasional; controller mengambil `$request->user()->dusun`.
- Query index/edit/delete dibatasi `where('dusun_id', $dusun->id)` untuk kontak, UMKM, fasilitas, agenda, dan pengumuman.
- Admin Dusun dapat soft delete data modulnya. Tidak ada route restore atau force delete di group Admin Dusun.
- Admin Dusun tetap dapat melihat alert dan mengelola data ketika dusunnya `INACTIVE`; status ini hanya memengaruhi publik.

Evidence:

- Dashboard inactive notice: `src/resources/views/admin/dashboard.blade.php:58`, layout notice `src/resources/views/layouts/admin.blade.php:169-181`.
- Query scoping contoh: `src/app/Http/Controllers/Admin/UmkmController.php:20-25`, `:87-90`, `:173-176`; `src/app/Http/Controllers/Admin/FasilitasController.php:19-24`, `:72-73`, `:117-120`.
- Policies: `src/app/Policies/UmkmPolicy.php:24-58`, `src/app/Policies/FasilitasPolicy.php:24-58`, `src/app/Policies/PengumumanPolicy.php:24-58`, `src/app/Policies/AgendaKegiatanPolicy.php:24-58`.

## 7. Super Admin Inventory

Super Admin route group:

- Auth guard: `auth`.
- Akun harus aktif: `admin.active`.
- Role harus `SUPER_ADMIN`.
- Prefix route: `/super-admin`.

Evidence: `src/routes/web.php:118-205`, `src/bootstrap/app.php:18-37`.

Menu aktual Super Admin:

| Menu | Route | Action utama | Evidence | Confidence |
|---|---|---|---|---|
| Dashboard | `super-admin/dashboard` | Ringkasan global dan hub 10 area. | `src/resources/views/super-admin/dashboard.blade.php:12-106` | HIGH |
| Identitas Desa | `super-admin/desa` | Edit identitas/profil desa dan banner. | `src/routes/web.php:126-127`, `src/app/Http/Controllers/SuperAdmin/DesaController.php:15-49` | HIGH |
| Kelola Dusun | `super-admin/dusun` | List/edit profil, activate, deactivate. Tidak ada create/hard delete dusun. | `src/routes/web.php:130-134`, `src/app/Http/Controllers/SuperAdmin/DusunController.php:15-87` | HIGH |
| Kontak Pelayanan | `super-admin/kontak` | List/filter status/dusun, create, edit, soft delete, restore, force delete. | `src/routes/web.php:137-144`, `src/app/Http/Controllers/SuperAdmin/KontakPelayananController.php:16-152` | HIGH |
| Kelola UMKM | `super-admin/umkm` | List/filter status/dusun, create, edit, soft delete, restore, force delete, produk. | `src/routes/web.php:147-154`, `src/app/Http/Controllers/SuperAdmin/UmkmController.php:18-210` | HIGH |
| Kelola Fasilitas | `super-admin/fasilitas` | List/filter status/dusun/kategori, create, edit, soft delete, restore, force delete. | `src/routes/web.php:157-164`, `src/app/Http/Controllers/SuperAdmin/FasilitasController.php:17-171` | HIGH |
| Kategori Fasilitas | `super-admin/kategori-fasilitas` | List, create, edit, delete kategori. | `src/routes/web.php:167-172`, `src/app/Http/Controllers/SuperAdmin/KategoriFasilitasController.php:16-90` | HIGH |
| Agenda & Kegiatan | `super-admin/agenda` | List/filter status/scope/dusun, create Desa/Dusun, edit, soft delete, restore, force delete, media. | `src/routes/web.php:175-182`, `src/app/Http/Controllers/SuperAdmin/AgendaKegiatanController.php:20-217` | HIGH |
| Pengumuman | `super-admin/pengumuman` | List/filter status/scope/dusun, create Desa/Dusun, edit, soft delete, restore, force delete. | `src/routes/web.php:185-192`, `src/app/Http/Controllers/SuperAdmin/PengumumanController.php:16-138` | HIGH |
| Data / Peta | `super-admin/data-peta` | View map-centric data fasilitas, UMKM, kontak; filter dusun/kategori. | `src/routes/web.php:194-195`, `src/app/Http/Controllers/SuperAdmin/DataPetaController.php:16-106` | HIGH |
| Admin Dusun | `super-admin/admin-dusun` | List akun Admin Dusun, create, edit assignment, reset password, logical remove. | `src/routes/web.php:198-205`, `src/app/Http/Controllers/SuperAdmin/AdminAccountController.php:18-136` | HIGH |
| Logout | POST `/admin/logout` | Keluar dari sesi admin. | `src/resources/views/layouts/super-admin.blade.php:198-206` | HIGH |

Catatan implementasi:

- Super Admin dapat restore dan hard delete untuk kontak, UMKM, fasilitas, agenda, dan pengumuman.
- Super Admin tidak mempunyai hard delete dusun.
- Akun Admin Dusun tidak menggunakan soft delete Laravel; penghapusan admin memakai `removed_at` atau logical removal.
- Data / Peta adalah tampilan/proyeksi, bukan CRUD titik peta independen.

## 8. Form & Field Inventory

### 8.1 Authentication

| Form | Role | Field/label | Required | Tipe input pengguna | Validation/action | Evidence | Confidence |
|---|---|---|---|---|---|---|---|
| Login Admin | Guest admin | Username | Ya | Text | Required string; submit POST `/admin/login`. | `src/resources/views/auth/login.blade.php:60-120`, `src/app/Http/Requests/Auth/LoginRequest.php:16-26` | HIGH |
| Login Admin | Guest admin | Password | Ya | Password | Required string; login attempt. | same as above | HIGH |
| Login Admin | Guest admin | Ingat saya/remember | Tidak pasti dari request | Checkbox | Diteruskan di form; efek session perlu verifikasi manual. | `src/resources/views/auth/login.blade.php:113-120` | MEDIUM |

### 8.2 Admin Dusun Forms

| Form | Field/label | Required | Tipe input | Validation / behavior | Submit result | Evidence | Confidence |
|---|---|---|---|---|---|---|---|
| Profil Dusun | Nama Dusun | Ya | Text | `required`, string, max 150. | Redirect ke edit profil dengan success. | `src/resources/views/admin/profil/edit.blade.php:24-144`, `src/app/Http/Requests/Admin/ProfilDusunRequest.php:16-36`, `src/app/Http/Controllers/Admin/ProfilDusunController.php:22-48` | HIGH |
| Profil Dusun | Deskripsi Singkat | Ya | Textarea | `required`, string. | Sama. | same | HIGH |
| Profil Dusun | Nama Kepala Dusun | Ya | Text | `required`, string, max 150. | Sama. | same | HIGH |
| Profil Dusun | Jumlah RT | Ya | Number | Integer min 0 max 65535. | Sama. | same | HIGH |
| Profil Dusun | Jumlah RW | Ya | Number | Integer min 0 max 65535. | Sama. | same | HIGH |
| Profil Dusun | Banner | Tidak | File gambar | JPG/PNG/WebP, max 3MB. | Upload mengganti banner lama. | same | HIGH |
| Kontak Pelayanan | Nama Petugas / Pengurus | Ya | Text | `required`, max 150. | Redirect ke index kontak dengan success. | `src/resources/views/admin/kontak/create.blade.php:20-102`, `src/app/Http/Requests/Admin/KontakPelayananRequest.php:16-39`, `src/app/Http/Controllers/Admin/KontakPelayananController.php:33-110` | HIGH |
| Kontak Pelayanan | Jabatan / Peran Pelayanan | Ya | Text | `required`, max 150. | Sama. | same | HIGH |
| Kontak Pelayanan | Nomor WhatsApp | Ya | Text | `required`, max 32. | Sama. | same | HIGH |
| Kontak Pelayanan | Alamat Pelayanan | Tidak | Textarea | Nullable string. | Sama. | same | HIGH |
| Kontak Pelayanan | Foto Petugas | Tidak | File gambar | JPG/PNG/WebP, max 3MB. | Upload disimpan via MediaService. | same | HIGH |
| Kontak Pelayanan | Koordinat lokasi | Tidak | Coordinate picker + number | Latitude/longitude nullable; jika salah satu diisi, pasangan wajib; range latitude/longitude. | Marker publik jika koordinat lengkap. | `src/resources/views/components/admin/coordinate-picker.blade.php:12-87`, request same | HIGH |
| UMKM | Nama UMKM | Ya | Text | `required`, max 200. | Redirect ke index UMKM dengan success. | `src/resources/views/admin/umkm/create.blade.php:23-228`, `src/app/Http/Requests/Admin/UmkmRequest.php:16-50`, `src/app/Http/Controllers/Admin/UmkmController.php:36-178` | HIGH |
| UMKM | Nama Pemilik | Ya | Text | `required`, max 150. | Sama. | same | HIGH |
| UMKM | Jenis Usaha | Ya | Text | `required`, max 150. | Sama. | same | HIGH |
| UMKM | Jam Operasional | Ya | Text | `required`, max 255. | Sama. | same | HIGH |
| UMKM | Nomor WhatsApp | Ya | Text | `required`, max 32. | Sama. | same | HIGH |
| UMKM | Foto Utama | Tidak | File gambar | JPG/PNG/WebP, max 3MB. | Upload utama. | same | HIGH |
| UMKM | Deskripsi | Ya | Textarea | `required`, string. | Sama. | same | HIGH |
| UMKM | Alamat | Ya | Textarea | `required`, string. | Sama. | same | HIGH |
| UMKM | Produk | Tidak | Dynamic text rows | Array; nama produk wajib jika produk dikirim. | Produk dibuat/disinkronkan. | `src/resources/views/admin/umkm/create.blade.php:184-252`, `src/app/Http/Controllers/Admin/UmkmController.php:137-159` | HIGH |
| UMKM | Titik Koordinat Lokasi UMKM | Tidak | Coordinate picker + number | Nullable; pasangan lat/lng wajib jika salah satu diisi. | Marker jika lengkap. | `src/resources/views/admin/umkm/create.blade.php:218-228`, request same | HIGH |
| Fasilitas | Nama Fasilitas | Ya | Text | `required`, max 200. | Redirect ke index fasilitas dengan success. | `src/resources/views/admin/fasilitas/create.blade.php:23-152`, `src/app/Http/Requests/Admin/FasilitasRequest.php:19-53`, `src/app/Http/Controllers/Admin/FasilitasController.php:39-122` | HIGH |
| Fasilitas | Kategori Fasilitas | Ya | Select | Required; exists pada kategori desa admin. | Sama. | same | HIGH |
| Fasilitas | Nomor WhatsApp | Tidak | Text | Nullable max 32. | Tombol WhatsApp publik bila diisi. | same | HIGH |
| Fasilitas | Foto | Tidak | File gambar | JPG/PNG/WebP, max 3MB. | Upload foto. | same | HIGH |
| Fasilitas | Deskripsi | Ya | Textarea | `required`, string. | Sama. | same | HIGH |
| Fasilitas | Alamat | Ya | Textarea | `required`, string. | Sama. | same | HIGH |
| Fasilitas | Titik Koordinat Peta | Ya | Coordinate picker + number | Latitude dan longitude wajib, range valid. | Marker publik. | `src/resources/views/admin/fasilitas/create.blade.php:141-152`, request same | HIGH |
| Agenda/Kegiatan | Judul | Ya | Text | `required`, max 255. | Redirect ke index agenda dengan success. | `src/resources/views/admin/agenda/create.blade.php:24-217`, `src/app/Http/Requests/Admin/AgendaKegiatanRequest.php:17-47`, `src/app/Http/Controllers/Admin/AgendaKegiatanController.php:37-168` | HIGH |
| Agenda/Kegiatan | Tanggal Mulai | Ya | Date | `required`, date. | Sama. | same | HIGH |
| Agenda/Kegiatan | Tanggal Selesai | Tidak | Date | Nullable date, harus setelah/sama tanggal mulai. | Sama. | same | HIGH |
| Agenda/Kegiatan | Jam | Tidak | Time | Nullable format `H:i`. | Sama. | same | HIGH |
| Agenda/Kegiatan | Lokasi | Ya | Text | `required`, max 255. | Sama. | same | HIGH |
| Agenda/Kegiatan | Status Override Manual | Tidak | Select | Nullable; Akan Datang/Berlangsung/Selesai. | Mengubah status efektif. | same | HIGH |
| Agenda/Kegiatan | Deskripsi Singkat | Ya | Textarea | `required`, string. | Sama. | same | HIGH |
| Agenda/Kegiatan | Media | Tidak | Dynamic file + role select | JPG/PNG/WebP, max 3MB; role Poster Awal/Dokumentasi. | Media dibuat; edit dapat hapus media existing. | `src/resources/views/admin/agenda/create.blade.php:157-204`, `src/app/Http/Controllers/Admin/AgendaKegiatanController.php:61-72`, `:117-152` | HIGH |
| Pengumuman | Judul | Ya | Text | `required`, max 255. | Redirect ke index pengumuman dengan success. | `src/resources/views/admin/pengumuman/create.blade.php:28-83`, `src/app/Http/Requests/Admin/PengumumanRequest.php:16-29` | HIGH |
| Pengumuman | Tanggal Kedaluwarsa | Ya | Date | `required`, date. | Menentukan aktif/arsip. | same | HIGH |
| Pengumuman | Isi | Ya | Textarea | `required`, string. | Sama. | same | HIGH |

### 8.3 Super Admin Forms

Tambahan field Super Admin dibanding Admin Dusun:

| Form | Field/label | Required | Tipe input | Validation / behavior | Submit result | Evidence | Confidence |
|---|---|---|---|---|---|---|---|
| Identitas Desa | Nama Desa, Nama Kepala Desa, Nomor Kontak, Jam Pelayanan, Alamat Kantor, Deskripsi Singkat | Ya | Text/textarea | Required sesuai field; nomor max 32; banner optional. | Redirect ke edit desa dengan success. | `src/resources/views/super-admin/desa/edit.blade.php:19-155`, `src/app/Http/Requests/SuperAdmin/DesaRequest.php:16-38`, `src/app/Http/Controllers/SuperAdmin/DesaController.php:23-49` | HIGH |
| Identitas Desa | Banner | Tidak | File gambar | JPG/PNG/WebP, max 3MB. | Upload mengganti banner. | same | HIGH |
| Kelola Dusun | Nama Dusun, Kepala Dusun, Jumlah RT/RW, Deskripsi, Banner | Nama/deskripsi/kepala/RT/RW wajib; banner optional | Text, number, textarea, file | Mirip profil Admin Dusun; Super Admin lintas dusun. | Redirect ke list dusun. | `src/resources/views/super-admin/dusun/edit.blade.php:24-144`, `src/app/Http/Requests/SuperAdmin/DusunProfileRequest.php:16-38`, `src/app/Http/Controllers/SuperAdmin/DusunController.php:37-87` | HIGH |
| Kategori Fasilitas | Nama Kategori | Ya | Text | Required, max 100, unique per desa. | Redirect ke index kategori. | `src/resources/views/super-admin/kategori/create.blade.php:25-45`, `src/app/Http/Requests/SuperAdmin/KategoriFasilitasRequest.php:21-38`, `src/app/Http/Controllers/SuperAdmin/KategoriFasilitasController.php:32-90` | HIGH |
| Admin Dusun - Create | Username | Ya | Text | Required, alpha_dash, unique, max 50. | Akun dibuat. | `src/resources/views/super-admin/admin-dusun/create.blade.php:21-84`, `src/app/Http/Requests/SuperAdmin/AdminAccountCreateRequest.php:16-32`, `src/app/Http/Controllers/SuperAdmin/AdminAccountController.php:40-56` | HIGH |
| Admin Dusun - Create | Password | Ya | Password | Required min 6. | Hash disimpan di `password_hash`. | same; model `src/app/Models/AdminAccount.php:31-41` | HIGH |
| Admin Dusun - Create/Edit | Wilayah Dusun | Ya | Select | Required exists dusuns. | Penugasan akun diperbarui. | `src/resources/views/super-admin/admin-dusun/edit.blade.php:26-56`, `src/app/Http/Requests/SuperAdmin/AdminAccountUpdateRequest.php:16-25` | HIGH |
| Admin Dusun - Reset Password | Password Baru, Konfirmasi Password | Ya | Password | Required, min 6, confirmed. | Password direset, redirect ke index. | `src/resources/views/super-admin/admin-dusun/reset-password.blade.php:21-69`, `src/app/Http/Requests/SuperAdmin/AdminAccountResetPasswordRequest.php:16-26`, `src/app/Http/Controllers/SuperAdmin/AdminAccountController.php:104-119` | HIGH |
| Super Admin Kontak | Wilayah Dusun | Ya | Select | Tambahan dari Admin Dusun; required exists dusuns. | Redirect index kontak. | `src/resources/views/super-admin/kontak/create.blade.php:20-104`, `src/app/Http/Requests/SuperAdmin/KontakPelayananRequest.php:16-44` | HIGH |
| Super Admin UMKM | Wilayah Dusun | Ya | Select | Tambahan dari Admin Dusun; required exists dusuns. | Redirect index UMKM. | `src/resources/views/super-admin/umkm/create.blade.php:21-242`, `src/app/Http/Requests/SuperAdmin/UmkmRequest.php:16-54` | HIGH |
| Super Admin Fasilitas | Wilayah Dusun | Ya | Select | Tambahan dari Admin Dusun; required exists dusuns. | Redirect index fasilitas. | `src/resources/views/super-admin/fasilitas/create.blade.php:21-162`, `src/app/Http/Requests/SuperAdmin/FasilitasRequest.php:16-47` | HIGH |
| Super Admin Agenda | Cakupan Wilayah, Wilayah Dusun | Cakupan wajib; Dusun wajib jika cakupan DUSUN | Select | `scope_level` DESA/DUSUN; `dusun_id` required if DUSUN, prohibited if DESA. | Redirect index agenda. | `src/resources/views/super-admin/agenda/create.blade.php:21-207`, `src/app/Http/Requests/SuperAdmin/AgendaKegiatanRequest.php:17-60` | HIGH |
| Super Admin Pengumuman | Cakupan Wilayah, Wilayah Dusun | Cakupan wajib; Dusun wajib jika cakupan DUSUN | Select | `scope_level` DESA/DUSUN; `dusun_id` required if DUSUN, prohibited if DESA. | Redirect index pengumuman. | `src/resources/views/super-admin/pengumuman/create.blade.php:26-112`, `src/app/Http/Requests/SuperAdmin/PengumumanRequest.php:17-43` | HIGH |

Field modul kontak/UMKM/fasilitas/agenda/pengumuman lain sama secara substansi dengan Admin Dusun, dengan tambahan Super Admin dapat memilih `dusun_id` dan beberapa modul dapat memilih scope `DESA` atau `DUSUN`.

## 9. Current Role & Permission Matrix

| Capability | Public User | Admin Dusun | Super Admin | Evidence | Confidence |
|---|---|---|---|---|---|
| Melihat homepage publik | TERSEDIA | TERSEDIA | TERSEDIA | `src/routes/web.php:31-32`; public layout | HIGH |
| Melihat halaman dusun aktif | TERSEDIA | TERSEDIA | TERSEDIA | `src/routes/web.php:34-37`; `src/app/Models/Dusun.php:55` | HIGH |
| Melihat dusun inactive via URL publik | TIDAK TERSEDIA | TERBATAS (admin tetap kelola) | TERSEDIA di dashboard | `src/app/Http/Controllers/Public/DusunController.php:25`; admin notices | HIGH |
| Menggunakan WhatsApp public | TERSEDIA | TERSEDIA sebagai public viewer | TERSEDIA sebagai public viewer | partial WhatsApp, public views | HIGH |
| Menggunakan peta/filter public | TERSEDIA | TERSEDIA sebagai public viewer | TERSEDIA sebagai public viewer | public map views, `src/resources/js/map.js` | HIGH |
| Login admin | TIDAK TERSEDIA sebagai warga | TERSEDIA | TERSEDIA | `src/routes/web.php:67-69`; `LoginController` | HIGH |
| Dashboard Admin Dusun | TIDAK TERSEDIA | TERSEDIA hanya dusun sendiri | TIDAK TERSEDIA sebagai role Admin Dusun | `src/routes/web.php:78-112`; `EnsureRole` | HIGH |
| Dashboard Super Admin | TIDAK TERSEDIA | TIDAK TERSEDIA | TERSEDIA | `src/routes/web.php:118-205`; `EnsureRole` | HIGH |
| Kelola profil dusun sendiri | TIDAK TERSEDIA | TERSEDIA | TERSEDIA lintas dusun | `ProfilDusunController`, `DusunController`, `DusunPolicy` | HIGH |
| Kelola kontak sendiri | TIDAK TERSEDIA | TERSEDIA | TERSEDIA global | `KontakPelayananPolicy.php:24-58` | HIGH |
| Kelola UMKM sendiri | TIDAK TERSEDIA | TERSEDIA | TERSEDIA global | `UmkmPolicy.php:24-58` | HIGH |
| Kelola fasilitas sendiri | TIDAK TERSEDIA | TERSEDIA | TERSEDIA global | `FasilitasPolicy.php:24-58` | HIGH |
| Kelola agenda dusun sendiri | TIDAK TERSEDIA | TERSEDIA | TERSEDIA Desa dan Dusun | `AgendaKegiatanPolicy.php:24-58` | HIGH |
| Kelola pengumuman dusun sendiri | TIDAK TERSEDIA | TERSEDIA | TERSEDIA Desa dan Dusun | `PengumumanPolicy.php:24-58` | HIGH |
| Kelola identitas desa | TIDAK TERSEDIA | TIDAK TERSEDIA | TERSEDIA | `src/routes/web.php:126-127`, `DesaPolicy.php:25-42` | HIGH |
| Kelola kategori fasilitas | TIDAK TERSEDIA | TIDAK TERSEDIA untuk create/update | TERSEDIA | `KategoriFasilitasPolicy.php:20-42` | HIGH |
| Kelola akun Admin Dusun | TIDAK TERSEDIA | TIDAK TERSEDIA | TERSEDIA | `AdminAccountPolicy.php:9-61` | HIGH |
| Reset password Admin Dusun | TIDAK TERSEDIA | TIDAK TERSEDIA | TERSEDIA | `AdminAccountController.php:92-119` | HIGH |
| Restore data soft-deleted | TIDAK TERSEDIA | TIDAK TERSEDIA | TERSEDIA untuk kontak/UMKM/fasilitas/agenda/pengumuman | Super Admin route restore `src/routes/web.php:143,153,163,181,191`; policies | HIGH |
| Hard delete data operasional | TIDAK TERSEDIA | TIDAK TERSEDIA | TERSEDIA untuk kontak/UMKM/fasilitas/agenda/pengumuman | Super Admin route force delete `src/routes/web.php:144,154,164,182,192`; policies | HIGH |
| Hard delete dusun | TIDAK TERSEDIA | TIDAK TERSEDIA | TIDAK TERSEDIA | No route; `DusunPolicy.php:44-56` | HIGH |
| Create dusun baru | TIDAK TERSEDIA | TIDAK TERSEDIA | TIDAK TERSEDIA | No route; `DusunPolicy.php:20-22` | HIGH |
| Recovery akun Super Admin | TIDAK TERSEDIA | TIDAK TERSEDIA | TIDAK TERSEDIA | No route/view found | HIGH |
| Consent digital publikasi | TIDAK TERSEDIA | TIDAK TERSEDIA sebagai field | TIDAK TERSEDIA sebagai field | Form requests/views tidak memuat consent checkbox/proof upload | HIGH |

## 10. Current User Flows

### 10.1 Public

1. Masuk Homepage  
   Entry Point: `/`  
   -> Browser membuka route home  
   -> Controller mengambil identitas desa, dusun aktif, pengumuman aktif, agenda desa, dan marker peta  
   -> Result: Homepage publik tampil.

   Evidence: `src/routes/web.php:31-32`, `src/app/Http/Controllers/Public/HomeController.php:21-121`.

2. Memilih Dusun  
   Entry Point: section pilihan dusun homepage  
   -> User klik link dusun  
   -> Route `/dusun/{id}` memuat dusun active  
   -> Result: Halaman dusun tampil atau 404 bila tidak active/tidak ada.

   Evidence: `src/resources/views/public/home.blade.php:108`, `src/routes/web.php:34-37`, `src/app/Http/Controllers/Public/DusunController.php:25`.

3. Menjelajahi Halaman Dusun  
   Entry Point: `/dusun/{id}`  
   -> User scroll atau memakai navigasi cepat/anchor  
   -> Melihat peta, kontak, UMKM, fasilitas, agenda, pengumuman  
   -> Result: Informasi dusun terbaca.

   Evidence: `src/resources/views/public/dusun.blade.php:22-377`.

4. Melihat Kontak Pelayanan  
   Entry Point: section kontak pada halaman dusun  
   -> User melihat daftar kontak  
   -> Jika nomor tersedia, klik WhatsApp  
   -> Result: Handoff ke WhatsApp.

   Evidence: `src/resources/views/public/dusun.blade.php:187`, `src/resources/views/partials/whatsapp-btn.blade.php:1-14`.

5. Menghubungi melalui WhatsApp  
   Entry Point: tombol WhatsApp  
   -> Button membuat link WhatsApp  
   -> User keluar ke WhatsApp/web WhatsApp  
   -> Result: Chat eksternal terbuka.

   Evidence: `src/resources/views/partials/whatsapp-btn.blade.php:1-14`. Exact template pesan perlu verifikasi manual.

6. Melihat UMKM  
   Entry Point: section UMKM/marker UMKM  
   -> User klik detail UMKM  
   -> Detail menampilkan profil, produk, WhatsApp, arah jika koordinat tersedia  
   -> Result: Informasi UMKM terbaca atau handoff eksternal.

   Evidence: `src/routes/web.php:44-46`, `src/resources/views/public/umkm-detail.blade.php:12-80`.

7. Melihat Fasilitas  
   Entry Point: section fasilitas/marker fasilitas  
   -> User klik detail fasilitas  
   -> Detail menampilkan alamat, kategori, arah, dan WhatsApp bila nomor tersedia  
   -> Result: Informasi fasilitas terbaca atau handoff eksternal.

   Evidence: `src/routes/web.php:49-51`, `src/resources/views/public/fasilitas-detail.blade.php:12-84`.

8. Menggunakan Peta dan filter  
   Entry Point: Peta Desa atau Peta Dusun  
   -> User pilih filter dusun/kategori atau kategori saja pada dusun  
   -> JS memfilter marker  
   -> Result: Marker terfilter.

   Evidence: `src/resources/views/public/home.blade.php:271-301`, `src/resources/views/public/dusun.blade.php:113-137`, `src/resources/js/map.js:280-340`.

9. Membuka arah lokasi  
   Entry Point: popup marker atau tombol arah detail  
   -> User klik arah  
   -> Link Google Maps dibuka  
   -> Result: Handoff navigasi eksternal.

   Evidence: `src/resources/js/map.js:141-143`, `src/resources/views/partials/directions-btn.blade.php:5-9`.

10. Melihat Agenda/Kegiatan  
    Entry Point: section agenda homepage/dusun  
    -> User klik agenda  
    -> Detail agenda tampil dengan status efektif dan media  
    -> Result: Detail kegiatan terbaca.

    Evidence: `src/routes/web.php:54-56`, `src/app/Http/Controllers/Public/AgendaController.php:19-38`.

11. Membaca Pengumuman  
    Entry Point: section pengumuman atau arsip  
    -> User klik pengumuman aktif/arsip  
    -> Detail tampil dan status archive dihitung dari tanggal kedaluwarsa  
    -> Result: Detail pengumuman terbaca.

    Evidence: `src/routes/web.php:40-61`, `src/app/Http/Controllers/Public/PengumumanController.php:20-37`, `src/app/Http/Controllers/Public/PengumumanArchiveController.php:21-56`.

### 10.2 Admin Dusun

1. Login  
   Entry Point: `/admin/login`  
   -> Input username/password  
   -> Login sukses role `ADMIN_DUSUN`  
   -> Result: Redirect dashboard Admin Dusun.

   Evidence: `src/app/Http/Controllers/Auth/LoginController.php:34-67`.

2. Dashboard  
   Entry Point: `/admin-dusun/dashboard`  
   -> Sistem mengambil count data pada dusun akun  
   -> User memakai quick action atau menu  
   -> Result: Area kelola dusun sendiri terbuka.

   Evidence: `src/app/Http/Controllers/Admin/DashboardController.php:16-27`, `src/resources/views/admin/dashboard.blade.php:37-114`.

3. Kelola Profil Dusun  
   Entry Point: menu Profil Dusun  
   -> Edit field profil/banner  
   -> Submit PUT `/admin-dusun/profil`  
   -> Result: Profil dusun diperbarui.

   Evidence: `src/routes/web.php:86-87`, `src/app/Http/Controllers/Admin/ProfilDusunController.php:22-48`.

4. Kelola Kontak Pelayanan  
   Entry Point: menu Kontak Pelayanan  
   -> List/create/edit/delete  
   -> Controller membatasi `dusun_id` akun  
   -> Result: Kontak tersimpan, diperbarui, atau soft-deleted.

   Evidence: `src/routes/web.php:90-92`, `src/app/Http/Controllers/Admin/KontakPelayananController.php:15-110`.

5. Kelola UMKM  
   Entry Point: menu Kelola UMKM  
   -> List/create/edit/delete, tambah/hapus baris produk  
   -> Result: UMKM dan produk tersimpan/diperbarui/soft-deleted.

   Evidence: `src/app/Http/Controllers/Admin/UmkmController.php:17-178`.

6. Kelola Fasilitas  
   Entry Point: menu Kelola Fasilitas  
   -> Pilih kategori, isi koordinat wajib, create/edit/delete  
   -> Result: Fasilitas tersimpan/diperbarui/soft-deleted.

   Evidence: `src/app/Http/Controllers/Admin/FasilitasController.php:16-122`.

7. Kelola Agenda/Kegiatan  
   Entry Point: menu Agenda & Kegiatan  
   -> Create/edit dengan tanggal, status override, media  
   -> Delete soft delete  
   -> Result: Agenda tersimpan/diperbarui/soft-deleted.

   Evidence: `src/app/Http/Controllers/Admin/AgendaKegiatanController.php:18-168`.

8. Kelola Pengumuman  
   Entry Point: menu Kelola Pengumuman  
   -> Create/edit dengan tanggal kedaluwarsa  
   -> Delete soft delete  
   -> Result: Pengumuman tersimpan/diperbarui/soft-deleted; archive publik berdasarkan expiry.

   Evidence: `src/routes/web.php:110-112`, `src/app/Http/Requests/Admin/PengumumanRequest.php:16-29`.

9. Nonaktif/delete sesuai implementasi  
   Entry Point: tombol Nonaktifkan pada index modul  
   -> Submit DELETE resource  
   -> Controller memanggil `$model->delete()`  
   -> Result: Soft delete dan data tidak tampil publik.

   Evidence: `src/app/Http/Controllers/Admin/UmkmController.php:170-178`, `src/app/Http/Controllers/Admin/FasilitasController.php:114-122`, `src/app/Http/Controllers/Admin/AgendaKegiatanController.php:159-168`.

10. Logout  
    Entry Point: tombol Keluar  
    -> POST `/admin/logout`  
    -> Sesi invalidated  
    -> Result: Redirect login.

    Evidence: `src/resources/views/layouts/admin.blade.php:155-163`, `src/app/Http/Controllers/Auth/LoginController.php:81-88`.

### 10.3 Super Admin

1. Login  
   Entry Point: `/admin/login`  
   -> Input username/password  
   -> Login sukses role `SUPER_ADMIN`  
   -> Result: Redirect dashboard Super Admin.

   Evidence: `src/app/Http/Controllers/Auth/LoginController.php:64-67`.

2. Dashboard  
   Entry Point: `/super-admin/dashboard`  
   -> Sistem mengambil statistik global  
   -> User memilih hub/menu  
   -> Result: Area global terbuka.

   Evidence: `src/app/Http/Controllers/SuperAdmin/DashboardController.php:20-38`, `src/resources/views/super-admin/dashboard.blade.php:12-106`.

3. Kelola Identitas/Profil Desa  
   Entry Point: menu Identitas Desa  
   -> Edit profil desa/banner  
   -> Result: Identitas homepage diperbarui.

   Evidence: `src/routes/web.php:126-127`, `src/app/Http/Controllers/SuperAdmin/DesaController.php:15-49`.

4. Kelola Dusun  
   Entry Point: menu Kelola Dusun  
   -> List/edit profil, activate/deactivate  
   -> Result: Status publik dusun berubah atau profil diperbarui.

   Evidence: `src/routes/web.php:130-134`, `src/app/Http/Controllers/SuperAdmin/DusunController.php:15-87`.

5. Kelola data lintas Dusun  
   Entry Point: kontak/UMKM/fasilitas/agenda/pengumuman  
   -> Filter status/dusun/scope sesuai modul  
   -> Create/edit/delete/restore/force delete sesuai route  
   -> Result: Data global tersimpan/dipulihkan/dihapus.

   Evidence: `src/routes/web.php:137-192`.

6. Kelola kategori  
   Entry Point: Kategori Fasilitas  
   -> Create/edit/delete kategori  
   -> Result: Kategori fasilitas tersimpan atau dihapus jika aturan relasi memungkinkan.

   Evidence: `src/routes/web.php:167-172`, `src/app/Http/Controllers/SuperAdmin/KategoriFasilitasController.php:16-90`.

7. Kelola Admin Dusun  
   Entry Point: Admin Dusun  
   -> Create akun, edit penugasan dusun, reset password, remove akun  
   -> Result: Akun aktif/ditugaskan/direset/dinonaktifkan logical.

   Evidence: `src/routes/web.php:198-205`, `src/app/Http/Controllers/SuperAdmin/AdminAccountController.php:18-136`.

8. Reset password  
   Entry Point: form reset password akun Admin Dusun  
   -> Input password dan konfirmasi  
   -> Result: Password hash diperbarui.

   Evidence: `src/resources/views/super-admin/admin-dusun/reset-password.blade.php:21-69`, `src/app/Http/Requests/SuperAdmin/AdminAccountResetPasswordRequest.php:16-26`.

9. Restore/nonaktif/delete sesuai implementasi  
   Entry Point: filter status soft-deleted pada index modul  
   -> Tombol Pulihkan/Hapus Permanen  
   -> Result: Restore atau force delete untuk data operasional. Dusun hanya activate/deactivate, tidak force delete.

   Evidence: `src/routes/web.php:137-192`, `src/routes/web.php:130-134`.

10. Logout  
    Entry Point: tombol Keluar  
    -> POST `/admin/logout`  
    -> Result: Redirect login.

    Evidence: `src/resources/views/layouts/super-admin.blade.php:198-206`.

## 11. Legacy Documentation Comparison

| Area | Klasifikasi | Catatan as-built | Dokumen lama | Evidence implementasi |
|---|---|---|---|---|
| Public tanpa akun | MATCH | Semua route publik berada di luar auth. | Baseline `FR-001`, Sitemap public. | `src/routes/web.php:28-61` |
| Homepage data-driven | MATCH | Identitas desa langsung, dusun aktif, peta, agenda desa, pengumuman desa dari modul. | Baseline `FR-002`-`FR-004`, PRD sections 10-11, Sitemap 13. | `HomeController.php:21-121` |
| Tidak ada halaman Tentang Desa | MATCH | Tidak ditemukan route/page Tentang Desa. | Baseline `FR-021`. | `src/routes/web.php:28-61` |
| Halaman Dusun single page/scroll | MATCH + VISUAL CHANGE | Struktur aktual adalah section panjang dengan layout visual yang mungkin berbeda dari wireframe lama. | Baseline `FR-005`-`FR-007`, Sitemap 6.2. | `resources/views/public/dusun.blade.php:22-377` |
| Detail kontak pelayanan | MATCH | Tidak ada detail kontak; marker pelayanan mengarah ke section kontak. | Sitemap marker pelayanan tidak membuat tipe detail baru. | `HomeController.php:93-94`, `DusunController.php:96` |
| UMKM sebagai direktori, bukan e-commerce | MATCH | Tidak ada transaksi/order/payment. | Baseline `FR-011`. | `resources/views/public/umkm-detail.blade.php:12-80` |
| Produk UMKM multi-row | MATCH | Form mendukung array produk dan sinkronisasi produk. | Baseline `FR-012`. | `UmkmRequest.php:27-29`, `Admin/UmkmController.php:137-159` |
| Galeri multi-foto UMKM | NOT IMPLEMENTED | Hanya foto utama UMKM terlihat. | Baseline `MEDIA-004` future. | `UmkmRequest.php:24`, `SuperAdmin/UmkmRequest.php:25` |
| Fasilitas koordinat wajib | MATCH | Request dan schema mewajibkan lat/lng. | Baseline `MAP-008`. | `Admin/FasilitasRequest.php:34-35`, migration fasilitas `:23-24` |
| Agenda status lifecycle + override | MATCH | Model menghitung status efektif dan request menyediakan override. | Baseline `FR-015`. | `AgendaKegiatan.php:43-65`, `AgendaKegiatanRequest.php:24` |
| Agenda media poster/dokumentasi | MATCH | Form dynamic media dengan role Poster Awal/Dokumentasi. | Baseline `MEDIA-007`. | `resources/views/admin/agenda/create.blade.php:157-204` |
| Pengumuman arsip publik | MATCH | Archive memakai expired announcements dan bukan soft-deleted. | Baseline `FR-018`, AMB-002. | `PengumumanArchiveController.php:18-56`, `Pengumuman.php:52-69` |
| Peta Desa filter dusun dan kategori | MATCH | Homepage map punya filter dusun/kategori. | Baseline `MAP-005`. | `resources/views/public/home.blade.php:271-282` |
| Peta Dusun filter kategori | MATCH | Peta Dusun punya filter kategori. | Sitemap Peta Dusun. | `resources/views/public/dusun.blade.php:116-126` |
| Pencarian lokasi pada peta | NOT IMPLEMENTED | Tidak ditemukan search nama lokasi pada peta publik. | Baseline `MAP-011` future. | `src/resources/js/map.js:141-345` |
| Batas wilayah dusun pada peta | NOT IMPLEMENTED | Tidak ditemukan layer garis/bidang batas dusun. | Baseline `MAP-012` future. | `src/resources/js/map.js:141-345` |
| Admin Dusun scoped ke dusun sendiri | MATCH | Controller query dan policy membatasi `dusun_id`. | Baseline `ROLE-003`-`ROLE-004`, Roles/Permissions. | Admin controllers and policies |
| Admin Dusun direct publish | MATCH | Store/update redirect success tanpa approval workflow. | Baseline `FR-019`. | Admin controllers store/update |
| Admin Dusun restore | MATCH | Tidak ada route restore Admin Dusun. | Baseline menyatakan restore Super Admin. | `src/routes/web.php:78-112` |
| Admin Dusun hard delete | MATCH | Tidak ada force delete route Admin Dusun. | Baseline `ROLE-006`. | `src/routes/web.php:78-112` |
| Super Admin restore/hard delete data operasional | MATCH | Route restore/force untuk lima modul operasional. | Baseline `ROLE-008`, `SEC-009`. | `src/routes/web.php:137-192` |
| Hard delete Dusun | MATCH | Tidak ada route, policy false. | Baseline `SEC-007`. | `src/routes/web.php:130-134`, `DusunPolicy.php:44-56` |
| Create dusun baru | MATCH | Tidak ada route create/store dusun. | Baseline `DATA-004` future. | `src/routes/web.php:130-134` |
| Data / Peta | MATCH | Ada view map-centric/proyeksi, bukan CRUD titik peta independen. | Sitemap 12.3. | `src/routes/web.php:194-195`, `DataPetaController.php:16-106` |
| Consent digital | MATCH | Tidak ada checkbox/proof upload consent. | Baseline privacy offline. | Form requests/views inspected |
| Tech stack | IMPLEMENTED BUT UNDOCUMENTED / IMPLEMENTATION CHANGE | Baseline menyatakan tech stack belum disetujui; implementasi sekarang memakai Laravel 13, Vite, Leaflet. | Baseline section technical candidates. | `composer.json`, `package.json` |
| UI visual/layout | VISUAL CHANGE | View aktual memakai layout admin/public tertentu, card, sidebar, nav, filter bar. Behavior utama tetap sesuai dokumen. | UX specs/wireframes lama. | Blade layouts and views |
| Self-service reset password | MATCH | Tidak ditemukan forgot/reset self-service. | Sitemap auth boundary, Open recovery Super Admin. | `src/routes/web.php:67-205` |
| Rate limiting login | UNCERTAIN | Requirements menyebut perlindungan brute force, tetapi audit statis ini belum menemukan throttle eksplisit pada route login. Perlu cek Laravel middleware default/runtime. | Baseline `SEC-006`. | `src/routes/web.php:67-69`, `src/bootstrap/app.php:18-37` |

## 12. User Manual Coverage Matrix

### A. Masyarakat/Pengunjung

| Materi | Fitur | Prosedur | Screenshot | Callout/catatan |
|---|---|---|---|---|
| Membuka portal | Homepage | Buka URL/scan QR, pahami menu utama. | Homepage desktop/mobile. | Portal publik tidak memerlukan akun. |
| Memilih Dusun | Pilihan Dusun | Klik nama/card dusun aktif. | Section dusun homepage. | Dusun nonaktif tidak tampil. |
| Membaca halaman Dusun | Halaman Dusun | Scroll ke profil, peta, kontak, UMKM, fasilitas, agenda, pengumuman. | Hero dusun dan section utama. | Isi bisa kosong; empty state bukan error. |
| Kontak via WhatsApp | Kontak Pelayanan | Klik tombol WhatsApp. | Kartu kontak. | WhatsApp adalah layanan eksternal. |
| UMKM | Direktori/detail UMKM | Buka detail, lihat produk, klik WhatsApp/arah bila ada. | Section UMKM dan detail UMKM. | Portal bukan toko online. |
| Fasilitas | Direktori/detail fasilitas | Buka detail, gunakan arah/kontak jika tersedia. | Section fasilitas dan detail fasilitas. | Nomor kontak fasilitas bisa kosong. |
| Peta | Peta Desa/Dusun | Gunakan filter, klik marker, buka detail/arah. | Peta Desa, Peta Dusun, popup marker. | Arah membuka Google Maps. |
| Agenda | Agenda/Kegiatan | Klik agenda dari homepage/dusun. | Section agenda dan detail agenda. | Status dapat Akan Datang/Berlangsung/Selesai. |
| Pengumuman | Pengumuman aktif/arsip | Buka detail atau arsip. | Section pengumuman, arsip, detail. | Arsip berbeda dari data nonaktif. |

### B. Admin Dusun

| Materi | Fitur | Prosedur | Screenshot | Callout/catatan |
|---|---|---|---|---|
| Login/logout | Auth admin | Login username/password, logout dari header. | Login, dashboard, tombol keluar. | Akun inactive tidak dapat login. |
| Dashboard | Ringkasan dusun sendiri | Baca count dan quick action. | Dashboard Admin Dusun. | Jika dusun nonaktif, data tetap bisa dikelola. |
| Profil Dusun | Edit profil | Isi profil dan banner, submit. | Form Profil Dusun. | Perubahan langsung berdampak publik bila dusun active. |
| Kontak Pelayanan | CRUD kontak | Tambah/edit/nonaktifkan kontak, isi WhatsApp dan lokasi opsional. | Index, create/edit kontak. | Pastikan izin publikasi offline sebelum data personal/lokasi privat dimasukkan. |
| UMKM | CRUD UMKM | Tambah/edit UMKM, produk, foto, koordinat opsional. | Index, create/edit UMKM. | UMKM tanpa koordinat tetap tampil tetapi tidak jadi marker. |
| Fasilitas | CRUD fasilitas | Pilih kategori, isi data dan koordinat wajib. | Index, create/edit fasilitas. | Koordinat wajib. |
| Agenda | CRUD agenda/media | Tambah/edit tanggal, jam opsional, status override, media. | Index, create/edit agenda. | Override status harus dipakai hati-hati. |
| Pengumuman | CRUD pengumuman | Tambah/edit tanggal kedaluwarsa, nonaktifkan bila perlu. | Index, create/edit pengumuman. | Kedaluwarsa masuk arsip; soft delete menyembunyikan publik. |
| Nonaktifkan data | Soft delete | Klik Nonaktifkan pada index. | Modal/tombol nonaktifkan. | Admin Dusun tidak dapat restore; hubungi Super Admin. |

### C. Super Admin

| Materi | Fitur | Prosedur | Screenshot | Callout/catatan |
|---|---|---|---|---|
| Dashboard global | Hub Super Admin | Baca statistik dan buka menu. | Dashboard Super Admin. | Scope global seluruh desa. |
| Identitas Desa | Profil desa | Edit nama, kepala desa, kontak, jam, alamat, banner. | Form Identitas Desa. | Mempengaruhi homepage. |
| Kelola Dusun | Profil/status dusun | Edit profil, aktifkan/nonaktifkan dusun. | List Dusun, edit Dusun. | Tidak ada hard delete dusun. |
| Modul lintas dusun | Kontak/UMKM/Fasilitas | Filter, create/edit, nonaktifkan, restore, hard delete. | Index dengan filter dan action. | Hard delete permanen. |
| Agenda/Pengumuman Desa dan Dusun | Scope DESA/DUSUN | Pilih cakupan, pilih dusun bila DUSUN. | Form Agenda/Pengumuman Super Admin. | Cakupan DESA melarang pilih dusun. |
| Kategori Fasilitas | Master kategori | Tambah/edit/hapus kategori. | Index/form kategori. | Dampak penghapusan saat kategori dipakai perlu diverifikasi manual. |
| Data / Peta | Proyeksi peta admin | Filter data lokasi dan buka konteks sumber. | Halaman Data / Peta. | Bukan CRUD titik peta terpisah. |
| Admin Dusun | Akun admin | Buat akun, edit penugasan, reset password, remove. | Index/create/edit/reset admin. | Remove bersifat logical `removed_at`. |

### D. Pedoman Pengelolaan

| Materi | Isi yang perlu dijelaskan | Screenshot |
|---|---|---|
| Batas role | Public read-only, Admin Dusun own dusun, Super Admin global. | Matrix sederhana role. |
| Publikasi langsung | Create/update Admin Dusun langsung tampil sesuai status publik. | Contoh form dan hasil publik. |
| Soft delete vs arsip | Soft delete menyembunyikan publik; Arsip Pengumuman adalah expiry. | Index soft-deleted dan Arsip Pengumuman. |
| Consent offline | Tidak ada field consent; admin wajib memastikan izin sebelum memasukkan data privat. | Callout di modul kontak/UMKM/lokasi. |
| Koordinat | Fasilitas wajib, UMKM/kontak opsional, coordinate picker. | Coordinate picker. |
| Media | Format JPG/PNG/WebP max 3MB; foto optional sesuai modul. | Upload foto/media. |
| Data nonaktif dusun | Dusun inactive tidak tampil publik, admin tetap bisa kelola. | Dashboard Admin Dusun inactive notice. |

### E. Troubleshooting

| Masalah | Target pembaca | Prosedur buku | Screenshot |
|---|---|---|---|
| Tidak bisa login | Admin Dusun/Super Admin | Cek username/password, hubungi Super Admin untuk reset Admin Dusun. Recovery Super Admin belum tersedia. | Login error, reset password. |
| Data tidak tampil publik | Admin Dusun/Super Admin | Cek soft delete, status dusun, tanggal kedaluwarsa pengumuman, koordinat marker. | Index status/filter, halaman publik. |
| Marker tidak muncul | Admin Dusun/Super Admin | Cek koordinat dan status dusun/data. | Form koordinat, peta. |
| Tombol WhatsApp tidak muncul/bermasalah | Pengunjung/Admin | Cek nomor WhatsApp field dan format. | Detail/kontak. |
| Pengumuman masuk arsip | Admin/Pengunjung | Jelaskan tanggal kedaluwarsa. | Arsip Pengumuman. |
| Gambar gagal upload | Admin | Cek format JPG/PNG/WebP dan ukuran max 3MB. | Error upload. |
| Akun Admin Dusun dinonaktifkan | Super Admin/Admin Dusun | Jelaskan logical removal dan reset tidak tersedia untuk akun removed. | Index akun Admin Dusun. |

## 13. Required Screenshot List

Public:

1. Homepage full first viewport.
2. Public nav desktop dan mobile menu.
3. Section pilihan dusun.
4. Peta Desa dengan filter dusun/kategori.
5. Popup marker peta dengan detail/arah.
6. Kontak Desa pada homepage.
7. Halaman Dusun hero/profil.
8. Peta Dusun dengan filter kategori.
9. Section Kontak Pelayanan Dusun.
10. Section UMKM dan Detail UMKM.
11. Section Fasilitas dan Detail Fasilitas.
12. Section Agenda dan Detail Agenda.
13. Section Pengumuman, Arsip Pengumuman, Detail Pengumuman.
14. Empty state public untuk modul tanpa data.

Authentication:

15. Login admin.
16. Error login gagal.
17. Logout button/header.

Admin Dusun:

18. Dashboard Admin Dusun normal.
19. Dashboard/Admin layout saat dusun `INACTIVE`.
20. Form Profil Dusun.
21. Index dan form Kontak Pelayanan.
22. Index dan form UMKM dengan produk dinamis.
23. Index dan form Fasilitas dengan coordinate picker.
24. Index dan form Agenda dengan media dinamis.
25. Index dan form Pengumuman.
26. Modal/action Nonaktifkan data.

Super Admin:

27. Dashboard Super Admin.
28. Form Identitas Desa.
29. List Dusun dan form edit Dusun.
30. Tombol Aktifkan/Nonaktifkan Dusun.
31. Index global Kontak/UMKM/Fasilitas dengan filter status/dusun.
32. Action Pulihkan dan Hapus Permanen.
33. Form Kategori Fasilitas.
34. Form Agenda/Pengumuman dengan scope DESA/DUSUN.
35. Halaman Data / Peta.
36. Index Admin Dusun.
37. Form Tambah Admin Dusun.
38. Form Edit Penugasan Admin Dusun.
39. Form Reset Password Admin Dusun.

## 14. Manual Verification Required

Item berikut perlu diverifikasi manual di browser atau environment runtime:

| Item | Alasan | Evidence statis |
|---|---|---|
| Login gagal actual UI copy | Static inspection melihat controller/request, tetapi tampilan error perlu dilihat di browser. | `LoginController.php:34-76`, `login.blade.php:60-127` |
| Remember me behavior | Form memiliki checkbox, tetapi efek durasi sesi tidak diuji. | `login.blade.php:113-120` |
| Toggle password | Ada button/JS di view, perlu cek interaksi. | `login.blade.php:103-108` |
| Rate limiting brute force | Tidak ditemukan eksplisit pada route; perlu cek middleware default Laravel/runtime. | `routes/web.php:67-69`, `bootstrap/app.php:18-37` |
| Semua submit form | Audit tidak melakukan POST/PUT/DELETE runtime. | Controllers/requests terkait |
| Upload media | Validasi dan service ada, tetapi perlu uji storage permission, resize/compression output, dan akses publik file. | `MediaService.php:31-140`, form upload |
| Coordinate picker browser behavior | Source JS ada, perlu cek klik peta, GPS permission, smart input, clear point. | `coordinate-picker.blade.php:12-306` |
| Peta publik tile loading | Leaflet/OpenStreetMap source ada, perlu cek koneksi dan rendering tile. | `map.js:245-262` |
| Popup marker detail/arah | Source membuat link; perlu cek UI popup aktual. | `map.js:141-345` |
| WhatsApp URL dan template pesan | Button ada; exact template dan nomor normalized perlu cek runtime. | `whatsapp-btn.blade.php:1-14` |
| Modal/action delete/restore/force delete | Button dan route ada; perlu cek modal confirm dan submit bekerja. | Index views dan route destroy/restore/force |
| Kategori fasilitas delete saat dipakai | Controller memiliki logic, perlu verifikasi pesan dan constraint saat kategori masih digunakan. | `KategoriFasilitasController.php:72-90` |
| Data / Peta action detail | Static inspection mengonfirmasi page map-centric; perlu cek link/action yang terlihat. | `DataPetaController.php:16-106`, `super-admin/peta/index.blade.php` |
| Responsif/mobile | Dokumen lama mobile-first; static audit tidak mengambil screenshot viewport. | Public/admin layouts |
| Akun removed saat sedang login | Middleware ada; perlu verifikasi session aktif yang akunnya kemudian di-remove. | `EnsureAdminAccountActive.php:16-26` |
| Pengumuman archive tanggal Asia/Jakarta | Model memakai tanggal bisnis; perlu uji boundary pada hari kedaluwarsa. | `Pengumuman.php:52-69` |

## 15. Findings & Recommendations

### Findings

1. Implementasi utama sudah membentuk portal as-built lengkap: area publik, auth, Admin Dusun, dan Super Admin tersedia. Evidence route utama ada di `src/routes/web.php:28-205`.
2. Role separation aktual kuat: route middleware membedakan role, policy membatasi resource, dan controller Admin Dusun melakukan query scoping berdasarkan `dusun_id` akun. Evidence: `src/bootstrap/app.php:18-37`, `src/routes/web.php:72-205`, `src/app/Policies/*Policy.php`, `src/app/Http/Controllers/Admin/*Controller.php`.
3. Super Admin memiliki kemampuan restore dan hard delete untuk data operasional, tetapi tidak untuk Dusun. Ini sesuai dokumen lama. Evidence: `src/routes/web.php:137-192`, `src/routes/web.php:130-134`, `src/app/Policies/DusunPolicy.php:44-56`.
4. Data / Peta sudah tersedia sebagai view/proyeksi map-centric, bukan CRUD titik peta independen. Ini penting untuk buku agar tidak mengajarkan "menambah titik peta" sebagai modul terpisah. Evidence: `src/routes/web.php:194-195`, `src/app/Http/Controllers/SuperAdmin/DataPetaController.php:16-106`.
5. Tidak ditemukan fitur self-service forgot password atau recovery Super Admin. Buku perlu menyatakan reset password hanya untuk Admin Dusun oleh Super Admin, dan recovery Super Admin adalah prosedur operasional di luar UI saat ini. Evidence: `src/routes/web.php:67-205`.
6. Tidak ditemukan field consent digital. Buku harus menekankan izin publikasi dilakukan administratif/offline sebelum data personal, foto, atau lokasi privat dimasukkan. Evidence: seluruh form request/view terkait kontak/media/lokasi tidak memuat consent field.
7. Beberapa requirement future tidak terimplementasi, sesuai baseline: pencarian nama lokasi pada peta, batas wilayah dusun, QR khusus per dusun, dan galeri multi-foto UMKM. Evidence: `src/resources/js/map.js:141-345`, route publik/admin saat ini.
8. Tech stack sekarang sudah nyata (Laravel 13, Vite, Leaflet), sedangkan baseline lama mencatat tech stack belum disetujui. Ini adalah implementation change yang harus dicatat sebagai as-built, bukan dikoreksi ke dokumen lama. Evidence: `src/composer.json:9-20`, `src/package.json:6-15`.

### Recommendations for Manual Book Preparation

1. Susun buku dari implementasi aktual, bukan dari PRD lama. Gunakan audit ini sebagai daftar isi operasional.
2. Ambil screenshot dari sistem berjalan setelah login sebagai tiga konteks: public, Admin Dusun, dan Super Admin.
3. Buat callout eksplisit untuk perbedaan penting: Arsip Pengumuman vs Soft Delete, Peta/Data Peta bukan CRUD titik independen, Admin Dusun tidak bisa restore, hard delete permanen hanya Super Admin untuk data operasional.
4. Tandai prosedur yang belum bisa dipastikan sampai verifikasi manual selesai: upload, peta, WhatsApp URL, modal delete/restore, responsif, dan rate limiting.
5. Jangan memasukkan prosedur untuk fitur yang tidak tersedia: registrasi warga, self-service reset password, pencarian lokasi, batas wilayah dusun, pembayaran UMKM, pemesanan UMKM, page builder homepage, create dusun baru, hard delete dusun, dan consent digital.

