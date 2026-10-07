# User Manual Screenshot Plan (Daftar Pengambilan Tangkapan Layar)

## 1. Panduan Pengambilan Screenshot untuk Manusia

> [!NOTE]
> **Petunjuk bagi Tim Penyusun Buku Panduan (Manusia):**
> Dokumen ini memuat daftar rencana pengambilan tangkapan layar (*screenshot shot list*) untuk buku panduan ukuran **A5**. AI Agent **tidak mengambil screenshot otomatis**, melainkan memberikan spesifikasi lengkap agar gambar yang diambil seragam, profesional, dan siap disisipkan ke naskah buku.

### Standar Teknis Pengambilan Screenshot:
1. **Rasio & Resolusi:**
   * **Desktop:** Gunakan resolusi browser minimal `1280 x 800 px` atau `1440 x 900 px` dengan zoom 100%.
   * **Mobile:** Gunakan simulasi mobile browser (DevTools Device Toolbar) dengan ukuran `375 x 667 px` (iPhone SE/8) atau `390 x 844 px` (iPhone 12/13/14).
2. **Kerapian Data:** Gunakan data yang rapi, tidak ada teks acak/asdf, dan foto profil/tempat usaha yang jelas.
3. **Pemberian Anotasi / Callout:**
   * Beri lingkaran nomor merah/oranye `(1)`, `(2)`, `(3)` pada elemen penting di gambar untuk memudahkan perujukan teks pada naskah buku.
4. **Format Simpan File:** Simpan dengan format PNG berkualitas tinggi dengan penamaan file sesuai kolom `Nama file`.

---

## 2. Ringkasan Kebutuhan Screenshot

* **Total Shot List:** 28 Item
* **Prioritas MUST:** 18 Item (Wajib ada untuk buku panduan dasar)
* **Prioritas SHOULD:** 8 Item (Sangat disarankan untuk kelengkapan)
* **Prioritas OPTIONAL:** 2 Item (Pelengkap tambahan)
* **Topik Rekomendasi Flowchart (Tanpa Screenshot Rumit):** 7 Topik

---

## 3. Daftar Pengambilan Screenshot (Shot List)

### A. Portal Publik (Warga & Pengunjung)

#### 1. ID: `PUB-001`
* **Nama file:** `01_pub_homepage_hero_desktop.png`
* **Role:** Publik / Warga
* **Halaman:** Beranda Utama Desa (`/`)
* **Apa yang harus di-screenshot:** Bagian atas beranda (Header navbar, Identitas Desa Bendung, Deskripsi Singkat, dan Pilihan Dusun).
* **Elemen yang harus terlihat:** Logo desa, menu navigasi desktop, tombol kontak cepat, kartu/tautan 6 dusun.
* **Crop:** Header navbar hingga bagian bawah grid pilihan dusun.
* **Viewport:** Desktop (`1280 x 800`)
* **Callout:**
  - `(1)` Menu Navigasi Utama
  - `(2)` Identitas & Slogan Desa Bendung
  - `(3)` Kartu Pilihan Wilayah Dusun
* **Prioritas:** **MUST**
* **Bisa dipakai di subbab:** Subbab 2.1 — Menjelajahi Beranda Desa
* **Flowchart Preferred:** NO

#### 2. ID: `PUB-002`
* **Nama file:** `02_pub_homepage_mobile.png`
* **Role:** Publik / Warga
* **Halaman:** Beranda Utama Desa (`/`)
* **Apa yang harus di-screenshot:** Tampilan homepage pada layar smartphone dengan mobile drawer menu terbuka.
* **Elemen yang harus terlihat:** Tombol hamburger menu, drawer navigasi vertikal, kartu dusun responsif.
* **Crop:** Full viewport smartphone.
* **Viewport:** Mobile (`390 x 844`)
* **Callout:**
  - `(1)` Tombol Menu Navigasi Mobile
  - `(2)` Menu Navigasi Drawer
* **Prioritas:** **SHOULD**
* **Bisa dipakai di subbab:** Subbab 2.1 — Menjelajahi Beranda Desa (Versi Mobile)
* **Flowchart Preferred:** NO

