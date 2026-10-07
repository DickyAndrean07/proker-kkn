# Manual Runtime Verification Checklist

## 1. Metadata

| Item | Nilai |
|---|---|
| Project | Portal Informasi Desa Bendung |
| Checklist type | Manual runtime verification for user manual preparation |
| Checklist date | 2026-08-20 |
| Source basis | `docs/07-handover/00-source-of-truth/as-built-user-manual-audit.md` and current browser runtime |
| Target output after testing | Evidence and notes for buku "Panduan Penggunaan dan Pengelolaan Portal Informasi Desa Bendung" |
| Source code rule | Read-only. Jangan mengubah source code, audit existing, PRD, Sitemap, User Flow, atau dokumen lama. |

Status rule for each test case:

- Keep `[x] NOT TESTED` until the test is actually run.
- After testing, mark exactly one runtime result: `[x] PASS`, `[x] FAIL`, `[x] DIFFERENT`, or `[x] NOT TESTED`.
- Use `DIFFERENT` when the runtime behavior works but differs from the expected behavior below.
- Use `FAIL` when the behavior is broken or blocks the user flow.

Status template:

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

## 2. Testing Preconditions

General:

- Jalankan aplikasi pada environment runtime yang akan diverifikasi melalui browser.
- Gunakan browser desktop dan mobile viewport atau perangkat mobile nyata.
- Gunakan data uji baru untuk semua aksi tambah/edit/nonaktifkan/restore/hapus permanen.
- Jangan memakai data produksi/serah-terima untuk test destructive seperti remove admin, restore, dan hapus permanen.
- Simpan screenshot ke folder evidence terpisah di luar source bila belum ada keputusan folder resmi.
- Jangan menyimpan password mentah ke dokumen handover. Gunakan kredensial uji yang sudah diberikan secara terpisah oleh pemilik project.

Recommended test data:

| Data | Kebutuhan |
|---|---|
| Dusun active | Minimal satu Dusun berstatus ACTIVE, disarankan Pohsengir bila tersedia. |
| Dusun inactive | Minimal satu Dusun dapat dinonaktifkan sementara oleh Super Admin untuk test visibility. |
| Kontak uji | Nama, jabatan, nomor WhatsApp uji, alamat pelayanan optional, foto optional, koordinat optional. |
| UMKM uji | Nama UMKM uji, pemilik, jenis usaha, produk, deskripsi, alamat, WhatsApp, jam operasional, foto, koordinat optional. |
| Fasilitas uji | Nama fasilitas uji, kategori, deskripsi, alamat, koordinat wajib, WhatsApp optional, foto optional. |
| Agenda uji | Judul, tanggal mulai, tanggal selesai optional, jam optional, lokasi, deskripsi, media optional, status override optional. |
| Pengumuman uji | Judul, isi, tanggal kedaluwarsa hari ini/masa depan dan satu data kedaluwarsa untuk arsip. |
| Admin Dusun uji | Akun Admin Dusun baru khusus test untuk create/edit/reset/remove. |

Runtime URLs/menu names to use:

| Area | Entry point/menu |
|---|---|
| Public | Homepage `/`, menu Beranda, Dusun, Informasi, Pengumuman, Agenda, Peta, Kontak |
| Auth | `/admin/login` |
| Admin Dusun | Dashboard, Profil Dusun, Kontak Pelayanan, Kelola UMKM, Kelola Fasilitas, Agenda & Kegiatan, Kelola Pengumuman |
| Super Admin | Dashboard, Identitas Desa, Kelola Dusun, Kontak Pelayanan, Kelola UMKM, Kelola Fasilitas, Kategori Fasilitas, Agenda & Kegiatan, Pengumuman, Data / Peta, Admin Dusun |

## 3. Public Verification

### RT-PUB-001 - Homepage dapat dimuat

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan homepage public dapat dibuka tanpa login.
Precondition/data: Aplikasi berjalan dan database memiliki identitas Desa.
Langkah manual: Buka `/` pada browser desktop.
Expected: Homepage tampil tanpa redirect login dan menampilkan identitas desa, pilihan dusun, pengumuman/agenda, peta, dan kontak desa bila data tersedia.
Actual:
Status:
Evidence Screenshot: Homepage first viewport.
Notes:

### RT-PUB-002 - Navigasi desktop

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan menu desktop sesuai implementasi aktual.
Precondition/data: Viewport desktop.
Langkah manual: Buka homepage, klik Beranda, Dusun, Informasi, Pengumuman, Agenda, Peta, Kontak.
Expected: Link mengarah ke section yang sesuai pada homepage atau kontak sesuai konteks; tidak ada login public.
Actual:
Status:
Evidence Screenshot: Header desktop dan section tujuan.
Notes:

### RT-PUB-003 - Navigasi mobile

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan mobile menu dapat dibuka dan link bekerja.
Precondition/data: Viewport mobile atau perangkat mobile.
Langkah manual: Buka homepage, tap tombol menu mobile, tap setiap item: Beranda, Dusun, Informasi Desa, Pengumuman, Agenda Kegiatan, Peta, Kontak & Pelayanan.
Expected: Menu terbuka, item dapat dipilih, dan halaman scroll/navigasi ke section terkait.
Actual:
Status:
Evidence Screenshot: Mobile menu terbuka.
Notes:

### RT-PUB-004 - Pilihan Dusun

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan dusun active tampil sebagai pilihan.
Precondition/data: Minimal satu Dusun ACTIVE.
Langkah manual: Dari homepage, buka section Dusun dan klik satu dusun active.
Expected: Link membuka halaman `/dusun/{id}` untuk dusun terkait.
Actual:
Status:
Evidence Screenshot: Section pilihan Dusun dan halaman tujuan.
Notes:

