# Blueprint Buku Panduan Penggunaan dan Pengelolaan Portal Informasi Desa Bendung

## 1. Book Identity (Identitas Buku)

| Atribut | Keterangan |
|---|---|
| **Judul Utama** | Panduan Penggunaan dan Pengelolaan Portal Informasi Desa Bendung |
| **Subjudul** | Panduan Praktis bagi Masyarakat, Admin Dusun, dan Pemerintah Desa |
| **Format Cetak** | Buku Ukuran A5 (14,8 x 21 cm) |
| **Target Tebal** | 60 sampai 70 Halaman A5 (Minimal 50 Halaman) |
| **Tujuan Penerbitan** | 1. Buku Prosedur Operasional Standar (SOP) Serah Terima KKN ke Pemerintah Desa<br>2. Karya Tulis Dokumentasi Pengajuan Hak Cipta / HKI (Hak Kekayaan Intelektual) |
| **Penyusun** | Tim KKN Universitas / Pengembang Sistem Portal Informasi Desa Bendung |
| **Tahun Terbit** | 2026 |
| **Dasar Otoritas** | `docs/07-handover/00-source-of-truth/as-built-user-manual-baseline.md` (Version 1.0 — Frozen) |

---

## 2. Target Readers (Target Pembaca)

Buku panduan ini dirancang untuk tiga kelompok pembaca dengan tingkat keahlian teknis yang berbeda:

1. **Masyarakat Umum, Warga Desa Bendung, dan Pengunjung Luar:**
   * Membutuhkan panduan navigasi yang ramah, visual, dan langsung ke tujuan untuk menemukan kontak pamong, potensi UMKM, lokasi fasilitas, serta warta desa.
2. **Admin Dusun (Kepala Dusun / Perangkat Wilayah):**
   * Membutuhkan panduan operasional langkah-demi-langkah yang jelas untuk mengelola profil dusun, kontak pelayanan, produk UMKM, fasilitas, agenda, dan pengumuman tanpa kebingungan teknis.
3. **Super Admin (Pemerintah Desa / Sekretariat Balai Desa Bendung):**
   * Membutuhkan panduan tata kelola menyeluruh tingkat desa, pemulihan data, manajemen akun admin dusun, serta pengawasan status wilayah 6 dusun.

---

## 3. Writing Principles (Prinsip Penulisan)

Dalam menulis naskah buku, tim penulis wajib berpegang pada prinsip berikut:

1. **Bahasa Komunikatif dan Instruksional:** Gunakan kalimat aktif, lugas, dan terstruktur. Hindari kalimat berputar-putar dan istilah abstrak.
2. **Kesesuaian Label UI Aktual:** Seluruh nama tombol, menu, dan label form harus persis sama dengan yang tampil di aplikasi (misalnya: gunakan label *Hubungi via WhatsApp*, bukan *Beli Online*; gunakan *Filter Kategori*, bukan *Pencarian Fasilitas*).
3. **Bebas Jargon Pemrograman:** Jangan mencantumkan istilah internal pengembang seperti nama controller, route backend, tipe data database (`VARCHAR`, `FOREIGN KEY`), atau potongan skrip kode.
4. **Pemisahan Peran yang Jujur:** Diagram alur dan teks wajib membedakan secara tegas hak akses Admin Dusun (hanya soft delete) dan Super Admin (bisa restore dan hapus permanen).
5. **Kepatuhan Terhadap Fitur yang Tersedia:** Jangan mencantumkan fitur yang tidak tersedia seperti registrasi akun warga, keranjang belanja UMKM, pembayaran online, pencarian nama jalan pada peta, atau penambahan dusun baru.

---

## 4. Page Budget (Rencana Alokasi Halaman A5)

| Bagian Buku | Estimasi Halaman | Fokus dan Cakupan Konten |
|---|:---:|---|
| **Sampul Depan (Cover Depan)** | *Di luar hitungan isi* | Desain sampul fisik depan (Mockup, Logo, Judul, Penyusun). |
| **Bagian Awal (Front Matter)** | 6 - 8 hal | Halaman Judul, Hak Cipta, Kata Pengantar, Daftar Isi, Gambar, Tabel, Petunjuk Buku. |
| **BAB I: Mengenal Portal Desa** | 5 - 7 hal | Latar belakang, tujuan, manfaat, 3 peran pengguna, dan ringkasan fitur. |
| **BAB II: Panduan Masyarakat** | 10 - 13 hal | Akses web, beranda, 6 dusun, peta interaktif, kontak, UMKM, fasilitas, agenda, warta. |
| **BAB III: Panduan Admin Dusun** | 14 - 18 hal | Login, ingat saya, dashboard, CRUD 6 modul dusun, soft delete, batas wewenang. |
| **BAB IV: Panduan Super Admin** | 14 - 18 hal | Identitas desa, status dusun, data lintas wilayah, kategori, admin dusun, restore, hapus permanen. |
| **BAB V: Pedoman Pengelolaan** | 5 - 7 hal | Akurasi data, penentuan koordinat, standar media/foto, privasi warga, jadwal pembaruan. |
| **BAB VI: Troubleshooting & FAQ** | 4 - 6 hal | Solusi kendala login, upload foto, titik koordinat, handoff WhatsApp/Maps, kontak bantuan. |
| **Bagian Akhir (Back Matter)** | 3 - 5 hal | Glosarium istilah, lembar checklist pemeliharaan, profil penyusun. |
| **Sampul Belakang (Cover Belakang)** | *Di luar hitungan isi* | Sinopsis buku, sasaran pembaca, identitas instansi, ruang QR code. |
| **TOTAL ESTIMASI HALAMAN ISI** | **61 - 72 hal** | **Memenuhi target ideal buku A5 (60 - 70 halaman).** |

> [!NOTE]
> **Ketentuan Sampul Fisik Buku:**
> Cover Depan dan Cover Belakang merupakan bagian fisik sampul luar buku dan **tidak dihitung** ke dalam nomor halaman isi (61 - 72 halaman A5). Material sampul ditentukan pada tahap finalisasi produksi/cetak. Halaman Judul tetap berada pada lembar pertama di bagian dalam buku (*Front Matter*).

---

## 5. Final Physical Structure & Table of Contents (Struktur Fisik & Daftar Isi)

### 5.1 Urutan Struktur Fisik Buku Lengkap

```text
COVER DEPAN (Sampul Depan Luar)
  ↓
HALAMAN JUDUL (Lembar Judul Dalam)
  ↓
HALAMAN HAK CIPTA & IDENTITAS PENYUSUN
  ↓
KATA PENGANTAR
  ↓
DAFTAR ISI
  ↓
DAFTAR GAMBAR
  ↓
DAFTAR TABEL
  ↓
PETUNJUK PENGGUNAAN BUKU
  ↓
BAB I   — MENGENAL PORTAL INFORMASI DESA BENDUNG
BAB II  — PANDUAN PENGGUNAAN PORTAL BAGI MASYARAKAT
BAB III — PANDUAN PENGELOLAAN UNTUK ADMIN DUSUN
BAB IV  — PANDUAN TATA KELOLA UNTUK SUPER ADMIN
BAB V   — PEDOMAN STANDAR PENGELOLAAN INFORMASI
BAB VI  — PANDUAN PENYELESAIAN MASALAH (TROUBLESHOOTING & FAQ)
  ↓
GLOSARIUM ISTILAH
  ↓
CHECKLIST PEMELIHARAAN SISTEM BERKALA
  ↓
PROFIL TIM PENYUSUN
  ↓
COVER BELAKANG (Sampul Belakang Luar & Sinopsis)
```

### 5.2 Daftar Isi Rinci Naskah

```text
HALAMAN JUDUL
HALAMAN HAK CIPTA DAN IDENTITAS PENYUSUN
KATA PENGANTAR
DAFTAR ISI
DAFTAR GAMBAR
DAFTAR TABEL
PETUNJUK PENGGUNAAN BUKU

BAB I — MENGENAL PORTAL INFORMASI DESA BENDUNG
  1.1 Latar Belakang Digitalisasi Desa Bendung
  1.2 Tujuan Pengembangan Portal
  1.3 Manfaat Portal bagi Warga dan Pemerintah Desa
  1.4 Pengguna dan Pembagian Hak Akses
  1.5 Ringkasan Fitur Utama Portal
  1.6 Panduan Membaca Buku Manual

BAB II — PANDUAN PENGGUNAAN PORTAL BAGI MASYARAKAT
  2.1 Mengakses Portal Informasi Desa
  2.2 Mengenal Beranda Utama Desa
  2.3 Memilih dan Menjelajahi Halaman 6 Dusun
  2.4 Menggunakan Peta Desa Interaktif dan Filter Sebaran Titik
  2.5 Menghubungi Kontak Pelayanan Dusun
  2.6 Menjelajahi Etalase Potensi UMKM Lokal
  2.7 Menemukan Direktori Fasilitas Umum
  2.8 Memantau Agenda Kegiatan Warga
  2.9 Membaca Pengumuman Resmi dan Mengakses Arsip Warta

BAB III — PANDUAN PENGELOLAAN UNTUK ADMIN DUSUN
  3.1 Prosedur Masuk Sistem, Fitur Ingat Saya, dan Dashboard Dusun
  3.2 Mengelola Profil dan Foto Banner Wilayah Dusun
  3.3 Mengelola Kontak Pelayanan Warga
  3.4 Mendaftarkan dan Memperbarui Data UMKM
  3.5 Mengelola Data Fasilitas Umum dan Titik Lokasi
  3.6 Mempublikasikan Agenda Kegiatan Dusun
  3.7 Menerbitkan Pengumuman Resmi Dusun
  3.8 Prosedur Menonaktifkan Data (Soft Delete)
  3.9 Batas Hak Akses dan Kewenangan Admin Dusun

BAB IV — PANDUAN TATA KELOLA UNTUK SUPER ADMIN
  4.1 Prosedur Masuk dan Dashboard Tata Kelola Global
  4.2 Mengelola Identitas dan Profil Resmi Desa
  4.3 Mengelola Status dan Narasi Profil 6 Dusun
  4.4 Mengelola Data Kontak, UMKM, dan Fasilitas Lintas Dusun
  4.5 Mengelola Master Kategori Fasilitas Desa
  4.6 Mempublikasikan Agenda dan Pengumuman Tingkat Desa
  4.7 Monitoring Sebaran Titik Spasial Desa (Data / Peta)
  4.8 Mengelola Akun Pengelola Dusun (Admin Dusun)
  4.9 Prosedur Reset Password Akun Admin Dusun
  4.10 Memulihkan Data Terhapus (Restore)
  4.11 Menghapus Data Secara Permanen (Hard Delete)
  4.12 Batasan Struktural Terhadap Master 6 Dusun

BAB V — PEDOMAN STANDAR PENGELOLAAN INFORMASI
  5.1 Prinsip Akurasi dan Validitas Data Desa
  5.2 Panduan Teknis Penentuan Titik Koordinat Peta
  5.3 Standar Format Foto dan Media Publikasi
  5.4 Standar Penulisan Nomor WhatsApp dan Kontak Resmi
  5.5 Perlindungan Privasi dan Prosedur Izin Publikasi Data Warga
  5.6 Jadwal dan Siklus Pembaruan Data Berkala
  5.7 Memahami Perbedaan Data Aktif, Data Nonaktif, dan Arsip

BAB VI — PANDUAN PENYELESAIAN MASALAH (TROUBLESHOOTING & FAQ)
  6.1 Kendala Masuk Sistem (Gagal Login dan Akun Dinonaktifkan)
  6.2 Prosedur Penanganan Lupa Password Admin Dusun
  6.3 Mengatasi Kegagalan Unggah Foto dan Banner
  6.4 Titik Lokasi Peta Tidak Muncul atau Bergeser
  6.5 Tombol WhatsApp atau Petunjuk Arah Google Maps Tidak Berfungsi
  6.6 Data yang Disimpan Tidak Tampil di Halaman Publik
  6.7 Mengapa Pengumuman Berpindah ke Halaman Arsip
  6.8 Menemukan Kembali Data yang Tidak Sengaja Dinonaktifkan
  6.9 Daftar Pertanyaan Umum (FAQ)
  6.10 Saluran Bantuan dan Eskalasi ke Super Admin

BAGIAN AKHIR
  GLOSARIUM ISTILAH
  CHECKLIST PEMELIHARAAN SISTEM BERKALA
  PROFIL TIM PENYUSUN
```

---

## 6. Detailed Chapter Blueprint (Blueprint Subbab Rinci)

### BAB I — MENGENAL PORTAL INFORMASI DESA BENDUNG

