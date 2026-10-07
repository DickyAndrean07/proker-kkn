# WORD LAYOUT PRODUCTION REPORT
## Buku Panduan Portal Informasi Desa Bendung (Master Microsoft Word A5)

**Tanggal Produksi:** 2026-08-21  
**Status Produksi:** `WORD MASTER STATUS: DRAFT LAYOUT COMPLETE — READY FOR HUMAN VISUAL REVIEW`  
**Otoritas Teknis:** DOCX Skill & Production Manifest  
**Manuskrip Sumber:** `docs/07-handover/02-manuscript/` (Status: *Locked & 100% Preserved*)

---

## 1. Ringkasan Eksekutif & File Master

| Parameter | Nilai / Spesifikasi |
|---|---|
| **Path File Master DOCX** | `docs/07-handover/04-final-book/docx/Panduan-Portal-Informasi-Desa-Bendung-2026.docx` |
| **Ukuran File DOCX** | 14.34 MB (15,033,318 bytes) |
| **Format Dokumen** | Microsoft Word Document (.docx, OpenXML standard) |
| **Ukuran Halaman (Page Setup)** | A5 (148 mm × 210 mm / 5.83 in × 8.27 in) |
| **Orientasi Halaman** | Portrait |
| **Pengaturan Margin (Mirror Margins)** | Top: 1.9 cm \| Bottom: 1.9 cm \| Inside: 2.1 cm \| Outside: 1.6 cm |
| **Lebar Bidang Cetak Efektif** | 11.1 cm (111 mm = 6293 DXA / 314.65 pt) |
| **Estimasi Total Halaman** | ~88–92 halaman (termasuk cover depan & belakang) |

---

## 2. Struktur Bagian & Rentang Halaman

| Bagian Buku | Penomoran Halaman | Rentang Estimasi | Deskripsi & Komponen |
|---|:---:|:---:|---|
| **Cover Depan** | *Tanpa Nomor* | Halaman Depan | Full-page cover dari approved asset (`cover-depan.png`), tanpa header/footer. |
| **Front Matter** | Romawi Kecil (`i` s.d. `vii`) | Hal. i–vii | Halaman Judul, Hak Cipta & Identitas, Kata Pengantar, Daftar Isi, Daftar Gambar, Daftar Tabel, Petunjuk Penggunaan Buku. |
| **BAB I: Mengenal Portal** | Arab (`1` s.d. `8`) | Hal. 1–8 | Latar belakang, tujuan, manfaat 3 peran, hak akses (FLOW-03 & Tabel 1.1), ringkasan fitur, panduan membaca. |
| **BAB II: Panduan Masyarakat** | Arab (`9` s.d. `20`) | Hal. 9–20 | Akses portal (FLOW-01), beranda (PUB-001/002), 6 dusun (PUB-003), peta (PUB-004), kontak (PUB-005), UMKM (PUB-006), fasilitas (PUB-007), agenda & pengumuman (PUB-008). |
| **BAB III: Admin Dusun** | Arab (`21` s.d. `38`) | Hal. 21–38 | Login (AUTH-001), dashboard (AD-001/FLOW-02), logout (AUTH-002), profil dusun (AD-002), kontak (AD-003), UMKM (AD-004/MAP-001), fasilitas (AD-005/FLOW-07), agenda (AD-006/FLOW-05), pengumuman (AD-007/FLOW-06), penonaktifan data (FLOW-04), 8 tabel panduan (Tabel 3.1–3.8). |
| **BAB IV: Super Admin** | Arab (`39` s.d. `58`) | Hal. 39–58 | Login/Dashboard (SA-001/FLOW-08), identitas desa (SA-002), master 6 dusun (SA-003), data lintas dusun, kategori fasilitas (SA-004), warta desa (SA-005), monitoring spasial (SA-007), akun operator (SA-008), reset password, restore (SA-006/FLOW-04), hard delete, kewenangan master 6 dusun (Tabel 4.1–4.5). |
| **BAB V: Pedoman Pengelolaan** | Arab (`59` s.d. `67`) | Hal. 59–67 | Akurasi data, standar koordinat peta (MAP-001/FLOW-07), standar foto/media, format nomor WA, privasi warga, siklus pembaruan, status data aktif/nonaktif/arsip (FLOW-04, Tabel 5.1–5.4). |
| **BAB VI: Troubleshooting & FAQ** | Arab (`68` s.d. `79`) | Hal. 68–79 | Kendala login, lupa password, upload foto (TRB-001), marker peta, WhatsApp/Maps, diagnosis visibilitas (Tabel 6.1), arsip pengumuman, restore, 12 butir FAQ, saluran eskalasi & format laporan. |
| **Back Matter** | Arab (`80` s.d. `88`) | Hal. 80–88 | Glosarium istilah portal, Checklist Pemeliharaan Berkala (matriks periksa `[ ]`), Profil Tim Penyusun KKN (tabel identitas). |
| **Cover Belakang** | *Tanpa Nomor* | Halaman Belakang | Full-page cover dari approved asset (`cover-belakang.png`), tanpa header/footer. |