### RT-PUB-005 - Halaman Dusun aktif

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan halaman Dusun ACTIVE dapat dibaca publik.
Precondition/data: Dusun ACTIVE dengan atau tanpa data modul.
Langkah manual: Buka halaman Dusun dari homepage.
Expected: Halaman menampilkan profil Dusun, Peta Dusun, Kontak Pelayanan, UMKM, Fasilitas, Agenda & Kegiatan, dan Pengumuman atau empty state.
Actual:
Status:
Evidence Screenshot: Hero Dusun dan satu section modul.
Notes:

### RT-PUB-006 - Behavior Dusun inactive

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Dusun INACTIVE tidak tampil sebagai public normal.
Precondition/data: Ada Dusun yang dapat dinonaktifkan sementara oleh Super Admin.
Langkah manual: Catat URL Dusun, nonaktifkan dari Super Admin, buka ulang homepage dan URL langsung Dusun.
Expected: Dusun tidak tampil pada pilihan homepage; URL langsung tidak menampilkan konten publik normal. Bila status belum dapat dibuat inactive, Expected: PERLU OBSERVASI.
Actual:
Status:
Evidence Screenshot: Homepage setelah inactive dan hasil URL langsung.
Notes:

### RT-PUB-007 - Peta Desa dimuat

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Peta Desa public tampil.
Precondition/data: Homepage dapat dibuka; koneksi internet untuk tile OpenStreetMap.
Langkah manual: Buka section Peta Desa pada homepage.
Expected: Peta tampil dengan tile OpenStreetMap, toolbar filter Dusun dan Kategori, legend, dan marker bila data koordinat tersedia.
Actual:
Status:
Evidence Screenshot: Peta Desa.
Notes:

### RT-PUB-008 - Filter Dusun pada Peta Desa

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan filter Dusun mengubah marker Peta Desa.
Precondition/data: Marker dari lebih dari satu Dusun atau minimal satu marker dengan dusun teridentifikasi.
Langkah manual: Pada Peta Desa, pilih filter Dusun.
Expected: Marker difilter sesuai Dusun yang dipilih. Jika data marker tidak memadai, Expected: PERLU OBSERVASI.
Actual:
Status:
Evidence Screenshot: Peta sebelum/sesudah filter.
Notes:

### RT-PUB-009 - Filter kategori pada Peta Desa

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan filter kategori mengubah marker Peta Desa.
Precondition/data: Minimal satu kategori marker tersedia.
Langkah manual: Pada Peta Desa, pilih filter kategori.
Expected: Marker difilter sesuai kategori yang dipilih. Jika hanya satu kategori, Expected: PERLU OBSERVASI.
Actual:
Status:
Evidence Screenshot: Filter kategori aktif.
Notes:

### RT-PUB-010 - Peta Dusun dimuat

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Peta Dusun tampil pada halaman Dusun.
Precondition/data: Dusun ACTIVE.
Langkah manual: Buka halaman Dusun dan scroll ke Peta Dusun.
Expected: Peta Dusun tampil, otomatis scoped ke Dusun tersebut, dengan filter kategori.
Actual:
Status:
Evidence Screenshot: Peta Dusun.
Notes:

### RT-PUB-011 - Popup marker

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan marker dapat diklik dan popup tampil.
Precondition/data: Minimal satu marker pada Peta Desa atau Peta Dusun.
Langkah manual: Klik marker fasilitas/UMKM/kontak.
Expected: Popup tampil dengan nama, kategori, alamat/foto bila tersedia, link detail/konteks, dan tombol arah.
Actual:
Status:
Evidence Screenshot: Popup marker terbuka.
Notes:

### RT-PUB-012 - Link detail marker

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan link detail marker menuju halaman/konteks benar.
Precondition/data: Marker UMKM, fasilitas, dan kontak bila tersedia.
Langkah manual: Klik link detail pada popup marker.
Expected: Marker UMKM membuka Detail UMKM; marker Fasilitas membuka Detail Fasilitas; marker Kontak mengarah ke section Kontak Pelayanan Dusun. Jika jenis marker tertentu tidak tersedia, Expected: PERLU OBSERVASI.
Actual:
Status:
Evidence Screenshot: Popup dan halaman/konteks tujuan.
Notes:

### RT-PUB-013 - Tombol arah Google Maps

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan arah lokasi membuka Google Maps.
Precondition/data: Marker atau detail dengan koordinat.
Langkah manual: Klik tombol arah pada popup/detail.
Expected: Browser membuka URL Google Maps directions eksternal dengan destination koordinat terkait.
Actual:
Status:
Evidence Screenshot: Popup/detail dan tab Google Maps.
Notes:

### RT-PUB-014 - Tombol WhatsApp

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan tombol WhatsApp membuka chat eksternal.
Precondition/data: Kontak/UMKM/Fasilitas dengan nomor WhatsApp.
Langkah manual: Klik tombol WhatsApp pada kontak atau detail.
Expected: Browser membuka WhatsApp/web WhatsApp dengan nomor terkait. Template pesan: PERLU OBSERVASI.
Actual:
Status:
Evidence Screenshot: Tombol sebelum klik dan URL/handoff.
Notes:

### RT-PUB-015 - Detail UMKM

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Detail UMKM dapat dibuka dari section/marker.
Precondition/data: Minimal satu UMKM aktif.
Langkah manual: Buka Detail UMKM.
Expected: Detail menampilkan nama UMKM, pemilik, jenis usaha, produk bila ada, deskripsi, alamat, jam operasional, WhatsApp, foto/placeholder, dan arah bila koordinat tersedia.
Actual:
Status:
Evidence Screenshot: Detail UMKM.
Notes:

### RT-PUB-016 - Detail fasilitas

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Detail Fasilitas dapat dibuka.
Precondition/data: Minimal satu fasilitas aktif.
Langkah manual: Buka Detail Fasilitas.
Expected: Detail menampilkan nama, kategori, deskripsi, alamat, foto/placeholder, arah Google Maps, dan WhatsApp bila nomor tersedia.
Actual:
Status:
Evidence Screenshot: Detail Fasilitas.
Notes:

