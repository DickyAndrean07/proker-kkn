# BAB V
# PEDOMAN STANDAR PENGELOLAAN INFORMASI

Bab ini memuat **pedoman standar dan etika pengelolaan informasi** bagi seluruh pengelola portal, baik Admin Dusun maupun Super Admin di lingkungan Pemerintah Desa Bendung. Berbeda dengan bab-bab sebelumnya yang menjelaskan tata cara teknis pengoperasian menu, Bab V berfokus pada standardisasi mutu data, ketepatan penentuan lokasi spasial, keseragaman media publikasi, perlindungan privasi warga, serta pemeliharaan data berkala.

Penerapan standar pengelolaan yang konsisten membantu memastikan bahwa informasi yang tersaji pada Portal Informasi Desa Bendung senantiasa rapi, dapat dipertanggungjawabkan, serta memberikan manfaat bagi masyarakat luas.

---

## 5.1 Prinsip Akurasi dan Validitas Data Desa

Setiap data yang dipublikasikan pada portal desa mencerminkan kredibilitas Pemerintah Desa Bendung dan pemangku wilayah dusun. Sebagai pedoman pengelolaan, data sebaiknya diperiksa terlebih dahulu sebelum dipublikasikan agar sesuai dengan kondisi yang diketahui oleh pengelola.

### Prinsip Utama Mutu Informasi
1. **Ketepatan Identitas (*Accuracy*):** Penulisan nama pamong, nama pemilik usaha UMKM, nama sarana publik, dan nomor kontak disesuaikan dengan data yang benar.
2. **Kesesuaian Ruang (*Spatial Consistency*):** Penentuan titik koordinat lokasi diposisikan pada objek fisik yang sesuai di dalam wilayah dusun yang bersangkutan.
3. **Keterbacaan Narasi (*Clarity*):** Deskripsi profil, agenda, maupun warta maklumat disusun menggunakan bahasa yang santun, jelas, dan mudah dipahami masyarakat.
4. **Kelayakan Publikasi (*Appropriateness*):** Informasi publik tidak memuat ujaran kebencian, informasi menyesatkan, atau materi yang tidak relevan dengan layanan dan informasi desa.

> **REKOMENDASI PENGELOLAAN: LEMBAR PERIKSA DATA MANDIRI**
> Sebelum menekan tombol simpan atau publikasikan, pengelola disarankan melakukan pemeriksaan mandiri secara singkat:
> - [ ] Nama orang, nama sarana, atau nama usaha sudah tepat dan benar ejaannya.
> - [ ] Nomor kontak WhatsApp aktif dan dapat dihubungi.
> - [ ] Alamat fisik ditulis lengkap dengan petunjuk lingkungan sekitar.
> - [ ] Tanggal dan jam kegiatan pada agenda telah dikonfirmasi ke panitia terkait.
> - [ ] Foto yang diunggah memiliki pencahayaan baik dan objek terlihat jelas.
> - [ ] Titik penanda pada peta telah diperiksa posisinya.
> - [ ] Izin publikasi administratif/offline telah diperoleh jika mencantumkan data pribadi.

### Prinsip Perbaikan Data vs Penonaktifan Data
Pengelola perlu memahami kapan sebaiknya menyunting data dan kapan menonaktifkan data:
- **Gunakan Fungsi Edit (Sunting):** Apabila terjadi kesalahan pengetikan huruf, perubahan nomor telepon, atau pembaruan foto, lakukan perbaikan langsung melalui tombol **Edit**. Jangan menonaktifkan data yang masih relevan hanya untuk memperbaiki isinya.
- **Gunakan Fungsi Nonaktifkan (*Soft Delete*):** Gunakan penonaktifan jika tempat usaha sudah tidak beroperasi, petugas sudah tidak bertugas, atau data sarana sudah tidak relevan untuk ditampilkan.

---

## 5.2 Panduan Teknis Penentuan Titik Koordinat Peta

Penentuan titik koordinat (*latitude* dan *longitude*) berfungsi untuk menempatkan pin penanda pada peta digital portal dan menghubungkannya dengan rute petunjuk arah Google Maps.

### Ketentuan Status Koordinat Berdasarkan Modul

| Modul Data | Status Pengisian Koordinat | Keterangan Tata Kelola |
|---|:---:|---|
| **Fasilitas Umum** | **WAJIB** | Sarana publik harus memiliki titik koordinat agar masyarakat dapat menemukan lokasinya pada peta interaktif dan membuka petunjuk arah. |
| **Data UMKM** | **OPSIONAL** | Sangat dianjurkan bagi UMKM yang memiliki gerai toko atau rumah produksi tetap. Dapat dikosongkan bagi usaha keliling atau jasa tanpa lokasi fisik tetap. |
| **Kontak Pelayanan** | **OPSIONAL** | Dapat diisi dengan titik koordinat pos pelayanan atau kantor dusun jika lokasi tersebut melayani kunjungan warga. |

