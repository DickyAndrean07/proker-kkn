# As-Built User Manual Baseline

## 1. Document Control

| Atribut | Nilai |
|---|---|
| **Project** | Portal Informasi Desa Bendung |
| **Document** | As-Built User Manual Baseline (Frozen Source of Truth) |
| **Version** | 1.0 |
| **Status** | **FROZEN — SOURCE OF TRUTH FOR USER MANUAL** |
| **Date** | 2026-08-20 |
| **Target Audience** | Tim Penulis Buku Panduan Pengguna (User Manual / HKI), Pengelola Desa, Tim KKN |

### Purpose
Dokumen ini mendefinisikan seluruh fakta fungsional, batasan arsitektur, peran pengguna, tata letak informasi, dan siklus hidup data aktual (*as-built*) dari **Portal Informasi Desa Bendung**. Dokumen ini dibekukan (*frozen*) dan menjadi **satu-satunya sumber kebenaran (*single source of truth*)** dalam penyusunan naskah Buku Panduan Penggunaan dan Pengelolaan Portal Informasi Desa Bendung (Ukuran A5).

### Source Hierarchy (Hierarki Sumber Kebenaran)
Jika ditemukan perbedaan antara dokumen perancangan terdahulu dengan perilaku sistem aktual, urutan prioritas kebenaran adalah sebagai berikut:
1. **Verified Current Implementation / Runtime Behavior** (Pengujian langsung pada database testing dan kernel aplikasi).
2. **Current Source Implementation** (Source code Blade, Controller, Request, Model, Routes, dan Migrations aktual).
3. **As-Built Audit Documents** (`docs/07-handover/00-source-of-truth/as-built-user-manual-audit.md` dan `docs/07-handover/00-source-of-truth/runtime-verification-report.md`).
4. **Legacy Documents** (PRD awal, backlog, atau dokumen perancangan lama hanya diposisikan sebagai referensi historis).

---

## 2. Product Purpose (Tujuan Produk)

**Portal Informasi Desa Bendung** adalah platform web informasi publik terpadu yang dirancang untuk mempublikasikan profil wilayah desa, potensi 6 dusun, direktori kontak pelayanan warga, etalase promosi UMKM lokal, pemetaan fasilitas umum, warta pengumuman resmi, serta agenda kegiatan desa/dusun.

### Batasan Esensial Produk:
* **Sistem Informasi Publik, Bukan Marketplace:** Portal ini adalah media publikasi dan direktori informasi. Portal **bukan** platform *e-commerce*, *marketplace*, ataupun sistem transaksi daring (*online payment*).
* **Komunikasi Langsung via WhatsApp:** Seluruh interaksi tindak lanjut (pemesanan produk UMKM atau permohonan layanan ke pamong/kadus) diarahkan langsung ke aplikasi **WhatsApp** masing-masing pengelola/warga melalui tautan resmi `wa.me`.
* **Sistem Mandiri & Terdesentralisasi:** Pengelolaan konten dilakukan secara mandiri oleh Pemerintah Desa (Super Admin) dan masing-masing Kepala Dusun / Admin Wilayah (Admin Dusun).

---

## 3. Final User Roles (Peran Pengguna Final)

Sistem memiliki 3 (tiga) peran pengguna yang terdefinisi secara tegas:

```
┌─────────────────────────────────────────────────────────────┐
│                     PORTAL INFORMASI DESA                   │
└──────────────────────────────┬──────────────────────────────┘
                               │
       ┌───────────────────────┼───────────────────────┐
       ▼                       ▼                       ▼
┌──────────────┐      ┌─────────────────┐     ┌─────────────────┐
│ Public User  │      │   Admin Dusun   │     │   Super Admin   │
│ (Warga/Tamu) │      │ (Kepala Dusun)  │     │(Pemerintah Desa)│
└──────────────┘      └─────────────────┘     └─────────────────┘
 [Read-Only]          [Scoped CRUD Dusun]      [Global Governance]
```

