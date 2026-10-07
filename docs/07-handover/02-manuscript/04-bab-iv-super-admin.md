# BAB IV
# PANDUAN TATA KELOLA UNTUK SUPER ADMIN

Bab ini memandu peran dan tata kelola **Super Admin** (Pemerintah Desa Bendung) selaku pengelola utama portal desa. Melalui panel administrasi global, Super Admin mengawasi keterbukaan informasi publik, memelihara identitas resmi desa, mengatur status publikasi enam dusun, mengelola data operasional lintas wilayah, menetapkan master kategori sarana, mendistribusikan warta tingkat desa, memantau sebaran peta spasial, mengelola akun Admin Dusun, serta menjalankan prosedur pemulihan (*restore*) dan penghapusan permanen data.

Tata kelola yang tertib di tingkat desa memastikan integritas data, kesinambungan pelayanan informasi, dan keselarasan publikasi antara pemerintah desa dan seluruh pemangku wilayah dusun.

---

## 4.1 Prosedur Masuk dan Dashboard Tata Kelola Global

Panel kendali Super Admin diakses melalui gerbang masuk (*login*) tunggal yang sama dengan Admin Dusun. Sistem mengenali peran akun dan mengarahkan Super Admin ke pusat pengelolaan global se-Desa Bendung.

### Tujuan Aktivitas
1. Mengakses panel kerja tata kelola desa melalui peramban web (*browser*).
2. Memasukkan kredensial akun Super Admin.
3. Mengenali tata letak metrik statistik agregat dan 10 modul pada Dashboard Super Admin.
4. Menutup sesi kerja melalui prosedur keluar (*logout*) yang benar.

### Prosedur Masuk Sistem (Login)
1. Buka peramban web pada komputer atau laptop kerja Anda.
2. Masukkan alamat tautan login portal desa:
   ```text
   https://[domain-portal-desa]/admin/login
   ```
3. Masukkan nama pengguna resmi Super Admin pada kolom **Username**.
4. Masukkan kata sandi pada kolom **Password**. Gunakan **Ikon Mata** untuk memeriksa kebenaran karakter sandi.
5. Tentukan pilihan pada kotak centang **Ingat saya**:
   - Fitur *Ingat Saya* bekerja dengan prinsip yang sama seperti dijelaskan pada Bab III. Centang opsi ini hanya jika Anda menggunakan perangkat kerja pribadi yang aman.
6. Klik tombol **Masuk ke Portal**.
7. Sistem mengarahkan Anda ke **Dashboard Super Admin**.

<!-- FIGURE: SA-001 | ../03-assets/screenshots/super-admin/18_sa_dashboard_global.png -->
**Gambar — Dashboard Tata Kelola Global Super Admin**

<!-- FLOWCHART: FLOW-08 | ../03-assets/flowcharts/svg/FLOW-08-operasional-super-admin.svg -->
**Diagram — Alur Operasional Kerja Super Admin**

### Mengenal Dashboard Tata Kelola Global
Dashboard Super Admin menyajikan ringkasan statistik menyeluruh yang terbagi dalam empat kelompok metrik utama serta sepuluh area manajemen data:

1. **Kelompok Metrik Wilayah:** Memantau jumlah total dusun (6 dusun), jumlah dusun berstatus aktif publik, dan dusun berstatus nonaktif.
2. **Kelompok Metrik Data Desa:** Menampilkan akumulasi kontak pelayanan, unit UMKM terdaftar, sarana fasilitas umum, dan jumlah kategori fasilitas aktif.
3. **Kelompok Metrik Informasi:** Memantau total warta agenda kegiatan dan pengumuman resmi yang sedang beredar.
4. **Kelompok Metrik Akun:** Menampilkan rekapitulasi akun Admin Dusun yang aktif bertugas dan akun yang dinonaktifkan aksesnya.

Berikut adalah daftar 10 modul tata kelola pada panel kerja Super Admin:

| No | Modul Manajemen | Fungsi Tata Kelola Utama |
|:---:|---|---|
| 1 | **Identitas Desa** | Mengelola profil resmi desa, nama kepala desa, kontak balai desa, jam pelayanan, dan foto banner. |
| 2 | **Kelola Dusun** | Mengatur status publikasi (*ACTIVE/INACTIVE*) dan menyunting profil 6 dusun. |
| 3 | **Kontak Pelayanan** | Mengelola direktori kontak pamong seluruh dusun beserta pemulihan data. |
| 4 | **Kelola UMKM** | Mengelola katalog potensi usaha warga se-desa dan verifikasi sebaran data. |
| 5 | **Kelola Fasilitas** | Mengelola direktori sarana umum, koordinat wajib, pemulihan, dan hapus permanen. |
| 6 | **Kategori Fasilitas** | Menetapkan master klasifikasi kategori sarana fasilitas publik desa. |
| 7 | **Agenda & Kegiatan** | Menerbitkan warta agenda tingkat desa (`DESA`) maupun tingkat dusun (`DUSUN`). |
| 8 | **Pengumuman** | Menerbitkan maklumat resmi warga dengan cakupan tingkat desa atau dusun. |
| 9 | **Data / Peta** | Memantau visualisasi spasial agregat seluruh titik sebaran lokasi se-Desa Bendung. |
| 10 | **Admin Dusun** | Mengelola akun operator wilayah, penugasan dusun, dan reset kata sandi. |