#### 1.1 Latar Belakang Digitalisasi Desa Bendung
* **Target pembaca:** Seluruh Pembaca (Masyarakat, Admin Dusun, Super Admin).
* **Tujuan:** Menjelaskan konteks kebutuhan keterbukaan informasi dan digitalisasi potensi di 6 dusun Desa Bendung.
* **Dasar baseline:** Bagian 2 (Product Purpose).
* **Materi yang dibahas:** Kondisi geografis 6 dusun, tantangan penyebaran informasi manual, dan inisiatif digitalisasi desa.
* **Prosedur utama:** Tidak ada (Pengantar Konseptual).
* **Screenshot:** Tidak ada.
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Ringkasan Wilayah 6 Dusun Desa Bendung.
* **Callout:** `CATATAN` — Portal dirancang ringan dan dapat diakses dari peramban ponsel pintar maupun komputer.
* **Estimasi halaman A5:** 1,0 Halaman.
* **Catatan penulisan:** Gunakan nada pembuka yang profesional, ringkas, dan membumi.

#### 1.2 Tujuan Pengembangan Portal
* **Target pembaca:** Seluruh Pembaca.
* **Tujuan:** Menjelaskan fungsi portal sebagai pusat informasi publik dan etalase potensi desa.
* **Dasar baseline:** Bagian 2 (Product Purpose).
* **Materi yang dibahas:** Publikasi profil desa, transparansi kontak pelayanan, promosi UMKM, direktori fasilitas, warta agenda, dan pengumuman.
* **Prosedur utama:** Tidak ada.
* **Screenshot:** Tidak ada.
* **Flowchart:** Tidak ada.
* **Tabel:** Tidak ada.
* **Callout:** `PENTING` — Portal Informasi Desa Bendung adalah media publikasi resmi, bukan marketplace atau platform transaksi keuangan online.
* **Estimasi halaman A5:** 1,0 Halaman.
* **Catatan penulisan:** Tegaskan batasan portal sejak awal agar pembaca tidak mengharapkan fitur belanja daring.

#### 1.3 Manfaat Portal bagi Warga dan Pemerintah Desa
* **Target pembaca:** Masyarakat dan Aparatur Desa.
* **Tujuan:** Menguraikan manfaat praktis bagi masyarakat umum, pengelola dusun, dan pemerintah desa.
* **Dasar baseline:** Bagian 2 & Bagian 3 (User Roles).
* **Materi yang dibahas:** Kemudahan akses layanan bagi warga, sarana promosi bagi UMKM lokal, dan efisiensi koordinasi administrasi desa.
* **Prosedur utama:** Tidak ada.
* **Screenshot:** Tidak ada.
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Matriks Manfaat Berdasarkan Peran Pengguna.
* **Callout:** `TIPS` — Warga dapat menyimpan tautan portal di layar utama ponsel untuk akses cepat layaknya aplikasi.
* **Estimasi halaman A5:** 1,0 Halaman.
* **Catatan penulisan:** Sajikan manfaat dalam poin-poin terstruktur.

#### 1.4 Pengguna dan Pembagian Hak Akses
* **Target pembaca:** Seluruh Pembaca, terutama Pengelola Sistem.
* **Tujuan:** Memberikan pemahaman tegas mengenai 3 peran pengguna dan batasan wewenangnya.
* **Dasar baseline:** Bagian 3 (Final User Roles).
* **Materi yang dibahas:** Peran Publik (Read-Only), Admin Dusun (Pengelola Wilayah Terisolasi), dan Super Admin (Tata Kelola Global).
* **Prosedur utama:** Memahami matriks kewenangan sebelum mengoperasikan sistem.
* **Screenshot:** Tidak ada.
* **Flowchart:** `FLOW-03` — Diagram Pembagian Otoritas Pengguna.
* **Tabel:** Tabel Matriks Hak Akses dan Batas Kewenangan Pengguna.
* **Callout:** `PERHATIAN` — Admin Dusun tidak memiliki akses untuk mengubah data dusun lain atau memulihkan data yang telah dihapus.
* **Estimasi halaman A5:** 1,5 Halaman.
* **Catatan penulisan:** Pastikan batasan peran disajikan secara lugas agar tidak terjadi tumpang tindih tanggung jawab.

#### 1.5 Ringkasan Fitur Utama Portal
* **Target pembaca:** Seluruh Pembaca.
* **Tujuan:** Memberikan ringkasan visual atas modul-modul yang tersedia di portal.
* **Dasar baseline:** Bagian 4 (Public Architecture).
* **Materi yang dibahas:** Beranda Desa, Profil 6 Dusun, Peta Interaktif, Kontak Pelayanan, Etalase UMKM, Direktori Fasilitas, Agenda Kegiatan, dan Arsip Pengumuman.
* **Prosedur utama:** Tidak ada (Ikhtisar Modul).
* **Screenshot:** Tidak ada.
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Ringkasan Fitur dan Lokasi Akses.
* **Callout:** `CATATAN` — Seluruh data kontak terhubung langsung dengan WhatsApp untuk memudahkan komunikasi warga.
* **Estimasi halaman A5:** 1,0 Halaman.
* **Catatan penulisan:** Gunakan bahasa deskriptif ringkas.

#### 1.6 Panduan Membaca Buku Manual
* **Target pembaca:** Seluruh Pembaca.
* **Tujuan:** Memberi petunjuk bagian mana yang harus dibaca oleh warga, Admin Dusun, dan Super Admin.
* **Dasar baseline:** Bagian 15 (Manual Authoring Rules).
* **Materi yang dibahas:** Struktur bab, arti kotak callout peringatan, dan panduan membaca alur kerja.
* **Prosedur utama:** Cara memilih bab yang relevan sesuai peran pembaca.
* **Screenshot:** Tidak ada.
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Panduan Rute Membaca Berdasarkan Kebutuhan.
* **Callout:** `TIPS` — Pembaca dapat langsung melompat ke Bab II (Warga), Bab III (Admin Dusun), atau Bab IV (Super Admin).
* **Estimasi halaman A5:** 0,5 Halaman.
* **Catatan penulisan:** Buat panduan navigasi buku yang memudahkan pembaca.

---

### BAB II — PANDUAN PENGGUNAAN PORTAL BAGI MASYARAKAT

#### 2.1 Mengakses Portal Informasi Desa
* **Target pembaca:** Warga Desa dan Pengunjung.
* **Tujuan:** Menjelaskan cara membuka portal melalui peramban web dan pemindaian kode QR.
* **Dasar baseline:** Bagian 4 (Public Architecture).
* **Materi yang dibahas:** Alamat web resmi portal, cara memindai kode QR dari papan informasi dusun, dan kompatibilitas perangkat.
* **Prosedur utama:** Membuka peramban di ponsel/komputer dan memasukkan alamat web portal.
* **Screenshot:** Tidak ada.
* **Flowchart:** `FLOW-01` — Alur Akses dan Penjelajahan Warga.
* **Tabel:** Tidak ada.
* **Callout:** `TIPS` — Tidak diperlukan instalasi aplikasi tambahan dari toko aplikasi; portal dapat langsung dibuka di Google Chrome, Safari, atau peramban lainnya.
* **Estimasi halaman A5:** 1,0 Halaman.
* **Catatan penulisan:** Tekankan kemudahan akses tanpa perlu mendaftar akun.

#### 2.2 Mengenal Beranda Utama Desa
* **Target pembaca:** Warga Desa dan Pengunjung.
* **Tujuan:** Memandu pembaca memahami tata letak dan informasi yang tersaji di halaman depan portal.
* **Dasar baseline:** Bagian 4 (Public Architecture).
* **Materi yang dibahas:** Header navigasi, banner identitas desa, slogan, kartu pilihan 6 dusun, cuplikan warta terkini, dan footer kontak resmi desa.
* **Prosedur utama:** Menjelajahi section beranda desa dari atas hingga bawah.
* **Screenshot:** 
  - `PUB-001` — `01_pub_homepage_hero_desktop.png` (MUST)
  - `PUB-002` — `02_pub_homepage_mobile.png` (SHOULD)
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Panduan Elemen Beranda Utama Desa.
* **Callout:** `CATATAN` — Pada tampilan ponsel, menu navigasi tersimpan rapi di dalam tombol menu samping (*drawer*).
* **Estimasi halaman A5:** 2,0 Halaman.
* **Catatan penulisan:** Berikan penjelasan sesuai nomor callout pada tangkapan layar `(1)`, `(2)`, `(3)`.

#### 2.3 Memilih dan Menjelajahi Halaman 6 Dusun
* **Target pembaca:** Warga Desa dan Pengunjung.
* **Tujuan:** Menjelaskan cara masuk ke halaman khusus masing-masing dusun (Bendung, Klubuk, Bantengan, Belik, Pohsengir, Kaliasin).
* **Dasar baseline:** Bagian 4 (Public Architecture).
* **Materi yang dibahas:** Kartu navigasi 6 dusun, bilah pintasan cepat 2-baris, narasi selayang pandang dusun, data RT/RW, dan foto kepala dusun.
* **Prosedur utama:** Memilih kartu dusun di beranda dan menggunakan bilah navigasi cepat untuk melompat ke section yang diinginkan.
* **Screenshot:** `PUB-003` — `03_pub_dusun_page_overview.png` (MUST)
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Struktur Informasi Halaman Dusun.
* **Callout:** `TIPS` — Gunakan tombol pintasan cepat di bawah banner dusun untuk langsung melompat ke Kontak, UMKM, Fasilitas, atau Agenda tanpa perlu menggulir layar secara manual.
* **Estimasi halaman A5:** 1,5 Halaman.
* **Catatan penulisan:** Jelaskan tata letak navigasi 2-baris yang responsif.

#### 2.4 Menggunakan Peta Desa Interaktif dan Filter Sebaran Titik
* **Target pembaca:** Warga Desa dan Pengunjung.
* **Tujuan:** Memandu pembaca memanfaatkan atlas digital peta desa, memfilter kategori titik, dan membuka rute navigasi Google Maps.
* **Dasar baseline:** Bagian 9 (Final Map & Coordinate Rules).
* **Materi yang dibahas:** Navigasi peta (geser dan perbesar/perkecil), dropdown filter wilayah dusun, filter kategori (Fasilitas Umum, UMKM, Kontak Pelayanan), balon informasi marker (*popup*), dan tombol Petunjuk Arah.
* **Prosedur utama:** Membuka peta, memilih filter kategori, mengklik marker titik, dan menekan tombol petunjuk arah untuk navigasi perjalanan.
* **Screenshot:** 
  - `PUB-004` — `04_pub_peta_interaktif_popup.png` (MUST)
  - `MAP-002` — `27_map_gmaps_directions_tab.png` (OPTIONAL)
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Arti Warna Marker dan Kategori Peta.
* **Callout:** `PENTING` — Peta menyediakan filter kategori dan wilayah dusun. Peta tidak menggunakan kotak pencarian teks nama lokasi.
* **Estimasi halaman A5:** 1,5 Halaman.
* **Catatan penulisan:** Jelaskan bahwa tombol petunjuk arah akan membuka aplikasi Google Maps di ponsel pengguna secara otomatis.

#### 2.5 Menghubungi Kontak Pelayanan Dusun
* **Target pembaca:** Warga Desa yang Membutuhkan Layanan Administrasi.
* **Tujuan:** Menjelaskan cara menemukan nomor pamong/kadus dan memulai percakapan WhatsApp resmi.
* **Dasar baseline:** Bagian 4 & Bagian 11.
* **Materi yang dibahas:** Daftar petugas pelayanan dusun, foto petugas, bidang pelayanan, dan fungsi tombol hijau WhatsApp.
* **Prosedur utama:** Mencari nama petugas pada daftar kontak lalu menekan tombol *Hubungi via WhatsApp*.
* **Screenshot:** `PUB-005` — `05_pub_kontak_pelayanan_wa.png` (MUST)
* **Flowchart:** Tidak ada.
* **Tabel:** Tidak ada.
* **Callout:** `CATATAN` — Saat tombol WhatsApp ditekan, peramban akan langsung membuka ruang percakapan dengan format nomor resmi yang sudah terstandarisasi.
* **Estimasi halaman A5:** 1,0 Halaman.
* **Catatan penulisan:** Berikan etika singkat menghubungi perangkat desa melalui pesan daring.