### 3.1 Public User (Masyarakat Umum / Warga / Pengunjung)
* **Tujuan:** Menjelajahi informasi profil desa, mencari data potensi 6 dusun, melihat sebaran titik lokasi pada peta interaktif, membaca agenda dan pengumuman, serta menghubungi kontak pelayanan atau UMKM.
* **Cakupan Akses:** Seluruh rute publik (`/`, `/dusun/{id}`, `/umkm/{id}`, `/fasilitas/{id}`, `/agenda/{id}`, `/pengumuman/{id}`, `/pengumuman/arsip`).
* **Batasan Utama:** Bersifat *Read-Only* (hanya membaca); tidak memiliki akun login; tidak dapat memposting atau mengubah data secara mandiri melalui web.

### 3.2 Admin Dusun (Kepala Dusun / Operator Wilayah)
* **Tujuan:** Mengelola data profil dusun, kontak pelayanan, UMKM, fasilitas, agenda, dan pengumuman yang berada di wilayah dusunnya.
* **Cakupan Akses:** Dashboard dan formulir manajemen di bawah prefix `/admin-dusun/*`.
* **Batasan Utama:**
  * **Isolasi Wilayah:** Hanya dapat mengelola data pada dusun yang ditugaskan (*assigned dusun*).
  * **Tanpa Fitur Pemulihan:** Hanya dapat melakukan *Soft Delete (Nonaktifkan)*; **tidak dapat** melakukan *Restore (Pulihkan)* ataupun *Hapus Permanen*.
  * **Tanpa Hak Akses Global:** Tidak dapat mengedit identitas desa, master kategori fasilitas, akun admin lain, atau data dusun lain.

### 3.3 Super Admin (Pemerintah Desa / Administrator Utama)
* **Tujuan:** Mengelola tata kelola portal secara menyeluruh (*global governance*), mengelola identitas resmi desa, memantau 6 dusun, menerbitkan warta tingkat desa, memulihkan data terhapus, serta mengelola akun Admin Dusun.
* **Cakupan Akses:** Dashboard dan seluruh modul manajemen di bawah prefix `/super-admin/*`.
* **Batasan Utama:**
  * **Struktur 6 Dusun Tetap:** Tidak dapat menambah dusun baru (*no create dusun*) dan tidak dapat menghapus dusun (*no hard delete dusun*). Super Admin hanya dapat mengaktifkan/menonaktifkan status dusun.

---

## 4. Final Public Information Architecture (Arsitektur Informasi Publik)

Halaman dan komponen yang tersedia secara publik di portal:

| Halaman / Section | URL / Anchor | Deskripsi Konten yang Ditampilkan |
|---|---|---|
| **Beranda Utama Desa** | `/` | Hero identitas desa, slogan, pilihan kartu 6 dusun, sekilas warta terkini (agenda & pengumuman), Peta Desa interaktif, dan footer kontak resmi desa. |
| **Halaman Dusun** | `/dusun/{id}` | Hero banner dusun, *quick navigation* 2-baris, narasi profil & statistik RT/RW, nama kepala dusun, Peta Dusun, daftar Kontak Pelayanan, etalase UMKM, direktori Fasilitas, Agenda, dan Pengumuman dusun. |
| **Detail UMKM** | `/umkm/{id}` | Foto utama tempat/produk, nama UMKM, nama pemilik, jenis usaha, daftar tag produk unggulan, jam operasional, alamat, dan tombol *Hubungi via WhatsApp*. |
| **Detail Fasilitas** | `/fasilitas/{id}` | Foto fasilitas, nama fasilitas, badge kategori, deskripsi, alamat, tombol *Petunjuk Arah Google Maps*, dan tombol WhatsApp (jika tersedia). |
| **Detail Agenda** | `/agenda/{id}` | Judul kegiatan, tanggal mulai & selesai, jam pelaksanaan, lokasi teks, badge status efektif (*Akan Datang / Berlangsung / Selesai*), deskripsi, dan galeri poster/dokumentasi. |
| **Pengumuman Aktif** | `/pengumuman/{id}` | Judul maklumat resmi, tanggal publikasi, batas tanggal masa berlaku (kedaluwarsa), dan isi lengkap pengumuman. |
| **Arsip Pengumuman** | `/pengumuman/arsip` | Daftar warta pengumuman resmi yang masa berlakunya telah terlewati (kedaluwarsa), dapat dicari dan dibaca kembali oleh warga. |
| **Peta Desa Interaktif** | `#peta-desa` (pada `/`) | Atlas spasial seluruh wilayah desa dengan filter dropdown Dusun dan filter Kategori titik lokasi (Fasilitas, UMKM, Kontak). |
| **Peta Dusun** | `#peta-dusun` (pada `/dusun/{id}`) | Atlas spasial terisolasi khusus titik lokasi di wilayah dusun bersangkutan dengan filter Kategori. |