### Prosedur Keluar Sistem (Logout)
1. Arahkan kursor ke sudut kanan atas bilah navigasi panel kerja.
2. Klik tombol **Keluar**.
3. Sistem mengakhiri sesi kerja dan mengembalikan tampilan ke halaman login.

### Hasil yang Diharapkan
- Super Admin berhasil masuk ke pusat kendali tata kelola desa.
- Super Admin memahami struktur navigasi 10 modul kerja dan indikator statistik global.
- Sesi kerja ditutup secara tertib setelah pembaruan data selesai.

---

## 4.2 Mengelola Identitas dan Profil Resmi Desa

Informasi profil resmi Pemerintah Desa Bendung ditampilkan pada beranda utama portal untuk menyambut warga dan masyarakat luas. Modul ini digunakan untuk memelihara ketepatan data lembaga balai desa.

### Tujuan Aktivitas
1. Memperbarui identitas nama desa dan nama Kepala Desa (Lurah) yang menjabat.
2. Memperbarui nomor kontak resmi balai desa dan alamat kantor pemerintah desa.
3. Menetapkan jam operasional pelayanan kantor balai desa bagi warga.
4. Mengunggah foto *banner* utama beranda desa.

### Langkah-Langkah Pengelolaan Identitas Desa
1. Pada menu navigasi dashboard, pilih menu **Identitas Desa**.
2. Formulir profil desa akan menampilkan data yang tersimpan saat ini.
3. Lakukan penyuntingan pada kolom yang memerlukan pembaruan sesuai tabel panduan di bawah.
4. Pada kolom **Foto Banner Utama Desa**, pilih berkas foto lanskap dari perangkat Anda jika ingin memperbarui tampilan beranda.
5. Klik tombol **Simpan Identitas Desa**.
6. Sistem menampilkan pemberitahuan sukses dan memperbarui informasi beranda utama desa.

<!-- FIGURE: SA-002 | ../03-assets/screenshots/super-admin/19_sa_identitas_desa_form.png -->
**Gambar — Formulir Pengelolaan Identitas dan Profil Resmi Desa**

### Panduan Isian Formulir Identitas Desa

| Isian | Wajib? | Cara Mengisi | Contoh Pengisian |
|---|:---:|---|---|
| **Nama Desa** | Ya | Masukkan nama resmi pemerintahan desa | Desa Contoh |
| **Deskripsi / Selayang Pandang** | Ya | Tuliskan narasi profil desa yang memuat gambaran umum, potensi masyarakat, dan informasi pelayanan pemerintahan desa | Desa [Nama Desa] memiliki profil wilayah yang memuat gambaran umum, potensi masyarakat, dan informasi pelayanan pemerintahan desa. |
| **Nama Kepala Desa (Lurah)** | Ya | Masukkan nama lengkap beserta gelar Kepala Desa yang menjabat | Bpk. Nama Kepala Desa Contoh |
| **Nomor Kontak Resmi Desa** | Ya | Masukkan nomor telepon kantor balai desa atau nomor WhatsApp layanan resmi | 081123456789 |
| **Jam Pelayanan Kantor** | Ya | Tuliskan jadwal operasional pelayanan administrasi balai desa | Senin - Jumat, Pukul 08.00 - 15.00 WIB |
| **Alamat Kantor Balai Desa** | Ya | Tuliskan alamat kantor pemerintah balai desa | [Alamat Kantor Balai Desa] |
| **Banner Utama Desa** | Opsional | Pilih berkas gambar berformat JPG, JPEG, PNG, atau WebP (maksimal 3 MB) | [Pilih Berkas: `banner-desa.jpg`] |

> **PENTING: REKOMENDASI PENGELOLAAN**
> Informasi nomor kontak resmi dan jam pelayanan kantor balai desa ditampilkan pada bagian bawah (*footer*) beranda portal. Pastikan nomor kontak yang dicantumkan dapat dihubungi pada jam kerja guna mempermudah warga yang membutuhkan informasi pelayanan administrasi kependudukan.

### Hasil yang Diharapkan
- Halaman muka portal menampilkan identitas resmi desa yang valid dan rapi.
- Warga perantau dan masyarakat umum memperoleh panduan jam kerja kantor desa dan saluran komunikasi resmi yang tepat.

---

## 4.3 Mengelola Status dan Narasi Profil 6 Dusun

Desa Bendung terdiri dari enam wilayah Dusun tetap: **Bendung, Klubuk, Bantengan, Belik, Pohsengir, dan Kaliasin**. Super Admin berwenang mengawasi profil narasi setiap dusun dan mengatur status publikasi wilayah.

### Tujuan Aktivitas
1. Meninjau daftar master enam dusun beserta ringkasan akumulasi data masing-masing.
2. Memperbarui narasi profil dusun, nama Kepala Dusun, dan fakta wilayah (RT/RW) jika diperlukan.
3. Mengatur status publikasi dusun antara kondisi aktif (*ACTIVE*) dan nonaktif (*INACTIVE*).