#### 2.6 Menjelajahi Etalase Potensi UMKM Lokal
* **Target pembaca:** Warga Desa, Pengunjung, dan Pembeli Potensial.
* **Tujuan:** Memandu warga melihat etalase usaha warga, daftar produk, jam operasional, dan menghubungi pemilik usaha.
* **Dasar baseline:** Bagian 11 (Final UMKM Rules).
* **Materi yang dibahas:** Kartu katalog UMKM di halaman dusun, halaman detail profil UMKM, tag daftar produk unggulan, jam buka, alamat, dan tombol untuk menghubungi pemilik usaha melalui WhatsApp.
* **Prosedur utama:** Membuka etalase UMKM, memilih profil usaha, melihat produk yang dijual, dan menekan tombol *Hubungi via WhatsApp*.
* **Screenshot:** `PUB-006` — `06_pub_umkm_showcase_detail.png` (MUST)
* **Flowchart:** Tidak ada.
* **Tabel:** Tidak ada.
* **Callout:** `PENTING` — Portal Desa Bendung berfungsi sebagai etalase promosi informasi usaha. Komunikasi dan transaksi lebih lanjut dilakukan langsung dengan pemilik usaha melalui WhatsApp tanpa perantara sistem web.
* **Estimasi halaman A5:** 1,5 Halaman.
* **Catatan penulisan:** Tegaskan bahwa tidak ada tombol keranjang belanja atau pembayaran di portal.

#### 2.7 Menemukan Direktori Fasilitas Umum
* **Target pembaca:** Warga Desa dan Tamu Wilayah.
* **Tujuan:** Memandu pencarian sarana publik seperti tempat ibadah, sarana kesehatan, balai pertemuan, dan fasilitas umum lainnya.
* **Dasar baseline:** Bagian 4 & Bagian 9.
* **Materi yang dibahas:** Daftar fasilitas per dusun, lencana (*badge*) kategori, deskripsi fungsi sarana, alamat, dan tombol rute navigasi.
* **Prosedur utama:** Memilih fasilitas dari daftar atau peta, membaca informasi pemanfaatan, dan membuka petunjuk arah.
* **Screenshot:** `PUB-007` — `07_pub_fasilitas_detail.png` (SHOULD)
* **Flowchart:** Tidak ada.
* **Tabel:** Tidak ada.
* **Callout:** `TIPS` — Fasilitas wajib memiliki titik koordinat agar dapat ditampilkan sebagai marker peta. Akurasi titik lokasi ditentukan oleh pengelola saat memasukkan data.
* **Estimasi halaman A5:** 1,0 Halaman.
* **Catatan penulisan:** Sajikan informasi secara praktis untuk warga luar dusun.

#### 2.8 Memantau Agenda Kegiatan Warga
* **Target pembaca:** Warga Desa dan Komunitas Lokal.
* **Tujuan:** Menjelaskan cara memantau jadwal kegiatan kemasyarakatan, rapat dusun, gotong royong, dan perayaan desa.
* **Dasar baseline:** Bagian 4 & Bagian 8.6.
* **Materi yang dibahas:** Daftar agenda di beranda dan halaman dusun, kotak tanggal, waktu pelaksanaan, lokasi kegiatan, status waktu (*Akan Datang / Berlangsung / Selesai*), poster acara, dan dokumentasi kegiatan lampau.
* **Prosedur utama:** Membuka kartu agenda, membaca jadwal rinci, dan melihat poster publikasi.
* **Screenshot:** `PUB-008` — `08_pub_agenda_pengumuman_terkini.png` (MUST, Bagian Agenda)
* **Flowchart:** `FLOW-05` — Siklus Status Waktu Agenda Kegiatan.
* **Tabel:** Tidak ada.
* **Callout:** `CATATAN` — Status agenda diperbarui secara otomatis oleh sistem berdasarkan tanggal dan jam kegiatan.
* **Estimasi halaman A5:** 1,5 Halaman.
* **Catatan penulisan:** Jelaskan bahwa agenda yang sudah selesai dapat memuat foto dokumentasi kegiatan.

#### 2.9 Membaca Pengumuman Resmi dan Mengakses Arsip Warta
* **Target pembaca:** Warga Desa.
* **Tujuan:** Memandu warga membaca maklumat resmi pemerintah desa/dusun dan menelusuri warta yang telah lampau di halaman arsip.
* **Dasar baseline:** Bagian 4 & Bagian 8.5.
* **Materi yang dibahas:** Pengumuman aktif di beranda/dusun, tanggal masa berlaku warta, isi maklumat, dan halaman Arsip Pengumuman (`/pengumuman/arsip`).
* **Prosedur utama:** Membaca warta aktif dan membuka tautan arsip untuk membaca warta bersejarah yang masa berlakunya telah terlewati.
* **Screenshot:** `PUB-008` — `08_pub_agenda_pengumuman_terkini.png` (MUST, Bagian Pengumuman & Tautan Arsip)
* **Flowchart:** `FLOW-06` — Siklus Hidup Pengumuman (Aktif ke Arsip).
* **Tabel:** Tidak ada.
* **Callout:** `CATATAN` — Pengumuman yang masa berlakunya habis tidak dihapus, melainkan otomatis dipindahkan ke halaman Arsip agar tetap dapat dibaca kembali oleh warga.
* **Estimasi halaman A5:** 1,5 Halaman.
* **Catatan penulisan:** Bedakan secara jelas antara warta kedaluwarsa (arsip) dan data yang dinonaktifkan (terhapus).

---

### BAB III — PANDUAN PENGELOLAAN UNTUK ADMIN DUSUN

#### 3.1 Prosedur Masuk Sistem, Fitur Ingat Saya, dan Dashboard Dusun
* **Target pembaca:** Admin Dusun (Kepala Dusun / Perangkat Wilayah).
* **Tujuan:** Memandu proses login yang aman, pemanfaatan fitur Ingat Saya, pengenalan dashboard wilayah, dan prosedur logout.
* **Dasar baseline:** Bagian 5 (Authentication) & Bagian 6 (Admin Dusun Capabilities).
* **Materi yang dibahas:** URL login `/admin/login`, input kredensial, tombol toggle lihat sandi, fungsi checkbox *Ingat Saya*, kartu statistik data dusun, tombol aksi cepat, dan tombol keluar.
* **Prosedur utama:**
  1. Membuka alamat `/admin/login`.
  2. Memasukkan username dan password.
  3. Mencentang *Ingat saya* jika menggunakan perangkat pribadi terpercaya.
  4. Menekan tombol *Masuk ke Portal*.
  5. Menjelajahi menu dashboard dusun.
  6. Menekan tombol *Keluar* setelah selesai bekerja.
* **Screenshot:** 
  - `AUTH-001` — `09_auth_login_form.png` (MUST)
  - `AUTH-002` — `10_auth_logout_button.png` (SHOULD)
  - `AD-001` — `11_ad_dashboard.png` (MUST)
* **Flowchart:** `FLOW-02` — Alur Operasional Kerja Admin Dusun.
* **Tabel:** Tabel Elemen Dashboard Admin Dusun.
* **Callout:**
  - `TIPS` — Centang kotak *Ingat saya* agar sesi login tersimpan aman di komputer pribadi tanpa perlu memasukkan kata sandi berulang kali.
  - `PERHATIAN` — Jangan mencentang *Ingat saya* jika Anda login menggunakan komputer umum di balai desa atau warnet. Selalu tekan tombol *Keluar* setelah selesai.
* **Estimasi halaman A5:** 2,5 Halaman.
* **Catatan penulisan:** Sajikan langkah demi langkah secara tegas dan mudah diikuti.

#### 3.2 Mengelola Profil dan Foto Banner Wilayah Dusun
* **Target pembaca:** Admin Dusun.
* **Tujuan:** Memandu pembaruan deskripsi narasi dusun, fakta jumlah RT/RW, nama kepala dusun, dan unggah foto banner utama wilayah.
* **Dasar baseline:** Bagian 6 & Bagian 10 (Media Rules).
* **Materi yang dibahas:** Form edit profil dusun, kolom deskripsi selayang pandang, isian angka RT dan RW, kolom nama kepala dusun, dan ketentuan unggah banner (JPG/PNG/WebP, maks 3 MB).
* **Prosedur utama:** Mengisi formulir profil dusun, memilih file banner dari komputer/ponsel, dan menekan tombol *Simpan Perubahan*.
* **Screenshot:** `AD-002` — `12_ad_profil_dusun_form.png` (MUST)
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Isian Form Profil Dusun (`| Isian | Wajib? | Cara Mengisi | Contoh |`).
* **Callout:** `PENTING` — Foto banner dusun disarankan menggunakan foto lanskap berpemandangan wilayah atau gapura dusun dengan pencahayaan yang jelas.
* **Estimasi halaman A5:** 1,5 Halaman.
* **Catatan penulisan:** Berikan contoh penulisan deskripsi dusun yang menarik.

#### 3.3 Mengelola Kontak Pelayanan Warga
* **Target pembaca:** Admin Dusun.
* **Tujuan:** Memandu penambahan, penyuntingan, dan pengelolaan kontak pamong atau petugas pelayanan dusun.
* **Dasar baseline:** Bagian 6, Bagian 9, & Bagian 10.
* **Materi yang dibahas:** Form tambah/edit kontak, penulisan nama dan jabatan pelayanan, format nomor WhatsApp resmi, alamat kantor/rumah, koordinat peta opsional, dan foto petugas.
* **Prosedur utama:** Menambah kontak baru, mengisi formulir data diri petugas, menentukan titik lokasi opsional, mengunggah foto, dan menyimpan data.
* **Screenshot:** `AD-003` — `13_ad_kontak_form.png` (MUST)
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Isian Form Kontak Pelayanan (`| Isian | Wajib? | Cara Mengisi | Contoh |`).
* **Callout:** `PENTING` — Pastikan nomor WhatsApp yang dimasukkan aktif dan telah mendapatkan izin dari petugas yang bersangkutan sebelum dipublikasikan.
* **Estimasi halaman A5:** 1,5 Halaman.
* **Catatan penulisan:** Tekankan format penulisan nomor HP/WhatsApp yang benar (diawali 08 atau 62).

#### 3.4 Mendaftarkan dan Memperbarui Data UMKM
* **Target pembaca:** Admin Dusun.
* **Tujuan:** Memandu pendaftaran usaha warga, pemanfaatan fitur repeater produk dinamis, unggah foto utama usaha, dan penentuan titik lokasi peta.
* **Dasar baseline:** Bagian 6, Bagian 9, Bagian 10, & Bagian 11.
* **Materi yang dibahas:** Form UMKM, nama usaha, nama pemilik, jenis usaha, deskripsi, alamat, jam operasional, tombol *Tambah Produk*, input nama produk dinamis, upload foto produk/toko, dan titik koordinat opsional.
* **Prosedur utama:**
  1. Menekan tombol *Tambah UMKM*.
  2. Mengisi identitas usaha dan kontak WhatsApp pemilik.
  3. Menekan tombol *Tambah Produk* untuk memasukkan item barang dagangan.
  4. Memilih foto utama tempat usaha atau produk unggulan.
  5. Menentukan titik koordinat peta (jika ada).
  6. Menekan tombol *Simpan Data UMKM*.
* **Screenshot:** 
  - `AD-004` — `14_ad_umkm_form_repeater.png` (MUST)
  - `MAP-001` — `26_map_smart_input_gps.png` (REUSABLE)
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Isian Form Pendaftaran UMKM (`| Isian | Wajib? | Cara Mengisi | Contoh |`).
* **Callout:** `CATATAN` — Koordinat peta pada UMKM bersifat opsional. Jika diisi, UMKM akan muncul sebagai pin di peta desa; jika dikosongkan, UMKM tetap tampil di etalase direktori kartu.
* **Estimasi halaman A5:** 2,0 Halaman.
* **Catatan penulisan:** Jelaskan cara menambah dan menghapus baris produk dinamis.

#### 3.5 Mengelola Data Fasilitas Umum dan Titik Lokasi
* **Target pembaca:** Admin Dusun.
* **Tujuan:** Memandu pendaftaran sarana publik dengan kewajiban penentuan titik koordinat peta yang akurat.
* **Dasar baseline:** Bagian 6, Bagian 9, & Bagian 10.
* **Materi yang dibahas:** Form fasilitas, pemilihan master kategori (Ibadah, Kesehatan, Pendidikan, dll.), nama fasilitas, deskripsi, alamat, nomor kontak sarana, foto fasilitas, dan **komponen peta koordinat wajib**.
* **Prosedur utama:**
  1. Menekan tombol *Tambah Fasilitas*.
  2. Memilih kategori sarana dari menu dropdown.
  3. Menuliskan nama dan deskripsi fasilitas.
  4. Menentukan titik koordinat menggunakan klik peta, Smart Input, atau GPS.
  5. Mengunggah foto dokumentasi fasilitas.
  6. Menekan tombol *Simpan Fasilitas*.
