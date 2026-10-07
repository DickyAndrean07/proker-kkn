# BAB VI
# TROUBLESHOOTING & FAQ

Bab ini menyediakan panduan pemecahan masalah (*troubleshooting*) dan jawaban atas pertanyaan yang sering diajukan (*Frequently Asked Questions* / FAQ) dalam pengoperasian Portal Informasi Desa Bendung. Panduan ini dirancang praktis agar masyarakat umum, Admin Dusun, dan Super Admin dapat mengenali penyebab kendala yang terjadi serta mengambil langkah penanganan yang tepat dan mandiri.

---

## 6.1 Kendala Masuk Sistem

Subbab ini memandu penyelesaian masalah saat Admin Dusun atau Super Admin mengalami kegagalan saat mencoba masuk (*login*) ke panel administrasi portal.

### Gejala
- Sistem menampilkan pesan peringatan bahwa kredensial tidak sesuai.
- Tombol masuk telah diklik, namun layar kembali ke formulir login kosong.
- Halaman login tidak dapat dimuat pada peramban web.

### Kemungkinan Penyebab
1. **Kesalahan Pengetikan Nama Pengguna (*Username*):** Username salah atau terdapat spasi yang tidak sengaja dimasukkan.
2. **Kesalahan Kata Sandi (*Password*):** Karakter sandi keliru atau tombol *Caps Lock* pada papan ketik aktif.
3. **Akun Telah Dinonaktifkan Aksesnya:** Akun Admin Dusun yang bersangkutan telah dinonaktifkan akses pengelolaannya oleh Super Admin.
4. **Sesi Kerja Berakhir (*Session Timeout*):** Sesi login sebelumnya telah habis masa aktifnya karena peramban lama tidak digunakan.
5. **Gangguan Sambungan Internet:** Perangkat pengguna tidak terhubung ke jaringan internet yang stabil.

### Langkah Penanganan
1. Periksa kembali penulisan **Username**. Pastikan tidak ada spasi di awal maupun di akhir teks.
2. Ketikkan ulang kata sandi. Gunakan **Ikon Mata** pada kolom password untuk menampilkan karakter sandi sehingga Anda dapat memastikan kebenaran huruf, angka, dan simbol yang dimasukkan.
3. Muat ulang (*refresh*) halaman peramban web Anda, lalu coba lakukan login kembali.
4. Pastikan koneksi internet perangkat Anda berfungsi dengan mencoba membuka halaman web lain.

### Jika Masih Bermasalah
- Bagi **Admin Dusun**: Segera hubungi Super Admin Pemerintah Desa Bendung untuk memeriksa status keaktifan akun atau meminta prosedur reset kata sandi resmi (lihat Subbab 6.2).
- Bagi **Super Admin**: Pastikan kredensial akun utama desa yang tersimpan dalam catatan resmi tata kelola desa sudah benar. Jika kendala berasal dari ketersediaan layanan web secara luas, hubungi pengelola teknis sistem desa.

---

## 6.2 Lupa Password Admin Dusun

Portal Informasi Desa Bendung menerapkan kebijakan keamanan terpusat dan **tidak menyediakan fitur lupa kata sandi mandiri (*self-service password reset*)** melalui pengiriman email publik. Apabila Admin Dusun lupa kata sandi akunnya, pengaturan ulang sandi dilakukan oleh Super Admin.

### Gejala
- Admin Dusun tidak dapat mengingat kata sandi akun dan tidak bisa masuk ke panel kerja wilayahnya.

### Kemungkinan Penyebab
- Kata sandi terlupa setelah masa rotasi tugas atau catatan sandi hilang.

### Langkah Penanganan
1. Admin Dusun menyampaikan permohonan reset kata sandi secara resmi kepada Super Admin (Pemerintah Desa Bendung).
2. Super Admin membuka menu **Admin Dusun** pada panel kendali desa.
3. Super Admin mencari nama akun Admin Dusun yang dituju, lalu mengklik tombol **Reset Password**.
4. Super Admin memasukkan kata sandi baru (minimal 6 karakter) dan mengonfirmasikannya.
5. Super Admin menyimpan perubahan dan menyerahkan kata sandi baru kepada Admin Dusun melalui saluran komunikasi pribadi yang aman.
6. Admin Dusun melakukan login ulang menggunakan kata sandi baru tersebut.