<!-- FIGURE: MAP-001 | ../03-assets/screenshots/map/26_map_smart_input_gps.png -->
**Gambar — Antarmuka Penentuan Titik Koordinat (Klik Peta, Smart Input, dan GPS)**

<!-- FLOWCHART: FLOW-07 | ../03-assets/flowcharts/svg/FLOW-07-penentuan-koordinat.svg -->
**Diagram — Alur Penentuan Titik Koordinat**

### Tiga Metode Penentuan Koordinat pada Portal
Sistem menyediakan tiga cara penentuan koordinat yang dapat dipilih sesuai kemudahan pengelola:

1. **Metode 1: Klik Langsung pada Peta Interaktif**
   - Sangat cocok digunakan jika pengelola sudah mengenali bentuk bangunan dan jalan lingkungan pada peta.
   - Arahkan kursor dan klik pada titik objek yang dituju. Pin penanda akan berpindah dan angka koordinat terisi secara otomatis.
2. **Metode 2: Menggunakan Kolom Smart Input**
   - Cocok digunakan jika pengelola menyalin titik lokasi dari aplikasi *Google Maps*.
   - Salin koordinat atau tautan Google Maps yang sesuai dengan lokasi objek, lalu tempelkan (*paste*) pada kolom **Smart Input**.
   - Tekan tombol **Terapkan**. Sistem akan membaca koordinat dan mengarahkan pin penanda pada peta.
3. **Metode 3: Menggunakan Tombol GPS (Lokasi Saya)**
   - Efektif digunakan saat pengelola sedang berada langsung di lokasi fisik objek menggunakan perangkat dengan GPS aktif.
   - Klik tombol **Gunakan Lokasi Saya (GPS)** dan izinkan peramban mengakses lokasi perangkat.
   - Sistem akan membaca posisi perangkat dan menaruh pin penanda pada lokasi terkini Anda.

> **PENTING: VERIFIKASI AKHIR POSISI PIN**
> Akurasi lokasi dapat berbeda menurut perangkat dan kondisi lingkungan. Selalu periksa kembali posisi pin pada kanvas peta sebelum menekan tombol simpan guna memastikan pin tepat pada objek yang dituju.

---

## 5.3 Standar Format Foto dan Media Publikasi

Foto dan gambar memberikan representasi visual yang nyata bagi pengunjung portal. Pengelola disarankan mematuhi batasan teknis sistem serta menerapkan standar visual yang baik.

### Batasan Teknis Berkas Gambar (Fitur Sistem)
- **Format Berkas yang Didukung:** JPG, JPEG, PNG, dan WebP.
- **Ukuran Maksimum Berkas:** Maksimal **3 MB** per berkas gambar.
- **Batasan Unggahan per Modul:**
  - *Identitas Desa & Profil Dusun:* 1 berkas foto banner utama.
  - *Kontak Pelayanan:* 1 berkas pasfoto petugas (opsional).
  - *UMKM:* 1 berkas foto utama produk atau tempat usaha (opsional, tidak ada galeri banyak foto).
  - *Fasilitas Umum:* 1 berkas foto gedung sarana (opsional).
  - *Agenda Kegiatan:* Agenda mendukung media dengan peran `POSTER_AWAL` dan `DOKUMENTASI` sesuai kebutuhan publikasi yang tersedia pada formulir.
  - *Pengumuman Resmi:* Murni informasi berbasis teks tanpa unggahan berkas atau lampiran PDF.

### Panduan Komposisi dan Kualitas Foto (Rekomendasi Pengelolaan)
1. **Pencahayaan yang Cukup:** Ambil foto pada siang hari atau di tempat dengan penerangan terang agar objek terlihat jelas dan tidak berbayang gelap.
2. **Fokus dan Kejernihan Objek:** Pastikan foto tidak goyang (*blur*), tidak terpotong pada bagian penting, serta objek utama (produk/gedung) berada di tengah bingkai foto (*center frame*).
3. **Orientasi Foto yang Tepat:**
   - Gunakan orientasi **Lanskap (Mendatar / 16:9 atau 4:3)** untuk foto banner desa, foto dusun, foto gedung fasilitas, dan dokumentasi agenda kegiatan.
   - Gunakan orientasi **Potret (Tegak / 3:4 atau 1:1)** untuk pasfoto petugas pelayanan dan foto produk UMKM.
4. **Kebersihan Visual:** Hindari mengunggah foto yang memuat barang yang tidak relevan atau mengganggu estetika publikasi.

> **REKOMENDASI PENGELOLAAN: EFISIENSI FOTO**
> Jika ukuran gambar melebihi 3 MB, lakukan *resize* atau kompresi gambar menggunakan alat yang sesuai sebelum mengunggahnya ke portal, sehingga proses penyimpanan berjalan lancar.