#### 3. ID: `PUB-003`
* **Nama file:** `03_pub_dusun_page_overview.png`
* **Role:** Publik / Warga
* **Halaman:** Halaman Dusun (`/dusun/1`)
* **Apa yang harus di-screenshot:** Hero Dusun, Quick Navigation 2-baris, dan bagian Profil "Tentang Dusun".
* **Elemen yang harus terlihat:** Judul dusun, tombol navigasi cepat (Profil, Peta, Kontak, UMKM, Fasilitas, Agenda, Pengumuman), statistik RT/RW, dan foto dusun.
* **Crop:** Header dusun hingga batas bawah kartu profil kepala dusun.
* **Viewport:** Desktop (`1280 x 800`)
* **Callout:**
  - `(1)` Identitas Wilayah Dusun
  - `(2)` Bilah Pintasan Navigasi Cepat
  - `(3)` Fakta Wilayah (Jumlah RT/RW) & Nama Kepala Dusun
* **Prioritas:** **MUST**
* **Bisa dipakai di subbab:** Subbab 2.2 — Menjelajahi Halaman Dusun
* **Flowchart Preferred:** NO

#### 4. ID: `PUB-004`
* **Nama file:** `04_pub_peta_interaktif_popup.png`
* **Role:** Publik / Warga
* **Halaman:** Peta Desa / Peta Dusun (`#peta-desa` / `#peta-dusun`)
* **Apa yang harus di-screenshot:** Tampilan Peta Interaktif Leaflet dengan salah satu marker terbuka menampilkan popup informasi.
* **Elemen yang harus terlihat:** Toolbar filter kategori/dusun, pin marker berwarna, popup informasi (nama, foto, alamat, tombol detail, tombol rute Google Maps).
* **Crop:** Frame atlas peta secara utuh.
* **Viewport:** Desktop (`1280 x 750`)
* **Callout:**
  - `(1)` Filter Kategori & Dusun
  - `(2)` Marker Titik Lokasi
  - `(3)` Popup Informasi & Tombol Rute Arah
* **Prioritas:** **MUST**
* **Bisa dipakai di subbab:** Subbab 2.3 — Menggunakan Peta Interaktif & Filter Sebaran Titik
* **Flowchart Preferred:** NO

#### 5. ID: `PUB-005`
* **Nama file:** `05_pub_kontak_pelayanan_wa.png`
* **Role:** Publik / Warga
* **Halaman:** Halaman Dusun (`/dusun/1#kontak-pelayanan`)
* **Apa yang harus di-screenshot:** Deretan kartu Kontak Pelayanan dengan tombol aksi WhatsApp.
* **Elemen yang harus terlihat:** Foto petugas, nama, jabatan/peran, dan tombol hijau WhatsApp.
* **Crop:** Section Kontak Pelayanan.
* **Viewport:** Desktop atau Mobile
* **Callout:**
  - `(1)` Nama & Jabatan Petugas
  - `(2)` Tombol Hubungi WhatsApp
* **Prioritas:** **MUST**
* **Bisa dipakai di subbab:** Subbab 2.4 — Layanan Kontak & Komunikasi Warga
* **Flowchart Preferred:** NO

#### 6. ID: `PUB-006`
* **Nama file:** `06_pub_umkm_showcase_detail.png`
* **Role:** Publik / Warga
* **Halaman:** Detail UMKM (`/umkm/1`)
* **Apa yang harus di-screenshot:** Halaman detail usaha UMKM warga.
* **Elemen yang harus terlihat:** Foto utama produk, nama UMKM, nama pemilik, jenis usaha, daftar tag produk, jam operasional, alamat, dan tombol Hubungi via WhatsApp.
* **Crop:** Full halaman detail UMKM.
* **Viewport:** Desktop (`1280 x 850`)
* **Callout:**
  - `(1)` Foto & Identitas Usaha
  - `(2)` Daftar Produk Unggulan
  - `(3)` Informasi Operasional & Tombol Hubungi via WhatsApp
* **Prioritas:** **MUST**
* **Bisa dipakai di subbab:** Subbab 2.5 — Direktori UMKM & Potensi Ekonomi Lokal
* **Flowchart Preferred:** NO

#### 7. ID: `PUB-007`
* **Nama file:** `07_pub_fasilitas_detail.png`
* **Role:** Publik / Warga
* **Halaman:** Detail Fasilitas Umum (`/fasilitas/1`)
* **Apa yang harus di-screenshot:** Halaman detail fasilitas umum desa.
* **Elemen yang harus terlihat:** Nama fasilitas, badge kategori, deskripsi pemanfaatan, alamat, tombol Petunjuk Arah Google Maps.
* **Crop:** Kontainer kartu detail fasilitas.
* **Viewport:** Desktop (`1280 x 750`)
* **Callout:**
  - `(1)` Kategori Fasilitas
  - `(2)` Tombol Petunjuk Arah (Navigasi Rute)