---

## 5. Final Authentication Behavior (Perilaku Autentikasi Final)

```
                            FORM LOGIN (/admin/login)
                       ┌─────────────────────────────────┐
                       │  [ Username                   ] │
                       │  [ Password                 👁 ] │
                       │  [✓] Ingat saya                 │
                       │  [ Masuk ke Portal            ] │
                       └────────────────┬────────────────┘
                                        │
                         Autentikasi Kredensial Valid?
                                        │
                        ┌───────────────┴───────────────┐
                        ▼                               ▼
                 [ ROLE: ADMIN_DUSUN ]        [ ROLE: SUPER_ADMIN ]
                        │                               │
                        ▼                               ▼
               /admin-dusun/dashboard         /super-admin/dashboard
```

* **Pintu Masuk Tunggal:** Seluruh pengelola (Admin Dusun dan Super Admin) masuk melalui satu URL login bersama: `/admin/login`.
* **Kredensial:** Kombinasi `username` dan `password`.
* **Fitur Lihat Password:** Tersedia tombol ikon mata (*toggle*) untuk menampilkan atau menyembunyikan karakter kata sandi saat mengetik.
* **Perilaku Fitur "Ingat Saya" (True Remember Me):**
  * *Jika Dicentang:* Laravel menerbitkan *Remember Token* unik 60-karakter ke database dan menyematkan cookie terenkripsi `remember_web_...` pada browser. Pengguna tetap masuk (*authenticated*) secara otomatis meskipun browser ditutup dan dibuka kembali.
  * *Jika Tidak Dicentang:* Sesi login dikelola oleh sesi normal dengan batas waktu *idle* standar **120 menit**.
* **Pengalihan Otomatis Berdasarkan Peran (*Role Redirection*):**
  * Admin Dusun dialihkan ke `/admin-dusun/dashboard`.
  * Super Admin dialihkan ke `/super-admin/dashboard`.
  * Pengguna yang sudah login dan membuka kembali `/admin/login` otomatis dialihkan ke dashboard masing-masing.
* **Prosedur Keluar (Logout):**
  * Tombol *Keluar* mengirim request `POST /admin/logout`.
  * Sistem mematikan sesi, me-reset token remember, menghapus cookie recaller, dan mengembalikan tampilan ke form login.
* **Kebijakan Lupa Password:**
  * **Tidak tersedia** fitur *Self-Service Forgot Password* (tidak ada pengiriman link reset via email publik).
  * Pemulihan kata sandi Admin Dusun dilakukan secara terpusat oleh **Super Admin** melalui menu *Manajemen Admin Dusun*.

---

## 6. Final Admin Dusun Capabilities (Kemampuan Admin Dusun)

Admin Dusun memiliki wewenang penuh atas konten wilayahnya dengan rincian:

1. **Dashboard Dusun (`/admin-dusun/dashboard`):** Menampilkan ringkasan jumlah data aktif wilayah (Kontak, UMKM, Fasilitas, Agenda, Pengumuman) dan tombol pintasan penambahan data cepat.
2. **Profil Dusun (`/admin-dusun/profil`):** Memperbarui deskripsi selayang pandang dusun, nama kepala dusun, jumlah RT, jumlah RW, dan mengunggah foto banner utama dusun.
3. **Kontak Pelayanan (`/admin-dusun/kontak`):** Menambah, mengedit, dan menonaktifkan (*soft delete*) data petugas pamong/pelayanan dusun beserta nomor WhatsApp.
4. **Data UMKM (`/admin-dusun/umkm`):**
   * Mendaftarkan profil usaha (nama, pemilik, jenis usaha, deskripsi, alamat, jam operasional, nomor WhatsApp).
   * Menambahkan daftar produk unggulan secara dinamis (*dynamic repeater*).
   * Mengunggah 1 foto utama produk/tempat usaha.
   * Menentukan titik koordinat peta (bersifat opsional).