### Memahami Status Publikasi Dusun
Sistem membedakan dua kondisi status publikasi wilayah:

1. **Status AKTIF PUBLIK (`ACTIVE`):** Halaman profil dusun dapat diakses publik melalui tautan navigasi dan kartu dusun pada beranda utama. Warga dapat menjelajahi konten dusun bersangkutan.
2. **Status NONAKTIF (`INACTIVE`):** Halaman publik dusun disembunyikan dari beranda utama dan tidak ditampilkan kepada masyarakat umum.

<!-- FIGURE: SA-003 | ../03-assets/screenshots/super-admin/20_sa_kelola_dusun_list_status.png -->
**Gambar — Tabel Pengelolaan Master 6 Dusun dan Pengaturan Status Publikasi**

### Prosedur Mengubah Profil dan Status Dusun
1. Pada bilah menu, klik menu **Kelola Dusun**.
2. Tabel menampilkan enam dusun lengkap dengan nama pimpinan, statistik RT/RW, dan status publikasi.
3. **Untuk Mengedit Profil Dusun:**
   - Klik tombol **Edit Profil** pada baris dusun yang dituju.
   - Perbarui deskripsi selayang pandang, nama kepala dusun, jumlah RT/RW, atau foto banner dusun.
   - Klik tombol **Simpan Perubahan**.
4. **Untuk Menonaktifkan Status Publik Dusun (Deaktivasi):**
   - Pada baris dusun yang berstatus aktif, klik tombol **Deaktivasi**.
   - Konfirmasikan pada kotak verifikasi peramban. Status berubah menjadi *NONAKTIF*.
5. **Untuk Mengaktifkan Kembali Status Dusun (Aktivasi):**
   - Pada baris dusun yang berstatus nonaktif, klik tombol **Aktivasi Publik**.
   - Status berubah menjadi *AKTIF PUBLIK* dan kembali tayang di beranda desa.

> **PENTING: STATUS WILAYAH NONAKTIF**
> - Ketika status dusun diatur ke *INACTIVE*, Admin Dusun tetap dapat login dan mengelola data di panel administrasi wilayahnya.
> - Halaman publik dusun tidak ditampilkan kepada masyarakat selama berstatus nonaktif.
> - Perubahan status Dusun tidak menghapus data internal Dusun.

### Hasil yang Diharapkan
- Informasi profil dan kepemimpinan di enam dusun selalu selaras dengan kondisi pemerintahan aktual.
- Super Admin dapat mengendalikan keterbukaan publikasi wilayah secara tertib.

---

## 4.4 Mengelola Data Kontak, UMKM, dan Fasilitas Lintas Dusun

Super Admin memiliki wewenang untuk melihat, menambah, menyunting, memfilter, dan menonaktifkan data operasional (Kontak Pelayanan, UMKM, dan Fasilitas Umum) di seluruh wilayah se-Desa Bendung.

### Tujuan Aktivitas
1. Memantau direktori kontak, etalase UMKM, dan sarana fasilitas di seluruh dusun.
2. Memfilter data berdasarkan wilayah dusun tertentu atau kategori sarana.
3. Menambahkan data baru atas nama wilayah dusun yang ditentukan.
4. Menyunting atau menonaktifkan data operasional jika diperlukan.

### Tata Kelola Data Lintas Dusun
Prosedur pengisian formulir kontak, UMKM, dan fasilitas pada panel Super Admin mengikuti aturan kelengkapan data yang sama seperti yang diuraikan pada Bab III, dengan penambahan fitur **Pilihan Wilayah Dusun**:

- **Pemilihan Dusun pada Formulir:** Saat menekan tombol tambah data baru, formulir Super Admin menyediakan menu pilihan tarik-turun (*dropdown*) **Wilayah Dusun**. Super Admin memilih dusun target yang menaungi data tersebut.
- **Bilah Filter Wilayah pada Tabel Data:** Pada bagian atas tabel data (Kontak, UMKM, Fasilitas), tersedia menu **Filter Dusun**. Memilih salah satu dusun akan menyaring daftar tabel agar hanya menampilkan rekod dari wilayah terpilih.

### Ringkasan Ketentuan Field Data Operasional

| Modul Data | Ketentuan Field Utama | Aturan Titik Koordinat |
|---|---|:---:|
| **Kontak Pelayanan** | Nama Petugas, Jabatan, Nomor WhatsApp resmi (Wajib). Alamat dan Foto (Opsional). | **Opsional** |
| **Data UMKM** | Nama Usaha, Pemilik, Jenis Usaha, Jam Operasional, Nomor WhatsApp, Deskripsi, Alamat (Wajib). Daftar Produk dan Foto Utama (Opsional). | **Opsional** |
| **Fasilitas Umum** | Kategori Fasilitas, Nama Sarana, Deskripsi, Alamat (Wajib). Nomor Kontak dan Foto (Opsional). | **WAJIB** |