* **Prioritas:** **SHOULD**
* **Bisa dipakai di subbab:** Subbab 2.6 — Direktori Fasilitas Umum
* **Flowchart Preferred:** NO

#### 8. ID: `PUB-008`
* **Nama file:** `08_pub_agenda_pengumuman_terkini.png`
* **Role:** Publik / Warga
* **Halaman:** Section Informasi Terkini (`#agenda` & `#pengumuman`)
* **Apa yang harus di-screenshot:** Grid berdampingan Agenda Kegiatan dan Pengumuman Resmi.
* **Elemen yang harus terlihat:** Kotak tanggal agenda, badge status (Akan Datang/Selesai), judul warta, tautan Arsip Pengumuman.
* **Crop:** Section Informasi Terkini.
* **Viewport:** Desktop (`1280 x 700`)
* **Callout:**
  - `(1)` Ledger Agenda Kegiatan & Status Waktu
  - `(2)` Daftar Pengumuman Resmi & Tautan Arsip
* **Prioritas:** **MUST**
* **Bisa dipakai di subbab:** Subbab 2.7 — Mengakses Agenda & Pengumuman Warga
* **Flowchart Preferred:** NO

---

### B. Autentikasi & Keamanan (Admin & Super Admin)

#### 9. ID: `AUTH-001`
* **Nama file:** `09_auth_login_form.png`
* **Role:** Pengelola Sistem
* **Halaman:** Form Login Admin (`/admin/login`)
* **Apa yang harus di-screenshot:** Tampilan form login bersih.
* **Elemen yang harus terlihat:** Input username, input password dengan icon toggle mata, checkbox ingat saya, tombol Masuk ke Portal.
* **Crop:** Kartu login di tengah layar.
* **Viewport:** Desktop (`1280 x 700`)
* **Callout:**
  - `(1)` Input Username Akun
  - `(2)` Input Password & Tombol Lihat Sandi
  - `(3)` Checkbox "Ingat Saya" (Simpan Sesi pada Perangkat Pribadi)
  - `(4)` Tombol Masuk
* **Prioritas:** **MUST**
* **Bisa dipakai di subbab:** Subbab 3.1 & 4.1 — Prosedur Masuk Sistem (Login)
* **Flowchart Preferred:** NO

#### 10. ID: `AUTH-002`
* **Nama file:** `10_auth_logout_button.png`
* **Role:** Pengelola Sistem
* **Halaman:** Header Dashboard Admin
* **Apa yang harus di-screenshot:** Area kanan atas header admin yang memuat profil aktif dan tombol Logout / Keluar.
* **Elemen yang harus terlihat:** Nama akun/role aktif dan tombol Keluar berwarna kontras.
* **Crop:** Header bilah navigasi atas admin.
* **Viewport:** Desktop (`1280 x 120`)
* **Callout:**
  - `(1)` Indikator Akun & Wilayah Aktif
  - `(2)` Tombol Keluar (Logout Aman)
* **Prioritas:** **SHOULD**
* **Bisa dipakai di subbab:** Subbab 3.1 & 5.1 — Prosedur Keluar & Keamanan Akun
* **Flowchart Preferred:** NO

---

### C. Modul Admin Dusun

#### 11. ID: `AD-001`
* **Nama file:** `11_ad_dashboard.png`
* **Role:** Admin Dusun
* **Halaman:** Dashboard Admin Dusun (`/admin-dusun/dashboard`)
* **Apa yang harus di-screenshot:** Tampilan beranda admin dusun lengkap.
* **Elemen yang harus terlihat:** Banner wilayah dusun, kartu ringkasan jumlah (Kontak, UMKM, Fasilitas, Agenda, Pengumuman), dan tombol aksi cepat.
* **Crop:** Seluruh area konten dashboard admin dusun.
* **Viewport:** Desktop (`1280 x 800`)
* **Callout:**
  - `(1)` Bilah Menu Navigasi Dusun
  - `(2)` Kartu Statistik Data Wilayah
  - `(3)` Tombol Tambah Cepat
* **Prioritas:** **MUST**
* **Bisa dipakai di subbab:** Subbab 3.1 — Pengenalan Dashboard Admin Dusun
* **Flowchart Preferred:** NO

