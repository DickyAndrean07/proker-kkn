TUGAS: FINAL REVISION MASTER DOCX A5 — PORTAL INFORMASI DESA BENDUNG

==================================================
OTORITAS TEKNIS — WAJIB
==================================================

Sebelum membaca, mengubah, atau menghasilkan file DOCX, WAJIB baca dan ikuti:

/home/oai/skills/docx/SKILL.md

DOCX Skill menjadi otoritas teknis utama untuk:

- struktur Microsoft Word;
- section break;
- page setup;
- page numbering;
- styles;
- heading;
- numbered list;
- tabel;
- caption;
- TOC;
- Daftar Gambar;
- Daftar Tabel;
- header/footer;
- gambar;
- pagination;
- rendering;
- visual QA.

Jika terdapat konflik antara teknik implementasi dalam prompt ini dan
SKILL.md, prioritaskan aturan teknis SKILL.md selama tidak mengubah fakta
dan isi manuskrip yang telah disetujui.

==================================================
INPUT UTAMA
==================================================

Master DOCX saat ini:

docs/07-handover/04-final-book/docx/
Panduan-Portal-Informasi-Desa-Bendung-2026.docx

Production report:

docs/07-handover/04-final-book/docx/
word-layout-production-report.md

Manifest:

docs/07-handover/01-planning/
final-book-production-manifest.md

Manuskrip:

docs/07-handover/02-manuscript/00-front-matter.md
docs/07-handover/02-manuscript/01-bab-i-mengenal-portal.md
docs/07-handover/02-manuscript/02-bab-ii-panduan-masyarakat.md
docs/07-handover/02-manuscript/03-bab-iii-admin-dusun.md
docs/07-handover/02-manuscript/04-bab-iv-super-admin.md
docs/07-handover/02-manuscript/05-bab-v-pedoman-pengelolaan.md
docs/07-handover/02-manuscript/06-bab-vi-troubleshooting.md
docs/07-handover/02-manuscript/07-back-matter.md

==================================================
OUTPUT
==================================================

JANGAN menimpa DOCX lama.

Buat:

docs/07-handover/04-final-book/docx/
Panduan-Portal-Informasi-Desa-Bendung-2026-v2.docx

Buat laporan:

docs/07-handover/04-final-book/docx/
word-layout-revision-report.md

Jangan membuat PDF final pada tahap ini.

==================================================
TUJUAN REVISI
==================================================

Revisi master DOCX dengan fokus pada:

1. koreksi 6 Dusun sesuai database production;
2. pembersihan seluruh data database lokal/test lama;
3. nomor halaman yang benar-benar terlihat;
4. seluruh teks editable interior berwarna hitam;
5. numbering prosedur restart dari angka 1;
6. pembersihan artefak Markdown;
7. sinkronisasi TOC, Daftar Gambar, dan Daftar Tabel;
8. visual QA seluruh halaman.

==================================================
SOURCE OF TRUTH — 6 DUSUN PRODUCTION
==================================================

INSTRUKSI INI MEMILIKI PRIORITAS TINGGI.

Telah dikonfirmasi manusia bahwa 6 Dusun yang BENAR pada database
PRODUCTION Portal Informasi Desa Bendung adalah:

1. Bendung
2. Klubuk
3. Bantengan
4. Belik
5. Pohsengir
6. Kaliasin

Gunakan daftar ini sebagai struktur 6 Dusun resmi pada buku.

PENTING:

"Desa Bendung" adalah nama desa.

"Dusun Bendung" adalah salah satu dari enam Dusun.

Jangan mencampur keduanya.

==================================================
DATA 6 DUSUN LAMA — BUKAN PRODUCTION
==================================================

Nama berikut berasal dari database lokal/test sebelumnya dan TIDAK BOLEH lagi
dinyatakan sebagai enam Dusun resmi:

- Bendung I
- Bendung II
- Gatak
- Karangsawo
- Banyuripan
- Plosorejo