> **PERHATIAN: KEAMANAN PENYERAHAN KATA SANDI**
> Jangan mengirimkan kata sandi baru melalui grup pesan terbuka atau media sosial publik. Serahkan kata sandi baru secara tertutup melalui pesan pribadi atau saat tatap muka di balai desa.

---

## 6.3 Gagal Mengunggah Foto atau Banner

Kendala pengunggahan foto dapat terjadi saat memperbarui Identitas Desa, profil dusun, kartu kontak, etalase UMKM, sarana fasilitas, ataupun warta agenda.

### Gejala
- Sistem menampilkan pesan validasi pada formulir.
- Proses penyimpanan terasa berhenti atau halaman gagal memuat foto yang baru dipilih.

<!-- FIGURE: TRB-001 | ../03-assets/screenshots/troubleshooting/28_trb_validation_error_example.png -->
**Gambar — Contoh Pesan Validasi pada Formulir**

### Kemungkinan Penyebab
1. **Ukuran Berkas Melebihi Batas:** Ukuran file foto lebih besar dari **3 MB**.
2. **Format Berkas Tidak Didukung:** Format gambar bukan JPG, JPEG, PNG, atau WebP (misalnya menggunakan format PDF, GIF, BMP, atau HEIC).
3. **Berkas Rusak (*Corrupt*):** Berkas gambar di perangkat mengalami kerusakan data sehingga tidak dapat diproses peramban.
4. **Koneksi Terputus:** Sambungan internet terputus saat proses pengiriman berkas gambar berlangsung.

### Langkah Penanganan
1. Periksa format ekstensi berkas foto Anda. Pastikan berakhiran `.jpg`, `.jpeg`, `.png`, atau `.webp`.
2. Periksa ukuran berkas foto. Jika ukurannya melebihi 3 MB, lakukan *resize* (pengecilan dimensi) atau kompresi berkas menggunakan aplikasi pengolah gambar sebelum mengunggah.
3. Pilih ulang berkas foto yang telah disesuaikan melalui tombol penjelajah berkas.
4. Klik kembali tombol simpan pada formulir.

---

## 6.4 Marker / Titik Lokasi Tidak Muncul atau Bergeser

Subbab ini membantu mengatasi kendala yang berkaitan dengan penanda lokasi pada peta digital desa maupun peta dusun.

### Gejala
- Objek data (UMKM atau Kontak) tidak memiliki pin penanda pada peta interaktif.
- Pin penanda fasilitas atau usaha berada di luar wilayah yang semestinya.
- Tombol GPS tidak menempatkan pin pada titik lokasi yang diharapkan.

### Kemungkinan Penyebab
1. **Koordinat Belum Diisi:** Pada modul UMKM dan Kontak Pelayanan, pengisian koordinat bersifat **opsional**. Jika kolom koordinat dikosongkan, data tersebut memang tidak akan memiliki pin pada peta.
2. **Perbedaan Akurasi Perangkat GPS:** Akurasi penentuan lokasi otomatis melalui tombol GPS bergantung pada perangkat keras dan kondisi lingkungan sekitar.
3. **Format Koordinat pada Smart Input Keliru:** Teks koordinat atau tautan URL Google Maps yang ditempelkan tidak terbaca dengan benar.

### Langkah Penanganan
1. Buka kembali data yang bersangkutan melalui tombol **Edit**.
2. **Jika Pin Belum Ada:** Tentukan titik lokasi menggunakan salah satu dari tiga metode:
   - Klik langsung pada objek bangunan di peta; atau
   - Tempelkan koordinat/tautan Google Maps pada kolom **Smart Input** lalu klik *Terapkan*; atau
   - Klik tombol **Gunakan Lokasi Saya (GPS)** jika sedang berada di lokasi fisik objek.
3. **Jika Posisi Pin Belum Sesuai:** Tentukan kembali titik hingga posisi pin sesuai dengan lokasi yang ingin dipublikasikan.
4. Periksa kembali angka koordinat yang tertera, lalu klik tombol **Simpan Perubahan**.