#### 12. ID: `AD-002`
* **Nama file:** `12_ad_profil_dusun_form.png`
* **Role:** Admin Dusun
* **Halaman:** Edit Profil Dusun (`/admin-dusun/profil`)
* **Apa yang harus di-screenshot:** Form pengisian profil dusun dan upload banner.
* **Elemen yang harus terlihat:** Input deskripsi wilayah, jumlah RT, jumlah RW, nama kepala dusun, dan area upload gambar banner.
* **Crop:** Form edit profil dusun.
* **Viewport:** Desktop (`1280 x 850`)
* **Callout:**
  - `(1)` Isian Data Wilayah & Pimpinan
  - `(2)` Area Unggah Foto Banner Wilayah
  - `(3)` Tombol Simpan Perubahan
* **Prioritas:** **MUST**
* **Bisa dipakai di subbab:** Subbab 3.2 — Pengelolaan Profil & Banner Dusun
* **Flowchart Preferred:** NO

#### 13. ID: `AD-003`
* **Nama file:** `13_ad_kontak_form.png`
* **Role:** Admin Dusun
* **Halaman:** Form Tambah/Edit Kontak (`/admin-dusun/kontak/create`)
* **Apa yang harus di-screenshot:** Form input data kontak pelayanan baru.
* **Elemen yang harus terlihat:** Input nama petugas, jabatan, nomor WhatsApp, alamat pelayanan, dan upload foto.
* **Crop:** Form kartu kontak.
* **Viewport:** Desktop (`1280 x 750`)
* **Callout:**
  - `(1)` Nama & Jabatan Pelayanan
  - `(2)` Nomor WhatsApp Resmi
  - `(3)` Unggah Foto Petugas (Opsional)
* **Prioritas:** **MUST**
* **Bisa dipakai di subbab:** Subbab 3.3 — Manajemen Kontak Pelayanan Dusun
* **Flowchart Preferred:** NO

#### 14. ID: `AD-004`
* **Nama file:** `14_ad_umkm_form_repeater.png`
* **Role:** Admin Dusun
* **Halaman:** Form Tambah/Edit UMKM (`/admin-dusun/umkm/create`)
* **Apa yang harus di-screenshot:** Form input data UMKM dan bagian repeater produk dinamis.
* **Elemen yang harus terlihat:** Input nama UMKM, jenis usaha, jam operasional, tombol *Tambah Produk*, baris input produk, dan upload foto utama.
* **Crop:** Form UMKM bagian atas hingga repeater produk.
* **Viewport:** Desktop (`1280 x 900`)
* **Callout:**
  - `(1)` Informasi Utama UMKM
  - `(2)` Tombol Tambah Baris Produk Dinamis
  - `(3)` Unggah Foto Utama Usaha
* **Prioritas:** **MUST**
* **Bisa dipakai di subbab:** Subbab 3.4 — Pendaftaran & Pengelolaan Data UMKM
* **Flowchart Preferred:** NO

#### 15. ID: `AD-005`
* **Nama file:** `15_ad_fasilitas_form_required_map.png`
* **Role:** Admin Dusun
* **Halaman:** Form Tambah/Edit Fasilitas (`/admin-dusun/fasilitas/create`)
* **Apa yang harus di-screenshot:** Form fasilitas yang memperlihatkan dropdown kategori dan komponen peta penentu titik lokasi (wajib).
* **Elemen yang harus terlihat:** Pilihan kategori, nama fasilitas, deskripsi, alamat, dan peta coordinate picker dengan pin aktif.
* **Crop:** Form fasilitas dan peta coordinate picker.
* **Viewport:** Desktop (`1280 x 950`)
* **Callout:**
  - `(1)` Pilihan Master Kategori Fasilitas
  - `(2)` Penentuan Titik Koordinat Peta (Wajib)
* **Prioritas:** **MUST**
* **Bisa dipakai di subbab:** Subbab 3.5 — Manajemen Fasilitas Umum Wilayah
* **Flowchart Preferred:** NO

#### 16. ID: `AD-006`
* **Nama file:** `16_ad_agenda_form_media.png`
* **Role:** Admin Dusun
* **Halaman:** Form Tambah/Edit Agenda (`/admin-dusun/agenda/create`)
* **Apa yang harus di-screenshot:** Form agenda dengan tanggal, jam, lokasi, dan bagian unggah poster kegiatan.
* **Elemen yang harus terlihat:** Judul agenda, tanggal mulai/selesai, input waktu, lokasi teks, dan pilihan tipe media (Poster Awal).
* **Crop:** Form agenda.
* **Viewport:** Desktop (`1280 x 850`)
* **Callout:**
  - `(1)` Pengaturan Waktu & Lokasi Kegiatan
  - `(2)` Unggah Poster / Media Dokumentasi