> **CATATAN TENTANG DIREKTORI UMKM**
> Selaras dengan prinsip yang telah dibakukan, modul UMKM berfungsi sebagai direktori etalase promosi informasi bagi potensi ekonomi desa. Pengunjung dapat menghubungi pemilik usaha melalui WhatsApp untuk memperoleh informasi lebih lanjut. Portal tidak memproses transaksi jual-beli, pemesanan daring, maupun pembayaran.

### Prosedur Penyuntingan Data Lintas Wilayah
1. Buka modul yang dituju (**Kontak Pelayanan**, **Kelola UMKM**, atau **Kelola Fasilitas**).
2. Gunakan bilah filter dusun untuk mempercepat pencarian data yang bersangkutan.
3. Klik tombol **Edit** pada baris data.
4. Lakukan penyesuaian informasi, termasuk memindahkan penugasan wilayah dusun jika terjadi perbaikan data administratif.
5. Klik tombol **Simpan Perubahan**.

### Hasil yang Diharapkan
- Data operasional kemasyarakatan di seluruh dusun terpantau rapi dan terkontrol secara terpusat.
- Super Admin dapat membantu perangkat dusun yang membutuhkan asistensi pembaruan konten secara cepat.

---

## 4.5 Mengelola Master Kategori Fasilitas Desa

Modul Kategori Fasilitas digunakan untuk menetapkan master pengelompokan sarana dan prasarana publik (seperti *Sarana Ibadah, Sarana Kesehatan, Sarana Pendidikan, Balai Pertemuan, Sarana Olahraga,* dan sebagainya). Master kategori ini menjadi pilihan baku bagi Admin Dusun dan Super Admin saat mendaftarkan fasilitas baru.

### Tujuan Aktivitas
1. Meninjau daftar master kategori sarana publik yang berlaku di Desa Bendung.
2. Menambahkan kategori fasilitas baru sesuai kebutuhan pendataan desa.
3. Mengubah penamaan kategori agar lebih komunikatif dan terstandarisasi.
4. Menghapus kategori yang sudah tidak diperlukan dengan mematuhi aturan proteksi ketergantungan data.

### Proteksi Otomatis Penghapusan Kategori
Sistem menerapkan perlindungan integritas data:
- Kategori yang **masih digunakan** oleh data fasilitas aktif **tidak dapat dihapus**. Sistem menolak permintaan penghapusan dan menampilkan pesan peringatan mengenai jumlah fasilitas yang masih terhubung.
- Untuk menghapus kategori tersebut, Super Admin perlu mengubah kategori pada fasilitas terkait ke kategori lain yang sesuai terlebih dahulu.
- Kategori yang **tidak memiliki keterkaitan data fasilitas** dapat dihapus.

<!-- FIGURE: SA-004 | ../03-assets/screenshots/super-admin/21_sa_kategori_fasilitas_crud.png -->
**Gambar — Master Kategori Fasilitas dan Form Tambah Kategori Baru**

### Langkah-Langkah Mengelola Kategori Fasilitas
1. Pada menu navigasi samping, klik menu **Kategori Fasilitas**.
2. Tabel menampilkan daftar nama kategori beserta kolom jumlah fasilitas yang terdaftar di bawah kategori tersebut.
3. **Untuk Menambah Kategori Baru:**
   - Klik tombol **+ Tambah Kategori Baru**.
   - Masukkan nama kategori baru (contoh: *Sarana Wisata & Budaya*).
   - Klik tombol **Simpan Kategori**.
4. **Untuk Mengubah Nama Kategori:**
   - Klik tombol **Edit** pada baris kategori yang bersangkutan.
   - Perbarui penamaan kategori, lalu klik **Simpan Perubahan**. Seluruh fasilitas yang menggunakan kategori tersebut mengikuti nama baru.
5. **Untuk Menghapus Kategori:**
   - Pastikan angka pada kolom jumlah fasilitas bernilai 0.
   - Klik tombol **Hapus** dan konfirmasikan penghapusan.

> **REKOMENDASI PENGELOLAAN**
> Gunakan nama kategori secara konsisten dan tidak berulang agar filter dan penyajian data fasilitas pada peta interaktif mudah dipahami masyarakat. Hindari membuat kategori yang terlalu spesifik untuk satu bangunan saja.

### Hasil yang Diharapkan
- Klasifikasi sarana publik di seluruh desa tersusun secara rapi, baku, dan konsisten.
- Direktori fasilitas publik dan filter peta menyajikan kelompok sarana yang informatif.

---

## 4.6 Mempublikasikan Agenda dan Pengumuman Tingkat Desa

Super Admin berwenang menerbitkan warta agenda kegiatan dan maklumat pengumuman resmi dengan pilihan cakupan wilayah, baik berskala desa secara menyeluruh maupun berskala khusus untuk dusun tertentu.

### Tujuan Aktivitas
1. Memahami penentuan cakupan wilayah warta (**Tingkat Desa** vs **Tingkat Dusun**).
2. Mempublikasikan agenda kegiatan dan pengumuman tingkat desa yang tampil di beranda utama portal.
3. Mengatur jadwal waktu agenda dan masa berlaku aktif pengumuman.