Cari seluruh kemunculan nama-nama tersebut pada:

- seluruh manuskrip 00–07;
- DOCX;
- Daftar Isi;
- Daftar Gambar;
- Daftar Tabel;
- Glosarium;
- Checklist;
- Profil Tim;
- screenshot;
- caption;
- contoh data;
- tabel;
- callout;
- header/footer jika ada.

Jika konteksnya adalah struktur wilayah resmi, GANTI dengan:

Bendung, Klubuk, Bantengan, Belik, Pohsengir, dan Kaliasin.

==================================================
CONTROLLED FACTUAL CORRECTION TERHADAP MANUSKRIP
==================================================

Manuskrip sebelumnya berstatus LOCKED.

Namun perubahan berikut DIIZINKAN karena merupakan koreksi fakta production
yang telah dikonfirmasi manusia:

- mengganti daftar nama enam Dusun lama;
- menyesuaikan kalimat agar gramatikal setelah pergantian nama;
- membersihkan contoh/data lokal yang berasal dari database sebelumnya;
- memperbaiki screenshot yang masih memuat data lokal lama.

JANGAN melakukan rewriting lain.

Jangan mengubah:

- fitur;
- hak akses;
- struktur role;
- workflow;
- ketentuan koordinat;
- media;
- lifecycle agenda;
- lifecycle pengumuman;
- restore;
- hard delete;
- batasan sistem;
- struktur Bab;
- substansi tutorial.

==================================================
PENGGANTIAN STRUKTUR 6 DUSUN
==================================================

Contoh SALAH:

"Desa Bendung terdiri dari Bendung I, Bendung II, Gatak, Karangsawo,
Banyuripan, dan Plosorejo."

Contoh BENAR:

"Desa Bendung terdiri dari enam wilayah Dusun, yaitu Bendung, Klubuk,
Bantengan, Belik, Pohsengir, dan Kaliasin."

Gunakan bentuk penulisan tersebut secara konsisten bila daftar lengkap
memang dibutuhkan.

Jangan menyebut daftar lengkap enam Dusun secara berlebihan jika cukup
menggunakan frasa:

"enam Dusun di Desa Bendung."

==================================================
SANITASI DATA DATABASE LOKAL / TEST
==================================================

Lakukan audit seluruh isi buku terhadap data dari database lokal/test lama.

Yang harus diperiksa:

- nama Dusun;
- nama orang;
- Kepala Dusun;
- nama petugas;
- username;
- password;
- nama UMKM;
- nama pemilik UMKM;
- nama fasilitas;
- alamat;
- RT/RW;
- nomor WhatsApp;
- koordinat;
- agenda;
- pengumuman;
- nilai statistik dashboard;
- record tabel;
- dropdown/filter;
- data akun;
- data contoh lain.

Jangan membawa record lama ke buku final.

==================================================
STRUKTUR RESMI VS DATA CONTOH
==================================================

Nama:

Bendung
Klubuk
Bantengan
Belik
Pohsengir
Kaliasin

BOLEH muncul ketika memang menjelaskan struktur enam Dusun production.

Namun untuk contoh formulir/tutorial, prioritaskan data generik seperti:

Dusun Contoh
Nama Petugas Contoh
Nama Kepala Dusun Contoh
Usaha Contoh
Pemilik Usaha Contoh
Fasilitas Contoh
Agenda Contoh
Pengumuman Contoh
RT 01 / RW 01
081234567890
[Alamat Contoh]

Jangan mengarang fakta nyata tentang salah satu Dusun production.

==================================================
SCREENSHOT SANITIZATION — WAJIB
==================================================

Audit seluruh 28 screenshot yang ditempatkan di DOCX.

Periksa apakah screenshot memuat:

- enam nama Dusun lama;
- Karangsawo;
- Gatak;
- Bendung I;
- Bendung II;
- Banyuripan;
- Plosorejo;
- username lokal;
- nama orang lokal;
- nomor WhatsApp lokal;
- UMKM lokal/test;
- fasilitas lokal/test;
- agenda lokal/test;
- pengumuman lokal/test;
- koordinat test;
- data runtime yang tidak layak dimasukkan buku.

Jika screenshot mengandung data lama:

JANGAN gunakan screenshot tersebut di DOCX v2.

Prioritas:

1. gunakan screenshot lain yang sudah bersih; atau
2. capture ulang melalui TEST DATABASE dengan dataset sanitized;
3. gunakan struktur nama Dusun production bila diperlukan untuk UI;
4. data operasional di dalam screenshot tetap gunakan data generik.

JANGAN melakukan destructive action pada database production.

JANGAN menggunakan production database untuk eksperimen screenshot.

Jika membuat screenshot sanitized, simpan di:

docs/07-handover/03-assets/screenshots/sanitized/

Raw screenshot lama jangan dihapus.

==================================================
IDENTITAS YANG HARUS DIPERTAHANKAN
==================================================

Jangan sanitasi identitas berikut:

Desa Bendung

Universitas Muhammadiyah Gresik

Tim KKN Desa Bendung

2026

Ibu Norainny Yunitasari, S.Pd., M.Pd.

Enam Dusun production:

Bendung
Klubuk
Bantengan
Belik
Pohsengir
Kaliasin

==================================================
NOMOR HALAMAN — WAJIB BENAR-BENAR TERLIHAT
==================================================

Masalah DOCX sebelumnya:

nomor halaman tidak terlihat sebagaimana mestinya.

Jangan hanya membuat PAGE field di XML.

Nomor halaman harus TERLIHAT secara visual saat:

- DOCX dibuka di Microsoft Word;
- DOCX dirender melalui tool DOCX;
- halaman diperiksa pada hasil PNG.

==================================================
SISTEM NOMOR HALAMAN
==================================================

COVER DEPAN

- tanpa nomor halaman;
- jangan tampilkan angka.

FRONT MATTER

gunakan:

i
ii
iii
iv
v
...

Halaman Judul boleh dihitung sebagai halaman i tetapi nomor pada Halaman Judul
boleh disembunyikan.

Mulai halaman interior front matter berikutnya, nomor harus terlihat.

BAB I

restart menjadi:

1

BAB II–VI dan Back Matter:

lanjutkan angka Arab secara berurutan.

JANGAN restart pada setiap Bab.

COVER BELAKANG:

tanpa nomor.

==================================================
POSISI NOMOR HALAMAN
==================================================

Gunakan:

BOTTOM CENTER / TENGAH FOOTER

untuk mengurangi risiko nomor hilang karena mirror margin.

Font:

Aptos / Calibri fallback

Ukuran:

8.5–9 pt

Warna:

#000000

Nomor tidak boleh:

- keluar margin;
- terlalu dekat tepi;
- tertutup objek;
- terlalu kecil;
- berwarna putih;
- berwarna abu terlalu terang.

==================================================
SECTION BREAK PAGE NUMBER
==================================================

Audit semua section.

Periksa:

- cover depan;
- front matter;
- BAB I;
- BAB II;
- BAB III;
- BAB IV;
- BAB V;
- BAB VI;
- back matter;
- cover belakang.

Jika halaman pembuka Bab memakai:

Different First Page

maka:

header halaman pertama boleh berbeda/kosong;

TETAPI footer halaman pertama Bab tetap harus menampilkan nomor.

Jangan sampai nomor halaman pembuka Bab hilang.

==================================================
OOXML PAGE NUMBERING
==================================================

Jika mengatur melalui OOXML:

Front matter:

w:pgNumType format="lowerRoman"

BAB I:

w:pgNumType format="decimal" start="1"

BAB berikutnya:

decimal, continue.

Gunakan field PAGE asli.

Jangan hard-code angka halaman.