* **Prioritas:** **MUST**
* **Bisa dipakai di subbab:** Subbab 3.6 — Publikasi Agenda Kegiatan Dusun
* **Flowchart Preferred:** NO

#### 17. ID: `AD-007`
* **Nama file:** `17_ad_pengumuman_form.png`
* **Role:** Admin Dusun
* **Halaman:** Form Tambah/Edit Pengumuman (`/admin-dusun/pengumuman/create`)
* **Apa yang harus di-screenshot:** Form maklumat pengumuman dengan tanggal kedaluwarsa.
* **Elemen yang harus terlihat:** Judul pengumuman, textarea isi pengumuman, input tanggal kedaluwarsa masa berlaku.
* **Crop:** Form pengumuman.
* **Viewport:** Desktop (`1280 x 750`)
* **Callout:**
  - `(1)` Judul & Isi Pengumuman
  - `(2)` Tanggal Kedaluwarsa (Masa Aktif Warta)
* **Prioritas:** **MUST**
* **Bisa dipakai di subbab:** Subbab 3.7 — Publikasi Pengumuman Resmi Dusun
* **Flowchart Preferred:** NO

---

### D. Modul Super Admin (Pemerintah Desa)

#### 18. ID: `SA-001`
* **Nama file:** `18_sa_dashboard_global.png`
* **Role:** Super Admin
* **Halaman:** Dashboard Super Admin (`/super-admin/dashboard`)
* **Apa yang harus di-screenshot:** Tampilan dashboard utama pemerintah desa.
* **Elemen yang harus terlihat:** 10 menu navigasi sidebar, statistik data se-Desa Bendung, status ringkasan 6 dusun.
* **Crop:** Area dashboard global.
* **Viewport:** Desktop (`1280 x 800`)
* **Callout:**
  - `(1)` Menu Pengelolaan Global Desa
  - `(2)` Statistik Agregat Seluruh Wilayah
* **Prioritas:** **MUST**
* **Bisa dipakai di subbab:** Subbab 4.1 — Pengenalan Dashboard Super Admin
* **Flowchart Preferred:** NO

#### 19. ID: `SA-002`
* **Nama file:** `19_sa_identitas_desa_form.png`
* **Role:** Super Admin
* **Halaman:** Kelola Identitas Desa (`/super-admin/desa`)
* **Apa yang harus di-screenshot:** Form data profil utama Desa Bendung.
* **Elemen yang harus terlihat:** Nama desa, nama kepala desa (lurah), nomor kontak resmi desa, jam pelayanan kantor desa, alamat kantor balai desa, deskripsi / selayang pandang desa, dan area unggah foto banner utama desa.
* **Crop:** Form identitas desa.
* **Viewport:** Desktop (`1280 x 850`)
* **Callout:**
  - `(1)` Nama Desa, Nama Kepala Desa, & Kontak Resmi
  - `(2)` Jam Pelayanan & Alamat Kantor Balai Desa
  - `(3)` Deskripsi / Selayang Pandang & Unggah Foto Banner Desa
* **Prioritas:** **MUST**
* **Bisa dipakai di subbab:** Subbab 4.2 — Pengelolaan Identitas & Profil Resmi Desa
* **Flowchart Preferred:** NO

#### 20. ID: `SA-003`
* **Nama file:** `20_sa_kelola_dusun_list_status.png`
* **Role:** Super Admin
* **Halaman:** Kelola Dusun (`/super-admin/dusun`)
* **Apa yang harus di-screenshot:** Tabel daftar 6 Dusun tetap beserta tombol toggle status (Aktifkan / Nonaktifkan).
* **Elemen yang harus terlihat:** Kolom nama dusun, nama kepala dusun, jumlah RT/RW, status (ACTIVE/INACTIVE), tombol Edit, dan tombol Nonaktifkan/Aktifkan.
* **Crop:** Tabel master dusun.
* **Viewport:** Desktop (`1280 x 700`)
* **Callout:**
  - `(1)` Daftar 6 Dusun Tetap
  - `(2)` Tombol Toggle Status Wilayah (Active / Inactive)
  - `(3)` Tombol Edit Narasi Dusun
* **Prioritas:** **MUST**
* **Bisa dipakai di subbab:** Subbab 4.3 — Pengawasan & Status Wilayah Dusun
* **Flowchart Preferred:** NO