* **Screenshot:** `AD-005` — `15_ad_fasilitas_form_required_map.png` (MUST)
* **Flowchart:** `FLOW-07` — Alur Penentuan Titik Koordinat (Smart Input & GPS).
* **Tabel:** Tabel Isian Form Fasilitas Umum (`| Isian | Wajib? | Cara Mengisi | Contoh |`).
* **Callout:** `PENTING` — Berbeda dengan UMKM, pengisian titik koordinat pada fasilitas umum bersifat **WAJIB**. Formulir tidak dapat disimpan jika titik peta belum ditentukan.
* **Estimasi halaman A5:** 2,0 Halaman.
* **Catatan penulisan:** Jelaskan penggunaan Smart Input untuk menempel tautan Google Maps.

#### 3.6 Mempublikasikan Agenda Kegiatan Dusun
* **Target pembaca:** Admin Dusun.
* **Tujuan:** Memandu publikasi jadwal kegiatan dusun, pengaturan waktu, dan pengunggahan media poster atau foto dokumentasi.
* **Dasar baseline:** Bagian 6, Bagian 8.6, & Bagian 10.
* **Materi yang dibahas:** Form agenda, judul kegiatan, tanggal mulai, tanggal selesai, waktu/jam pelaksanaan (teks tampilan), lokasi pelaksanaan, deskripsi acara, pemilihan tipe media (*Poster Awal* vs *Dokumentasi*), dan status manual *override*.
* **Prosedur utama:** Mengisi tanggal dan lokasi acara, memilih berkas poster pengumuman, menetapkan peran media sebagai Poster Awal, dan menerbitkan agenda.
* **Screenshot:** `AD-006` — `16_ad_agenda_form_media.png` (MUST)
* **Flowchart:** `FLOW-05` — Siklus Status Waktu Agenda Kegiatan (REUSABLE).
* **Tabel:** Tabel Isian Form Agenda Kegiatan (`| Isian | Wajib? | Cara Mengisi | Contoh |`).
* **Callout:** `TIPS` — Setelah acara selesai dilaksanakan, Admin Dusun dapat mengedit agenda tersebut untuk mengunggah foto dokumentasi kegiatan lampau.
* **Estimasi halaman A5:** 1,5 Halaman.
* **Catatan penulisan:** Jelaskan bahwa status waktu agenda dihitung secara otomatis berdasarkan tanggal pelaksanaan kegiatan, serta agenda yang dibuat Admin Dusun otomatis memiliki cakupan wilayah dusunnya.

#### 3.7 Menerbitkan Pengumuman Resmi Dusun
* **Target pembaca:** Admin Dusun.
* **Tujuan:** Memandu penerbitan maklumat resmi warga dusun dan pengaturan batas masa berlaku warta.
* **Dasar baseline:** Bagian 6, Bagian 8.5, & Bagian 10.
* **Materi yang dibahas:** Form pengumuman, judul maklumat, textarea isi berita pengumuman, dan penetapan tanggal kedaluwarsa (*masa aktif warta*).
* **Prosedur utama:** Menulis judul dan isi pengumuman, memilih tanggal batas aktif warta pada kalender, dan menekan tombol *Terbitkan Pengumuman*.
* **Screenshot:** `AD-007` — `17_ad_pengumuman_form.png` (MUST)
* **Flowchart:** `FLOW-06` — Siklus Pengumuman Masuk Arsip (REUSABLE).
* **Tabel:** Tabel Isian Form Pengumuman Dusun (`| Isian | Wajib? | Cara Mengisi | Contoh |`).
* **Callout:** `PENTING` — Tetapkan tanggal kedaluwarsa secara realistis (misalnya batas akhir pembayaran PBB atau hari pelaksanaan kerja bakti). Setelah tanggal tersebut lewat, warta akan otomatis berpindah ke halaman Arsip.
* **Estimasi halaman A5:** 1,5 Halaman.
* **Catatan penulisan:** Tekankan penulisan warta yang padat dan jelas bagi warga. Form pengumuman memuat isian teks judul, isi pengumuman, dan tanggal kedaluwarsa.

#### 3.8 Prosedur Menonaktifkan Data (Soft Delete)
* **Target pembaca:** Admin Dusun.
* **Tujuan:** Menjelaskan prosedur menonaktifkan data kontak, UMKM, fasilitas, agenda, atau pengumuman yang sudah tidak berlaku.
* **Dasar baseline:** Bagian 6 & Bagian 8.1.
* **Materi yang dibahas:** Tombol *Hapus / Nonaktifkan* pada tabel data, konfirmasi dialog penonaktifan, dan dampak langsung terhadap tampilan publik.
* **Prosedur utama:** Membuka tabel data modul, menekan tombol *Hapus* pada baris yang dipilih, dan mengonfirmasi tindakan.
* **Screenshot:** Tidak ada.
* **Flowchart:** `FLOW-04` — Siklus Hidup Data (Soft Delete vs Restore vs Hapus Permanen).
* **Tabel:** Tidak ada.
* **Callout:** `PERHATIAN` — Data yang dinonaktifkan oleh Admin Dusun akan langsung hilang dari tampilan publik. Jika terjadi kesalahan hapus, Admin Dusun harus menghubungi Super Admin untuk memulihkannya.
* **Estimasi halaman A5:** 1,0 Halaman.
* **Catatan penulisan:** Jelaskan konsep *Soft Delete* dengan bahasa sederhana tanpa menyebut istilah database.

#### 3.9 Batas Hak Akses dan Kewenangan Admin Dusun
* **Target pembaca:** Admin Dusun.
* **Tujuan:** Menegaskan batasan kewenangan agar Admin Dusun memahami batas operasionalnya.
* **Dasar baseline:** Bagian 6 (Final Admin Dusun Capabilities).
* **Materi yang dibahas:** Isolasi data hanya pada dusun penugasan, larangan akses data dusun lain, ketiadaan tombol Restore/Hard Delete, dan penjelasan mengenai status wilayah dusun nonaktif (`INACTIVE`).
* **Prosedur utama:** Menghubungi Super Admin jika membutuhkan tindakan di luar kewenangan.
* **Screenshot:** Tidak ada.
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Matriks Apa yang Boleh dan Tidak Boleh Dilakukan Admin Dusun.
* **Callout:** `CATATAN` — Jika status dusun dinonaktifkan oleh Super Admin, Admin Dusun tetap dapat login dan mengelola data di dashboard, namun halaman publik dusun tersebut disembunyikan sementara dari masyarakat.
* **Estimasi halaman A5:** 1,0 Halaman.
* **Catatan penulisan:** Tuliskan dengan nada panduan kerja yang tertib dan kooperatif.

---

### BAB IV — PANDUAN TATA KELOLA UNTUK SUPER ADMIN

#### 4.1 Prosedur Masuk dan Dashboard Tata Kelola Global
* **Target pembaca:** Super Admin (Pemerintah Desa / Administrator Utama).
* **Tujuan:** Memandu login Super Admin dan pengenalan pusat kendali 10 modul agregat seluruh desa.
* **Dasar baseline:** Bagian 5 & Bagian 7.
* **Materi yang dibahas:** Login Super Admin, pengalihan otomatis ke `/super-admin/dashboard`, 10 menu navigasi bilah samping (*sidebar*), rekapitulasi data total se-Desa Bendung, dan ringkasan kondisi 6 dusun.
* **Prosedur utama:** Melakukan login dan memantau ringkasan statistik kesehatan data desa.
* **Screenshot:** 
  - `AUTH-001` — `09_auth_login_form.png` (REUSABLE)
  - `SA-001` — `18_sa_dashboard_global.png` (MUST)
* **Flowchart:** `FLOW-08` — Alur Operasional Super Admin.
* **Tabel:** Tabel 10 Menu Tata Kelola Super Admin dan Fungsinya.
* **Callout:** `PENTING` — Super Admin memegang kendali tertinggi atas keterbukaan informasi dan integritas seluruh data desa.
* **Estimasi halaman A5:** 2,0 Halaman.
* **Catatan penulisan:** Sajikan fungsi setiap menu sidebar secara sistematis.

#### 4.2 Mengelola Identitas dan Profil Resmi Desa
* **Target pembaca:** Super Admin.
* **Tujuan:** Memandu pembaruan data profil resmi Pemerintah Desa Bendung yang tampil di beranda utama.
* **Dasar baseline:** Bagian 7 (Super Admin Capabilities).
* **Materi yang dibahas:** Form identitas desa (`/super-admin/desa`), pengisian Nama Desa, Nama Kepala Desa (Lurah), Nomor Kontak Resmi Balai Desa, Jam Pelayanan Kantor, Alamat Kantor Balai Desa, Deskripsi / Selayang Pandang Desa, dan unggah Foto Banner Utama Desa.
* **Prosedur utama:** Membuka menu *Identitas Desa*, memperbarui isian data resmi, mengunggah banner baru jika diperlukan, dan menekan tombol *Simpan Identitas Desa*.
* **Screenshot:** `SA-002` — `19_sa_identitas_desa_form.png` (MUST)
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Isian Form Identitas Desa (`| Isian | Wajib? | Cara Mengisi | Contoh |`).
* **Callout:** `PENTING` — Nomor kontak dan jam pelayanan balai desa yang diisi di form ini akan tampil di bagian footer beranda utama untuk memandu warga yang hendak mengurus administrasi ke balai desa.
* **Estimasi halaman A5:** 1,5 Halaman.
* **Catatan penulisan:** Pastikan field yang dibahas hanya field aktual (tidak ada field Email/Logo pada form).

#### 4.3 Mengelola Status dan Narasi Profil 6 Dusun
* **Target pembaca:** Super Admin.
* **Tujuan:** Memandu pengawasan master 6 dusun, pembaruan profil wilayah, dan pengaturan status aktif/nonaktif dusun.
* **Dasar baseline:** Bagian 7 & Bagian 8.4.
* **Materi yang dibahas:** Tabel master 6 dusun tetap (Bendung, Klubuk, Bantengan, Belik, Pohsengir, Kaliasin), tombol Edit profil dusun, dan tombol toggle status *Aktifkan / Nonaktifkan* (`ACTIVE` vs `INACTIVE`).
* **Prosedur utama:** Membuka menu *Kelola Dusun*, mengedit narasi jika diperlukan, atau menekan tombol toggle status untuk menyembunyikan/menampilkan halaman publik dusun.
* **Screenshot:** `SA-003` — `20_sa_kelola_dusun_list_status.png` (MUST)
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Status Wilayah Dusun dan Implikasinya.
* **Callout:** `PERHATIAN` — Menonaktifkan dusun (`INACTIVE`) akan menyembunyikan kartu dusun dari beranda utama dan menghasilkan halaman *Tidak Ditemukan (404)* bagi publik, namun data internal dusun tetap aman dan dapat diakses oleh Admin Dusun terkait.
* **Estimasi halaman A5:** 1,5 Halaman.
* **Catatan penulisan:** Tekankan bahwa status nonaktif digunakan jika suatu dusun sedang dalam masa transisi data besar.

#### 4.4 Mengelola Data Kontak, UMKM, dan Fasilitas Lintas Dusun
* **Target pembaca:** Super Admin.
* **Tujuan:** Memandu pengelolaan data operasional se-desa dengan kemampuan memilih dusun target dan memfilter data lintas wilayah.
* **Dasar baseline:** Bagian 7 (Super Admin Capabilities).
* **Materi yang dibahas:** Form penambahan data lintas dusun (memiliki dropdown *Pilih Dusun*), toolbar filter wilayah dusun, dan filter status data (Aktif vs Terhapus).
* **Prosedur utama:** Menambah data atas nama dusun tertentu dan menyaring data berdasarkan dusun untuk keperluan monitoring berkala.
* **Screenshot:** `SA-006` — `23_sa_filter_lintas_dusun_restore.png` (MUST, Bagian Filter Dusun)
* **Flowchart:** Tidak ada.
* **Tabel:** Tidak ada.
* **Callout:** `TIPS` — Super Admin dapat membantu menginputkan data fasilitas atau UMKM untuk dusun yang adminnya berhalangan dengan memilih dusun tujuan pada form input.
* **Estimasi halaman A5:** 1,5 Halaman.
* **Catatan penulisan:** Tunjukkan fleksibilitas Super Admin dalam membantu operasional dusun.

#### 4.5 Mengelola Master Kategori Fasilitas Desa
* **Target pembaca:** Super Admin.
* **Tujuan:** Memandu penambahan, penyuntingan, dan penghapusan master kategori fasilitas umum serta memahami proteksi relasi data.
* **Dasar baseline:** Bagian 7 (Super Admin Capabilities).
* **Materi yang dibahas:** Halaman master kategori fasilitas (`/super-admin/kategori-fasilitas`), daftar kategori (Sarana Ibadah, Pendidikan, Kesehatan, Olahraga, Fasilitas Umum), form tambah kategori baru, dan **proteksi otomatis penghapusan kategori**.
* **Prosedur utama:** Menambah kategori baru dan mengedit nama kategori yang sudah ada.
* **Screenshot:** `SA-004` — `21_sa_kategori_fasilitas_crud.png` (SHOULD)
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Daftar Standar Kategori Fasilitas Desa Bendung.
* **Callout:** `PENTING` — Sistem secara otomatis menolak penghapusan kategori jika kategori tersebut masih digunakan oleh data fasilitas umum aktif di lapangan. Hapus atau pindahkan relasi fasilitas terlebih dahulu sebelum menghapus kategori.
* **Estimasi halaman A5:** 1,0 Halaman.
* **Catatan penulisan:** Jelaskan proteksi integritas data dengan kalimat sederhana.