### Memahami Pilihan Cakupan Wilayah (Scope Level)
Saat membuat Agenda atau Pengumuman baru, Super Admin memilih cakupan informasi:

1. **Cakupan Tingkat Desa (`DESA`):**
   - Warta ditujukan bagi seluruh warga se-Desa Bendung.
   - Warta berskala desa tampil di Beranda Utama Desa (pada bagian Warta Terkini) serta dapat dibaca oleh seluruh pengunjung portal.
2. **Cakupan Tingkat Dusun (`DUSUN`):**
   - Warta ditujukan khusus bagi warga di dusun tertentu.
   - Super Admin memilih nama dusun target pada menu pilihan. Warta ditampilkan pada halaman profil dusun bersangkutan.

<!-- FIGURE: SA-005 | ../03-assets/screenshots/super-admin/22_sa_scope_wilayah_selector.png -->
**Gambar — Penentuan Cakupan Wilayah (Tingkat Desa vs Tingkat Dusun)**

### Langkah-Langkah Menerbitkan Agenda Tingkat Desa
1. Klik menu **Agenda & Kegiatan**, lalu klik tombol **Tambah Agenda Baru**.
2. Pada bagian **Cakupan Wilayah**, pilih opsi **Tingkat Desa (Global)**.
3. Masukkan judul agenda, tanggal mulai, tanggal selesai (opsional), waktu/jam (opsional), dan lokasi kegiatan.
4. Tuliskan deskripsi lengkap mengenai kegiatan desa.
5. Pada bagian **Media Gambar**, unggah poster publikasi awal (opsional, maksimal 3 MB).
6. Klik tombol **Terbitkan Agenda**.

> **CATATAN SIKLUS WAKTU AGENDA**
> Agenda kegiatan mengikuti perputaran status waktu: **AKAN DATANG $\longrightarrow$ BERLANGSUNG $\longrightarrow$ SELESAI**. Setelah kegiatan tingkat desa selesai diselenggarakan, Super Admin dapat menyunting agenda tersebut untuk menyematkan foto dokumentasi pelaksanaan.

### Langkah-Langkah Menerbitkan Pengumuman Tingkat Desa
1. Klik menu **Pengumuman**, lalu klik tombol **Tambah Pengumuman**.
2. Pada bagian **Cakupan Wilayah**, pilih opsi **Tingkat Desa (Global)**.
3. Ketikkan judul maklumat resmi pada kolom **Judul Pengumuman**.
4. Tuliskan teks isi maklumat secara lengkap pada kolom **Isi Pengumuman**.
5. Tetapkan batas akhir masa aktif pada kolom **Tanggal Kedaluwarsa**.
6. Klik tombol **Terbitkan Pengumuman**.

> **CATATAN PENGUMUMAN DAN ARSIP OTOMATIS**
> Formulir pengumuman murni memuat teks warta tanpa lampiran berkas/PDF. Pengumuman berstatus aktif selama tanggal kedaluwarsa belum terlewati. Setelah tanggal tersebut lewat, pengumuman berpindah ke halaman **Arsip Pengumuman** desa sehingga rekam warta tetap dapat ditelusuri masyarakat.

### Hasil yang Diharapkan
- Maklumat resmi Pemerintah Desa Bendung tersampaikan secara terbuka di beranda utama portal.
- Penyebaran informasi tingkat desa dan tingkat dusun terkelola secara terstruktur.

---

## 4.7 Monitoring Sebaran Titik Spasial Desa (Data / Peta)

Modul **Data / Peta** menyajikan visualisasi peta digital yang memetakan titik lokasi sarana fasilitas umum, tempat usaha UMKM, dan pos kontak pelayanan yang memiliki koordinat di wilayah Desa Bendung.

### Tujuan Aktivitas
1. Memantau persebaran spasial sarana publik dan kegiatan ekonomi warga se-desa.
2. Memfilter visualisasi titik lokasi berdasarkan wilayah dusun atau kategori sarana.
3. Memeriksa popup informasi titik lokasi dan mengakses pintasan penyuntingan data dari peta.

<!-- FIGURE: SA-007 | ../03-assets/screenshots/super-admin/24_sa_data_peta_overview.png -->
**Gambar — Panel Monitoring Visualisasi Data dan Peta Spasial Desa**

### Fitur Pemantauan Peta Spasial
Panel Data / Peta menyajikan peta interaktif dengan fitur pendukung:

1. **Filter Dusun:** Menyaring sebaran pin penanda agar menampilkan titik-titik di wilayah dusun tertentu, atau menampilkan seluruh desa.
2. **Filter Jenis Titik:** Menyaring tampilan berdasarkan kelompok data:
   - Fasilitas Umum (atau kategori fasilitas tertentu);
   - Unit Usaha UMKM;
   - Pos Kontak Pelayanan.
3. **Popup Informasi dan Pintasan Edit:** Mengklik pin penanda pada peta akan memunculkan jendela informasi yang memuat nama titik, kategori, dusun, koordinat, dan tautan tombol **Edit Data** untuk membuka formulir perbaikan data yang bersangkutan.