---

## 5.4 Standar Penulisan Nomor WhatsApp dan Kontak Resmi

Nomor kontak WhatsApp merupakan saluran komunikasi utama yang menghubungkan warga secara langsung dengan pamong pelayanan dan pemilik usaha UMKM. Penulisan nomor yang tepat memastikan sambungan komunikasi dapat dibuka dengan lancar.

### Pedoman Format Penulisan Nomor
1. **Gunakan Format Angka Baku:** Masukkan nomor telepon dengan awalan standar nasional `08` atau awalan internasional `628` tanpa menyisipkan tanda spasi, tanda hubung, atau tanda kurung.
   - *Contoh:* `081234567890` atau `6281234567890`
2. **Pastikan Nomor Terdaftar Aktif di WhatsApp:** Periksa dan pastikan bahwa nomor yang dimasukkan aktif terdaftar pada aplikasi WhatsApp resmi atau WhatsApp Business.
3. **Uji Coba Sambungan Pasca-Penyimpanan:** Setelah menyimpan data kontak atau UMKM baru, buka halaman publik dusun dan lakukan pengetesan dengan mengetuk tombol **Hubungi via WhatsApp** untuk memastikan tautan obrolan membuka tujuan yang benar.

> **PERHATIAN: PENGGUNAAN NOMOR LAYANAN RESMI**
> Untuk Kontak Pelayanan Pamong Desa, disarankan menggunakan nomor dinas atau nomor pelayanan khusus lingkungan agar privasi percakapan pribadi petugas di luar jam dinas tetap terjaga.

---

## 5.5 Perlindungan Privasi dan Prosedur Izin Publikasi Data Warga

Sebagai penyelenggara informasi publik desa, Pemerintah Desa Bendung berkomitmen menjaga privasi warga. Data yang dipublikasikan melalui halaman publik portal dapat diakses oleh pengunjung.

### Prinsip Izin Publikasi Administratif / Offline
Sistem portal dirancang sederhana dan **tidak menyediakan formulir persetujuan digital (*digital consent checkbox*)** di dalam aplikasi. Oleh karena itu, pengelola bertanggung jawab menjalankan prosedur izin secara administratif di lapangan:

> **PENTING: PROSEDUR IZIN PUBLIKASI OFFLINE**
> Pemerintah desa dan pengelola dusun perlu memastikan izin publikasi administratif/offline telah diperoleh sebelum mencantumkan data pribadi yang akan ditampilkan kepada publik, seperti nama, nomor WhatsApp pribadi, foto individu, alamat rumah, atau titik koordinat tempat tinggal.

### Pertimbangan Privasi Berdasarkan Jenis Informasi

| Jenis Data | Potensi Risiko Privasi | Tindakan Pengamanan dan Standar Publikasi |
|---|---|---|
| **Nomor WhatsApp Pamong** | Gangguan pesan di luar jam dinas | Mintakan persetujuan pamong yang bersangkutan dan informasikan bahwa nomor akan ditampilkan secara terbuka pada kartu pelayanan. |
| **Data Kontak Pemilik UMKM** | Pesan penawaran / panggilan promosi | Pastikan pemilik usaha telah menyetujui pencantuman nomor WhatsApp untuk keperluan promosi produk usahanya. |
| **Foto Individu Warga** | Risiko publikasi foto tanpa izin | Pastikan izin publikasi yang diperlukan telah diperoleh sebelum menampilkan foto individu, terutama foto anak-anak pada kegiatan kemasyarakatan. |
| **Alamat & Koordinat Rumah** | Titik lokasi privat diketahui publik | Pada data Kontak dan UMKM rumahan, pastikan pemilik rumah menyetujui jika titik lokasi rumahnya dipetakan pada peta publik. |

---

## 5.6 Jadwal dan Siklus Pembaruan Data Berkala

Agar portal desa tetap relevan dan bermanfaat bagi masyarakat, data yang tersaji perlu diperbarui secara berkala. Sistem portal tidak mengirimkan pengingat (*reminder*) otomatis, sehingga pemeliharaan data bergantung pada ketertiban operasional pengelola.

### Rekomendasi Siklus Pemeliharaan Data
Contoh jadwal pemeriksaan berikut dapat disesuaikan dengan kebutuhan dan kapasitas Pemerintah Desa Bendung:

| Kelompok Data | Pemicu Pembaruan Lapangan (*Trigger*) | Contoh Waktu Pemeriksaan |
|---|---|---|
| **Identitas Desa & Dusun** | Pergantian Kepala Desa, pergantian Kepala Dusun, perubahan jumlah RT/RW. | Diperiksa saat terjadi perubahan struktur organisasi desa atau secara berkala per semester. |
| **Kontak Pelayanan** | Mutasi tugas pamong, pergantian ketua RT/RW, perubahan nomor ponsel dinas. | Diperiksa secara berkala setiap beberapa bulan atau saat ada pembaruan nomor kontak. |
| **Data Usaha UMKM** | Usaha baru beroperasi, perubahan jam buka, pergantian nomor WhatsApp, atau usaha tutup. | Diperiksa secara berkala melalui koordinasi berkala dengan kelompok usaha warga. |
| **Direktori Fasilitas** | Pembangunan fasilitas baru, renovasi gedung, perubahan nama sarana, perbaikan alamat. | Diperiksa setiap ada pembangunan atau perubahan sarana fisik desa. |
| **Agenda Kegiatan** | Perencanaan acara baru, perubahan tanggal/lokasi, penyematan dokumentasi pasca-acara. | Diperbarui saat jadwal terbit dan ditinjau kembali setelah kegiatan apabila dokumentasi atau informasi perlu diperbarui. |
| **Pengumuman Resmi** | Maklumat baru pemerintah desa, masa aktif warta telah terlewati (*kedaluwarsa*). | Diperbarui saat ada kebijakan baru dan ditinjau status relevansinya secara berkala. |
| **Akun Admin Dusun** | Pergantian operator wilayah dusun atau aparatur purnatugas. | Disesuaikan langsung oleh Super Admin saat terjadi pergantian pengelola. |

> **REKOMENDASI PENGELOLAAN: KOORDINASI BERKALA DESA & DUSUN**
> Pemerintah Desa disarankan menyelenggarakan pertemuan koordinasi berkala antara Super Admin dan Admin Dusun untuk menyelaraskan pembaruan data antar-wilayah dan meninjau publikasi warta di setiap dusun.

---

## 5.7 Memahami Perbedaan Data Aktif, Data Nonaktif, dan Arsip

Pengelola perlu memahami perbedaan status data di dalam sistem agar tidak terjadi kekeliruan antara data yang sedang tayang, data yang dinonaktifkan, dan data yang masuk ke arsip pengumuman.

### Matriks Perbandingan Status Data Portal

| Status Data | Ditampilkan ke Publik? | Dapat Dipulihkan (*Restore*)? | Peran Pengelola Terkait |
|---|:---:|:---:|---|
| **Data Aktif (`Active`)** | **Ya** *(sesuai aturan visibilitas)* | Tidak Berlaku *(sudah aktif)* | Data dikelola sesuai kewenangan masing-masing pengguna. |
| **Data Nonaktif (`Soft Deleted`)** | ❌ **Tidak Tampil** | **Bisa Dipulihkan** *(oleh Super Admin)* | Data operasional yang disembunyikan dari publik. Tetap tersimpan di sistem dan dapat dipulihkan oleh Super Admin selama belum dihapus permanen. |
| **Arsip Pengumuman** | **Ya** *(di halaman Arsip)* | Tidak Berlaku *(bukan data terhapus)* | Warta pengumuman resmi yang tanggal kedaluwarsanya telah terlewati. Setelah masa berlaku terlewati, pengumuman ditampilkan pada halaman Arsip Pengumuman dan tetap dapat dibaca publik. |

### Aturan Visibilitas Data Aktif
Status data *Aktif* tidak menjamin data langsung tampil di hadapan publik apabila kondisi berikut terjadi:
1. **Dusun Berstatus Nonaktif (`INACTIVE`):** Fasilitas, UMKM, kontak, agenda, dan pengumuman yang berstatus aktif pada dusun tersebut akan disembunyikan dari publik sampai status publikasi dusun diaktifkan kembali oleh Super Admin.
2. **Pengumuman Telah Kedaluwarsa:** Pengumuman aktif yang tanggal berlakunya telah terlewati ditampilkan pada halaman arsip.
3. **Agenda yang Telah Selesai:** Agenda yang tanggal pelaksanaannya telah lewat berstatus *SELESAI*.

Arsip Pengumuman merupakan mekanisme terpisah dari penonaktifan data operasional.

<!-- FLOWCHART: FLOW-04 | ../03-assets/flowcharts/svg/FLOW-04-lifecycle-data.svg -->
**Diagram — Siklus Pengelolaan Data Operasional**

---

### Hasil yang Diharapkan dari Pembacaan Bab V
Setelah mempelajari pedoman standar pada Bab V ini, pengelola portal diharapkan:
1. Menjaga konsistensi, keakuratan, dan estetika informasi yang diunggah ke Portal Informasi Desa Bendung.
2. Mematuhi standar penentuan titik koordinat, format media foto, dan penulisan kontak resmi secara tertib.
3. Menjunjung tinggi perlindungan privasi warga melalui prosedur izin publikasi yang benar.
4. Menjalankan pemutakhiran data secara berkala demi memelihara kesinambungan informasi desa.