> **CATATAN**
> Sarana Fasilitas Umum **wajib** memiliki titik koordinat. Formulir fasilitas tidak dapat disimpan apabila koordinat belum ditentukan.

---

## 6.5 WhatsApp atau Google Maps Tidak Bekerja

Tautan tombol WhatsApp dan Google Maps dirancang untuk menghubungkan pengunjung portal ke aplikasi komunikasi dan navigasi di perangkat masing-masing jika tersedia.

### Gejala
- Mengklik tombol **Hubungi via WhatsApp** menghasilkan halaman kosong atau pesan nomor tidak terdaftar.
- Mengklik tombol **Petunjuk Arah** tidak membuka peta rute Google Maps.

### Kemungkinan Penyebab
1. **Kesalahan Pengetikan Nomor Telepon:** Nomor WhatsApp yang dimasukkan pengelola salah digit, kurang angka, atau mengandung karakter non-angka yang tidak valid.
2. **Aplikasi Belum Terpasang:** Perangkat pengunjung belum memiliki aplikasi WhatsApp/Google Maps atau peramban tidak mendukung pembukaan tautan eksternal.
3. **Koordinat Titik Lokasi Keliru:** Angka koordinat fasilitas tidak valid sehingga Google Maps tidak dapat menentukan rute tujuan.

### Langkah Penanganan
- **Untuk Masalah WhatsApp:**
  1. Pengelola memeriksa kembali nomor kontak melalui formulir **Edit**.
  2. Pastikan penulisan nomor menggunakan format angka baku (contoh: `081234567890` atau `6281234567890`).
  3. Pastikan nomor tersebut memang aktif terdaftar di WhatsApp.
  4. Lakukan pengetesan tombol WhatsApp pada halaman publik setelah menyimpan data.
- **Untuk Masalah Google Maps:**
  1. Pengelola memeriksa kembali posisi pin koordinat sarana melalui formulir **Edit**.
  2. Simpan perbaikan dan uji coba kembali tombol *Petunjuk Arah*.

---

## 6.6 Data Tidak Tampil di Halaman Publik

Ketika data telah ditambahkan tetapi tidak terlihat pada halaman publik desa atau dusun, pengelola dapat melakukan pemeriksaan bertahap sesuai panduan berikut.

### Checklist Diagnosis Visibilitas Data

| Urutan Pemeriksaan | Kondisi yang Perlu Dicek | Penjelasan dan Solusi |
|:---:|---|---|
| **1** | **Apakah status Dusun aktif?** | Ketika Dusun berstatus **`INACTIVE`**, halaman publik Dusun tidak ditampilkan sesuai aturan visibilitas. Admin Dusun tetap dapat login dan mengelola data internal wilayahnya. |
| **2** | **Apakah data berstatus dinonaktifkan?** | Data yang dinonaktifkan tidak tampil kepada publik. Jika Admin Dusun menduga data tidak sengaja dinonaktifkan, hubungi Super Admin. Super Admin dapat memeriksa data nonaktif melalui filter status dan melakukan pemulihan bila diperlukan. |
| **3** | **Apakah masa berlaku pengumuman habis?** | Pengumuman yang tanggal kedaluwarsanya telah lewat otomatis ditampilkan pada halaman **Arsip Pengumuman** (`/pengumuman/arsip`). |
| **4** | **Apakah data UMKM/Kontak memiliki koordinat?** | Pada peta interaktif, UMKM dan Kontak hanya muncul jika kolom koordinatnya diisi saat pendaftaran data. |
| **5** | **Apakah tombol simpan sudah ditekan?** | Pastikan proses penyimpanan selesai dan sistem memberikan pemberitahuan bahwa perubahan berhasil disimpan. |

> **PENTING: INTEGRITAS DUSUN NONAKTIF**
> Ketika Dusun berstatus *INACTIVE*, halaman publik Dusun tidak ditampilkan sesuai aturan visibilitas. Admin Dusun tetap dapat login dan mengelola data internal wilayahnya.

---

## 6.7 Pengumuman Berpindah ke Arsip

Pengelola atau warga terkadang mendapati bahwa warta pengumuman tertentu tidak lagi muncul pada deretan warta utama beranda desa atau halaman dusun.