> **BATASAN FITUR PETA SPASIAL**
> Sistem portal dirancang khusus untuk pemetaan sebaran titik (*point marker*) sarana dan potensi desa. Peta tidak menyediakan kotak pencarian nama tempat otomatis (*geocoding search*) maupun lapisan garis batas poligon wilayah dusun (*boundary polygon*).

### Hasil yang Diharapkan
- Pemerintah Desa memiliki gambaran spasial mengenai persebaran sarana publik dan pusat ekonomi warga.
- Koordinasi sarana antar-dusun dapat dipantau dengan bantuan peta digital yang membantu memantau sebaran lokasi.

---

## 4.8 Mengelola Akun Pengelola Dusun (Admin Dusun)

Super Admin memegang wewenang atas tata kelola akun pengelola wilayah (*role* `ADMIN_DUSUN`). Modul ini digunakan untuk mendaftarkan akun operator baru, mengatur penugasan wilayah dusun, dan mencabut hak akses akun.

### Tujuan Aktivitas
1. Mendaftarkan akun Admin Dusun baru bagi pemangku wilayah yang ditugaskan.
2. Menyesuaikan penugasan wilayah dusun akun jika terjadi pergeseran tugas.
3. Mencabut hak akses login akun yang purnatugas melalui prosedur penonaktifan akun (*Logical Removal*).

<!-- FIGURE: SA-008 | ../03-assets/screenshots/super-admin/25_sa_admin_dusun_management.png -->
**Gambar — Manajemen Akun Admin Dusun dan Penugasan Wilayah**

### Langkah-Langkah Mendaftarkan Akun Admin Dusun Baru
1. Pada menu navigasi, klik menu **Admin Dusun**.
2. Klik tombol **+ Tambah Admin Dusun Baru**.
3. Pada formulir pembuatan akun:
   - Pilih wilayah penugasan pada menu dropdown **Penugasan Wilayah Dusun**.
   - Masukkan nama pengguna unik pada kolom **Username** (gunakan huruf kecil tanpa spasi, contoh: `admindusun_contoh`).
   - Masukkan kata sandi awal pada kolom **Password** (minimal 6 karakter).
   - Masukkan konfirmasi sandi pada kolom **Konfirmasi Password**.
4. Klik tombol **Simpan Akun**. Akun aktif dan dapat digunakan login oleh perangkat dusun yang bersangkutan.

### Prosedur Mengubah Penugasan Wilayah Dusun
1. Pada tabel akun Admin Dusun, temukan nama akun yang ingin disesuaikan.
2. Klik tombol **Ubah Dusun** pada kolom aksi.
3. Pilih nama dusun penugasan yang baru pada menu dropdown.
4. Klik tombol **Simpan Perubahan**. Hak akses pengelolaan akun beralih ke dusun yang baru dipilih.

### Prosedur Penonaktifan Akses Akun (Logical Removal)
Apabila seorang perangkat dusun telah purnatugas atau tidak lagi berwenang mengelola data, Super Admin mencabut hak akses akun tersebut dengan prosedur berikut:

1. Pada baris akun yang dituju, klik tombol **Hapus Akses**.
2. Kotak dialog verifikasi akan meminta konfirmasi penonaktifan akses akun.
3. Klik **Ya, Nonaktifkan Akses**.
4. Status akun berubah menjadi *Akses Dinonaktifkan*.

> **PENTING: PENGELOLAAN AKSES AKUN**
> Hapus Akses mencabut kemampuan akun untuk masuk ke portal, sementara identitas akun tetap tercatat pada sistem. Akun yang telah dihapus aksesnya tidak dapat diaktifkan kembali melalui fitur yang tersedia.

### Hasil yang Diharapkan
- Setiap wilayah dusun memiliki akun pengelola resmi yang aktif dan terdata.
- Hak akses operasional dusun dapat disesuaikan saat terjadi rotasi aparatur desa.

---

## 4.9 Prosedur Reset Password Akun Admin Dusun

Portal Informasi Desa Bendung menerapkan kebijakan keamanan terpusat dan **tidak menyediakan fitur lupa kata sandi mandiri (*self-service password reset*)** melalui pengiriman tautan email publik. Apabila Admin Dusun lupa kata sandi akunnya, proses pengaturan ulang kata sandi dilakukan secara resmi oleh Super Admin.

### Tujuan Aktivitas
1. Mengatur ulang (*reset*) kata sandi akun Admin Dusun yang lupa kata sandi atau mengalami kendala login.
2. Menetapkan kata sandi baru yang memenuhi standar keamanan.
3. Menyerahkan kata sandi baru kepada perangkat dusun melalui saluran komunikasi yang aman.

### Langkah-Langkah Melakukan Reset Password
1. Buka menu **Admin Dusun**.
2. Temukan akun pengelola dusun yang meminta pemulihan kata sandi.
3. Pada kolom aksi, klik tombol **Reset Password**.
4. Layar menampilkan formulir **Reset Kata Sandi Akun** dengan identitas akun target yang terkunci.
5. Masukkan kata sandi baru pada kolom **Kata Sandi Baru** (minimal 6 karakter, disarankan kombinasi huruf dan angka).
6. Masukkan kembali kata sandi pada kolom **Konfirmasi Kata Sandi Baru**.
7. Klik tombol **Simpan Kata Sandi Baru**.
8. Sistem memperbarui kredensial akun dan menampilkan notifikasi sukses.