==================================================
SEMUA TEKS INTERIOR = HITAM
==================================================

Permintaan manusia:

SELURUH TEKS EDITABLE INTERIOR WORD HARUS BERWARNA HITAM.

Gunakan:

#000000

untuk:

- Book Title interior;
- Subtitle interior;
- Front Matter Title;
- Heading 1;
- Heading 2;
- Heading 3;
- Heading 4;
- Body Text;
- numbered list;
- bullet list;
- nomor prosedur;
- table header;
- table body;
- caption gambar;
- caption diagram;
- caption tabel;
- TOC;
- Daftar Gambar;
- Daftar Tabel;
- header;
- footer;
- nomor halaman;
- hyperlink;
- callout title;
- callout body;
- placeholder;
- Glosarium;
- Checklist;
- Profil Tim.

JANGAN gunakan teks:

Forest Green
Slate
Muted Grey
Blue
Teal
Amber
Red
White

untuk teks editable interior.

SEMUA TEXT EDITABLE = #000000.

==================================================
PENGECUALIAN COVER DAN VISUAL
==================================================

Aturan teks hitam TIDAK berlaku terhadap teks raster yang menjadi bagian dari:

- cover depan;
- cover belakang;
- screenshot;
- flowchart.

Jangan mengedit warna teks yang sudah menyatu ke dalam gambar.

Cover tetap menggunakan desain approved saat ini.

JANGAN redesign cover.

==================================================
ELEMEN NON-TEKS BOLEH BERWARNA
==================================================

Yang masih boleh menggunakan warna:

- border;
- divider;
- shape;
- background callout;
- shading tabel;
- garis aksen;
- ikon.

Namun gunakan warna sangat muda agar teks hitam tetap jelas.

Jangan menggunakan background hijau gelap karena teks harus hitam.

==================================================
HEADING
==================================================

Karena semua teks hitam, hierarchy heading dibuat melalui:

- ukuran font;
- bold;
- spacing;
- divider/border ringan.

Rekomendasi:

Heading 1:
17–18 pt
Bold
Black

Heading 2:
13–14 pt
Bold
Black

Heading 3:
11–12 pt
Bold
Black

Heading 4:
10–11 pt
Bold
Black

==================================================
TABLE DESIGN
==================================================

Semua teks tabel:

BLACK.

Header tabel:

- teks hitam;
- bold;
- background hijau sangat muda atau abu sangat muda.

Body:

- teks hitam;
- alternating shading sangat ringan jika diperlukan.

Jangan gunakan:

background hijau gelap + teks putih.

==================================================
CALLOUT DESIGN
==================================================

Pertahankan lima tipe:

CATATAN
TIPS
PENTING
PERHATIAN
REKOMENDASI PENGELOLAAN

Semua judul dan isi callout:

#000000

Tipe callout dibedakan menggunakan:

- border;
- icon;
- background pastel sangat muda;
- label bold.

Jangan membedakan tipe menggunakan warna teks.

==================================================
HYPERLINK
==================================================

Hyperlink interior:

warna hitam.

Boleh gunakan underline.

Jangan gunakan biru default Word.

==================================================
NUMBERING PROSEDUR — WAJIB
==================================================

PERTAHANKAN prinsip:

SETIAP PROSEDUR BARU DIMULAI DARI 1.

Contoh BENAR:

Prosedur Login

1.
2.
3.

Prosedur Logout

1.
2.
3.

BUKAN:

4.
5.
6.

==================================================
SISTEM NUMBERING TERPISAH
==================================================

Empat sistem numbering berikut harus independen:

A. Bab/Subbab
B. Langkah Prosedur
C. List/Sublist
D. Caption Gambar/Diagram/Tabel

Tidak boleh saling memengaruhi.

Heading:

1.1
1.2
2.1
...

tidak boleh terganggu list langkah.

Caption:

Gambar 3.1
Diagram 3.1
Tabel 3.1