---

## 3. Sistem Tipografi & Gaya (Word Style Hierarchy)

Seluruh pemformatan interior menggunakan style terpusat dengan palet warna bernuansa hijau-putih (*Forest Green Theme*) selaras dengan identitas cover:

| Nama Style | Font Family | Ukuran / Bobot | Warna (Hex) | Paragraf / Spacing | Karakteristik Khusus |
|---|---|---|---|---|---|
| **Body Text (Normal)** | Aptos / Calibri | 10 pt / Regular | `#1E293B` (Slate) | Line spacing 1.15, Space after 4.5 pt | Clean, legibel pada format buku A5 |
| **Heading 1 (Bab)** | Aptos / Calibri | 17–18 pt / Bold | `#1B7340` (Forest Green) | Space before 14 pt, Space after 8 pt | `keepWithNext: true`, huruf kapital |
| **Heading 2 (Subbab)** | Aptos / Calibri | 13–13.5 pt / Bold | `#1B7340` (Forest Green) | Space before 11 pt, Space after 5 pt | `keepWithNext: true`, penomoran 1.1, 2.1, dst. |
| **Heading 3 (Sub-subbab)** | Aptos / Calibri | 10.5–11 pt / Bold | `#334155` (Slate Dark) | Space before 8 pt, Space after 4 pt | `keepWithNext: true` |
| **Heading 4 (Grup)** | Aptos / Calibri | 10 pt / Bold | `#475569` (Slate Medium) | Space before 6 pt, Space after 2 pt | `keepWithNext: true` |
| **Numbered Steps** | Aptos / Calibri | 10 pt / Bold Prefix | `#1B7340` (Num) + `#1E293B` | Left Indent 5 mm, Hanging -5 mm | Restart mandiri di angka 1 per prosedur |
| **Bullet List** | Aptos / Calibri | 10 pt / Bold Prefix | `#1B7340` (Bullet `•`) | Left Indent 4 mm, Hanging -4 mm | Space after 3 pt |
| **Table Header** | Aptos / Calibri | 8.5–9 pt / Bold | `#FFFFFF` (White) | Shading `#1B7340`, Padding 5 pt | `tblHeader: true`, `cantSplit: true` |
| **Table Text** | Aptos / Calibri | 8.5 pt / Regular | `#1E293B` (Dark) | Line spacing 1.15, Padding 4 pt | Row alternate `#F8FAFC`, `cantSplit: true` |
| **Figure / Diagram Caption** | Aptos / Calibri | 8.5 pt / Bold Italic | `#64748B` (Muted) | Center, Space before 2 pt, after 8 pt | `keepWithNext: true` |
| **Table Caption** | Aptos / Calibri | 8.5 pt / Bold | `#1B7340` (Green) | Left, Space before 8 pt, after 4 pt | `keepWithNext: true` (terletak di atas tabel) |
| **Running Header** | Aptos / Calibri | 8.5 pt / Regular | `#64748B` (Muted) | Header kanan: Judul Buku / Header kiri | Space after 4 pt |
| **Running Footer** | Aptos / Calibri | 8.5 pt / Regular | `#64748B` (Muted) | Center (Front Matter) / Right (Isi) | Nomor halaman dinamis (`PAGE`) |

---

## 4. Desain Kotak Penanda Khusus (Callout Boxes)

Sebanyak 5 tipe kotak penanda khusus diwujudkan menggunakan kontainer tabel berpadding lembut dengan garis aksen kiri tebal (4.5 pt) dan latar belakang pastel tematik:

| Tipe Callout | Warna Garis Kiri | Warna Background | Warna Judul Badge | Fungsi & Penggunaan |
|---|---|---|---|---|
| **CATATAN** | `#0284C7` (Sky Blue) | `#F0F9FF` (Light Sky) | `#0369A1` (Deep Sky) | Penjelasan latar belakang, konteks, dan batasan fitur sistem. |
| **TIPS** | `#16A34A` (Green) | `#F0FDF4` (Light Green) | `#15803D` (Deep Green) | Kiat efisiensi, saran praktis, dan jalan pintas pengoperasian. |
| **PENTING** | `#D97706` (Amber/Gold) | `#FFFBEB` (Light Amber) | `#B45309` (Deep Amber) | Ketentuan wajib, aturan integritas data, dan batasan sistem. |
| **PERHATIAN** | `#DC2626` (Red/Rose) | `#FEF2F2` (Light Rose) | `#B91C1C` (Deep Red) | Peringatan tindakan permanen, risiko keamanan, dan batasan fatal. |
| **REKOMENDASI PENGELOLAAN** | `#0D9488` (Teal) | `#F0FDFA` (Light Teal) | `#0F766E` (Deep Teal) | Pedoman kerja administratif, etika publikasi, dan koordinasi kerja. |

---

## 5. Audit Penempatan Visual (Screenshots & Flowcharts)

Seluruh 39 visual placeholder telah digantikan secara sempurna dengan file aset grafis beresolusi tinggi tanpa ada gambar yang hilang atau terdistorsi:

| Kategori | Jumlah Placement | Unique Assets | Format yang Digunakan | Status Kesiapan |
|---|:---:|:---:|:---:|:---:|
| **Tangkapan Layar (Screenshots)** | 28 | 27 | PNG High-Resolution | 28 / 28 (100% READY) |
| **Diagram Alir (Flowcharts)** | 11 | 8 | PNG High-Resolution (Clean) | 11 / 11 (100% READY) |
| **Aset Cover (Depan & Belakang)** | 2 | 2 | PNG Print-Resolution | 2 / 2 (100% READY) |
| **TOTAL VISUAL PLACEMENTS** | **41** | **37** | **PNG** | **100% READY** |

### Rincian Asset yang Digunakan Berulang (Reused Assets):
1. **`MAP-001` (Antarmuka Penentuan Titik Koordinat):** Digunakan pada Bab III (Subbab 3.4) sebagai `Gambar 3.7` dan Bab V (Subbab 5.2) sebagai `Gambar 5.1`.
2. **`FLOW-04` (Siklus Pengelolaan Data Operasional):** Digunakan pada Bab III (Subbab 3.8) sebagai `Diagram 3.5`, Bab IV (Subbab 4.10) sebagai `Diagram 4.2`, dan Bab V (Subbab 5.7) sebagai `Diagram 5.2`.
3. **`FLOW-07` (Alur Penentuan Titik Koordinat Lokasi):** Digunakan pada Bab III (Subbab 3.5) sebagai `Diagram 3.2` dan Bab V (Subbab 5.2) sebagai `Diagram 5.1`.

---

## 6. Audit Tabel Substantif

Sebanyak 20 tabel substantif pada Bab I–VI beserta tabel pelengkap pada Front & Back Matter telah ditata dengan lebar 11.1 cm (pas bidang teks A5) dan dilengkapi caption resmi:

| Nomor Tabel | Judul Caption Resmi | Lokasi Bab | Jumlah Kolom |
|---|---|---|:---:|
| — | *Panduan Membaca Berdasarkan Peran Pengguna* | Front Matter / Petunjuk | 3 |
| **Tabel 1.1** | *Matriks Ringkasan Hak Akses Tiga Peran Pengguna* | Bab I / Subbab 1.4 | 4 |
| **Tabel 2.1** | *Ragam Informasi yang Dapat Diakses Masyarakat* | Bab II / Subbab 2.1 | 2 |
| **Tabel 3.1** | *Ringkasan Elemen Antarmuka Dashboard Admin Dusun* *(Reconciled)* | Bab III / Subbab 3.1 | 3 |
| **Tabel 3.2** | *Panduan Isian Formulir Profil Dusun* | Bab III / Subbab 3.2 | 4 |
| **Tabel 3.3** | *Panduan Isian Formulir Kontak Pelayanan* | Bab III / Subbab 3.3 | 4 |
| **Tabel 3.4** | *Panduan Isian Formulir Data UMKM* | Bab III / Subbab 3.4 | 4 |
| **Tabel 3.5** | *Panduan Isian Formulir Fasilitas Umum* | Bab III / Subbab 3.5 | 4 |
| **Tabel 3.6** | *Panduan Isian Formulir Agenda Kegiatan* | Bab III / Subbab 3.6 | 4 |
| **Tabel 3.7** | *Panduan Isian Formulir Pengumuman Dusun* | Bab III / Subbab 3.7 | 4 |
| **Tabel 3.8** | *Matriks Kewenangan Kelola Data Admin Dusun* | Bab III / Subbab 3.9 | 3 |
| **Tabel 4.1** | *Daftar 10 Modul Tata Kelola pada Panel Kerja Super Admin* | Bab IV / Subbab 4.1 | 3 |
| **Tabel 4.2** | *Panduan Isian Formulir Identitas Desa* | Bab IV / Subbab 4.2 | 4 |
| **Tabel 4.3** | *Ringkasan Ketentuan Field Data Operasional* | Bab IV / Subbab 4.4 | 3 |
| **Tabel 4.4** | *Perbedaan Penonaktifan Data (Soft Delete) vs Hapus Permanen (Hard Delete)* | Bab IV / Subbab 4.11 | 3 |
| **Tabel 4.5** | *Matriks Kewenangan Tata Kelola Super Admin* | Bab IV / Subbab 4.12 | 3 |
| **Tabel 5.1** | *Ketentuan Status Koordinat Berdasarkan Modul* | Bab V / Subbab 5.2 | 3 |
| **Tabel 5.2** | *Pertimbangan Privasi Berdasarkan Jenis Informasi* | Bab V / Subbab 5.5 | 3 |
| **Tabel 5.3** | *Rekomendasi Siklus Pemeliharaan Data* | Bab V / Subbab 5.6 | 3 |
| **Tabel 5.4** | *Matriks Perbandingan Status Data Portal* | Bab V / Subbab 5.7 | 4 |
| **Tabel 6.1** | *Checklist Diagnosis Visibilitas Data* | Bab VI / Subbab 6.6 | 3 |
| — | *Lembar Periksa Pemeliharaan Data Portal* | Back Matter / Checklist | 4 |
| — | *Daftar Anggota Tim KKN Desa Bendung* | Back Matter / Profil Tim | 4 |

---

## 7. Rekonsiliasi Daftar Otomatis (TOC, Gambar, Tabel)

Sesuai instruksi audit manifest, inkonsistensi pada draft awal front-matter telah disinkronkan secara definitif:

1. **Daftar Isi (Table of Contents):**  
   - Dibangun menggunakan struktur heading aktual dari Bab I s.d. Bab VI dan Back Matter.
   - Perbedaan judul Bab III telah diperbaiki (misalnya: *3.1 Prosedur Masuk Sistem, Fitur Ingat Saya, dan Dashboard Dusun*; *3.2 Mengelola Profil dan Foto Banner Wilayah Dusun*; *3.3 Mengelola Kontak Pelayanan Warga*; *3.4 Mendaftarkan dan Memperbarui Data UMKM*; *3.5 Mengelola Data Fasilitas Umum dan Titik Lokasi*; *3.7 Menerbitkan Pengumuman Resmi Dusun*; *3.8 Prosedur Menonaktifkan Data*; *3.9 Batas Hak Akses dan Kewenangan Admin Dusun*).
   - Seluruh entri dilengkapi garis titik bantu (*dot leaders*) rapi yang sejajar pada batas margin kanan.
2. **Daftar Gambar:**  
   - Disusun dari caption aktual seluruh 39 visual placement (Diagram 1.1, Diagram 2.1, Gambar 2.1–2.8, Gambar 3.1–3.10, Diagram 3.1–3.5, Gambar 4.1–4.8, Diagram 4.1–4.2, Gambar 5.1, Diagram 5.1–5.2, Gambar 6.1).
3. **Daftar Tabel:**  
   - Disusun dari caption aktual 20 tabel Bab I–VI.
   - Judul Tabel 3.1 telah direkonsiliasi menjadi *"Ringkasan Elemen Antarmuka Dashboard Admin Dusun"* (bukan draft lama "Daftar 7 Modul...").

---

## 8. MANDATORY NUMBERING QA AUDIT

Audit menyeluruh terhadap seluruh ordered list prosedural pada dokumen menghasilkan kepatuhan 100%:

```text
================================================================================
HASIL AUDIT SISTEM PENOMORAN PROSEDUR (NUMBERING QA GATE)
================================================================================
Total Blok Numbered List Prosedural : 92 blok
Blok yang Memulai Numbering dari 1  : 92 blok (100.0% COMPLIANT)
Blok yang Tidak Sengaja Melompat     : 0 blok
Nomor Langkah Prosedur Terbesar      : 8 (Langkah 8 pada prosedur terpanjang)
Kebocoran Sequence Antar-Subbab      : 0 (ZERO LEAKAGE)
Kebocoran Sequence Antar-Bab         : 0 (ZERO LEAKAGE)
Heading Numbering Collision          : 0 (Heading mandiri terpisah dari list)
Caption Numbering Collision          : 0 (Caption mandiri terpisah dari list)
================================================================================
STATUS QUALITY GATE PENOMORAN: LULUS (PASS)
================================================================================
```

### Rincian Sebaran Prosedur per Bab:
- **Bab I:** 3 numbered list (rentang 1..5, 1..6, 1..3)
- **Bab II:** 21 numbered list (seluruhnya dimulai dari 1, langkah terpanjang 1..7)
- **Bab III:** 20 numbered list (seluruhnya dimulai dari 1, langkah terpanjang 1..8)
- **Bab IV:** 30 numbered list (seluruhnya dimulai dari 1, langkah terpanjang 1..8)
- **Bab V:** 6 numbered list (seluruhnya dimulai dari 1, langkah terpanjang 1..4)
- **Bab VI:** 12 numbered list (seluruhnya dimulai dari 1, langkah terpanjang 1..6)

---

## 9. Inventaris Placeholder yang Dipertahankan untuk Review Manusia

Placeholder berikut sengaja dipertahankan dalam dokumen Word master agar dapat diisi/disesuaikan secara manual oleh tim KKN sebelum cetak final:

1. **Daftar Nama Anggota Penyusun** (`[Daftar Nama Anggota Penyusun — DIISI SAAT LAYOUT WORD]`) pada Halaman Hak Cipta.
2. **Daftar NIM Penyusun** (`[Daftar NIM Penyusun — DIISI SAAT LAYOUT WORD]`) pada Halaman Hak Cipta.
3. **Periode KKN** (`[Periode KKN — DIISI SAAT LAYOUT WORD]`) pada Halaman Hak Cipta.
4. **Tempat dan Bulan Kata Pengantar** (`[Tempat/Bulan], 2026`) pada Kata Pengantar.
5. **Placeholder QR Portal:** Area QR pada cover belakang tetap berstatus placeholder. Canonical URL workspace (`https://bendung.com`) telah tercatat, dan QR image final akan disematkan setelah verifikasi live HTTPS oleh manusia.
6. **Matriks Profil Anggota Tim KKN** pada Back Matter (kolom Nama, NIM, Program Studi, dan Peran Tim dipertahankan fleksibel).

---

## 10. Panduan Verifikasi & Langkah Selanjutnya (Human Review)

Dokumen DOCX ini adalah **MASTER EDITABLE**. Langkah-langkah yang disarankan bagi peninjau manusia:

1. **Buka Dokumen di Microsoft Word:** Buka file `Panduan-Portal-Informasi-Desa-Bendung-2026.docx` pada aplikasi desktop Microsoft Word.
2. **Perbarui Field (Jika Diperlukan):** Tekan `Ctrl + A` lalu `F9` (atau klik kanan pada Daftar Isi / Daftar Gambar / Daftar Tabel → *Update Field* → *Update entire table*) untuk menyegarkan nomor halaman aktual sesuai rendering mesin Word lokal Anda.
3. **Isi Placeholder Identitas:** Lengkapi nama-nama mahasiswa penyusun, NIM, program studi, dan tanggal pengesahan pada halaman Hak Cipta, Kata Pengantar, dan Profil Tim.
4. **Periksa Pemotongan Halaman (*Pagination Inspection*):** Periksa letak tabel dan gambar agar tidak terjadi split baris atau pemotongan visual yang tidak diinginkan.
5. **Ekspor PDF / Siap Cetak:** Setelah verifikasi visual manusia selesai, simpan ke PDF untuk distribusi digital (`docs/07-handover/04-final-book/pdf/`) dan siapkan file cetak (`docs/07-handover/04-final-book/print/`).

---

**WORD MASTER STATUS:**  
`DRAFT LAYOUT COMPLETE — READY FOR HUMAN VISUAL REVIEW`