#### 4.6 Mempublikasikan Agenda dan Pengumuman Tingkat Desa
* **Target pembaca:** Super Admin.
* **Tujuan:** Memandu penerbitan warta dengan cakupan tingkat desa (global) vs tingkat dusun tertentu.
* **Dasar baseline:** Bagian 7 (Super Admin Capabilities).
* **Materi yang dibahas:** Dropdown selektor cakupan wilayah (*Scope Level*), pilihan **Tingkat Desa (`DESA`)** yang otomatis tampil di beranda desa, dan pilihan **Tingkat Dusun (`DUSUN`)** yang ditujukan ke dusun spesifik.
* **Prosedur utama:** Membuat agenda/pengumuman, memilih opsi cakupan wilayah pada formulir, dan mempublikasikannya.
* **Screenshot:** `SA-005` — `22_sa_scope_wilayah_selector.png` (MUST)
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Perbedaan Warta Scope DESA vs Scope DUSUN.
* **Callout:** `TIPS` — Gunakan cakupan *Tingkat Desa* untuk kegiatan massal seperti HUT RI desa atau pengumuman pelayanan balai desa; gunakan cakupan *Tingkat Dusun* untuk warta yang hanya relevan bagi warga dusun tertentu.
* **Estimasi halaman A5:** 1,5 Halaman.
* **Catatan penulisan:** Berikan contoh kasus nyata untuk mempermudah pemilihan scope.

#### 4.7 Monitoring Sebaran Titik Spasial Desa (Data / Peta)
* **Target pembaca:** Super Admin.
* **Tujuan:** Memandu pemanfaatan halaman agregat spasial untuk memantau sebaran fasilitas dan potensi UMKM di seluruh Desa Bendung.
* **Dasar baseline:** Bagian 7 & Bagian 9.
* **Materi yang dibahas:** Halaman Data / Peta (`/super-admin/data-peta`), tampilan atlas desa layar penuh, filter dusun terpadu, filter layer titik, dan evaluasi pemerataan sarana publik.
* **Prosedur utama:** Membuka menu *Data / Peta*, menyaring titik berdasarkan dusun/kategori, dan mengevaluasi kelengkapan data spasial.
* **Screenshot:** `SA-007` — `24_sa_data_peta_overview.png` (SHOULD)
* **Flowchart:** Tidak ada.
* **Tabel:** Tidak ada.
* **Callout:** `CATATAN` — Halaman Data / Peta adalah proyeksi visual agregat untuk monitoring wilayah, bukan tempat untuk menambah titik secara mandiri. Penambahan titik tetap dilakukan melalui modul Fasilitas, UMKM, atau Kontak.
* **Estimasi halaman A5:** 1,0 Halaman.
* **Catatan penulisan:** Jelaskan fungsi halaman ini sebagai alat bantu perencanaan pembangunan desa.

#### 4.8 Mengelola Akun Pengelola Dusun (Admin Dusun)
* **Target pembaca:** Super Admin.
* **Tujuan:** Memandu pendaftaran akun Admin Dusun baru, perubahan penugasan wilayah, dan penonaktifan akun pengelola.
* **Dasar baseline:** Bagian 7 (Super Admin Capabilities).
* **Materi yang dibahas:** Menu *Manajemen Admin Dusun* (`/super-admin/admin-dusun`), form pembuatan akun (username, password awal, pilihan dusun penugasan), tombol *Ubah Penugasan*, dan tombol *Remove Akun* (penonaktifan akun via `removed_at`).
* **Prosedur utama:** Mendaftarkan akun baru untuk pamong/kadus baru, mengubah wilayah penugasan jika terjadi pergantian tugas, dan mencabut hak akses akun yang sudah tidak aktif.
* **Screenshot:** `SA-008` — `25_sa_admin_dusun_management.png` (MUST)
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Isian Form Tambah Admin Dusun.
* **Callout:** `PERHATIAN` — Menekan tombol *Remove Akun* akan mencabut hak akses akun tersebut secara instan sehingga tidak dapat login kembali ke portal.
* **Estimasi halaman A5:** 1,5 Halaman.
* **Catatan penulisan:** Tekankan kerahasiaan username dan password awal saat diserahkan ke Admin Dusun.

#### 4.9 Prosedur Reset Password Akun Admin Dusun
* **Target pembaca:** Super Admin.
* **Tujuan:** Memberikan panduan resmi penanganan akun Admin Dusun yang lupa kata sandi.
* **Dasar baseline:** Bagian 5 & Bagian 7.
* **Materi yang dibahas:** Prosedur verifikasi identitas pemohon, tombol *Reset Password* pada tabel akun admin dusun, input password baru, dan penyerahan kata sandi baru secara aman.
* **Prosedur utama:**
  1. Menerima laporan lupa password dari Kepala Dusun terkait.
  2. Membuka menu *Manajemen Admin Dusun*.
  3. Menekan tombol *Reset Password* pada baris akun yang bersangkutan.
  4. Memasukkan kata sandi sementara baru yang kuat.
  5. Menekan *Simpan Password*.
  6. Menyerahkan kata sandi baru secara langsung atau melalui saluran pesan pribadi aman.
* **Screenshot:** `SA-008` — `25_sa_admin_dusun_management.png` (REUSABLE, Tombol Reset Password)
* **Flowchart:** Tidak ada.
* **Tabel:** Tidak ada.
* **Callout:** `PENTING` — Sistem tidak menyediakan fitur lupa password mandiri via email. Super Admin adalah satu-satunya otoritas yang dapat mereset kata sandi Admin Dusun.
* **Estimasi halaman A5:** 1,0 Halaman.
* **Catatan penulisan:** Ingatkan Super Admin untuk tidak membagikan password di grup umum.

#### 4.10 Memulihkan Data Terhapus (Restore)
* **Target pembaca:** Super Admin.
* **Tujuan:** Memandu pemulihan data operasional (kontak, UMKM, fasilitas, agenda, pengumuman) yang tidak sengaja dinonaktifkan oleh Admin Dusun.
* **Dasar baseline:** Bagian 7 & Bagian 8.2.
* **Materi yang dibahas:** Filter status *Soft Deleted / Terhapus* pada tabel data Super Admin, penelusuran data yang berada di tong sampah, dan tombol *Pulihkan (Restore)*.
* **Prosedur utama:**
  1. Membuka modul data yang bersangkutan (misal: *Kelola Fasilitas*).
  2. Mengubah filter status menjadi *Data Terhapus / Trashed*.
  3. Mencari baris data yang hendak dipulihkan.
  4. Menekan tombol *Pulihkan*.
  5. Data kembali dari status terhapus dan dapat tampil kembali sesuai aturan visibilitasnya, seperti status Dusun, scope wilayah, dan masa berlaku data.
* **Screenshot:** `SA-006` — `23_sa_filter_lintas_dusun_restore.png` (MUST, Tombol Pulihkan)
* **Flowchart:** `FLOW-04` — Siklus Hidup Data (Restore Path, REUSABLE).
* **Tabel:** Tidak ada.
* **Callout:** `TIPS` — Saat data dipulihkan, seluruh informasi dan titik koordinat yang melekat pada data tersebut akan kembali aktif dalam pengelolaan sistem.
* **Estimasi halaman A5:** 1,0 Halaman.
* **Catatan penulisan:** Sajikan langkah pemulihan dengan jelas untuk mengatasi insiden data terhapus.

#### 4.11 Menghapus Data Secara Permanen (Hard Delete)
* **Target pembaca:** Super Admin.
* **Tujuan:** Memandu pembersihan data sampah yang benar-benar tidak terpakai secara permanen dari sistem.
* **Dasar baseline:** Bagian 7 & Bagian 8.3.
* **Materi yang dibahas:** Tombol *Hapus Permanen (Force Delete)* pada filter data terhapus, dialog konfirmasi akhir, dan penghapusan data mutlak dari sistem.
* **Prosedur utama:**
  1. Membuka filter *Data Terhapus*.
  2. Menekan tombol merah *Hapus Permanen*.
  3. Membaca pesan peringatan konfirmasi.
  4. Mengonfirmasi penghapusan mutlak.
* **Screenshot:** `SA-006` — `23_sa_filter_lintas_dusun_restore.png` (REUSABLE, Tombol Hapus Permanen)
* **Flowchart:** `FLOW-04` — Siklus Hidup Data (Hard Delete Path, REUSABLE).
* **Tabel:** Tidak ada.
* **Callout:** `PERHATIAN` — Tindakan *Hapus Permanen* bersifat mutlak dan tidak dapat dibatalkan. Data yang dihapus permanen akan hilang dari pengelolaan sistem dan tidak dapat dipulihkan kembali melalui antarmuka pengguna.
* **Estimasi halaman A5:** 1,0 Halaman.
* **Catatan penulisan:** Berikan peringatan visual yang kuat agar Super Admin berhati-hati sebelum menekan tombol ini.

#### 4.12 Batasan Struktural Terhadap Master 6 Dusun
* **Target pembaca:** Super Admin.
* **Tujuan:** Menjelaskan invarian struktur wilayah desa pada implementasi saat ini.
* **Dasar baseline:** Bagian 7 (Batasan Tegas Super Admin) & Bagian 13.
* **Materi yang dibahas:** Struktur 6 dusun tetap (Bendung, Klubuk, Bantengan, Belik, Pohsengir, Kaliasin), tidak tersedianya fitur tambah dusun baru (*no create dusun*), dan tidak tersedianya fitur hapus dusun permanen (*no hard delete dusun*).
* **Prosedur utama:** Memahami tata kelola wilayah statis desa.
* **Screenshot:** Tidak ada.
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel 6 Wilayah Dusun Statis Desa Bendung.
* **Callout:** `CATATAN` — Pada implementasi saat ini, sistem menggunakan enam dusun tetap dan tidak menyediakan fitur tambah atau hapus permanen Dusun melalui antarmuka.
* **Estimasi halaman A5:** 0,5 Halaman.
* **Catatan penulisan:** Jelaskan batasan arsitektur ini secara ringkas dan lugas sesuai kondisi as-built.

---

### BAB V — PEDOMAN STANDAR PENGELOLAAN INFORMASI

#### 5.1 Prinsip Akurasi dan Validitas Data Desa
* **Target pembaca:** Admin Dusun dan Super Admin.
* **Tujuan:** Menetapkan standar kebenaran, kejujuran, dan pembaruan informasi yang dipublikasikan ke masyarakat luas.
* **Dasar baseline:** Bagian 2 & Bagian 12.
* **Materi yang dibahas:** Verifikasi kebenaran nama pejabat/kadus, kepastian jam buka usaha UMKM, pengecekan jadwal kegiatan sebelum diposting, dan dampak informasi keliru terhadap warga.
* **Prosedur utama:** Melakukan verifikasi data lapangan sebelum menekan tombol simpan.
* **Screenshot:** Tidak ada.
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Rekomendasi Pengelolaan: Prinsip 5T dalam Pemeliharaan Data Desa (Tepat, Teliti, Terbaru, Terpercaya, Tertib).
* **Callout:** `PENTING` — Seluruh data yang dipublikasikan mencerminkan nama baik dan profesionalisme Pemerintah Desa Bendung.
* **Estimasi halaman A5:** 1,0 Halaman.
* **Catatan penulisan:** Gunakan bahasa instruksional yang membangun komitmen pengelola sebagai panduan tata kelola editorial.

#### 5.2 Panduan Teknis Penentuan Titik Koordinat Peta
* **Target pembaca:** Admin Dusun dan Super Admin.
* **Tujuan:** Memberikan panduan praktis 4 metode penentuan titik koordinat menggunakan fitur Smart Input, GPS, klik peta, dan hapus titik.
* **Dasar baseline:** Bagian 9 (Final Map & Coordinate Rules).
* **Materi yang dibahas:** Format koordinat desimal, format derajat/DMS, cara menyalin tautan Google Maps dari ponsel, tombol *Gunakan GPS*, dan tombol *Hapus Titik*.
* **Prosedur utama:**
  1. *Metode Salin Link Google Maps:* Buka Google Maps di HP -> Pilih lokasi -> Salin Tautan (*Copy Link*) -> Tempel di kotak Smart Input -> Tekan *Terapkan*.
  2. *Metode GPS di Lapangan:* Buka portal di HP saat berada di lokasi fisik -> Tekan tombol *Gunakan GPS* -> Izinkan akses lokasi -> Pin otomatis berpindah.
  3. *Metode Klik Peta:* Menggeser pin langsung di atas canvas peta ke titik yang tepat.