### RT-PUB-017 - Detail agenda

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Detail Agenda/Kegiatan dapat dibuka.
Precondition/data: Minimal satu agenda tingkat Desa atau Dusun.
Langkah manual: Klik agenda dari homepage atau halaman Dusun.
Expected: Detail agenda tampil dengan judul, tanggal, lokasi, deskripsi, status efektif, dan media bila tersedia.
Actual:
Status:
Evidence Screenshot: Detail Agenda.
Notes:

### RT-PUB-018 - Pengumuman aktif

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Pengumuman aktif tampil dan detailnya dapat dibaca.
Precondition/data: Minimal satu pengumuman dengan tanggal kedaluwarsa hari ini atau masa depan.
Langkah manual: Buka section Pengumuman homepage/Dusun dan klik item.
Expected: Pengumuman aktif tampil di daftar aktif dan detail dapat dibuka.
Actual:
Status:
Evidence Screenshot: Section dan detail pengumuman.
Notes:

### RT-PUB-019 - Arsip Pengumuman

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan pengumuman kedaluwarsa tampil di Arsip Pengumuman.
Precondition/data: Minimal satu pengumuman kedaluwarsa dan tidak soft-deleted.
Langkah manual: Buka link Arsip Pengumuman dari homepage atau halaman Dusun.
Expected: Arsip menampilkan pengumuman kedaluwarsa, dan detailnya dapat dibuka. Jika belum ada pengumuman kedaluwarsa, empty state tampil.
Actual:
Status:
Evidence Screenshot: Arsip Pengumuman.
Notes:

### RT-PUB-020 - Empty state public

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan section public tetap informatif saat data kosong.
Precondition/data: Modul tertentu pada Dusun tidak memiliki data aktif.
Langkah manual: Buka halaman Dusun atau homepage dengan section kosong.
Expected: Section tetap tampil dengan empty state, bukan error. Jika semua modul sudah berisi data, Expected: PERLU OBSERVASI.
Actual:
Status:
Evidence Screenshot: Empty state.
Notes:

## 4. Authentication Verification

### RT-AUTH-001 - Login Admin Dusun berhasil

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan akun Admin Dusun dapat login.
Precondition/data: Akun Admin Dusun aktif tersedia.
Langkah manual: Buka `/admin/login`, isi kredensial Admin Dusun, submit.
Expected: Login sukses dan redirect ke Dashboard Admin Dusun.
Actual:
Status:
Evidence Screenshot: Login form dan Dashboard Admin Dusun.
Notes:

### RT-AUTH-002 - Login Super Admin berhasil

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan akun Super Admin dapat login.
Precondition/data: Akun Super Admin aktif tersedia.
Langkah manual: Logout bila masih login, buka `/admin/login`, isi kredensial Super Admin, submit.
Expected: Login sukses dan redirect ke Dashboard Super Admin.
Actual:
Status:
Evidence Screenshot: Login form dan Dashboard Super Admin.
Notes:

### RT-AUTH-003 - Login username/password salah

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan login gagal tidak memberi akses.
Precondition/data: Browser belum login.
Langkah manual: Isi username salah atau password salah, submit.
Expected: Tetap di form login dan muncul pesan error. Exact UI copy: PERLU OBSERVASI.
Actual:
Status:
Evidence Screenshot: Error login.
Notes:

### RT-AUTH-004 - Toggle lihat password

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan tombol lihat password bekerja.
Precondition/data: Halaman login terbuka.
Langkah manual: Isi password, klik toggle lihat password, klik kembali.
Expected: Field password berubah terlihat/tersembunyi sesuai toggle.
Actual:
Status:
Evidence Screenshot: Sebelum/sesudah toggle bila aman tanpa menampilkan password nyata.
Notes:

### RT-AUTH-005 - Remember me

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Mengamati efek checkbox remember.
Precondition/data: Akun admin aktif.
Langkah manual: Login dengan checkbox remember dicentang, tutup browser/session sesuai skenario test, buka kembali.
Expected: PERLU OBSERVASI.
Actual:
Status:
Evidence Screenshot: Checkbox remember dan hasil setelah buka ulang.
Notes:

### RT-AUTH-006 - Logout

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan logout mengakhiri sesi.
Precondition/data: Login sebagai Admin Dusun atau Super Admin.
Langkah manual: Klik tombol Keluar pada header admin.
Expected: Redirect ke halaman login; akses dashboard setelah logout meminta login kembali.
Actual:
Status:
Evidence Screenshot: Tombol Keluar dan halaman login.
Notes:

### RT-AUTH-007 - Redirect role saat sudah login

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan user yang sudah login diarahkan sesuai role.
Precondition/data: Login sebagai Admin Dusun, lalu ulangi dengan Super Admin.
Langkah manual: Saat sudah login, buka `/admin/login`.
Expected: Admin Dusun diarahkan ke Dashboard Admin Dusun; Super Admin diarahkan ke Dashboard Super Admin.
Actual:
Status:
Evidence Screenshot: URL tujuan setelah membuka login.
Notes:

### RT-AUTH-008 - Akun Admin Dusun removed/nonaktif

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan akun Admin Dusun yang di-remove tidak dapat digunakan.
Precondition/data: Akun Admin Dusun uji yang aman untuk di-remove oleh Super Admin.
Langkah manual: Login Super Admin, remove akun Admin Dusun uji, logout, coba login memakai akun tersebut.
Expected: Login ditolak atau sesi aktif akun removed diputus oleh middleware. Jika tidak aman untuk diuji, Expected: PERLU OBSERVASI.
Actual:
Status:
Evidence Screenshot: Remove akun dan hasil login.
Notes:

## 5. Admin Dusun Verification