> **PERHATIAN: KEAMANAN PENYERAHAN KATA SANDI BARU**
> Jangan mengirimkan kata sandi baru melalui grup pesan terbuka atau media sosial publik. Serahkan kata sandi baru secara langsung kepada pengelola dusun bersangkutan melalui saluran tertutup (misalnya melalui pesan pribadi atau saat tatap muka di balai desa), dan imbau pengelola untuk menjaga kerahasiaan kredensial akunnya.

### Hasil yang Diharapkan
- Admin Dusun yang terkendala dapat kembali masuk dan melanjutkan pengelolaan informasi wilayahnya.
- Keamanan akun administrasi desa tetap terjaga dari risiko penyalahgunaan akun.

---

## 4.10 Memulihkan Data Terhapus (Restore)

Data operasional (Kontak, UMKM, Fasilitas, Agenda, atau Pengumuman) yang dinonaktifkan oleh Admin Dusun maupun Super Admin tidak langsung hilang dari sistem, melainkan tersimpan pada status *Soft Deleted (Nonaktif)*. Super Admin memiliki hak untuk memulihkan (*restore*) data tersebut agar aktif kembali.

### Tujuan Aktivitas
1. Membuka dan meninjau daftar data yang berstatus dinonaktifkan (*Soft Deleted*).
2. Memulihkan data yang tidak sengaja dinonaktifkan oleh perangkat dusun.
3. Memastikan data yang dipulihkan kembali tayang sesuai aturan visibilitas yang berlaku.

<!-- FIGURE: SA-006 | ../03-assets/screenshots/super-admin/23_sa_filter_lintas_dusun_restore.png -->
**Gambar — Bilah Filter Data Terhapus (Soft Deleted) dan Tombol Pemulihan Data (Restore)**

<!-- FLOWCHART: FLOW-04 | ../03-assets/flowcharts/svg/FLOW-04-lifecycle-data.svg -->
**Diagram — Siklus Hidup Data (Penonaktifan vs Pemulihan)**

### Langkah-Langkah Memulihkan Data (Restore)
1. Buka modul data yang bersangkutan (misalnya menu **Kelola Fasilitas**, **Kelola UMKM**, **Kontak Pelayanan**, **Agenda**, atau **Pengumuman**).
2. Pada bilah filter di atas tabel data, ubah pilihan **Status Data** menjadi **Soft Deleted (Nonaktif)**.
3. Tabel menampilkan seluruh rekod data yang sedang dalam kondisi nonaktif.
4. Temukan baris data yang hendak dipulihkan.
5. Pada kolom aksi, klik tombol **Pulihkan** (atau tombol *Restore*).
6. Konfirmasikan tindakan pemulihan pada dialog verifikasi peramban.
7. Sistem mengembalikan status rekod menjadi data aktif.

> **CATATAN TENTANG VISIBILITAS DATA PASCA-PEMULIHAN**
> Data yang telah dipulihkan akan kembali berstatus aktif di dalam sistem. Namun, penayangannya di halaman publik tetap mengikuti aturan visibilitas modul yang bersangkutan (misalnya: warta pengumuman yang tanggal kedaluwarsanya telah lewat akan masuk ke halaman Arsip, dan fasilitas pada dusun yang berstatus *INACTIVE* hanya akan tampil setelah status publik dusun diaktifkan kembali).

### Hasil yang Diharapkan
- Kesalahan penonaktifan data di tingkat dusun dapat diatasi tanpa perlu memasukkan ulang seluruh informasi dari awal.
- Kontinuitas data sejarah dan informasi sarana desa tetap terjaga.

---

## 4.11 Menghapus Data Secara Permanen (Hard Delete)

Hapus Permanen adalah tindakan khusus Super Admin untuk menghapus data operasional yang sebelumnya telah dinonaktifkan. Setelah tindakan ini dilakukan, data tidak dapat dipulihkan kembali melalui portal. Wewenang ini hanya dapat dilakukan pada data yang telah berstatus *Soft Deleted*.

### Tujuan Aktivitas
1. Membersihkan data uji coba atau rekod yang sudah dipastikan tidak diperlukan lagi.
2. Memahami konsekuensi dari tindakan penghapusan data secara permanen.
3. Menjalankan verifikasi sebelum mengeksekusi penghapusan permanen.

### Perbedaan Penonaktifan Data vs Hapus Permanen

| Parameter | Menonaktifkan Data (*Soft Delete*) | Menghapus Permanen (*Hard Delete*) |
|---|---|---|
| **Pelaksana** | Admin Dusun & Super Admin | **Super Admin Saja** |
| **Status Data** | Disembunyikan dari publik, disimpan di sistem | **Data dihapus permanen melalui portal** |
| **Pemulihan** | **Dapat dipulihkan (*Restore*)** | **Tidak dapat dipulihkan melalui portal** |