#### 21. ID: `SA-004`
* **Nama file:** `21_sa_kategori_fasilitas_crud.png`
* **Role:** Super Admin
* **Halaman:** Master Kategori Fasilitas (`/super-admin/kategori-fasilitas`)
* **Apa yang harus di-screenshot:** Daftar kategori fasilitas umum dan form tambah kategori baru.
* **Elemen yang harus terlihat:** Tabel nama kategori master (Sarana Ibadah, Pendidikan, Kesehatan, dll.), tombol tambah kategori, tombol hapus.
* **Crop:** Tabel dan form kategori.
* **Viewport:** Desktop (`1280 x 700`)
* **Callout:**
  - `(1)` Master Kategori Fasilitas
  - `(2)` Tambah Kategori Baru
* **Prioritas:** **SHOULD**
* **Bisa dipakai di subbab:** Subbab 4.4 — Manajemen Master Kategori Fasilitas
* **Flowchart Preferred:** NO

#### 22. ID: `SA-005`
* **Nama file:** `22_sa_scope_wilayah_selector.png`
* **Role:** Super Admin
* **Halaman:** Form Tambah Agenda / Pengumuman Super Admin
* **Apa yang harus di-screenshot:** Bagian dropdown pilihan *Cakupan Wilayah* (Tingkat Desa vs Tingkat Dusun).
* **Elemen yang harus terlihat:** Pilihan radio/select Cakupan Wilayah (Desa = Global, Dusun = Terarah) dan dropdown pilih Dusun.
* **Crop:** Potongan form pada bagian penentuan cakupan wilayah.
* **Viewport:** Desktop (`1280 x 450`)
* **Callout:**
  - `(1)` Pilihan Cakupan Desa (Tampil di Beranda Desa)
  - `(2)` Pilihan Cakupan Dusun (Tampil Khusus di Dusun Terpilih)
* **Prioritas:** **MUST**
* **Bisa dipakai di subbab:** Subbab 4.5 — Publikasi Warta Tingkat Desa & Lintas Dusun
* **Flowchart Preferred:** NO

#### 23. ID: `SA-006`
* **Nama file:** `23_sa_filter_lintas_dusun_restore.png`
* **Role:** Super Admin
* **Halaman:** Index Kontak / UMKM / Fasilitas Super Admin
* **Apa yang harus di-screenshot:** Toolbar filter dusun dan filter status (Aktif vs Terhapus) beserta tombol *Pulihkan (Restore)*.
* **Elemen yang harus terlihat:** Dropdown filter dusun, filter status *Soft Deleted*, baris data terhapus, tombol *Pulihkan* dan *Hapus Permanen*.
* **Crop:** Toolbar filter dan tabel data terhapus.
* **Viewport:** Desktop (`1280 x 700`)
* **Callout:**
  - `(1)` Filter Lintas Dusun
  - `(2)` Filter Status Data Terhapus (Soft Deleted)
  - `(3)` Tombol Pulihkan (Restore) & Hapus Permanen
* **Prioritas:** **MUST**
* **Bisa dipakai di subbab:** Subbab 4.6 — Pemulihan Data (Restore) & Penghapusan Permanen
* **Flowchart Preferred:** NO

#### 24. ID: `SA-007`
* **Nama file:** `24_sa_data_peta_overview.png`
* **Role:** Super Admin
* **Halaman:** Data / Peta (`/super-admin/data-peta`)
* **Apa yang harus di-screenshot:** Halaman visualisasi peta agregat seluruh titik lokasi se-desa.
* **Elemen yang harus terlihat:** Peta desa ukuran penuh, filter dusun, filter jenis titik, dan rekap sebaran fasilitas/UMKM.
* **Crop:** Peta dan toolbar data peta.
* **Viewport:** Desktop (`1280 x 800`)
* **Callout:**
  - `(1)` Toolbar Filter Agregat
  - `(2)` Peta Sebaran Titik Seluruh Desa
* **Prioritas:** **SHOULD**
* **Bisa dipakai di subbab:** Subbab 4.7 — Monitoring Peta Spasial Desa
* **Flowchart Preferred:** NO

#### 25. ID: `SA-008`
* **Nama file:** `25_sa_admin_dusun_management.png`
* **Role:** Super Admin
* **Halaman:** Manajemen Akun Admin Dusun (`/super-admin/admin-dusun`)
* **Apa yang harus di-screenshot:** Tabel daftar akun pengelola dusun.
* **Elemen yang harus terlihat:** Username, dusun penugasan, status aktif/removed, tombol *Reset Password*, tombol *Ubah Penugasan*, dan tombol *Remove Akun*.
* **Crop:** Tabel akun admin dusun.
* **Viewport:** Desktop (`1280 x 750`)
* **Callout:**
  - `(1)` Akun & Dusun yang Ditugaskan
  - `(2)` Tombol Reset Password Akun
  - `(3)` Tombol Nonaktifkan Akun (Remove)