5. **Fasilitas Umum (`/admin-dusun/fasilitas`):**
   * Mendaftarkan sarana publik (nama fasilitas, pilihan master kategori, deskripsi, alamat, nomor kontak jika ada).
   * Mengunggah foto dokumentasi fasilitas.
   * **Wajib menentukan titik koordinat peta** (`latitude` dan `longitude`).
6. **Agenda & Kegiatan (`/admin-dusun/agenda`):**
   * Mempublikasikan jadwal kegiatan dusun (`scope_level = 'DUSUN'`).
   * Menetapkan tanggal mulai, tanggal selesai, waktu/jam, dan lokasi pelaksanaan.
   * Mengunggah berkas media dengan peran *Poster Awal* atau *Dokumentasi*.
   * Mengatur status manual *override* jika diperlukan (*Akan Datang / Berlangsung / Selesai*).
7. **Pengumuman Resmi (`/admin-dusun/pengumuman`):**
   * Menerbitkan warta maklumat warga dusun (`scope_level = 'DUSUN'`).
   * Menetapkan batas tanggal masa berlaku warta (*tanggal kedaluwarsa*).
8. **Penonaktifan Data (*Soft Delete*):** Menghapus sementara data kontak, UMKM, fasilitas, agenda, atau pengumuman dari etalase publik dusun.

### Batasan Tegas Admin Dusun:
* ❌ Tidak dapat melihat atau mengedit data dusun lain.
* ❌ Tidak memiliki tombol atau menu **Restore (Pulihkan)** data terhapus.
* ❌ Tidak memiliki tombol **Hapus Permanen (Force Delete)**.
* ℹ️ *Catatan Wilayah Nonaktif:* Jika dusun di-nonaktifkan (`INACTIVE`) oleh Super Admin, Admin Dusun tetap dapat login dan memperbarui data, namun seluruh halaman publik dusun tersebut disembunyikan dari masyarakat.

---

## 7. Final Super Admin Capabilities (Kemampuan Super Admin)

Super Admin memegang tata kelola tertinggi portal desa dengan kemampuan:

1. **Dashboard Global (`/super-admin/dashboard`):** Memantau statistik agregat seluruh desa (total 6 dusun, rekapitulasi kontak, UMKM, fasilitas, agenda, dan pengumuman aktif maupun terhapus).
2. **Kelola Identitas Desa (`/super-admin/desa`):** Memperbarui informasi resmi pemerintahan Desa Bendung:
   * Nama Desa (`nama_desa`)
   * Nama Kepala Desa / Lurah (`nama_kepala_desa`)
   * Nomor Kontak Resmi Desa (`nomor_kontak`)
   * Jam Pelayanan Kantor Balai Desa (`jam_pelayanan`)
   * Alamat Kantor Balai Desa (`alamat_kantor`)
   * Deskripsi / Selayang Pandang Desa (`deskripsi_singkat`)
   * Foto Banner Utama Desa (`banner`)
3. **Kelola 6 Dusun (`/super-admin/dusun`):**
   * Melihat tabel master 6 Dusun tetap (Bendung I, Bendung II, Gatak, Karangsawo, Banyuripan, Plosorejo).
   * Mengedit narasi profil dan fakta RT/RW dusun.
   * Mengaktifkan (`ACTIVE`) atau menonaktifkan (`INACTIVE`) status publikasi wilayah dusun.
4. **Manajemen Data Lintas Dusun (Kontak, UMKM, Fasilitas):**
   * Menambah data baru dengan memilih dusun target.
   * Memfilter daftar data berdasarkan dusun tertentu.
   * Memfilter data berdasarkan status aktif vs data terhapus (*soft deleted*).