### Gejala
- Pengumuman yang sebelumnya tayang di halaman depan kini tidak terlihat pada daftar warta terkini.

### Penjelasan Sistem
Kondisi ini merupakan perilaku normal sistem dan **bukan merupakan kesalahan (*bukan error*)**. Sistem portal mengatur masa publikasi warta:
- Pengumuman aktif tayang selama tanggal batas berlaku (*tanggal kedaluwarsa*) belum terlewati.
- Setelah tanggal kedaluwarsa terlewati, pengumuman ditampilkan pada halaman **Arsip Pengumuman** (`/pengumuman/arsip`) dan tetap dapat dibaca secara terbuka oleh publik.

### Langkah Penanganan
- Jika masa berlakunya memang telah selesai, pengumuman dapat tetap tersedia pada halaman Arsip Pengumuman.
- Jika pengelola ingin memperpanjang masa tayang pengumuman di halaman depan, buka formulir **Edit Pengumuman**, perbarui kolom **Tanggal Kedaluwarsa** ke tanggal di masa depan, lalu klik **Simpan Perubahan**.

---

## 6.8 Data Tidak Sengaja Dinonaktifkan

Apabila seorang pengelola tidak sengaja menekan tombol hapus/nonaktifkan pada data yang masih dibutuhkan, data tersebut dapat dipulihkan kembali.

### Panduan bagi Admin Dusun
1. Perlu diketahui bahwa Admin Dusun **tidak memiliki hak pemulihan (*restore*) mandiri** pada antarmuka panel dusun.
2. Catat nama data dan modul yang tidak sengaja dinonaktifkan (misalnya: *Fasilitas Umum: Balai Pertemuan*).
3. Segera laporkan kepada Super Admin Pemerintah Desa Bendung untuk meminta pemulihan data.

### Panduan bagi Super Admin
1. Buka modul data yang bersangkutan pada panel Super Admin (misalnya menu **Kelola Fasilitas**).
2. Pada bilah filter di atas tabel data, ubah pilihan **Status Data** menjadi **Soft Deleted (Nonaktif)**.
3. Temukan baris data yang dimaksud.
4. Klik tombol **Pulihkan** (*Restore*) dan konfirmasikan pada kotak dialog.
5. Status data kembali aktif. Penayangan data di halaman publik akan mengikuti aturan visibilitas yang berlaku.

> **PERHATIAN: BATASAN HAPUS PERMANEN**
> Apabila Super Admin telah menjalankan prosedur *Hapus Permanen (Hard Delete)* terhadap suatu data, data tersebut **tidak dapat dipulihkan kembali melalui portal**.

---

## 6.9 Pertanyaan yang Sering Diajukan (FAQ)

Berikut adalah ringkasan jawaban atas pertanyaan yang paling sering diajukan terkait pengoperasian Portal Informasi Desa Bendung:

**Q1: Apakah masyarakat umum harus membuat akun untuk membaca informasi desa?**  
*A:* Tidak. Informasi yang telah dipublikasikan pada portal dapat diakses tanpa membuat akun masyarakat.

**Q2: Apakah Admin Dusun dapat menyunting atau menghapus data milik wilayah dusun lain?**  
*A:* Tidak. Wilayah kelola Admin Dusun terisolasi secara khusus pada dusun tempatnya ditugaskan. Hanya Super Admin yang berwenang mengelola data lintas dusun se-Desa Bendung.

**Q3: Apakah Admin Dusun dapat memulihkan data yang telah dinonaktifkan?**  
*A:* Tidak. Fitur pemulihan data (*restore*) secara eksklusif merupakan kewenangan Super Admin. Admin Dusun perlu berkoordinasi dengan Super Admin untuk memulihkan data nonaktif.

**Q4: Mengapa tombol tambah dusun baru tidak tersedia di panel Super Admin?**  
*A:* Implementasi portal saat ini menggunakan struktur master enam dusun tetap (Bendung, Klubuk, Bantengan, Belik, Pohsengir, dan Kaliasin). Tata kelola wilayah dilakukan melalui pengubahan status publikasi (*ACTIVE/INACTIVE*).