* **Prioritas:** **MUST**
* **Bisa dipakai di subbab:** Subbab 4.8 — Manajemen Akun & Hak Akses Admin Dusun
* **Flowchart Preferred:** NO

---

### E. Fitur Khusus: Peta, Koordinat & Media

#### 26. ID: `MAP-001`
* **Nama file:** `26_map_smart_input_gps.png`
* **Role:** Admin / Super Admin
* **Halaman:** Komponen Coordinate Picker pada Form Lokasi
* **Apa yang harus di-screenshot:** Kotak fitur *Smart Input* dan tombol *Gunakan GPS*.
* **Elemen yang harus terlihat:** Input teks koordinat/link Maps, tombol *Terapkan*, tombol *Gunakan GPS*, feedback hijau deteksi format, dan pin pada peta.
* **Crop:** Komponen Smart Input dan peta picker.
* **Viewport:** Desktop atau Tablet (`900 x 600`)
* **Callout:**
  - `(1)` Kotak Tempel Link Google Maps / Format Derajat (Smart Input)
  - `(2)` Tombol Deteksi Lokasi Otomatis (Gunakan GPS)
  - `(3)` Peta Interaktif & Pin yang Dapat Digeser (Draggable Pin)
  - `(4)` Tombol Hapus Titik
* **Prioritas:** **MUST**
* **Bisa dipakai di subbab:** Subbab 5.2 — Panduan Penentuan Titik Koordinat GPS & Peta
* **Flowchart Preferred:** NO

#### 27. ID: `MAP-002`
* **Nama file:** `27_map_gmaps_directions_tab.png`
* **Role:** Publik
* **Halaman:** Tab Eksternal Google Maps
* **Apa yang harus di-screenshot:** Halaman Google Maps yang terbuka setelah menekan tombol *Petunjuk Arah*.
* **Elemen yang harus terlihat:** Koordinat tujuan yang terisi otomatis di Google Maps Directions.
* **Crop:** Tampilan Google Maps dengan rute arah.
* **Viewport:** Desktop / Smartphone
* **Callout:**
  - `(1)` Titik Tujuan Sesuai Koordinat Portal Desa
* **Prioritas:** **OPTIONAL**
* **Bisa dipakai di subbab:** Subbab 2.3 — Integrasi Rute Navigasi Google Maps
* **Flowchart Preferred:** NO

---

### F. Penyelesaian Masalah (Troubleshooting & FAQ)

#### 28. ID: `TRB-001`
* **Nama file:** `28_trb_validation_error_example.png`
* **Role:** Admin / Super Admin
* **Halaman:** Contoh Pesan Validasi Form
* **Apa yang harus di-screenshot:** Tampilan form saat terjadi kesalahan input (misal: ukuran foto > 3 MB atau koordinat fasilitas kosong).
* **Elemen yang harus terlihat:** Kotak input berwarna merah dan pesan error peringatan di bawah input.
* **Crop:** Area input yang memicu validasi error.
* **Viewport:** Desktop (`800 x 450`)
* **Callout:**
  - `(1)` Pesan Peringatan Kesalahan Input
* **Prioritas:** **SHOULD**
* **Bisa dipakai di subbab:** Subbab 6.2 — Penanganan Kendala Validasi & Upload Media
* **Flowchart Preferred:** NO

---

## 4. Materi yang Direkomendasikan Menggunakan Diagram / Flowchart

Untuk beberapa topik konseptual, penggunaan **diagram alur (flowchart)** jauh lebih mudah dipahami oleh pembaca dibandingkan screenshot layar. Berikut topik yang disarankan dibuatkan bagan alur di buku:

| No | Topik Alur | Alasan Penggunaan Flowchart | Penempatan Subbab |
|:---:|---|---|---|
| **1** | **Alur Akses Warga (Scan QR / Link → Beranda → Dusun → Kontak/Peta/WhatsApp)** | Menjelaskan bagaimana warga desa membuka portal dari baliho/surat hingga menemukan layanan WhatsApp petugas desa atau pelaku UMKM tanpa fitur checkout/transaksi online. | Subbab 2.1 |
| **2** | **Alur Operasional Admin Dusun (Login → Dashboard → Input Data → Publikasi)** | Memberikan gambaran siklus kerja Kepala Dusun/Admin dalam memperbarui data wilayah. | Subbab 3.1 |
| **3** | **Alur Otoritas Super Admin (Pemerintah Desa vs Admin Dusun)** | Menjelaskan pembagian hak akses (siapa mengelola apa). | Subbab 1.3 & 4.1 |
| **4** | **Siklus Hidup Data (Soft Delete → Tong Sampah → Restore vs Hapus Permanen)** | Menjelaskan konsep keamanan data dengan batasan peran tegas:<br>• **Admin Dusun:** Hanya dapat melakukan *Soft Delete (Nonaktifkan)*; **tidak dapat** melakukan *Restore* atau *Hapus Permanen*.<br>• **Super Admin:** Dapat melakukan *Soft Delete*, melihat daftar terhapus, lalu memilih *Restore (Pulihkan)* atau *Hapus Permanen (Force Delete)*. | Subbab 4.6 |
| **5** | **Siklus Waktu Agenda Kegiatan (Akan Datang → Berlangsung → Selesai)** | Menjelaskan bagaimana status agenda berganti otomatis berdasarkan kalender tanpa intervensi manual berkala. | Subbab 3.6 |
| **6** | **Siklus Pengumuman (Aktif di Beranda/Dusun → Kedaluwarsa → Masuk Arsip)** | Menjelaskan perbedaan antara pengumuman aktif dan pengumuman arsip lampau yang tetap dapat diakses publik via `/pengumuman/arsip`. | Subbab 2.7 & 3.7 |
| **7** | **Alur Penentuan Titik Koordinat (Salin Link Google Maps → Tempel Smart Input → Verifikasi Pin)** | Mempermudah admin yang belum terbiasa dengan angka koordinat lintang/bujur melalui fitur Smart Input & GPS. | Subbab 5.2 |

---

## 5. Checklist Pengambilan Gambar (Untuk Tim Manusia)

Gunakan tabel centang berikut saat proses pengambilan gambar berlangsung:

- [ ] `PUB-001` — `01_pub_homepage_hero_desktop.png` (MUST)
- [ ] `PUB-002` — `02_pub_homepage_mobile.png` (SHOULD)
- [ ] `PUB-003` — `03_pub_dusun_page_overview.png` (MUST)
- [ ] `PUB-004` — `04_pub_peta_interaktif_popup.png` (MUST)
- [ ] `PUB-005` — `05_pub_kontak_pelayanan_wa.png` (MUST)
- [ ] `PUB-006` — `06_pub_umkm_showcase_detail.png` (MUST)
- [ ] `PUB-007` — `07_pub_fasilitas_detail.png` (SHOULD)
- [ ] `PUB-008` — `08_pub_agenda_pengumuman_terkini.png` (MUST)
- [ ] `AUTH-001` — `09_auth_login_form.png` (MUST)
- [ ] `AUTH-002` — `10_auth_logout_button.png` (SHOULD)
- [ ] `AD-001` — `11_ad_dashboard.png` (MUST)
- [ ] `AD-002` — `12_ad_profil_dusun_form.png` (MUST)
- [ ] `AD-003` — `13_ad_kontak_form.png` (MUST)
- [ ] `AD-004` — `14_ad_umkm_form_repeater.png` (MUST)
- [ ] `AD-005` — `15_ad_fasilitas_form_required_map.png` (MUST)
- [ ] `AD-006` — `16_ad_agenda_form_media.png` (MUST)
- [ ] `AD-007` — `17_ad_pengumuman_form.png` (MUST)
- [ ] `SA-001` — `18_sa_dashboard_global.png` (MUST)
- [ ] `SA-002` — `19_sa_identitas_desa_form.png` (MUST)
- [ ] `SA-003` — `20_sa_kelola_dusun_list_status.png` (MUST)
- [ ] `SA-004` — `21_sa_kategori_fasilitas_crud.png` (SHOULD)
- [ ] `SA-005` — `22_sa_scope_wilayah_selector.png` (MUST)
- [ ] `SA-006` — `23_sa_filter_lintas_dusun_restore.png` (MUST)
- [ ] `SA-007` — `24_sa_data_peta_overview.png` (SHOULD)
- [ ] `SA-008` — `25_sa_admin_dusun_management.png` (MUST)
- [ ] `MAP-001` — `26_map_smart_input_gps.png` (MUST)
- [ ] `MAP-002` — `27_map_gmaps_directions_tab.png` (OPTIONAL)
- [ ] `TRB-001` — `28_trb_validation_error_example.png` (SHOULD)