* **Screenshot:** `MAP-001` — `26_map_smart_input_gps.png` (MUST)
* **Flowchart:** `FLOW-07` — Alur Penentuan Titik Koordinat (REUSABLE).
* **Tabel:** Tabel Contoh Format Koordinat yang Dikenali Fitur Smart Input.
* **Callout:**
  - `TIPS` — Cara paling mudah bagi Admin Dusun adalah menyalin link lokasi dari Google Maps di WhatsApp lalu menempelkannya ke kotak Smart Input.
  - `PENTING` — Pastikan posisi pin telah dicek kembali pada peta sebelum data disimpan.
* **Estimasi halaman A5:** 1,5 Halaman.
* **Catatan penulisan:** Tuliskan panduan ini secara sangat praktis agar mudah dipraktikkan orang awam.

#### 5.3 Standar Format Foto dan Media Publikasi
* **Target pembaca:** Admin Dusun dan Super Admin.
* **Tujuan:** Menjelaskan spesifikasi foto yang dapat diunggah agar halaman web tetap cepat dimuat dan foto tampil proporsional.
* **Dasar baseline:** Bagian 10 (Final Media Rules).
* **Materi yang dibahas:** Format file yang didukung (`JPG`, `JPEG`, `PNG`, `WebP`), batasan ukuran maksimal **3 MB (3072 KB)**, rasio foto lanskap untuk banner, dan tips kompresi foto sederhana.
* **Prosedur utama:** Mengecek ukuran file foto di galeri/komputer sebelum diunggah ke formulir.
* **Screenshot:** Tidak ada.
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Standar Ukuran dan Rasio Foto per Modul.
* **Callout:** `PERHATIAN` — Foto dengan ukuran di atas 3 MB akan ditolak otomatis oleh sistem. Gunakan aplikasi kompres foto atau kirim foto ke WhatsApp terlebih dahulu untuk mengecilkan ukurannya secara otomatis.
* **Estimasi halaman A5:** 1,0 Halaman.
* **Catatan penulisan:** Berikan solusi praktis mengatasi foto berukuran besar dari kamera ponsel resolusi tinggi.

#### 5.4 Standar Penulisan Nomor WhatsApp dan Kontak Resmi
* **Target pembaca:** Admin Dusun dan Super Admin.
* **Tujuan:** Menstandarisasi penulisan nomor kontak agar tombol WhatsApp berfungsi dengan sempurna saat diklik warga.
* **Dasar baseline:** Bagian 4, Bagian 6, & Bagian 11.
* **Materi yang dibahas:** Standar penulisan nomor Indonesia (contoh: `081234567890` atau `6281234567890`), larangan penggunaan karakter khusus yang merusak tautan (seperti tanda kurung atau spasi di tengah), dan penulisan nama jabatan yang formal.
* **Prosedur utama:** Menuliskan nomor kontak pada formulir sesuai standar.
* **Screenshot:** Tidak ada.
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Contoh Penulisan Nomor Kontak yang Benar vs Salah.
* **Callout:** `PENTING` — Sistem secara otomatis memformat nomor menjadi tautan resmi `wa.me`. Cukup masukkan angka nomor telepon yang valid.
* **Estimasi halaman A5:** 0,5 Halaman.
* **Catatan penulisan:** Berikan contoh perbandingan yang jelas.

#### 5.5 Perlindungan Privasi dan Prosedur Izin Publikasi Data Warga
* **Target pembaca:** Admin Dusun dan Super Admin.
* **Tujuan:** Mengingatkan pengelola mengenai etika privasi dan keharusan mengantongi izin warga sebelum mempublikasikan data ke internet.
* **Dasar baseline:** Bagian 12 (Final Privacy & Publication Rules).
* **Materi yang dibahas:** Kewajiban memastikan izin publikasi administratif/offline dari warga sebelum mempublikasikan data pribadi, perlindungan nomor telepon pribadi, kehati-hatian mempublikasikan foto wajah perorangan, dan penanganan permintaan pencabutan data oleh warga.
* **Prosedur utama:** Meminta izin kepada pemilik usaha/warga sebelum mendaftarkan data ke portal desa.
* **Screenshot:** Tidak ada.
* **Flowchart:** Tidak ada.
* **Tabel:** Tidak ada.
* **Callout:** `PERHATIAN` — Portal tidak menggunakan checkbox persetujuan digital di formulir. Tanggung jawab izin publikasi data warga berada pada pengelola yang menginput data.
* **Estimasi halaman A5:** 1,0 Halaman.
* **Catatan penulisan:** Tuliskan dengan nada etika hukum dan tanggung jawab sosial.

#### 5.6 Jadwal dan Siklus Pembaruan Data Berkala
* **Target pembaca:** Admin Dusun dan Super Admin.
* **Tujuan:** Memandu rutinitas pemeliharaan data agar informasi di portal tidak usang.
* **Dasar baseline:** Bagian 6 & Bagian 7.
* **Materi yang dibahas:** Rekomendasi rutinitas mingguan (memeriksa agenda dan pengumuman), bulanan (memperbarui kontak pamong dan etalase UMKM baru), dan evaluasi berkala bersama Pemerintah Desa.
* **Prosedur utama:** Menjalankan checklist pembaruan rutin.
* **Screenshot:** Tidak ada.
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Rekomendasi Pengelolaan: Siklus Pembaruan Data Rutin.
* **Callout:** `TIPS` — Tetapkan satu hari khusus dalam seminggu untuk meninjau dan memperbarui warta dusun.
* **Estimasi halaman A5:** 0,5 Halaman.
* **Catatan penulisan:** Dorong terciptanya kebiasaan kerja yang teratur di tingkat dusun. Materi ini merupakan rekomendasi operasional bagi pengelola, bukan batasan otomatis oleh sistem web.

#### 5.7 Memahami Perbedaan Data Aktif, Data Nonaktif, dan Arsip
* **Target pembaca:** Admin Dusun dan Super Admin.
* **Tujuan:** Mengeliminasi kerancuan antara data aktif, data yang dinonaktifkan (*soft deleted*), dan pengumuman kedaluwarsa (*arsip*).
* **Dasar baseline:** Bagian 8 (Final Data Lifecycle Rules).
* **Materi yang dibahas:** Perbedaan status, lokasi keterlihatan publik, hak akses pemulihan, dan tujuan penyimpanan masing-masing status.
* **Prosedur utama:** Memilih status yang tepat saat mengelola data.
* **Screenshot:** Tidak ada.
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Matriks Perbandingan Data Aktif vs Data Nonaktif (Trashed) vs Pengumuman Arsip.
* **Callout:** `CATATAN` — Pengumuman arsip tetap tampil di publik pada menu khusus arsip, sedangkan data soft delete disembunyikan sepenuhnya dari publik.
* **Estimasi halaman A5:** 0,5 Halaman.
* **Catatan penulisan:** Sajikan dalam bentuk tabel komparasi yang sangat jelas.

---

### BAB VI — PANDUAN PENYELESAIAN MASALAH (TROUBLESHOOTING & FAQ)

#### 6.1 Kendala Masuk Sistem (Gagal Login dan Akun Dinonaktifkan)
* **Target pembaca:** Admin Dusun dan Super Admin.
* **Tujuan:** Menyelesaikan masalah gagal login akibat salah sandi atau status akun dinonaktifkan.
* **Dasar baseline:** Bagian 5 & Bagian 6.
* **Materi yang dibahas:** Pesan error *Kredensial tidak cocok*, penggunaan fitur toggle lihat sandi, dan penanganan akun yang telah dinonaktifkan (`removed_at`).
* **Prosedur utama:** Memeriksa tombol lihat sandi (*toggle mata*), memastikan huruf besar/kecil benar, dan melapor ke Super Admin jika akun dinonaktifkan.
* **Screenshot:** Tidak ada.
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Diagnosa Masalah Login dan Solusinya.
* **Callout:** `TIPS` — Gunakan tombol ikon mata pada kolom password untuk memastikan tidak ada huruf besar/kecil yang salah ketik.
* **Estimasi halaman A5:** 0,5 Halaman.
* **Catatan penulisan:** Sajikan dalam format Problem -> Cause -> Solution.

#### 6.2 Prosedur Penanganan Lupa Password Admin Dusun
* **Target pembaca:** Admin Dusun.
* **Tujuan:** Memberikan alur resmi bagi Kepala Dusun yang lupa kata sandi akunnya.
* **Dasar baseline:** Bagian 5 & Bagian 7.
* **Materi yang dibahas:** Tidak adanya form reset email mandiri, keharusan melapor langsung ke Super Admin di Balai Desa, dan proses penerimaan kata sandi baru.
* **Prosedur utama:** Menghubungi Super Admin, memverifikasi identitas, dan menerima kata sandi reset.
* **Screenshot:** Tidak ada.
* **Flowchart:** Tidak ada.
* **Tabel:** Tidak ada.
* **Callout:** `CATATAN` — Karena portal tidak menyediakan tombol reset password otomatis, koordinasi langsung ke Super Admin di Balai Desa merupakan jalur resmi pemulihan akses akun Admin Dusun.
* **Estimasi halaman A5:** 0,5 Halaman.
* **Catatan penulisan:** Tekankan koordinasi langsung antar-perangkat desa.

#### 6.3 Mengatasi Kegagalan Unggah Foto dan Banner
* **Target pembaca:** Admin Dusun dan Super Admin.
* **Tujuan:** Menyelesaikan kendala formulir ditolak karena ukuran file foto melebihi batas atau format tidak didukung.
* **Dasar baseline:** Bagian 10 (Final Media Rules).
* **Materi yang dibahas:** Tampilan error validasi form, batas 3 MB, format yang diizinkan (JPG, PNG, WebP), dan cara mengecilkan ukuran gambar.
* **Prosedur utama:** Memeriksa pesan error di bawah kotak upload, mengompres foto, dan mengunggah ulang.
* **Screenshot:** `TRB-001` — `28_trb_validation_error_example.png` (SHOULD)
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Pesan Error Upload Foto dan Cara Mengatasinya.
* **Callout:** `TIPS` — Kirim foto ke akun WhatsApp Anda sendiri, lalu unduh kembali. WhatsApp akan otomatis mengompres ukuran foto menjadi di bawah 1 MB tanpa merusak kualitas visual di web.
* **Estimasi halaman A5:** 1,0 Halaman.
* **Catatan penulisan:** Berikan solusi praktis yang langsung bisa dicoba tanpa aplikasi rumit.

#### 6.4 Titik Lokasi Peta Tidak Muncul atau Bergeser
* **Target pembaca:** Admin Dusun dan Super Admin.
* **Tujuan:** Mengatasi marker yang tidak muncul di peta atau titik lokasi yang melenceng ke luar wilayah desa.
* **Dasar baseline:** Bagian 9 (Final Map & Coordinate Rules).
* **Materi yang dibahas:** Pemeriksaan apakah koordinat sudah diisi (khusus UMKM/Kontak), verifikasi tanda positif/negatif pada koordinat lintang dan bujur, serta pengecekan visual posisi pin pada canvas peta picker.
* **Prosedur utama:** Membuka form edit data, menempelkan ulang link Google Maps di Smart Input, mengecek posisi pin di peta, dan menyimpan ulang data.
* **Screenshot:** `MAP-001` — `26_map_smart_input_gps.png` (REUSABLE)
* **Flowchart:** Tidak ada.
* **Tabel:** Tidak ada.
* **Callout:** `PENTING` — Pastikan nilai latitude dan longitude sesuai lokasi aktual dan tanda positif/negatif tidak diubah secara sembarangan. Gunakan fitur Smart Input atau tombol GPS untuk membantu pengisian koordinat secara tepat.
* **Estimasi halaman A5:** 0,5 Halaman.
* **Catatan penulisan:** Jelaskan verifikasi visual pin sebelum data disimpan.