### RT-AD-001 - Dashboard Admin Dusun

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan dashboard Admin Dusun tampil dan scoped ke dusunnya.
Precondition/data: Login sebagai Admin Dusun.
Langkah manual: Buka Dashboard.
Expected: Dashboard menampilkan konteks Dusun, quick action, ringkasan Kontak, UMKM, Fasilitas, Agenda, Pengumuman, dan ringkasan profil.
Actual:
Status:
Evidence Screenshot: Dashboard Admin Dusun.
Notes:

### RT-AD-002 - Edit Profil Dusun

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan profil Dusun dapat diedit oleh Admin Dusun.
Precondition/data: Login sebagai Admin Dusun; siapkan perubahan kecil yang aman.
Langkah manual: Buka Profil Dusun, edit nama/deskripsi/kepala/jumlah RT/RW, simpan.
Expected: Perubahan tersimpan dan success message tampil; halaman publik Dusun mengikuti data terbaru bila Dusun active.
Actual:
Status:
Evidence Screenshot: Form Profil Dusun dan hasil publik.
Notes:

### RT-AD-003 - Upload/ganti banner Profil Dusun

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan banner Dusun dapat diupload.
Precondition/data: File JPG/PNG/WebP kurang dari 3 MB.
Langkah manual: Buka Profil Dusun, upload banner, simpan.
Expected: Upload sukses, banner tampil kembali pada form dan halaman publik Dusun.
Actual:
Status:
Evidence Screenshot: Form setelah upload dan halaman publik.
Notes:

### RT-AD-004 - Tambah Kontak Pelayanan

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Admin Dusun dapat menambah kontak pada dusunnya.
Precondition/data: Data kontak uji dan izin publikasi offline untuk data personal bila digunakan.
Langkah manual: Buka Kontak Pelayanan, klik Tambah, isi nama, jabatan, nomor WhatsApp, alamat/foto/koordinat bila perlu, simpan.
Expected: Kontak tersimpan di index Admin Dusun dan tampil di halaman Dusun bila data aktif.
Actual:
Status:
Evidence Screenshot: Form kontak, index, halaman publik.
Notes:

### RT-AD-005 - Edit Kontak Pelayanan

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan kontak uji dapat diedit.
Precondition/data: Kontak uji milik Dusun admin.
Langkah manual: Buka Kontak Pelayanan, edit kontak uji, simpan.
Expected: Perubahan tersimpan dan tampil di index/public.
Actual:
Status:
Evidence Screenshot: Edit kontak dan hasil.
Notes:

### RT-AD-006 - Nonaktifkan Kontak Pelayanan

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan soft delete kontak oleh Admin Dusun.
Precondition/data: Kontak uji yang aman untuk dinonaktifkan.
Langkah manual: Dari index Kontak Pelayanan, klik Nonaktifkan/Hapus sesuai UI.
Expected: Kontak tidak tampil public dan tidak tersedia sebagai data aktif Admin Dusun. Restore tidak tersedia untuk Admin Dusun.
Actual:
Status:
Evidence Screenshot: Action nonaktifkan dan hasil public.
Notes:

### RT-AD-007 - Tambah/Edit UMKM

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Admin Dusun dapat tambah dan edit UMKM.
Precondition/data: Data UMKM uji.
Langkah manual: Buka Kelola UMKM, tambah UMKM, simpan, lalu edit satu field dan simpan.
Expected: UMKM tersimpan di index, detail public dapat dibuka, dan edit terlihat pada public.
Actual:
Status:
Evidence Screenshot: Form UMKM, index, detail public.
Notes:

### RT-AD-008 - Produk dinamis UMKM

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan baris produk UMKM dapat ditambah/dihapus.
Precondition/data: Form tambah/edit UMKM.
Langkah manual: Tambah beberapa baris produk, hapus satu baris, simpan.
Expected: Produk yang tersimpan tampil pada detail UMKM; baris yang dihapus tidak tampil.
Actual:
Status:
Evidence Screenshot: Form produk dan detail UMKM.
Notes:

### RT-AD-009 - Foto UMKM

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan foto utama UMKM dapat diupload dan tampil.
Precondition/data: File gambar valid kurang dari 3 MB.
Langkah manual: Upload foto utama pada UMKM uji dan simpan.
Expected: Foto tampil pada index/detail public atau placeholder berubah menjadi foto.
Actual:
Status:
Evidence Screenshot: Upload dan detail UMKM.
Notes:

### RT-AD-010 - Coordinate picker UMKM

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan koordinat UMKM optional dan marker muncul bila lengkap.
Precondition/data: UMKM uji; browser mendukung peta.
Langkah manual: Buka form UMKM, klik peta/input koordinat, simpan, cek Peta Dusun.
Expected: Koordinat tersimpan; UMKM muncul sebagai marker. Jika koordinat dikosongkan, UMKM tetap tampil di direktori tetapi tidak menjadi marker.
Actual:
Status:
Evidence Screenshot: Coordinate picker dan marker UMKM.
Notes:

### RT-AD-011 - Nonaktifkan UMKM

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan UMKM dapat di-soft-delete oleh Admin Dusun.
Precondition/data: UMKM uji.
Langkah manual: Dari index Kelola UMKM, nonaktifkan UMKM uji.
Expected: UMKM hilang dari public dan Admin Dusun tidak memiliki restore/hard delete.
Actual:
Status:
Evidence Screenshot: Action nonaktif dan public setelahnya.
Notes:

### RT-AD-012 - Tambah/Edit Fasilitas

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Admin Dusun dapat tambah/edit fasilitas.
Precondition/data: Kategori fasilitas tersedia; data fasilitas uji.
Langkah manual: Buka Kelola Fasilitas, tambah fasilitas dengan koordinat, simpan, lalu edit.
Expected: Fasilitas tersimpan, tampil public, detail dapat dibuka.
Actual:
Status:
Evidence Screenshot: Form fasilitas, index, detail public.
Notes:

### RT-AD-013 - Coordinate picker fasilitas wajib

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan koordinat fasilitas wajib.
Precondition/data: Form tambah fasilitas.
Langkah manual: Coba submit fasilitas tanpa latitude/longitude, lalu isi koordinat dan submit ulang.
Expected: Submit tanpa koordinat ditolak dengan validasi; submit dengan koordinat valid berhasil.
Actual:
Status:
Evidence Screenshot: Error validasi dan submit sukses.
Notes:

### RT-AD-014 - Nonaktifkan Fasilitas

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan fasilitas dapat di-soft-delete oleh Admin Dusun.
Precondition/data: Fasilitas uji.
Langkah manual: Dari index Kelola Fasilitas, nonaktifkan fasilitas uji.
Expected: Fasilitas tidak tampil public dan tidak menjadi marker; Admin Dusun tidak memiliki restore/hard delete.
Actual:
Status:
Evidence Screenshot: Action nonaktif dan Peta/Detail public.
Notes:

### RT-AD-015 - Tambah/Edit Agenda

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Admin Dusun dapat tambah/edit Agenda & Kegiatan.
Precondition/data: Data agenda uji.
Langkah manual: Buka Agenda & Kegiatan, tambah agenda, simpan, edit satu field, simpan.
Expected: Agenda tersimpan pada index dan tampil public pada halaman Dusun.
Actual:
Status:
Evidence Screenshot: Form agenda, index, detail public.
Notes:

### RT-AD-016 - Media Agenda

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan media agenda dapat ditambah dan dihapus.
Precondition/data: File gambar valid kurang dari 3 MB; agenda uji.
Langkah manual: Tambah media dengan role Poster Awal/Dokumentasi, simpan, edit dan hapus media existing bila aman.
Expected: Media tampil pada detail agenda; media yang dihapus tidak tampil.
Actual:
Status:
Evidence Screenshot: Form media dan detail agenda.
Notes:

### RT-AD-017 - Status Agenda

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan status agenda tampil sesuai tanggal atau override.
Precondition/data: Agenda uji dengan tanggal masa depan/hari ini/lampau atau override manual.
Langkah manual: Buat/edit agenda dengan variasi tanggal atau pilih status override.
Expected: Status public/admin menjadi Akan Datang, Berlangsung, atau Selesai sesuai implementasi. Boundary tanggal: PERLU OBSERVASI.
Actual:
Status:
Evidence Screenshot: Status agenda.
Notes:

### RT-AD-018 - Tambah/Edit/Nonaktifkan Pengumuman

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Admin Dusun dapat mengelola Pengumuman Dusun.
Precondition/data: Data pengumuman uji.
Langkah manual: Tambah pengumuman, edit, cek public, lalu nonaktifkan data uji.
Expected: Pengumuman aktif tampil sebelum/sampai tanggal kedaluwarsa; setelah nonaktif, tidak tampil public.
Actual:
Status:
Evidence Screenshot: Form, index, public.
Notes:

### RT-AD-019 - Pengumuman kedaluwarsa masuk Arsip

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan expiry berbeda dari soft delete.
Precondition/data: Pengumuman uji dengan tanggal kedaluwarsa lampau dan tidak soft-deleted.
Langkah manual: Buat/ubah pengumuman uji menjadi kedaluwarsa, buka Arsip Pengumuman Dusun.
Expected: Pengumuman tidak tampil di daftar aktif tetapi tampil di Arsip Pengumuman public. Jika tidak aman mengubah tanggal, Expected: PERLU OBSERVASI.
Actual:
Status:
Evidence Screenshot: Index aktif dan Arsip.
Notes:

### RT-AD-020 - Batas akses Admin Dusun

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Admin Dusun tidak dapat mengakses Dusun lain, restore, atau hard delete.
Precondition/data: Login Admin Dusun; ketahui ID data milik dusun lain bila aman diuji.
Langkah manual: Cek tidak ada selector Dusun pada form Admin Dusun; coba akses URL edit data milik dusun lain bila aman; cek tidak ada tombol restore/hapus permanen.
Expected: Admin Dusun hanya melihat/kelola dusunnya sendiri; akses data dusun lain ditolak/404; restore/hard delete tidak tersedia.
Actual:
Status:
Evidence Screenshot: Form tanpa selector dusun, URL/access result, action buttons.
Notes:

## 6. Super Admin Verification

### RT-SA-001 - Dashboard global

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Dashboard Super Admin tampil.
Precondition/data: Login sebagai Super Admin.
Langkah manual: Buka Dashboard.
Expected: Dashboard menampilkan statistik global dan hub/menu Identitas Desa, Kelola Dusun, Kontak, UMKM, Fasilitas, Kategori, Agenda, Pengumuman, Data / Peta, Admin Dusun.
Actual:
Status:
Evidence Screenshot: Dashboard Super Admin.
Notes:

### RT-SA-002 - Edit Identitas Desa

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan identitas Desa dapat diedit.
Precondition/data: Login Super Admin; perubahan kecil yang aman.
Langkah manual: Buka Identitas Desa, edit field uji, simpan, cek homepage.
Expected: Data tersimpan dan homepage mengikuti perubahan.
Actual:
Status:
Evidence Screenshot: Form Identitas Desa dan homepage.
Notes:

### RT-SA-003 - Kelola Dusun

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Super Admin dapat melihat dan edit profil Dusun.
Precondition/data: Login Super Admin; Dusun uji.
Langkah manual: Buka Kelola Dusun, edit profil Dusun uji, simpan.
Expected: Perubahan tersimpan pada list dan halaman public bila Dusun active.
Actual:
Status:
Evidence Screenshot: List Dusun, form edit, public.
Notes:

### RT-SA-004 - Aktifkan/nonaktifkan Dusun

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan lifecycle status Dusun bekerja.
Precondition/data: Dusun uji yang aman untuk dinonaktifkan sementara.
Langkah manual: Dari Kelola Dusun, nonaktifkan Dusun uji, cek homepage/URL public, lalu aktifkan kembali.
Expected: Saat inactive, Dusun tidak tampil public normal; saat active kembali, tampil lagi.
Actual:
Status:
Evidence Screenshot: Action status dan public result.
Notes:

### RT-SA-005 - Kelola Kontak lintas Dusun

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Super Admin dapat mengelola kontak lintas Dusun.
Precondition/data: Data kontak uji dan minimal satu Dusun.
Langkah manual: Buka Kontak Pelayanan, gunakan filter Dusun/status, tambah/edit kontak dengan memilih Dusun.
Expected: Kontak tersimpan pada Dusun yang dipilih dan tampil public sesuai Dusun.
Actual:
Status:
Evidence Screenshot: Filter, form, public.
Notes:

### RT-SA-006 - Kelola UMKM lintas Dusun

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Super Admin dapat mengelola UMKM lintas Dusun.
Precondition/data: Data UMKM uji.
Langkah manual: Buka Kelola UMKM, filter Dusun/status, tambah/edit UMKM dengan memilih Dusun.
Expected: UMKM tersimpan pada Dusun pilihan dan tampil pada halaman Dusun terkait.
Actual:
Status:
Evidence Screenshot: Filter, form, detail public.
Notes:

### RT-SA-007 - Kelola Fasilitas lintas Dusun

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Super Admin dapat mengelola fasilitas lintas Dusun.
Precondition/data: Kategori tersedia; data fasilitas uji.
Langkah manual: Buka Kelola Fasilitas, filter Dusun/kategori/status, tambah/edit fasilitas dengan memilih Dusun.
Expected: Fasilitas tersimpan pada Dusun pilihan dan tampil public.
Actual:
Status:
Evidence Screenshot: Filter, form, detail public.
Notes:

### RT-SA-008 - Restore data

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Super Admin dapat restore data soft-deleted.
Precondition/data: Data uji soft-deleted pada kontak/UMKM/fasilitas/agenda/pengumuman.
Langkah manual: Buka modul terkait, filter status Soft Deleted, klik Pulihkan.
Expected: Data kembali aktif dan dapat tampil public sesuai scope/status.
Actual:
Status:
Evidence Screenshot: Filter Soft Deleted, action Pulihkan, hasil.
Notes:

### RT-SA-009 - Hapus Permanen data

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan hard delete hanya dilakukan pada data uji.
Precondition/data: Data uji yang sudah soft-deleted dan aman dihapus permanen.
Langkah manual: Filter Soft Deleted, klik Hapus Permanen pada data uji, konfirmasi.
Expected: Data terhapus permanen dan tidak dapat direstore melalui UI. Gunakan hanya data uji.
Actual:
Status:
Evidence Screenshot: Action Hapus Permanen dan hasil list.
Notes:

### RT-SA-010 - Kelola Kategori Fasilitas

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Super Admin dapat tambah/edit kategori fasilitas.
Precondition/data: Nama kategori uji unik.
Langkah manual: Buka Kategori Fasilitas, tambah kategori uji, edit nama kategori.
Expected: Kategori tersimpan dan tersedia pada form fasilitas.
Actual:
Status:
Evidence Screenshot: Form kategori dan select fasilitas.
Notes:

### RT-SA-011 - Behavior hapus kategori yang sedang dipakai

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Mengamati behavior saat kategori dipakai fasilitas.
Precondition/data: Kategori uji yang sedang digunakan fasilitas uji.
Langkah manual: Coba hapus kategori tersebut.
Expected: PERLU OBSERVASI. Audit statis mengindikasikan controller menangani kondisi relasi, tetapi pesan dan hasil runtime harus diamati.
Actual:
Status:
Evidence Screenshot: Attempt hapus dan pesan hasil.
Notes:

### RT-SA-012 - Agenda scope DESA

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Super Admin dapat membuat Agenda tingkat Desa.
Precondition/data: Data agenda uji.
Langkah manual: Buka Agenda & Kegiatan, tambah agenda, pilih Cakupan Wilayah tingkat Desa, simpan.
Expected: Agenda tersimpan tanpa memilih Dusun dan tampil pada homepage.
Actual:
Status:
Evidence Screenshot: Form scope Desa dan homepage.
Notes:

### RT-SA-013 - Agenda scope DUSUN

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Super Admin dapat membuat Agenda tingkat Dusun.
Precondition/data: Data agenda uji dan Dusun target.
Langkah manual: Tambah agenda, pilih Cakupan Wilayah tingkat Dusun, pilih Dusun, simpan.
Expected: Agenda tersimpan pada Dusun target dan tampil pada halaman Dusun target.
Actual:
Status:
Evidence Screenshot: Form scope Dusun dan halaman Dusun.
Notes:

### RT-SA-014 - Pengumuman scope DESA

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Super Admin dapat membuat Pengumuman tingkat Desa.
Precondition/data: Data pengumuman uji.
Langkah manual: Buka Pengumuman, tambah pengumuman, pilih Cakupan Wilayah tingkat Desa, simpan.
Expected: Pengumuman tersimpan tanpa memilih Dusun dan tampil pada homepage bila aktif.
Actual:
Status:
Evidence Screenshot: Form scope Desa dan homepage.
Notes:

### RT-SA-015 - Pengumuman scope DUSUN

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Super Admin dapat membuat Pengumuman tingkat Dusun.
Precondition/data: Data pengumuman uji dan Dusun target.
Langkah manual: Tambah pengumuman, pilih Cakupan Wilayah tingkat Dusun, pilih Dusun, simpan.
Expected: Pengumuman tersimpan pada Dusun target dan tampil pada halaman Dusun target bila aktif.
Actual:
Status:
Evidence Screenshot: Form scope Dusun dan halaman Dusun.
Notes:

### RT-SA-016 - Data / Peta

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan halaman Data / Peta adalah proyeksi data lokasi.
Precondition/data: Login Super Admin; data lokasi tersedia.
Langkah manual: Buka Data / Peta, gunakan filter Dusun/kategori, buka action/link detail bila tersedia.
Expected: Halaman menampilkan konteks peta/data fasilitas, UMKM, dan kontak; bukan CRUD titik peta independen. Action detail: PERLU OBSERVASI.
Actual:
Status:
Evidence Screenshot: Halaman Data / Peta dan filter.
Notes:

### RT-SA-017 - Tambah Admin Dusun

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Super Admin dapat membuat akun Admin Dusun uji.
Precondition/data: Username uji unik dan Dusun target.
Langkah manual: Buka Admin Dusun, Tambah, isi username, password uji, Dusun, simpan.
Expected: Akun Admin Dusun dibuat dan muncul di list.
Actual:
Status:
Evidence Screenshot: Form tambah dan list akun.
Notes:

### RT-SA-018 - Edit penugasan Admin Dusun

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan penugasan Dusun akun Admin Dusun dapat diubah.
Precondition/data: Akun Admin Dusun uji.
Langkah manual: Edit akun Admin Dusun uji, pilih Dusun lain yang aman, simpan.
Expected: Penugasan Dusun berubah; saat akun login, dashboard mengikuti Dusun baru.
Actual:
Status:
Evidence Screenshot: Edit assignment dan dashboard akun.
Notes:

### RT-SA-019 - Reset password Admin Dusun

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan reset password Admin Dusun bekerja.
Precondition/data: Akun Admin Dusun uji aktif.
Langkah manual: Dari Admin Dusun, buka Reset Password, isi password baru dan konfirmasi, simpan, coba login dengan password baru.
Expected: Password lama tidak digunakan lagi; password baru berhasil login.
Actual:
Status:
Evidence Screenshot: Form reset dan login sukses.
Notes:

### RT-SA-020 - Remove Admin Dusun

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan remove Admin Dusun bersifat logical removal.
Precondition/data: Akun Admin Dusun uji yang aman di-remove.
Langkah manual: Dari Admin Dusun, remove akun uji.
Expected: Akun ditandai nonaktif/removed dan tidak dapat login. Reset/edit untuk akun removed tidak tersedia atau ditolak sesuai UI.
Actual:
Status:
Evidence Screenshot: Action remove dan hasil login.
Notes:

### RT-SA-021 - Tidak tersedia create Dusun

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Super Admin tidak memiliki UI create Dusun baru.
Precondition/data: Login Super Admin.
Langkah manual: Buka Kelola Dusun dan cek action yang tersedia.
Expected: Tidak ada tombol/form Tambah Dusun baru.
Actual:
Status:
Evidence Screenshot: List Kelola Dusun.
Notes:

### RT-SA-022 - Tidak tersedia hard delete Dusun

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Dusun tidak bisa dihapus permanen dari UI.
Precondition/data: Login Super Admin.
Langkah manual: Buka Kelola Dusun dan edit Dusun.
Expected: Hanya edit profil dan aktifkan/nonaktifkan; tidak ada Hapus Permanen Dusun.
Actual:
Status:
Evidence Screenshot: List/action Dusun.
Notes:

## 7. Media & Map Verification

### RT-MM-001 - Upload JPG

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan upload JPG diterima.
Precondition/data: File JPG kurang dari 3 MB.
Langkah manual: Upload JPG pada salah satu form media, misalnya foto UMKM atau banner.
Expected: Upload berhasil dan gambar tampil kembali.
Actual:
Status:
Evidence Screenshot: Upload dan hasil tampil.
Notes:

### RT-MM-002 - Upload PNG

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan upload PNG diterima.
Precondition/data: File PNG kurang dari 3 MB.
Langkah manual: Upload PNG pada form media uji.
Expected: Upload berhasil dan gambar tampil kembali.
Actual:
Status:
Evidence Screenshot: Upload dan hasil tampil.
Notes:

### RT-MM-003 - Upload WebP

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan upload WebP diterima.
Precondition/data: File WebP kurang dari 3 MB.
Langkah manual: Upload WebP pada form media uji.
Expected: Upload berhasil dan gambar tampil kembali.
Actual:
Status:
Evidence Screenshot: Upload dan hasil tampil.
Notes:

### RT-MM-004 - Validasi file lebih dari 3 MB

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan file terlalu besar ditolak.
Precondition/data: File gambar lebih dari 3 MB yang aman untuk test.
Langkah manual: Upload file tersebut pada form media.
Expected: Submit ditolak dengan pesan validasi ukuran maksimal 3 MB.
Actual:
Status:
Evidence Screenshot: Error validasi.
Notes:

### RT-MM-005 - Penyimpanan/gambar tampil kembali

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan file yang diupload dapat dibaca setelah reload.
Precondition/data: Media valid sudah diupload.
Langkah manual: Simpan form, reload halaman admin, buka halaman public/detail terkait.
Expected: Gambar tetap tampil setelah reload dan tidak broken image.
Actual:
Status:
Evidence Screenshot: Admin reload dan public/detail.
Notes:

### RT-MM-006 - WhatsApp URL dan template pesan

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Mengamati URL WhatsApp dan pesan awal.
Precondition/data: Data dengan nomor WhatsApp.
Langkah manual: Klik tombol WhatsApp dari Kontak/UMKM/Fasilitas.
Expected: URL membuka WhatsApp untuk nomor terkait. Template pesan: PERLU OBSERVASI.
Actual:
Status:
Evidence Screenshot: Tombol dan URL/handoff.
Notes:

### RT-MM-007 - Google Maps direction

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan tombol arah memakai Google Maps destination.
Precondition/data: Data dengan koordinat.
Langkah manual: Klik tombol arah dari detail atau popup marker.
Expected: Google Maps terbuka dengan destination sesuai koordinat data.
Actual:
Status:
Evidence Screenshot: URL Google Maps.
Notes:

### RT-MM-008 - Loading tile OpenStreetMap

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan base map public/admin termuat.
Precondition/data: Koneksi internet mengizinkan akses OpenStreetMap.
Langkah manual: Buka Peta Desa, Peta Dusun, dan Data / Peta.
Expected: Tile peta tampil; jika tile gagal, notice/error terlihat sesuai runtime. Expected detail notice: PERLU OBSERVASI.
Actual:
Status:
Evidence Screenshot: Map tile loaded atau error state.
Notes:

### RT-MM-009 - Coordinate picker klik peta

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan klik peta mengisi latitude/longitude.
Precondition/data: Form UMKM/kontak/fasilitas dengan coordinate picker.
Langkah manual: Klik lokasi pada peta coordinate picker.
Expected: Pin berpindah dan field latitude/longitude terisi sesuai lokasi.
Actual:
Status:
Evidence Screenshot: Coordinate picker setelah klik.
Notes:

### RT-MM-010 - Coordinate picker smart input dan hapus titik

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan smart input dan clear point bekerja.
Precondition/data: Form coordinate picker.
Langkah manual: Masukkan koordinat pada smart input, klik Terapkan, lalu klik Hapus Titik.
Expected: Koordinat diterapkan ke field dan pin; Hapus Titik mengosongkan field/pin. Exact feedback text: PERLU OBSERVASI.
Actual:
Status:
Evidence Screenshot: Smart input, hasil apply, hasil clear.
Notes:

### RT-MM-011 - GPS coordinate picker

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Menguji tombol GPS bila browser/device mendukung.
Precondition/data: Browser mendukung geolocation dan user bersedia memberi izin lokasi.
Langkah manual: Klik tombol GPS pada coordinate picker dan izinkan lokasi.
Expected: Jika didukung/diizinkan, koordinat terisi dari lokasi device. Jika tidak didukung/ditolak, UI menampilkan feedback. Expected exact result: PERLU OBSERVASI.
Actual:
Status:
Evidence Screenshot: Prompt/feedback GPS.
Notes:

## 8. Responsive Verification

### RT-RESP-001 - Homepage mobile

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan homepage nyaman dibaca pada mobile.
Precondition/data: Viewport mobile.
Langkah manual: Buka homepage dan scroll seluruh section.
Expected: Tidak ada teks/tombol saling overlap, menu mobile bekerja, peta tetap dapat digunakan.
Actual:
Status:
Evidence Screenshot: Homepage mobile beberapa section.
Notes:

### RT-RESP-002 - Halaman Dusun mobile

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan halaman Dusun responsif.
Precondition/data: Viewport mobile dan Dusun ACTIVE.
Langkah manual: Buka halaman Dusun, scroll kontak, UMKM, fasilitas, agenda, pengumuman, peta.
Expected: Semua section terbaca, kartu tidak rusak, peta tidak menutupi konten.
Actual:
Status:
Evidence Screenshot: Halaman Dusun mobile.
Notes:

### RT-RESP-003 - Detail public mobile

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan detail UMKM/Fasilitas/Agenda/Pengumuman responsif.
Precondition/data: Minimal satu detail tiap jenis.
Langkah manual: Buka detail pada viewport mobile.
Expected: Konten, tombol WhatsApp/arah, dan media tampil tanpa overlap.
Actual:
Status:
Evidence Screenshot: Detail public mobile.
Notes:

### RT-RESP-004 - Admin Dusun mobile/tablet

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan admin layout bisa digunakan pada viewport sempit.
Precondition/data: Login Admin Dusun, viewport mobile/tablet.
Langkah manual: Buka dashboard, menu, satu form create/edit, dan index modul.
Expected: Sidebar/menu dapat dibuka, form dapat diisi, tombol action dapat diakses.
Actual:
Status:
Evidence Screenshot: Admin menu dan form mobile.
Notes:

### RT-RESP-005 - Super Admin mobile/tablet

- [ ] PASS
- [ ] FAIL
- [ ] DIFFERENT
- [x] NOT TESTED

Tujuan: Memastikan Super Admin layout bisa digunakan pada viewport sempit.
Precondition/data: Login Super Admin, viewport mobile/tablet.
Langkah manual: Buka dashboard, menu, Data / Peta, index modul dengan filter, dan form create/edit.
Expected: Sidebar/menu dapat dibuka, filter/form/action tetap dapat digunakan.
Actual:
Status:
Evidence Screenshot: Super Admin mobile/tablet.
Notes:

## 9. Runtime Differences Found

Gunakan tabel ini untuk mencatat perbedaan runtime yang ditemukan saat checklist dijalankan. Jangan memperbaiki sistem dari checklist ini; catat dulu untuk keputusan berikutnya.

| ID Test | Expected | Actual runtime | Status | Dampak ke buku manual | Screenshot | Follow-up |
|---|---|---|---|---|---|---|
| | | | | | | |

## 10. Final Verification Summary

Checklist count:

| Group | Jumlah test case |
|---|---:|
| Public | 20 |
| Authentication | 8 |
| Admin Dusun | 20 |
| Super Admin | 22 |
| Media & Map | 11 |
| Responsive | 5 |
| Total | 86 |

Execution summary:

| Status | Jumlah |
|---|---:|
| PASS | 0 |
| FAIL | 0 |
| DIFFERENT | 0 |
| NOT TESTED | 86 |

Final notes:

- Checklist ini belum membuktikan behavior runtime sampai manusia menjalankannya melalui browser.
- Setelah semua test dijalankan, update summary jumlah status dan gunakan bagian "Runtime Differences Found" sebagai input revisi user manual.
- Untuk laporan akhir yang diminta: Media/Map/Responsive berjumlah 16 test case (11 Media & Map + 5 Responsive).