tidak boleh menggunakan sequence numbered list prosedur.

==================================================
RESET LIST
==================================================

Setiap:

- heading baru;
- subheading baru;
- prosedur baru;
- modul baru;
- troubleshooting baru;

default:

RESTART AT 1.

Jika memang masih merupakan satu prosedur yang sama, numbering boleh continue.

Jangan menggunakan satu global sequence list untuk seluruh buku.

==================================================
NUMBERING QA
==================================================

Audit seluruh ordered list.

Laporkan:

- total numbered list;
- jumlah list restart dari 1;
- list yang intentionally continued;
- nomor prosedur terbesar;
- leakage antar Subbab;
- leakage antar Bab;
- collision dengan heading;
- collision dengan caption.

Target:

GLOBAL NUMBERING LEAKAGE = 0

Tidak boleh ada nomor langkah seperti:

57
103
182
200+

karena list global.

==================================================
ARTEFAK MARKDOWN — HARUS DIBERSIHKAN
==================================================

Audit DOCX terhadap literal artefak seperti:

`text

`

$$

\text{}

<!-- -->

<br>

backtick Markdown

heading marker Markdown yang bocor

Production Note

Jangan tampilkan artefak tersebut pada buku.

==================================================
URL PLACEHOLDER
==================================================

Jika masih belum difinalkan pada isi tutorial, tampilkan cukup:

https://[alamat-portal-desa]

atau:

https://[domain-portal-desa]/admin/login

dalam style teks/kode Word yang rapi.

Jangan tampilkan:

`text
https://...
`

==================================================
RUMUS / FLOW AGENDA
==================================================

Jika source mengandung:

$$
AKAN DATANG → BERLANGSUNG → SELESAI
$$

jangan tampilkan syntax LaTeX.

Tampilkan sebagai teks Word biasa atau gunakan diagram FLOW-05 yang sudah ada.

==================================================
PRODUCTION COMMENT
==================================================

Komentar seperti:

<!-- PRODUCTION NOTE: ... -->

tidak boleh terlihat pada buku final.

Boleh tetap ada pada source Markdown jika dibutuhkan operator,
tetapi jangan render ke DOCX pembaca.

==================================================
TOC — DAFTAR ISI
==================================================

Gunakan heading aktual sebagai source.

Jangan mempertahankan nomor halaman statis lama jika layout berubah.

Update Word TOC setelah revisi.

Pastikan TOC memuat:

BAB I
BAB II
BAB III
BAB IV
BAB V
BAB VI
Glosarium
Checklist Pemeliharaan Berkala
Profil Tim Penyusun

Nomor halaman harus mengikuti layout v2.

==================================================
DAFTAR GAMBAR
==================================================

Generate/update berdasarkan caption aktual.

Gunakan seluruh visual placement.

Repeated assets tetap boleh memiliki caption placement masing-masing.

Jangan mengambil caption lama yang berbeda dengan manuskrip aktual.

==================================================
DAFTAR TABEL
==================================================

Generate/update berdasarkan caption tabel aktual.

Pastikan Tabel 3.1 tetap:

Ringkasan Elemen Antarmuka Dashboard Admin Dusun

bukan:

Daftar 7 Modul Pengelolaan Wilayah...

==================================================
FIELD UPDATE
==================================================

Pastikan Word field untuk:

- PAGE;
- TOC;
- Daftar Gambar;
- Daftar Tabel;

dapat diperbarui.

Aktifkan update fields on open jika sesuai.

Jika generator dapat memperbarui display result sebelum save:
lakukan.

Jangan hanya menulis field instruction tanpa display yang berguna.

==================================================
COVER
==================================================

Gunakan cover existing:

docs/07-handover/03-assets/cover/cover-depan.png

docs/07-handover/03-assets/cover/cover-belakang.png

JANGAN:

- redesign;
- recolor;
- crop elemen penting;
- menambahkan nomor halaman;
- membuat cover baru.

==================================================
QR
==================================================

Canonical URL yang pernah ditemukan:

https://bendung.com

Namun QR final tetap menunggu verifikasi manusia.

Jangan generate QR baru pada revisi ini kecuali terdapat instruksi manusia
terpisah bahwa URL tersebut sudah diverifikasi live.

Pertahankan area QR cover yang ada.

==================================================
PLACEHOLDER IDENTITAS
==================================================

Pertahankan untuk diisi manusia saat finalisasi Word:

- nama anggota;
- NIM;
- Program Studi;
- periode KKN;
- Tempat/Bulan Kata Pengantar;
- jumlah anggota Tim.

Jangan mengarang.

==================================================
VISUAL QA — WAJIB
==================================================

Setelah DOCX v2 selesai, WAJIB gunakan renderer dari DOCX skill:

/home/oai/skills/docx/render_docx.py

Render SELURUH halaman DOCX v2 menjadi PNG.

Jangan hanya spot-check.

Periksa seluruh page PNG pada 100%.

==================================================
VISUAL QA — PAGE NUMBER
==================================================

Periksa secara visual:

[ ] cover depan tanpa nomor
[ ] front matter memakai Romawi
[ ] nomor front matter terlihat
[ ] BAB I dimulai dari 1
[ ] BAB II melanjutkan nomor
[ ] BAB III melanjutkan nomor
[ ] BAB IV melanjutkan nomor
[ ] BAB V melanjutkan nomor
[ ] BAB VI melanjutkan nomor
[ ] Back Matter melanjutkan nomor
[ ] halaman pembuka Bab tetap bernomor
[ ] cover belakang tanpa nomor

==================================================
VISUAL QA — BLACK TEXT
==================================================

Periksa:

[ ] Heading hitam
[ ] Body hitam
[ ] Caption hitam
[ ] nomor langkah hitam
[ ] bullet hitam
[ ] tabel hitam
[ ] table header hitam
[ ] hyperlink hitam
[ ] callout title hitam
[ ] callout body hitam
[ ] header/footer hitam
[ ] page number hitam
[ ] TOC hitam
[ ] Daftar Gambar hitam
[ ] Daftar Tabel hitam

==================================================
XML / STYLE COLOR QA
==================================================

Lakukan audit XML/style/run interior.

Hitung:

NON-BLACK EDITABLE TEXT BEFORE

dan

NON-BLACK EDITABLE TEXT AFTER

Target:

NON-BLACK EDITABLE TEXT AFTER = 0

Pengecualian:

teks yang sudah menjadi pixel pada:

- screenshot;
- flowchart;
- cover.

==================================================
VISUAL QA — DATA SANITIZATION
==================================================

Cari pada hasil final:

Bendung I
Bendung II
Gatak
Karangsawo
Banyuripan
Plosorejo

Target:

0 kemunculan sebagai struktur/data resmi.

Jika nama lama masih muncul di screenshot:
screenshot harus diganti.

Kemudian pastikan nama production muncul dengan benar bila konteksnya
membutuhkan daftar enam Dusun:

Bendung
Klubuk
Bantengan
Belik
Pohsengir
Kaliasin

==================================================
JANGAN MENGGANTI SECARA CEROBoh
==================================================

PENTING:

Jangan melakukan blind string replacement:

"Bendung I" -> "Bendung"

jika dapat menyebabkan teks aneh.

Baca konteks kalimat.

Lakukan semantic replacement.

Contoh:

"enam dusun (Bendung I, Bendung II, Gatak...)"

menjadi:

"enam Dusun (Bendung, Klubuk, Bantengan, Belik, Pohsengir, dan Kaliasin)."

==================================================
DATABASE PRODUCTION
==================================================

JANGAN melakukan:

- write;
- update;
- delete;
- migration destruktif;
- seed;

ke database production.