5. **Master Kategori Fasilitas (`/super-admin/kategori-fasilitas`):**
   * Menambah dan mengubah nama kategori fasilitas umum desa.
   * Menghapus kategori, dengan **proteksi otomatis** (kategori yang sedang dipakai oleh data fasilitas aktif ditolak penghapusannya).
6. **Agenda & Pengumuman Tingkat Desa vs Dusun:**
   * Mempublikasikan warta dengan cakupan **Tingkat Desa (`scope_level = 'DESA'`)** yang otomatis tampil di beranda utama desa.
   * Mempublikasikan warta dengan cakupan **Tingkat Dusun (`scope_level = 'DUSUN'`)** yang ditargetkan khusus pada halaman dusun tertentu.
7. **Monitoring Data / Peta (`/super-admin/data-peta`):** Visualisasi agregat peta spasial seluruh titik lokasi fasilitas, UMKM, dan kontak se-Desa Bendung.
8. **Manajemen Akun Admin Dusun (`/super-admin/admin-dusun`):**
   * Menambah akun pengelola dusun baru (menentukan username, password awal, dan penugasan dusun).
   * Mengubah penugasan wilayah dusun akun.
   * Melakukan **Reset Password** akun admin dusun yang lupa kata sandi.
   * Menonaktifkan akun (*logical removal* via `removed_at`) untuk mencabut hak akses login.
9. **Pemulihan Data (*Restore*):** Mengembalikan data kontak, UMKM, fasilitas, agenda, dan pengumuman yang sebelumnya di-soft delete agar aktif kembali.
10. **Penghapusan Permanen (*Hard Delete / Force Delete*):** Menghapus data operasional yang berada di tong sampah secara permanen dari basis data.

### Batasan Tegas Super Admin:
* ❌ Tidak tersedia formulir/tombol untuk menambah Dusun baru (*Fixed 6 Dusun Invariant*).
* ❌ Tidak tersedia fitur untuk menghapus Dusun secara permanen.

---

## 8. Final Data Lifecycle Rules (Aturan Siklus Hidup Data)

```
[ Data Aktif ] ──(Admin Dusun / Super Admin: Soft Delete)──► [ Tong Sampah / Trashed ]
      ▲                                                             │
      │                                             ┌───────────────┴───────────────┐
      │                                             ▼                               ▼
      └──────────(Super Admin: Restore)─────────────┘              (Super Admin: Hapus Permanen)
                                                                                    │
                                                                                    ▼
                                                                            [ Terhapus Mutlak ]
```

### 8.1 Soft Delete (Penonaktifan Data)
* Dilakukan melalui tombol *Hapus / Nonaktifkan* pada tabel data.
* Data tidak dihapus dari baris database, melainkan diisi stempel waktu `deleted_at`.
* Efek langsung: Data langsung hilang dari website publik, direktori, dan marker peta.

### 8.2 Restore (Pemulihan Data)
* **Hanya dapat dilakukan oleh Super Admin.**
* Dilakukan dengan membuka filter status *Terhapus / Trashed* pada modul Super Admin lalu menekan tombol *Pulihkan*.
* Efek: Stempel waktu `deleted_at` dikosongkan kembali (`null`), data langsung aktif kembali di web publik.

### 8.3 Hard Delete (Penghapusan Permanen)
* **Hanya dapat dilakukan oleh Super Admin.**
* Dilakukan melalui tombol *Hapus Permanen* pada data yang berstatus trashed.
* Efek: Data dan berkas foto terkait dimusnahkan secara permanen dari server database dan storage.

### 8.4 Siklus Status Wilayah Dusun
* Status dusun hanya berupa **`ACTIVE`** (tampil publik di homepage dan dapat dibuka) atau **`INACTIVE`** (menghasilkan HTTP 404 pada URL publik dan disembunyikan dari homepage). Tidak ada status terhapus pada data dusun.

### 8.5 Siklus Hidup Pengumuman: Masa Aktif vs Arsip
Sistem membedakan secara tegas antara pengumuman kedaluwarsa dan pengumuman terhapus:

```
[ Pengumuman Baru Diterbitkan ]
               │
               ▼
[ STATUS: AKTIF ] ──(Tanggal Kedaluwarsa Terlewati)──► [ STATUS: KEDALUWARSA (ARSIP) ]
(Tampil di Beranda / Dusun)                             (Otomatis Pindah ke /pengumuman/arsip)
               │                                                       │
               └─────────(Soft Delete oleh Admin)──────────────────────┘
                                       │
                                       ▼
                         [ STATUS: SOFT DELETED ]
                         (Hilang dari Publik & Arsip)
```

* **Aktif:** `tanggal_kedaluwarsa >= hari ini` (Asia/Jakarta) dan belum di-soft delete. Tampil di Beranda Desa / Halaman Dusun.
* **Kedaluwarsa (Masuk Arsip):** `tanggal_kedaluwarsa < hari ini`. Otomatis berpindah dari daftar aktif ke halaman **`/pengumuman/arsip`** agar warta historis tetap dapat dibaca masyarakat.
* **Soft Deleted:** Pengumuman yang dinonaktifkan manual oleh admin; tidak tampil di halaman aktif maupun di halaman arsip publik.

### 8.6 Siklus Waktu Agenda Kegiatan
Status agenda dihitung otomatis oleh sistem berdasarkan tanggal dan waktu saat ini (Asia/Jakarta):
* **`AKAN_DATANG`:** Tanggal pelaksanaan berada di masa mendatang (`tanggal_mulai > hari ini`).
* **`BERLANGSUNG`:** Hari ini berada di dalam rentang waktu kegiatan (`tanggal_mulai <= hari ini <= tanggal_selesai`).
* **`SELESAI`:** Rentang tanggal kegiatan telah terlewati (`tanggal_selesai < hari ini`).
* **Manual Status Override:** Admin dapat mengunci status menjadi *Akan Datang*, *Berlangsung*, atau *Selesai* secara manual apabila jadwal lapangan mengalami pergeseran khusus.

---

## 9. Final Map & Coordinate Rules (Aturan Peta & Titik Koordinat)

### 9.1 Fitur Tampilan Peta
* **Peta Desa (`#peta-desa`):** Memuat sebaran seluruh titik se-desa dengan dropdown filter **Pilih Dusun** dan filter **Kategori** (Fasilitas Umum, UMKM, Kontak Pelayanan).
* **Peta Dusun (`#peta-dusun`):** Memuat sebaran titik yang terisolasi khusus di wilayah dusun tersebut dengan filter Kategori.
* **Marker Popups:** Mengklik marker menampilkan jendela informasi berisi foto, nama titik, kategori, alamat ringkas, link detail, dan tombol *Petunjuk Arah*.
* **Integrasi Google Maps Directions:** Tombol arah menghasilkan URL rute navigasi eksternal: `https://www.google.com/maps/dir/?api=1&destination={latitude},{longitude}`.

### 9.2 Aturan Pengisian Koordinat Form
* **Fasilitas Umum:** Koordinat `latitude` dan `longitude` bersifat **WAJIB (*Required*)**. Data fasilitas tidak dapat disimpan jika titik koordinat belum ditentukan.
* **UMKM:** Koordinat bersifat **OPSIONAL**. Jika diisi, UMKM akan muncul sebagai pin marker di peta; jika dikosongkan, UMKM tetap tampil di direktori etalase kartu tanpa marker peta.
* **Kontak Pelayanan:** Koordinat bersifat **OPSIONAL**.

### 9.3 Fitur Komponen Penentu Koordinat (*Coordinate Picker*)
Formulir input koordinat menyediakan 4 metode penentuan titik:
1. **Klik pada Peta:** Mengklik canvas peta Leaflet atau menggeser pin (*draggable pin*) langsung mengisi kolom latitude dan longitude secara otomatis.
2. **Smart Input:** Kotak teks pintar yang mampu mengenali dan mem-parsing:
   * Teks derajat / DMS (contoh: `7°23'56.0"S 112°26'32.5"E`).
   * Pasangan angka desimal (contoh: `-7.834123, 110.718542`).
   * Tautan / link URL Google Maps hasil salinan dari browser/ponsel.