**Q5: Ke mana perginya warta pengumuman yang tanggal berlakunya sudah lewat?**  
*A:* Pengumuman yang telah kedaluwarsa ditampilkan pada halaman Arsip Pengumuman (`/pengumuman/arsip`) dan tetap dapat ditelusuri serta dibaca oleh masyarakat.

**Q6: Apakah setiap unit UMKM wajib memiliki titik penanda pada peta desa?**  
*A:* Tidak. Pengisian titik koordinat pada modul UMKM bersifat opsional. Usaha yang tidak memiliki toko fisik tetap dapat terdaftar di direktori tanpa pin peta.

**Q7: Apakah sarana fasilitas umum wajib memiliki titik koordinat?**  
*A:* Ya. Seluruh sarana fasilitas umum wajib memiliki titik koordinat agar lokasinya dapat dipetakan dan dihubungkan dengan fitur petunjuk arah Google Maps.

**Q8: Apakah masyarakat dapat melakukan pemesanan dan pembayaran langsung di portal?**  
*A:* Tidak. Portal berfungsi sebagai etalase/direktori informasi usaha dan tidak menyediakan fungsi belanja atau pembayaran di dalam sistem. Pengunjung dapat menghubungi pemilik usaha melalui WhatsApp untuk memperoleh informasi lebih lanjut.

**Q9: Apakah tersedia fitur lupa kata sandi mandiri melalui pengiriman tautan email?**  
*A:* Tidak. Pengaturan ulang kata sandi dilakukan secara terpusat oleh Super Admin melalui formulir Reset Password demi menjaga keamanan akun desa.

**Q10: Apakah pengelola dapat mengunggah berkas dokumen PDF pada modul pengumuman?**  
*A:* Tidak. Pengumuman publik disajikan murni sebagai informasi teks tanpa lampiran berkas terpisah.

**Q11: Apakah peta interaktif menampilkan garis batas wilayah fisik antar-dusun?**  
*A:* Tidak. Peta menampilkan titik lokasi, bukan garis batas wilayah antar-Dusun.

**Q12: Apakah tersedia kotak pencarian nama tempat otomatis pada peta?**  
*A:* Tidak. Fitur pencarian nama lokasi otomatis tidak tersedia. Pengunjung dapat menggunakan filter yang tersedia dan menjelajahi peta secara manual.

---

## 6.10 Saluran Bantuan dan Eskalasi ke Super Admin

Koordinasi yang tertib dapat membantu penanganan kendala menjadi lebih terarah. Apabila terjadi kendala teknis sistem di luar kewenangan antarmuka pengguna, pengelola dapat menghubungi pengelola teknis sistem.

### Kapan Admin Dusun Perlu Menghubungi Super Admin?
Admin Dusun disarankan berkoordinasi dengan Super Admin apabila mengalami kondisi berikut:
1. Mengalami kendala lupa kata sandi akun Admin Dusun.
2. Memerlukan pemulihan (*restore*) atas data operasional yang tidak sengaja dinonaktifkan.
3. Memerlukan penambahan nama Kategori Fasilitas baru yang belum tersedia di dalam master kategori.
4. Terjadi rotasi penugasan wilayah dusun atau pergantian petugas operator dusun.
5. Halaman publik dusun tidak dapat diakses masyarakat akibat status dusun nonaktif (*INACTIVE*).

### Rekomendasi Format Penyampaian Kendala
Agar Super Admin dapat memberikan bantuan dengan tepat sasaran, Admin Dusun disarankan menyampaikan laporan kendala dengan menyertakan rincian berikut:

```text
FORMAT LAPORAN KENDALA TATA KELOLA
---------------------------------------------
1. Nama Dusun       : [Nama Dusun]
2. Nama Akun        : [Username Admin Dusun]
3. Modul Terkait    : [Nama Modul]
4. Nama Data        : [Nama Data]
5. Kendala          : [Ringkasan Kendala]
6. Tangkapan Layar  : [Jika diperlukan]
```

> **REKOMENDASI PENGELOLAAN: EVALUASI BERKALA**
> Koordinasi pengelolaan portal sebaiknya dilakukan secara berkala melalui pertemuan tatap muka di kantor balai desa atau komunikasi resmi perangkat desa guna mengevaluasi kelengkapan data di seluruh wilayah.