### Langkah-Langkah Menghapus Data Secara Permanen
1. Buka modul data yang dituju (Kontak, UMKM, Fasilitas, Agenda, atau Pengumuman).
2. Ubah pilihan filter **Status Data** menjadi **Soft Deleted (Nonaktif)**.
3. Periksa baris data yang akan dihapus permanen.
4. Pada kolom aksi, klik tombol **Hapus Permanen**.
5. Dialog konfirmasi verifikasi keamanan akan muncul memperingatkan bahwa data yang dihapus tidak dapat dikembalikan lagi.
6. Klik **Ya, Hapus Permanen**.
7. Data dihapus dari sistem portal.

> **PERHATIAN: TINDAKAN TIDAK DAPAT DIBATALKAN**
> Data yang telah dihapus melalui prosedur *Hapus Permanen* tidak dapat dipulihkan kembali melalui antarmuka portal desa. Lakukan verifikasi dengan perangkat dusun terkait sebelum memutuskan untuk menghapus data secara permanen.

### Hasil yang Diharapkan
- Data operasional yang memang tidak diperlukan lagi telah dihapus setelah melalui pemeriksaan.
- Daftar data pada portal tetap bersih dan relevan.

---

## 4.12 Batasan Struktural Terhadap Master 6 Dusun

Untuk memastikan stabilitas sistem informasi dan konsistensi tata ruang wilayah Desa Bendung, arsitektur Portal Informasi Desa Bendung menerapkan batasan struktural yang baku pada level pemerintah desa.

Subbab ini merangkum batas kewenangan operasional Super Admin sebagai acuan kerja pemerintahan desa.

### Matriks Kewenangan Tata Kelola Super Admin

| Bidang Pengelolaan | Hak Akses Super Admin | Keterangan Operasional |
|---|:---:|---|
| **Identitas & Profil Resmi Desa** | **Boleh** | Mengelola narasi, pimpinan desa, jam pelayanan, kontak, dan banner utama. |
| **Status Publikasi 6 Dusun** | **Boleh** | Mengatur status publikasi wilayah (*ACTIVE* atau *INACTIVE*). |
| **Profil Narasi 6 Dusun** | **Boleh** | Menyunting narasi selayang pandang, data RT/RW, dan nama Kepala Dusun se-desa. |
| **Data Operasional Lintas Dusun** | **Boleh** | Menambah, menyunting, dan menonaktifkan Kontak, UMKM, dan Fasilitas seluruh wilayah. |
| **Master Kategori Fasilitas** | **Boleh** | Menambah, menyunting, dan menghapus kategori (dengan proteksi relasi data). |
| **Warta Tingkat Desa & Dusun** | **Boleh** | Menerbitkan Agenda dan Pengumuman berskala `DESA` maupun `DUSUN`. |
| **Monitoring Peta Spasial Desa** | **Boleh** | Mengakses visualisasi sebaran titik lokasi se-Desa Bendung. |
| **Manajemen Akun Admin Dusun** | **Boleh** | Menambah akun operator, mengubah penugasan dusun, dan menonaktifkan akses (*logical remove*). |
| **Reset Password Akun Dusun** | **Boleh** | Mengatur ulang kata sandi akun Admin Dusun yang terkendala. |
| **Pemulihan Data (*Restore*)** | **Boleh** | Mengembalikan data operasional yang berstatus *Soft Deleted* menjadi aktif kembali. |
| **Penghapusan Permanen (*Hard Delete*)** | **Boleh** | Menghapus permanen data operasional nonaktif yang didukung sistem. |
| **Menambah Dusun Baru** | ❌ **Tidak Tersedia** | Implementasi portal saat ini menggunakan enam master Dusun tetap dan tidak menyediakan fungsi tambah dusun melalui antarmuka. |
| **Menghapus Permanen Master Dusun** | ❌ **Tidak Tersedia** | Enam wilayah dusun merupakan entitas tetap dan tidak dapat dihapus melalui antarmuka sistem. |

### Prinsip Ketetapan Enam Dusun
Implementasi portal saat ini dirancang secara khusus untuk melayani tata kelola enam wilayah dusun di Desa Bendung. Oleh karena itu:
- Antarmuka Super Admin tidak menyediakan tombol tambah dusun (*no create dusun*).
- Antarmuka Super Admin tidak menyediakan tombol hapus permanen dusun (*no hard delete dusun*).
- Apabila salah satu dusun sedang tidak ingin ditampilkan ke publik, tata kelola dilakukan melalui pengubahan status publikasi menjadi **`INACTIVE`**, tanpa mengubah struktur master data desa.

---

### Hasil yang Diharapkan dari Pembacaan Bab IV
Setelah mempelajari seluruh isi Bab IV ini, Super Admin Pemerintah Desa Bendung diharapkan:
1. Mampu mengoperasikan pusat kendali administrasi portal desa secara mandiri, tertib, dan berkesinambungan.
2. Memahami prosedur koordinasi tata kelola data dengan seluruh Admin Dusun di enam wilayah.
3. Menjalankan peran tata kelola keterbukaan informasi publik desa dengan penuh tanggung jawab sesuai batasan sistem yang telah ditetapkan.