Database production hanya boleh digunakan sebagai referensi baca jika memang
sudah tersedia secara aman dalam workspace/tooling.

Untuk recapture screenshot:
gunakan test environment/database.

==================================================
QUALITY GATE FINAL
==================================================

DOCX v2 hanya boleh PASS jika:

1. Skill DOCX telah dibaca dan diikuti.
2. Enam Dusun production benar:
   Bendung,
   Klubuk,
   Bantengan,
   Belik,
   Pohsengir,
   Kaliasin.
3. Enam nama Dusun lama tidak lagi menjadi fakta buku.
4. Screenshot database lokal lama telah disanitasi.
5. Tidak ada data pribadi/test lama yang tidak semestinya.
6. Nomor halaman benar-benar terlihat.
7. Front matter memakai Roman.
8. BAB I restart ke angka 1.
9. Nomor Arab berlanjut sampai Back Matter.
10. Cover depan/belakang tidak bernomor.
11. Semua teks editable interior = hitam.
12. Tidak ada teks heading hijau.
13. Tidak ada caption abu-abu.
14. Tidak ada teks table header putih.
15. Numbering prosedur restart dari 1.
16. Tidak ada sequence global sampai puluhan/ratusan.
17. Tidak ada artefak Markdown.
18. TOC sudah sinkron.
19. Daftar Gambar sudah sinkron.
20. Daftar Tabel sudah sinkron.
21. Semua halaman telah dirender.
22. Semua halaman hasil render telah diperiksa.
23. Tidak ada gambar terpotong.
24. Tidak ada tabel keluar margin.
25. Tidak ada halaman kosong tidak disengaja.
26. Cover belakang berada di halaman terakhir.

==================================================
REVISION REPORT
==================================================

Buat:

docs/07-handover/04-final-book/docx/
word-layout-revision-report.md

Isi laporan minimal:

1. input DOCX;
2. output DOCX v2;
3. DOCX Skill status;
4. total halaman;
5. daftar enam Dusun production yang digunakan;
6. jumlah kemunculan nama Dusun lama sebelum revisi;
7. jumlah kemunculan nama Dusun lama sesudah revisi;
8. daftar manuscript yang mendapat controlled factual correction;
9. screenshot yang diaudit;
10. screenshot yang diganti/disanitasi;
11. sanitized asset path;
12. kategori data lokal lain yang dibersihkan;
13. implementasi nomor halaman;
14. hasil visual QA nomor halaman;
15. front matter Roman range;
16. Arabic page range;
17. non-black editable text sebelum revisi;
18. non-black editable text sesudah revisi;
19. numbering procedure audit;
20. nomor prosedur terbesar;
21. sequence leakage status;
22. Markdown artifact audit;
23. TOC status;
24. Daftar Gambar status;
25. Daftar Tabel status;
26. jumlah page PNG yang dirender;
27. jumlah page PNG yang diperiksa;
28. masalah visual yang ditemukan;
29. masalah visual yang diperbaiki;
30. placeholder manusia yang masih tersisa.

==================================================
STATUS
==================================================

Jika seluruh quality gate lulus, gunakan:

WORD MASTER V2 STATUS:
REVISION COMPLETE — READY FOR HUMAN VISUAL REVIEW

Jangan gunakan:

FINAL PRINT READY

karena masih ada:

- review visual manusia;
- identitas anggota;
- NIM;
- Program Studi;
- Periode KKN;
- Tempat/Bulan;
- QR final.

==================================================
PRINSIP TERAKHIR
==================================================

Tujuan revisi ini bukan membuat desain baru.

Tujuannya adalah memperbaiki master Word yang sudah ada agar:

- faktanya sesuai production;
- tidak tercampur database lokal lama;
- nomor halaman berfungsi;
- penomoran prosedur benar;
- teks interior konsisten hitam;
- daftar otomatis sinkron;
- siap diperiksa manusia sebelum PDF/cetak.