#### 6.5 Tombol WhatsApp atau Petunjuk Arah Google Maps Tidak Berfungsi
* **Target pembaca:** Admin Dusun dan Super Admin.
* **Tujuan:** Mengatasi tombol aksi WhatsApp yang memunculkan pesan nomor tidak valid atau tombol rute yang membuka peta kosong.
* **Dasar baseline:** Bagian 4, Bagian 9, & Bagian 11.
* **Materi yang dibahas:** Pengecekan angka 0 atau 62 di awal nomor WhatsApp, pembersihan karakter non-angka, dan pengecekan pasangan koordinat lintang/bujur.
* **Prosedur utama:** Mengedit data kontak/UMKM, membetulkan nomor HP, dan menyimpan perubahan.
* **Screenshot:** Tidak ada.
* **Flowchart:** Tidak ada.
* **Tabel:** Tidak ada.
* **Callout:** `TIPS` — Uji coba tombol WhatsApp dan Petunjuk Arah dari halaman publik sesaat setelah Anda menyimpan data baru.
* **Estimasi halaman A5:** 0,5 Halaman.
* **Catatan penulisan:** Berikan instruksi pengecekan pasca-input.

#### 6.6 Data yang Disimpan Tidak Tampil di Halaman Publik
* **Target pembaca:** Admin Dusun dan Super Admin.
* **Tujuan:** Mendiagnosa mengapa data yang baru diinput belum terlihat oleh masyarakat di website.
* **Dasar baseline:** Bagian 6, Bagian 7, & Bagian 8.
* **Materi yang dibahas:** Pengecekan status wilayah dusun (apakah sedang `INACTIVE`), pengecekan tanggal kedaluwarsa pengumuman, dan pembersihan cache peramban (*refresh halaman*).
* **Prosedur utama:** Memeriksa status dusun di menu Super Admin dan menekan tombol refresh (F5 / tarik layar di HP).
* **Screenshot:** Tidak ada.
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Checklist Mengapa Data Tidak Tampil di Publik.
* **Callout:** `CATATAN` — Jika dusun Anda dinonaktifkan oleh Super Admin, seluruh data dusun tidak akan muncul di web publik hingga Super Admin mengaktifkannya kembali.
* **Estimasi halaman A5:** 0,5 Halaman.
* **Catatan penulisan:** Buat diagram alir penelusuran masalah dalam bentuk checklist sederhana.

#### 6.7 Mengapa Pengumuman Berpindah ke Halaman Arsip
* **Target pembaca:** Admin Dusun dan Warga.
* **Tujuan:** Menjelaskan mekanisme otomatis pemindahan warta kedaluwarsa ke arsip dan cara memperpanjang masa aktif jika warta masih berlaku.
* **Dasar baseline:** Bagian 8.5 (Siklus Pengumuman).
* **Materi yang dibahas:** Mekanisme otomatisasi tanggal kedaluwarsa, cara membuka halaman `/pengumuman/arsip`, dan cara mengedit tanggal kedaluwarsa ke masa depan agar pengumuman kembali tampil di beranda aktif.
* **Prosedur utama:** Membuka form edit pengumuman, memajukan tanggal kedaluwarsa, dan menyimpan warta.
* **Screenshot:** Tidak ada.
* **Flowchart:** Tidak ada.
* **Tabel:** Tidak ada.
* **Callout:** `TIPS` — Jika suatu pengumuman masih relevan (misalnya program bantuan yang diperpanjang), Admin Dusun cukup mengedit tanggal kedaluwarsanya ke tanggal baru.
* **Estimasi halaman A5:** 0,5 Halaman.
* **Catatan penulisan:** Jelaskan bahwa arsip adalah fitur otomatis yang menjaga beranda tetap segar.

#### 6.8 Menemukan Kembali Data yang Tidak Sengaja Dinonaktifkan
* **Target pembaca:** Admin Dusun.
* **Tujuan:** Memberikan prosedur darurat jika Admin Dusun salah menekan tombol hapus pada data penting.
* **Dasar baseline:** Bagian 6, Bagian 7, & Bagian 8.2.
* **Materi yang dibahas:** Penjelasan bahwa data soft delete tidak langsung hilang permanen, batas kewenangan Admin Dusun, dan cara meminta Super Admin melakukan pemulihan (*restore*).
* **Prosedur utama:** Mencatat nama data yang terhapus dan melaporkannya ke Super Admin untuk dipulihkan dari tong sampah sistem.
* **Screenshot:** Tidak ada.
* **Flowchart:** Tidak ada.
* **Tabel:** Format Pesan Permohonan Restore Data ke Super Admin.
* **Callout:** `CATATAN` — Data yang dinonaktifkan masih tersimpan di daftar data terhapus Super Admin dan dapat dipulihkan kembali jika diperlukan.
* **Estimasi halaman A5:** 0,5 Halaman.
* **Catatan penulisan:** Berikan rasa tenang kepada Admin Dusun bahwa data mereka aman.

#### 6.9 Daftar Pertanyaan Umum (FAQ)
* **Target pembaca:** Seluruh Pembaca.
* **Tujuan:** Merangkum 10 pertanyaan yang paling sering diajukan seputar penggunaan dan pengelolaan portal.
* **Dasar baseline:** Bagian 2 s.d. Bagian 13.
* **Materi yang dibahas:** 
  1. *Apakah warga harus membuat akun untuk melihat portal?* (Tidak, bebas akses).
  2. *Apakah bisa belanja dan bayar langsung di web?* (Tidak, via WhatsApp pemilik).
  3. *Mengapa UMKM saya tidak ada pinnya di peta?* (Karena koordinat dikosongkan).
  4. *Bagaimana jika lupa password admin?* (Hubungi Super Admin).
  5. *Apakah foto banner bisa diganti sewaktu-waktu?* (Bisa, kapan saja).
  6. *Mengapa pengumuman kemarin hilang dari beranda?* (Masuk ke halaman arsip).
  7. *Apakah bisa menambah dusun ke-7?* (Tidak, struktur 6 dusun tetap).
  8. *Berapa batas maksimal ukuran foto?* (Maksimal 3 MB).
  9. *Apakah data yang terhapus bisa kembali?* (Bisa, dipulihkan oleh Super Admin).
  10. *Apakah portal ini aman?* (Aman, kata sandi disimpan dengan enkripsi standar dan akses pengelola dilindungi sesi terotentikasi).
* **Prosedur utama:** Membaca daftar tanya-jawab ringkas.
* **Screenshot:** Tidak ada.
* **Flowchart:** Tidak ada.
* **Tabel:** Tidak ada.
* **Callout:** Tidak ada.
* **Estimasi halaman A5:** 1,5 Halaman.
* **Catatan penulisan:** Susun format Tanya (T) dan Jawab (J) yang lugas dan informatif.

#### 6.10 Saluran Bantuan dan Eskalasi ke Super Admin
* **Target pembaca:** Admin Dusun dan Pengelola Wilayah.
* **Tujuan:** Menetapkan kontak resmi sekretariat balai desa untuk koordinasi teknis portal.
* **Dasar baseline:** Bagian 1 & Bagian 7.
* **Materi yang dibahas:** Alamat Kantor Balai Desa Bendung, nomor kontak resmi administrator desa, jam pelayanan bantuan teknis, dan etika pelaporan kendala.
* **Prosedur utama:** Menghubungi pengelola balai desa saat menghadapi kendala tingkat lanjut.
* **Screenshot:** Tidak ada.
* **Flowchart:** Tidak ada.
* **Tabel:** Tabel Kontak Layanan Bantuan Teknis Desa Bendung.
* **Callout:** `PENTING` — Untuk kendala darurat yang memengaruhi pelayanan publik, segera hubungi Sekretariat Balai Desa Bendung.
* **Estimasi halaman A5:** 0,5 Halaman.
* **Catatan penulisan:** Tutup bab penanganan masalah dengan informasi kontak yang jelas.

---

## 7. Screenshot Mapping (Pemetaan 28 Tangkapan Layar)

Seluruh 28 item screenshot dari `docs/07-handover/01-planning/user-manual-screenshot-plan.md` telah dipetakan secara presisi ke subbab buku:

| ID Screenshot | Nama File Gambar | Prioritas | Penempatan Subbab Utama | Penggunaan Ulang (Reusable) |
|---|---|:---:|---|---|
| **PUB-001** | `01_pub_homepage_hero_desktop.png` | **MUST** | Subbab 2.2 — Mengenal Beranda Utama Desa | - |
| **PUB-002** | `02_pub_homepage_mobile.png` | **SHOULD** | Subbab 2.2 — Mengenal Beranda Utama Desa | - |
| **PUB-003** | `03_pub_dusun_page_overview.png` | **MUST** | Subbab 2.3 — Memilih & Menjelajahi Dusun | - |
| **PUB-004** | `04_pub_peta_interaktif_popup.png` | **MUST** | Subbab 2.4 — Menggunakan Peta Interaktif | - |
| **PUB-005** | `05_pub_kontak_pelayanan_wa.png` | **MUST** | Subbab 2.5 — Menghubungi Kontak Pelayanan | - |
| **PUB-006** | `06_pub_umkm_showcase_detail.png` | **MUST** | Subbab 2.6 — Menjelajahi Potensi UMKM | - |
| **PUB-007** | `07_pub_fasilitas_detail.png` | **SHOULD** | Subbab 2.7 — Menemukan Fasilitas Umum | - |
| **PUB-008** | `08_pub_agenda_pengumuman_terkini.png` | **MUST** | Subbab 2.8 & Subbab 2.9 — Agenda & Warta | Reusable di Subbab 2.9 |
| **AUTH-001** | `09_auth_login_form.png` | **MUST** | Subbab 3.1 — Prosedur Masuk Sistem | Reusable di Subbab 4.1 |
| **AUTH-002** | `10_auth_logout_button.png` | **SHOULD** | Subbab 3.1 — Prosedur Keluar & Keamanan | - |
| **AD-001** | `11_ad_dashboard.png` | **MUST** | Subbab 3.1 — Pengenalan Dashboard Dusun | - |
| **AD-002** | `12_ad_profil_dusun_form.png` | **MUST** | Subbab 3.2 — Mengelola Profil Dusun | - |
| **AD-003** | `13_ad_kontak_form.png` | **MUST** | Subbab 3.3 — Mengelola Kontak Pelayanan | - |
| **AD-004** | `14_ad_umkm_form_repeater.png` | **MUST** | Subbab 3.4 — Mendaftarkan Data UMKM | - |
| **AD-005** | `15_ad_fasilitas_form_required_map.png` | **MUST** | Subbab 3.5 — Mengelola Fasilitas Umum | - |
| **AD-006** | `16_ad_agenda_form_media.png` | **MUST** | Subbab 3.6 — Mempublikasikan Agenda | - |
| **AD-007** | `17_ad_pengumuman_form.png` | **MUST** | Subbab 3.7 — Menerbitkan Pengumuman | - |
| **SA-001** | `18_sa_dashboard_global.png` | **MUST** | Subbab 4.1 — Dashboard Super Admin | - |
| **SA-002** | `19_sa_identitas_desa_form.png` | **MUST** | Subbab 4.2 — Mengelola Identitas Desa | - |
| **SA-003** | `20_sa_kelola_dusun_list_status.png` | **MUST** | Subbab 4.3 — Status & Narasi 6 Dusun | - |
| **SA-004** | `21_sa_kategori_fasilitas_crud.png` | **SHOULD** | Subbab 4.5 — Master Kategori Fasilitas | - |
| **SA-005** | `22_sa_scope_wilayah_selector.png` | **MUST** | Subbab 4.6 — Warta Tingkat Desa & Dusun | - |
| **SA-006** | `23_sa_filter_lintas_dusun_restore.png` | **MUST** | Subbab 4.4 & Subbab 4.10 — Restore Data | Reusable di Subbab 4.11 |
| **SA-007** | `24_sa_data_peta_overview.png` | **SHOULD** | Subbab 4.7 — Monitoring Peta Spasial | - |
| **SA-008** | `25_sa_admin_dusun_management.png` | **MUST** | Subbab 4.8 & Subbab 4.9 — Akun Admin | Reusable di Subbab 4.9 |
| **MAP-001** | `26_map_smart_input_gps.png` | **MUST** | Subbab 5.2 — Penentuan Titik Koordinat | Reusable di Subbab 3.4 & 6.4 |
| **MAP-002** | `27_map_gmaps_directions_tab.png` | **OPTIONAL** | Subbab 2.4 — Rute Navigasi Google Maps | - |
| **TRB-001** | `28_trb_validation_error_example.png` | **SHOULD** | Subbab 6.3 — Mengatasi Gagal Upload Foto | - |

*Rekapitulasi Shot List:* **18 MUST**, **8 SHOULD**, **2 OPTIONAL**. Seluruh 18 item MUST dan 8 item SHOULD telah teralokasikan secara proporsional di seluruh bab panduan.

---

## 8. Flowchart Mapping (Pemetaan 8 Bagan Alur Kerja)

Bagan alur digunakan untuk menyederhanakan konsep prosedural yang kompleks tanpa membebani pembaca dengan tangkapan layar berlebih:

| ID Flowchart | Nama Alur Kerja | Penempatan Subbab | Deskripsi Visual yang Ditampilkan |
|---|---|---|---|
| **FLOW-01** | **Alur Akses Warga** | Subbab 2.1 | Scan QR / Buka Link -> Beranda Desa -> Pilih Dusun -> Informasi Layanan / Peta / WhatsApp Pamong & UMKM. |
| **FLOW-02** | **Alur Operasional Admin Dusun** | Subbab 3.1 | Login `/admin/login` -> Dashboard Dusun -> Pilih Modul -> Isi Form / Smart Input -> Simpan -> Data Otomatis Tampil di Web Publik. |
| **FLOW-03** | **Pembagian Otoritas Pengguna** | Subbab 1.4 | Matriks 3 Peran: Publik (Read-only) vs Admin Dusun (Scoped Wilayah, Soft Delete) vs Super Admin (Tata Kelola Global, Restore, Hard Delete). |
| **FLOW-04** | **Siklus Hidup Data (Lifecycle Data)** | Subbab 3.8 & Subbab 4.10 | Data Aktif -> (Soft Delete oleh Admin Dusun / Super Admin) -> Tong Sampah (Trashed) -> Pilihan Super Admin: [Restore -> Aktif Kembali] atau [Hapus Permanen -> Hilang Mutlak]. |
| **FLOW-05** | **Siklus Status Waktu Agenda Kegiatan** | Subbab 2.8 & Subbab 3.6 | Jadwal Baru -> (Tanggal Masa Depan: `AKAN_DATANG`) -> (Hari Pelaksanaan: `BERLANGSUNG`) -> (Tanggal Terlewati: `SELESAI` + Unggah Dokumentasi). |
| **FLOW-06** | **Siklus Pengumuman Masuk Arsip** | Subbab 2.9 & Subbab 3.7 | Pengumuman Baru -> (Masa Aktif: Tampil di Beranda / Dusun) -> (Tanggal Kedaluwarsa Lewat: Otomatis Pindah ke `/pengumuman/arsip`). |
| **FLOW-07** | **Alur Penentuan Titik Koordinat** | Subbab 3.5 & Subbab 5.2 | Ambil Titik / Salin Link Google Maps -> Tempel di Smart Input atau Tekan GPS -> Verifikasi Posisi Pin di Peta -> Simpan Data. |
| **FLOW-08** | **Alur Operasional Super Admin** | Subbab 4.1 | Login Super Admin -> Dashboard Tata Kelola -> Pilih Kebutuhan Pengelolaan (Identitas, Lintas Dusun, Warta, Data/Peta, Admin Dusun) -> Kelola Data -> Simpan. Cabang: Data Terhapus (Pulihkan vs Hapus Permanen). |

---

## 9. Table & Callout Mapping (Daftar Tabel dan Kotak Peringatan)

### 9.1 Daftar Tabel Panduan Form dan Matriks
Seluruh tabel isian formulir menggunakan format standar non-teknis: `| Isian | Wajib? | Cara Mengisi | Contoh |`.

1. **Tabel Ringkasan Wilayah 6 Dusun Desa Bendung** (Subbab 1.1)
2. **Tabel Matriks Hak Akses dan Batas Kewenangan Pengguna** (Subbab 1.4)
3. **Tabel Panduan Elemen Beranda Utama Desa** (Subbab 2.2)
4. **Tabel Isian Form Profil Dusun** (Subbab 3.2)
5. **Tabel Isian Form Kontak Pelayanan** (Subbab 3.3)
6. **Tabel Isian Form Pendaftaran UMKM** (Subbab 3.4)
7. **Tabel Isian Form Fasilitas Umum** (Subbab 3.5)
8. **Tabel Isian Form Agenda Kegiatan** (Subbab 3.6)
9. **Tabel Isian Form Pengumuman Dusun** (Subbab 3.7)
10. **Tabel Isian Form Identitas Desa** (Subbab 4.2)
11. **Tabel 10 Menu Tata Kelola Super Admin** (Subbab 4.1)
12. **Tabel Isian Form Tambah Admin Dusun** (Subbab 4.8)
13. **Tabel Standar Ukuran dan Rasio Foto per Modul** (Subbab 5.3)
14. **Tabel Matriks Perbandingan Data Aktif vs Data Nonaktif vs Arsip** (Subbab 5.7)
15. **Tabel Diagnosa Masalah dan Solusi Cepat** (Subbab 6.1 s.d. 6.6)
16. **Checklist Pemeliharaan Sistem Berkala** (Bagian Akhir)

### 9.2 Standar Tipe Callout Penulisan
* `CATATAN` : Informasi latar belakang, penjelasan konsep, atau tips kenyamanan penggunaan peramban.
* `TIPS` : Cara cepat, jalan pintas, rekomendasi efisiensi kerja, atau praktik terbaik.
* `PENTING` : Aturan wajib yang menentukan keberhasilan operasi (misal: koordinat fasilitas wajib diisi, format nomor kontak).
* `PERHATIAN` : Peringatan risiko tinggi (misal: tindakan Hapus Permanen tidak dapat dibatalkan, pencabutan akun pengelola).

---

## 10. Front Matter & Cover Depan Plan (Rencana Sampul Depan & Bagian Awal)

### 10.1 Spesifikasi Sampul Depan (Cover Depan Fisik Luar)
Sampul depan dirancang sebagai sampul fisik luar buku yang terpisah dari nomor halaman isi (material sampul ditentukan pada tahap produksi/cetak):
* **Judul Utama:** Panduan Penggunaan dan Pengelolaan Portal Informasi Desa Bendung
* **Subjudul:** Panduan Praktis bagi Masyarakat, Admin Dusun, dan Pemerintah Desa
* **Elemen Visual yang Direncanakan:**
  1. Identitas visual resmi Portal Informasi Desa Bendung.
  2. Logo resmi Pemerintah Desa Bendung.
  3. Logo universitas / lambang program KKN (bila diperlukan).
  4. Visualisasi / mockup tampilan portal pada perangkat komputer desktop dan smartphone.
  5. Nama Tim KKN / Tim Penyusun Sistem.
  6. Tahun Penerbitan: 2026.
* *Catatan Teknis:* Desain grafis sampul depan dikerjakan pada tahap tata letak visual akhir dan tidak dihitung ke dalam target nomor halaman isi (61 - 72 halaman).

### 10.2 Rencana Bagian Awal Isi (Front Matter Dalam Buku)
Bagian awal berada di dalam buku setelah sampul depan dibuka:
1. **Halaman Judul (Title Page):** Lembar judul pertama bagian dalam buku (memuat judul lengkap, subjudul, logo desa, nama instansi penyusun/KKN, dan tahun terbit). *Berbeda fungsi dari Cover Depan luar.*
2. **Halaman Hak Cipta & Identitas Penerbitan:** Informasi pemegang hak cipta, status dokumen pendaftaran HKI, klausul perlindungan hak cipta, dan identitas edisi (Edisi Pertama, Agustus 2026).
3. **Kata Pengantar:** Sambutan resmi dari Kepala Desa Bendung dan Ketua Tim Penyusun/DPL KKN mengenai pentingnya digitalisasi informasi desa.
4. **Daftar Isi:** Daftar navigasi halaman seluruh bab dan subbab.
5. **Daftar Gambar:** Daftar nomor dan nama seluruh tangkapan layar serta bagan alur kerja.
6. **Daftar Tabel:** Daftar nomor dan nama seluruh tabel panduan form dan matriks kewenangan.
7. **Petunjuk Penggunaan Buku:** Panduan singkat simbol callout, tipografi penulisan tombol antarmuka, dan rute membaca sesuai peran pengguna.

---

## 11. Back Matter & Cover Belakang Plan (Rencana Bagian Akhir & Sampul Belakang)

### 11.1 Rencana Bagian Akhir Isi (Back Matter Dalam Buku)
1. **Glosarium Istilah:** Kamus istilah praktis untuk istilah penting (misalnya: *Peramban/Browser*, *Koordinat Geografis*, *Smart Input*, *Soft Delete*, *Restore*, *Arsip Pengumuman*, *Draggable Pin*, *Drawer Menu*, *Scope Level*).
2. **Checklist Pemeliharaan Sistem Berkala:** Lembar kerja evaluasi mingguan, bulanan, dan semesteran bagi perangkat desa untuk memastikan kelengkapan data portal.
3. **Profil Tim Penyusun:** Biodata singkat tim pengembang KKN dan pembimbing teknis yang terlibat dalam perancangan dan dokumentasi sistem.

### 11.2 Spesifikasi Sampul Belakang (Cover Belakang Fisik Luar)
Sampul belakang dicetak sebagai sisi belakang sampul fisik luar buku:
* **Sinopsis Singkat Buku:** Ringkasan narasi yang menjelaskan fungsi buku sebagai pedoman resmi operasional, pemeliharaan data, dan serah-terima teknologi informasi Desa Bendung.
* **Sasaran Pembaca:** Keterangan peruntukan buku (Masyarakat Umum, Kepala Dusun/Admin Dusun, dan Pemerintah Desa Bendung).
* **Identitas Penyusun / Instansi:** Nama tim pelaksana KKN, universitas, dan Pemerintah Desa Bendung.
* **Tahun Penerbitan:** 2026.
* **Ruang Opsional QR Code Portal:** Disediakan area placeholder untuk penempatan kode QR tautan langsung ke portal desa jika domain/URL final telah dikonfirmasi saat pencetakan.

---

## 12. Authoring Order (Urutan Penulisan Naskah yang Direkomendasikan)

Untuk memastikan penulisan berjalan efisien tanpa pengulangan kerja, disarankan urutan penulisan Markdown sebagai berikut:

```
[Tahap 1: Inti Prosedural] ──► BAB III (Admin Dusun) & BAB IV (Super Admin)
                                      │
                                      ▼
[Tahap 2: Panduan Publik]   ──► BAB II (Panduan Masyarakat)
                                      │
                                      ▼
[Tahap 3: Standar & Solusi] ──► BAB V (Pedoman Pengelolaan) & BAB VI (Troubleshooting)
                                      │
                                      ▼
[Tahap 4: Konsep & Penutup] ──► BAB I (Pengenalan Portal) & Bagian Akhir (Glosarium/Checklist)
                                      │
                                      ▼
[Tahap 5: Finalisasi]       ──► Bagian Awal (Kata Pengantar, Daftar Isi, Gambar, Tabel)
```

*Alasan Urutan:* Menulis Bab III dan Bab IV terlebih dahulu mengunci seluruh detail prosedur teknis formulir. Dengan demikian, penulisan Bab II, Bab V, dan Bab VI akan sangat konsisten mengacu pada prosedur yang telah baku. Bagian Awal ditulis paling akhir setelah nomor halaman dan daftar gambar final terbentuk.

---

## 13. Quality Gate Before Writing (Pemeriksaan Kualitas Naskah)

Sebelum naskah Markdown tiap bab ditulis, tim penulis wajib memastikan:

* [x] Seluruh fitur yang ditulis 100% selaras dengan `as-built-user-manual-baseline.md`.
* [x] Tidak ada pencantuman fitur fiktif (tidak ada registrasi warga, checkout UMKM, payment gateway, pencarian nama jalan peta, batas poligon dusun, tambah dusun baru).
* [x] Peran Admin Dusun dan Super Admin terpisah dengan benar (Admin Dusun tidak diberi fitur restore/hard delete).
* [x] Fitur "Ingat Saya" (Remember Me) dijelaskan sebagai fitur fungsional berbasis remember token + cookie.
* [x] Halaman Data / Peta diposisikan sebagai monitoring agregat, bukan form input mandiri.
* [x] Pengumuman arsip lampau dibedakan secara tegas dari data soft delete.
* [x] Seluruh 18 screenshot MUST dan 8 SHOULD telah memiliki tempat di subbab.
* [x] Estimasi tebal naskah berada di rentang 61 sampai 72 halaman A5.

---

## 14. Final Summary (Ringkasan Blueprint)

* **Status Dokumen Blueprint:** **SELESAI & SIAP JADI PANDUAN PENULISAN NASKAH**
* **Struktur Buku:** 6 Bab Utama + Bagian Awal + Bagian Akhir
* **Total Subbab:** 44 Subbab Prosedural Terperinci
* **Total Screenshot Dipetakan:** 28 Item Shot List (18 MUST, 8 SHOULD, 2 OPTIONAL)
* **Total Flowchart Dipetakan:** 8 Bagan Alur Kerja
* **Total Tabel Standar Dipetakan:** 16 Tabel Panduan Form & Matriks
* **Estimasi Ketebalan Final:** **61 - 72 Halaman A5**
* **OPEN QUESTION:** **0**