3. **Gunakan GPS:** Tombol yang memanfaatkan sensor lokasi perangkat (*browser geolocation API*) untuk mengambil koordinat posisi admin saat berada di lokasi fisik objek.
4. **Hapus Titik:** Tombol untuk mengosongkan kembali nilai koordinat pada data yang bersifat opsional.

> [!NOTE]
> Portal **tidak menyediakan** fitur pencarian nama tempat / *geocoding search box* (mengetik nama gedung lalu peta bergerak otomatis). Penentuan titik dilakukan via Smart Input, klik peta, atau GPS.

---

## 10. Final Media Rules (Aturan Berkas & Gambar)

Aturan unggah media yang berlaku untuk seluruh formulir:

* **Format File yang Diterima:** `JPG`, `JPEG`, `PNG`, `WebP`.
* **Ukuran Maksimum File:** **3072 KB (3 MB)** per berkas. Unggahan yang melebihi 3 MB akan ditolak otomatis oleh sistem dengan pesan peringatan.
* **Alokasi Media per Modul:**
  * *Identitas Desa:* 1 Foto Banner Utama Desa.
  * *Profil Dusun:* 1 Foto Banner Utama Dusun.
  * *Kontak Pelayanan:* 1 Foto Petugas Pelayanan (Opsional).
  * *UMKM:* 1 Foto Utama Produk / Tempat Usaha.
  * *Fasilitas Umum:* 1 Foto Utama Fasilitas.
  * *Agenda Kegiatan:* Multi-media pendukung dengan klasifikasi peran **Poster Awal** (selebaran publikasi sebelum acara) atau **Dokumentasi** (foto dokumentasi kegiatan).

---

## 11. Final UMKM Rules (Aturan Etalase UMKM)

* **Direktori Promosi Digital:** Modul UMKM difungsikan sebagai etalase katalog promosi digital bagi usaha mikro, kecil, dan menengah warga Desa Bendung.
* **Daftar Produk Dinamis:** Setiap UMKM dapat mencantumkan daftar nama produk/jasa yang dijual beserta deskripsi singkat melalui tombol *Tambah Produk*.
* **Kanal Transaksi & Pemesanan:**
  * Pengunjung yang berminat membeli produk menekan tombol **"Hubungi via WhatsApp"**.
  * Sistem akan mengarahkan pengunjung ke ruang obrolan WhatsApp pribadi pemilik usaha.
* **Bukan Sistem Marketplace:**
  * ❌ Tidak ada fitur keranjang belanja (*shopping cart*).
  * ❌ Tidak ada fitur checkout atau formulir pemesanan internal di website.
  * ❌ Tidak ada gerbang pembayaran daring (*payment gateway*).

---

## 12. Final Privacy & Publication Rules (Aturan Privasi & Publikasi)

* **Izin Administratif Offline:** Sistem portal **tidak menyediakan** checkbox *digital consent* / persetujuan daring pada formulir publikasi.
* **Tanggung Jawab Pengelola:** Admin Dusun dan Super Admin wajib memastikan telah memperoleh izin secara lisan atau administratif (*offline consent*) dari warga/pemilik usaha sebelum mempublikasikan data pribadi, nomor telepon seluler/WhatsApp, foto perorangan, atau titik koordinat rumah tinggal ke dalam portal.

---

## 13. Explicitly Not Available (Fitur yang Ditegaskan Tidak Tersedia)

Untuk mencegah kesalahan penulisan pada naskah buku manual, daftar fitur berikut **TIDAK TERSEDIA** di dalam sistem dan **DILARANG** ditulis sebagai fitur portal:

1. ❌ **Registrasi / Pendaftaran Akun Warga:** Warga umum tidak memiliki akun login.
2. ❌ **Lupa Password Mandiri (*Self-Service Password Reset*):** Tidak ada form kirim link reset ke email pengguna.
3. ❌ **Sistem Keranjang & Checkout Transaksi UMKM:** Tidak ada transaksi jual-beli di dalam web portal.
4. ❌ **Pembayaran Daring (*Online Payment Gateway*):** Tidak ada pembayaran transfer/QRIS otomatis di web.
5. ❌ **Pencarian Nama Lokasi pada Peta (*Geocoding Search Bar*):** Peta tidak memiliki kotak pencarian teks nama jalan/gedung.
6. ❌ **Poligon Batas Wilayah Dusun (*Boundary Polygons*):** Peta menyajikan marker titik lokasi (*points*), bukan batas garis wilayah dusun.
7. ❌ **Penambahan Dusun Baru (*Create Dusun*):** Jumlah dusun terkunci tetap 6 dusun.
8. ❌ **Penghapusan Dusun (*Hard Delete Dusun*):** Dusun tidak dapat dihapus, hanya dapat dinonaktifkan statusnya.
9. ❌ **Galeri Multi-Foto UMKM:** UMKM hanya mendukung 1 foto utama.
10. ❌ **Checkbox Digital Consent pada Form:** Persetujuan publikasi data warga dikelola secara offline.
11. ❌ **Page Builder Drag-and-Drop:** Tata letak dan struktur komponen beranda telah terstandarisasi.

---

## 14. Human Visual Verification Notes (Catatan Observasi Visual Manusia)

Seluruh logika teknis dan data telah 100% terverifikasi secara runtime. Poin-poin observasi berikut merupakan ranah visual yang diverifikasi secara langsung oleh tim manusia saat pengambilan tangkapan layar (*screenshot*):

* **Tampilan Responsif Mobile:** Memastikan tata letak kartu dan navigasi drawer terlihat proporsional pada layar ponsel fisik.
* **Rendering Grafis Peta & Tiles OpenStreetMap:** Memastikan unduhan gambar peta dunia OSM tampil utuh di canvas browser lokal.
* **Tata Letak Popup Peta:** Memastikan balon informasi marker tampil rapi saat diklik.
* **Dialog Izin Sensor GPS:** Memastikan dialog izin lokasi browser (*Allow Location Access*) muncul saat tombol *Gunakan GPS* ditekan di smartphone.
* **Handoff Aplikasi WhatsApp & Google Maps:** Memastikan klik tombol membuka aplikasi eksternal WhatsApp dan Google Maps dengan parameter nomor dan koordinat yang tepat.

---

## 15. Manual Authoring Rules (Aturan Penulisan Buku Panduan)

Tim penulis naskah buku manual (format A5) wajib mematuhi panduan penulisan berikut:

1. **Gunakan Istilah UI Aktual:** Selalu gunakan teks tombol dan label yang persis sama dengan tampilan website (contoh: gunakan *"Hubungi via WhatsApp"*, bukan *"Beli Sekarang"*; gunakan *"Filter Kategori"*, bukan *"Cari Fasilitas"*).
2. **Patuhi Screenshot Plan:** Seluruh pengambilan tangkapan layar wajib mengikuti daftar 28 item pada dokumen `docs/07-handover/01-planning/user-manual-screenshot-plan.md`.
3. **Patuhi Batasan Peran pada Flowchart:** Diagram alur harus menggambarkan batasan hak akses secara jujur (misal: Admin Dusun hanya memiliki alur *Soft Delete*, sedangkan alur *Restore* dan *Hapus Permanen* hanya ada pada Super Admin).
4. **Screenshot Asli Manusia:** Tangkapan layar diambil langsung oleh manusia dari browser aktif, bukan gambar rekayasa grafis non-aplikasi.
5. **Prosedur Perubahan Pasca-Freeze:** Jika terdapat perbaikan atau pembaruan kode program setelah dokumen ini dibekukan, perubahan tersebut wajib direview dan disetujui terlebih dahulu sebelum dimasukkan ke naskah buku manual.

---

## 16. Freeze Declaration (Deklarasi Pembekuan Baseline)

> ### PERNYATAAN PEMBEKUAN BASELINE:
> **Dokumen ini secara resmi DIBEKUKAN (*FROZEN*) sebagai SUMBER KEBENARAN UTAMA (*SOURCE OF TRUTH*) untuk penyusunan Buku Panduan Penggunaan dan Pengelolaan Portal Informasi Desa Bendung (Format A5 untuk Keperluan HKI & Serah Terima).**
> 
> *Any post-freeze implementation change affecting user-visible behavior must be reviewed and audited before inclusion in the manual.*
