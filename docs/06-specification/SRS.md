# Software Requirements Specification (SRS)

**Project:** Portal Informasi Desa Bendung  
**Document:** Software Requirements Specification  
**Version:** 1.2  
**Status:** FROZEN FOR MVP  
**Requirement Source:** Requirements Baseline v1.0 — FROZEN FOR MVP  
**Product Source:** PRD v1.0 — FROZEN FOR MVP  
**UX Source:** Sitemap v1.0 + User Flows v1.0 — FROZEN FOR MVP  
**Authorization Source:** Roles & Permissions v1.0 — FROZEN FOR MVP  
**Data Source:** ERD/Data Model v1.0 + Physical Database Schema v1.2 — FROZEN FOR MVP  
**Technical Source:** Technical R&D v1.0 — FROZEN FOR MVP  
**Database Engine:** MariaDB  
**Business Time Zone:** Asia/Jakarta  

## 1. Source Authority

| Urutan authority | Source FROZEN | Versi/status |
| --- | --- | --- |
| 1 | `docs/01-requirements/requirements-baseline.md` | v1.0 — FROZEN FOR MVP |
| 2 | `docs/02-product/PRD.md` | v1.0 — FROZEN FOR MVP |
| 3 | `docs/03-ux/sitemap.md` | v1.0 — FROZEN FOR MVP |
| 4 | `docs/03-ux/user-flows.md` | v1.0 — FROZEN FOR MVP |
| 5 | `docs/04-system/roles-permissions.md` | v1.0 — FROZEN FOR MVP |
| 6 | `docs/04-system/erd-data-model.md` | v1.0 — FROZEN FOR MVP |
| 7 | `docs/05-rnd/technical-rnd.md` | v1.0 — APPROVED TECHNICAL BASELINE |
| 8 | `docs/04-system/physical-database-schema.md` | v1.2 — FROZEN FOR MVP |

Requirement produk mengalahkan implementation convenience. SRS SHALL NOT mengubah source FROZEN, menyelesaikan OPEN secara diam-diam, menaikkan OPTIONAL menjadi mandatory, memasukkan FUTURE ke MVP, atau mengubah authorization, data semantics, maupun approved technical stack.

## 2. Output

SRS Version `1.2`, Status **FROZEN FOR MVP**, memasukkan approved technical clarification `PDS-CR-001` serta Change Request yang disetujui `PDS-CR-002` (Dukungan Remember Me / `remember_token` pada `admin_accounts`) tanpa membuka kembali keputusan produk yang telah dibekukan.

| Item | Nilai |
| --- | --- |
| Pemilik produk | Pemerintah Desa Bendung / pihak yang ditetapkan pada handover |
| Target pembaca | Product owner, analis, UI/UX, developer, tester, operator desa |
| Tahap | Software specification sebelum UI/UX dan Testing Specification |
| Historical approved/applied Physical Schema CR | 2 — `PDS-CR-001`, `PDS-CR-002` |
| Open Change Request setelah aplikasi | 0 |

Tidak terdapat Change Request terbuka. `PDS-CR-001 — Laravel Migration Repository Metadata Clarification` dan `PDS-CR-002 — Remember Me / remember_token Support` telah diputuskan manusia dan berstatus **APPROVED / APPLIED**; dampak product/behavior, ERD, authorization, dan UI/UX semuanya `NONE`.

## 3. SRS Purpose

SRS ini menggabungkan requirement FROZEN menjadi implementation contract yang testable untuk development, detail UI/UX, Testing Specification, acceptance testing, dan deployment preparation. Cakupannya meliputi portal publik, dashboard Admin Dusun dan Super Admin, data, lifecycle, peta, media, autentikasi, otorisasi, privasi, serta operasional MVP.

SRS menetapkan observable behavior, input/output, validation, state, authorization, error behavior, acceptance condition, non-functional requirement, external integration, dan approved technical constraints. SRS tidak membuat visual UI, wireframe, CSS, component design, API contract, implementation class, SQL, migration, test code, atau deployment configuration.

## 4. Specification Notation

Setiap requirement normatif memakai ID `SRS-<CATEGORY>-NNN` dan kata **SHALL** atau **SHALL NOT**. **MAY** hanya dipakai untuk behavior yang memang optional. ID SRS adalah normalisasi/traceability dan bukan requirement produk baru. Referensi rentang seperti `SRS-FR-001–005` bersifat inklusif.

## 5. System Overview

Portal Informasi Desa Bendung SHALL menyediakan informasi publik terpusat mengenai profil Dusun, Kontak Pelayanan, UMKM, fasilitas, Agenda/Kegiatan, Pengumuman, dan peta interaktif. Sistem SHALL menyediakan dashboard administratif dengan cakupan berbasis peran. MVP mencakup satu konteks Desa Bendung dan enam Dusun awal; penambahan Dusun baru tidak termasuk MVP.

Sistem memiliki dua surface utama: (A) Public Website tanpa login yang mencakup Homepage, Halaman Dusun, detail publik, peta, serta handoff WhatsApp/Google Maps; dan (B) Administrative Dashboard yang mencakup shared Admin Login, Dashboard Admin Dusun, dan Dashboard Super Admin. Tidak ada citizen account/registration, marketplace/payment, page builder, mobile app, atau internal maps-routing engine.

## 6. Actors

| Actor | Tujuan dan batas utama |
| --- | --- |
| Public User | Membaca informasi, memakai peta, membuka kontak/navigasi; tanpa akun, login, atau write access. |
| Admin Dusun | Mengelola profil dan data operasional Dusunnya sendiri; tidak dapat menjangkau Dusun lain, restore, atau hard delete. |
| Super Admin | Mengelola data lintas Desa/Dusun, kategori, akun Admin Dusun, restore, dan hard delete non-Dusun. |

## 7. System Context

Portal adalah aplikasi web server-rendered berbasis Laravel 13, PHP 8.3+, Blade, progressive JavaScript, dan MariaDB. Peta publik menggunakan Leaflet; media tersimpan pada filesystem hosting; autentikasi admin dikelola aplikasi dengan username/password. Target deployment adalah shared hosting cPanel yang wajib lulus qualification sebelum produksi.

Konteks eksternal terdiri dari browser/client web responsif, WhatsApp untuk handoff pesan, Google Maps untuk handoff navigasi, replaceable map tile/basemap provider, filesystem/media hosting, MariaDB, dan environment shared hosting yang cPanel-compatible. Production tile provider tetap pre-production open dan bagian ini tidak menetapkan API protocol.

## 8. Functional Requirements — Public Homepage

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-FR-001 | Homepage SHALL menjadi destination satu QR utama pada papan utama Desa Bendung di Balai Desa serta menampilkan identitas Desa, logo, banner, deskripsi singkat, alamat kantor, kontak, email opsional, Kepala Desa, jam pelayanan, dan akses navigasi utama dari data yang tersedia. | BR-001; DATA-001–002; OPS-001; Sitemap Homepage |
| SRS-FR-002 | Sistem SHALL menampilkan daftar enam Dusun awal yang berstatus ACTIVE dan menyediakan akses ke Halaman Dusun masing-masing; Dusun INACTIVE SHALL tidak tampil atau menyumbang marker ke Peta Desa publik. | FR-002; FR-022; UF-PUB-001 |
| SRS-FR-003 | Sistem SHALL menampilkan Pengumuman Desa aktif terbaru dan Agenda/Kegiatan Desa terbaru yang relevan berdasarkan data, tanpa page builder. | FR-008; AUTH-INV-009; UF-PUB-002 |
| SRS-FR-004 | Sistem SHALL menampilkan peta publik terintegrasi dan kontak kantor Desa yang tersedia pada Homepage. | MAP-001; Sitemap Homepage |
| SRS-FR-005 | Sistem SHALL menampilkan empty state yang ramah apabila suatu bagian Homepage tidak memiliki data yang eligible. | FR-009 |

## 9. Functional Requirements — Halaman Dusun

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-FR-006 | Sistem SHALL menyediakan satu Halaman Dusun publik per Dusun ACTIVE sebagai konsep single-page/scroll dengan navigasi cepat antarseksi. | FR-003; FR-007; UF-PUB-003 |
| SRS-FR-007 | Halaman Dusun SHALL menampilkan profil, Kepala Dusun, jumlah RT/RW, dan informasi Dusun yang tersedia. | DATA-003; Sitemap Halaman Dusun |
| SRS-FR-008 | Halaman Dusun SHALL menyediakan seksi Kontak Pelayanan, UMKM, fasilitas, Agenda/Kegiatan, Pengumuman, dan peta sesuai eligibility data. | FR-004–FR-006; Sitemap |
| SRS-FR-009 | Jika Dusun INACTIVE, sistem SHALL menyembunyikan Halaman Dusun dan seluruh child data dari publik tanpa menghapus data atau menonaktifkan login admin Dusun. | FR-022; AUTH-INV-006 |

## 10. Functional Requirements — Kontak Pelayanan

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-FR-010 | Sistem SHALL memungkinkan pengelolaan nama, jabatan/jenis pelayanan fleksibel, WhatsApp wajib untuk publikasi, foto opsional, serta alamat/lokasi opsional yang relevan dan permitted pada Kontak Pelayanan; Admin Dusun terbatas pada Dusunnya dan Super Admin lintas Dusun. | DATA-006–008; ROLE-003; ROLE-008; UF-AD-002–003; UF-SA-002 |
| SRS-FR-011 | Kontak Pelayanan SHALL tampil publik hanya jika `deleted_at IS NULL`, parent Dusun ACTIVE, nomor WhatsApp tersedia, dan precondition privasi/publikasi telah dipenuhi. | DATA-007; PRIV-001; PDS-DEC-005 |
| SRS-FR-012 | Sistem SHALL membentuk external handoff WhatsApp dari nomor Kontak Pelayanan dengan pesan awal yang menunjukkan bahwa kontak diperoleh dari Portal Desa Bendung; exact wording ditetapkan melalui keputusan OPEN-002. | FR-010; OPEN-002; UF-PUB-005 |
| SRS-FR-013 | Marker Kontak Pelayanan SHALL eligible hanya jika pasangan latitude/longitude valid dan izin publikasi lokasi offline telah diperoleh. | MAP-010; PRIV-001; UF-PUB-004–005 |

## 11. Functional Requirements — UMKM

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-FR-014 | Sistem SHALL memungkinkan pengelolaan UMKM per Dusun, termasuk identitas usaha/pemilik, jenis usaha, deskripsi, alamat, WhatsApp, jam operasional, foto utama opsional, dan koordinat opsional. | DATA-009; MEDIA-003; UF-AD-002–003; UF-SA-002 |
| SRS-FR-015 | Sistem SHALL menampilkan daftar dan detail UMKM yang eligible kepada publik serta menyediakan external WhatsApp handoff menggunakan nomor WhatsApp yang tersimpan. | DATA-009; UF-PUB-006; Physical Schema table `umkms` |
| SRS-FR-016 | Sistem SHALL memungkinkan beberapa Produk UMKM berbentuk daftar/tag sebagai child UMKM, tanpa cart, checkout, payment, stock, SKU, transaction, pemesanan, atau pembayaran. | FR-012; DATA-009; UF-PUB-006 |
| SRS-FR-017 | UMKM tanpa koordinat SHALL tetap tampil dalam direktori, tetapi SHALL NOT menghasilkan marker peta. | MAP-009; ERD-DIR-017 |

## 12. Functional Requirements — Fasilitas

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-FR-018 | Sistem SHALL memungkinkan pengelolaan fasilitas per Dusun dengan nama, kategori, deskripsi, alamat, koordinat wajib, serta foto dan WhatsApp opsional. | DATA-011; MAP-008; UF-AD-002–003; UF-SA-002 |
| SRS-FR-019 | Sistem SHALL menampilkan daftar/detail fasilitas yang eligible dan kategori fasilitasnya kepada publik. | FR-005; UF-PUB-007 |
| SRS-FR-020 | Sistem SHALL menampilkan external WhatsApp handoff fasilitas hanya ketika nomor tersedia; tidak ada phone-call action requirement. | DATA-012; UF-PUB-007 |
| SRS-FR-021 | Sistem SHALL menyediakan aksi navigasi fasilitas ke Google Maps menggunakan koordinat valid; portal SHALL NOT menyediakan routing sendiri. | MAP-007; UF-PUB-007 |

## 13. Functional Requirements — Kategori Fasilitas

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-FR-022 | Sistem SHALL menggunakan kategori fasilitas dinamis yang hanya dapat dibuat/diubah oleh Super Admin; Admin Dusun SHALL hanya dapat memilih kategori saat mengelola fasilitas dan SHALL NOT mengelola kategori atau memakai enum statis. | ROLE-011; UF-SA-002 |
| SRS-FR-023 | Nama kategori fasilitas SHALL unik dalam satu Desa, sesuai business UNIQUE `(desa_id, nama_kategori)`. | PDS-DEC-013; Physical Schema §23 |
| SRS-FR-024 | Kategori fasilitas SHALL menjadi sumber label marker fasilitas; sistem SHALL NOT membuat universal map-category table. | MAP-006; ERD-DIR-033; Physical Schema §19 |

## 14. Functional Requirements — Agenda & Kegiatan

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-FR-025 | Sistem SHALL memungkinkan Agenda/Kegiatan memiliki scope tepat satu: tingkat Desa atau satu Dusun. | FR-016; ERD-DIR-024; PDS-DEC-006 |
| SRS-FR-026 | Sistem SHALL menyimpan judul, deskripsi, tanggal mulai, tanggal selesai opsional, jam opsional, lokasi, dan media opsional. | DATA-014; DATA-015; DATA-017; MEDIA-007 |
| SRS-FR-027 | Sistem SHALL menentukan effective status Agenda dari `manual_status_override` bila ada; jika tidak, status SHALL diturunkan dari tanggal mulai/selesai. | FR-015; ERD-DIR-026–027; PDS-DEC-008 |
| SRS-FR-028 | Sistem SHALL NOT menyimpan `calculated_status` atau membuat index atas status hasil kalkulasi Agenda. | PDS-DEC-008; Physical Schema §§16, 24 |
| SRS-FR-029 | Sistem SHALL memungkinkan media Agenda berperan sebagai poster atau dokumentasi dan mengikuti lifecycle/otorisasi parent. | MEDIA-007; ERD-DIR-028; AUTH-INV-010 |
| SRS-FR-030 | Sistem SHALL menampilkan Agenda/Kegiatan yang eligible pada konteks Desa atau Dusun dan menyediakan detail publik. | FR-006; UF-PUB-008 |

## 15. Functional Requirements — Pengumuman

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-FR-031 | Sistem SHALL memungkinkan Pengumuman memiliki scope tepat satu: tingkat Desa atau satu Dusun. | FR-017; ERD-DIR-025; PDS-DEC-006 |
| SRS-FR-032 | Sistem SHALL menurunkan status publik active/archive dari `tanggal_kedaluwarsa`; expiry SHALL NOT mengubah axis Soft Delete. | FR-018; DATA-016; PDS-DEC-009 |
| SRS-FR-033 | Pengumuman kedaluwarsa yang tidak Soft Deleted dan berada pada parent ACTIVE SHALL tetap tersedia dalam Arsip Pengumuman publik. | FR-018; DATA-016; AUTH-INV-008; UF-PUB-009 |
| SRS-FR-034 | Sistem SHALL NOT membuat archive table, `archived_at`, archive enum, atau menyalin record untuk proses arsip. | PDS-DEC-009; Physical Schema §17 |

## 16. Functional Requirements — Peta

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-FR-035 | Peta Desa SHALL menyediakan filter Dusun dan filter kategori. Peta Dusun SHALL otomatis scoped ke Dusun ACTIVE yang sedang dibuka, SHALL NOT membutuhkan selector untuk berpindah Dusun, dan SHALL mendukung filter kategori sesuai konteks. Keduanya SHALL menggunakan Leaflet serta provider/base map yang lolos qualification. | MAP-001; MAP-002; MAP-005; Sitemap Peta Desa/Peta Dusun; UF-PUB-004; RND-DEC-005 |
| SRS-FR-036 | Peta SHALL menampilkan hanya marker dari data eligible dengan pasangan koordinat valid. | MAP-002; ERD-DIR-033 |
| SRS-FR-037 | Taksonomi marker SHALL diturunkan sebagai UMKM, PELAYANAN, atau `kategori_fasilitas.nama_kategori`. | MAP-003; MAP-005; MAP-006; ERD-DIR-033; Physical Schema §19 |
| SRS-FR-038 | Opsi `SEMUA` SHALL berfungsi sebagai pilihan query/filter dan SHALL NOT disimpan sebagai nilai kategori database. | ERD-DIR-033; Physical Schema §19 |
| SRS-FR-039 | Interaksi marker SHALL menampilkan popup dan memberi akses ke konteks/detail sumber serta external Google Maps directions yang tersedia. | MAP-002; UF-PUB-004 |
| SRS-FR-040 | Sistem SHALL NOT menyediakan map search, boundary polygon, entitas `map_points`, kategori universal, GPS tracking, atau routing internal portal. | MAP-007; MAP-011–012; ERD-DIR-033; Physical Schema §19 |

## 17. Functional Requirements — Login

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-FR-041 | Sistem SHALL menyediakan satu halaman login bersama untuk Admin Dusun dan Super Admin menggunakan username dan password, serta opsi persistent authentication ("Ingat Saya"). | SEC-008; UF-AD-001; UF-SA-001; PDS-CR-002 |
| SRS-FR-042 | Setelah autentikasi berhasil, sistem SHALL mengarahkan pengguna ke dashboard sesuai role dan scope. | ROLE-002; UF-AD-001; UF-SA-001 |
| SRS-FR-043 | Sistem SHALL menolak login jika kredensial tidak valid atau `removed_at IS NOT NULL` tanpa mengungkap detail akun sensitif. | ERD-DIR-035; PDS-DEC-004 |
| SRS-FR-044 | Sistem SHALL NOT menyediakan login/akun warga, email-required login, public registration, Admin self-registration, atau self-service password reset Admin Dusun melalui email/WhatsApp. | FR-001; SEC-008 |

## 18. Authentication Requirements

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-SEC-001 | Sistem SHALL mengautentikasi admin melalui mekanisme username/password yang dikelola aplikasi Laravel. | SEC-001; RND-DEC-004 |
| SRS-SEC-002 | Password SHALL disimpan hanya sebagai strong password hash yang didukung stack approved; plaintext password SHALL NOT disimpan. | SEC-002; RND-DEC-004 |
| SRS-SEC-003 | Username SHALL memiliki global UNIQUE constraint; normalisasi input exact seperti trim/casing validation SHALL dilakukan pada application boundary tanpa menetapkan perilaku casing yang bertentangan dengan collation MariaDB. | PDS-DEC-004; PDS-DEC-013; Physical Schema §23 |
| SRS-SEC-004 | Login SHALL dilindungi rate limiting terhadap brute force. | SEC-006 |
| SRS-SEC-005 | Admin Dusun yang lupa password SHALL meminta reset langsung oleh Super Admin; pemulihan Super Admin tetap external OPEN-010. | SEC-008; ROLE-009; OPEN-010 |
| SRS-SEC-006 | Sistem SHALL meregenerasi session setelah login berhasil; jika opsi "Ingat Saya" dipilih, sistem SHALL menyimpan persistent remember token menggunakan fasilitas native Laravel `remember_token` pada `admin_accounts` (`PDS-CR-002`); logout SHALL menginvalidasi authenticated session dan merotasi/membersihkan persistent remember token. Nilai durasi baru SHALL NOT diasumsikan sebelum kebijakan ditetapkan. | Technical R&D §15; User Flows logout; PDS-CR-002 |

## 19. Authorization Requirements

Runtime authorization SHALL ditegakkan melalui Laravel Policies/Gates serta pemeriksaan `OWN_DUSUN`/`GLOBAL`; schema hanya mendukung enforcement dan SHALL NOT menggantikan authority Roles & Permissions.

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-AUTH-001 | Admin Dusun SHALL NOT membaca atau memutasi resource milik Dusun lain. | AUTH-INV-001 |
| SRS-AUTH-002 | Pengguna Publik SHALL NOT memiliki write access, account-management access, atau dashboard access. | AUTH-INV-002 |
| SRS-AUTH-003 | Hanya Super Admin SHALL dapat menjalankan restore data operasional. | AUTH-INV-003 |
| SRS-AUTH-004 | Tidak ada role yang SHALL dapat melakukan hard delete entitas Dusun. | AUTH-INV-004 |
| SRS-AUTH-005 | Hanya Super Admin SHALL dapat melakukan hard delete permanen pada entitas non-Dusun yang didukung. | AUTH-INV-005 |
| SRS-AUTH-006 | Admin Dusun pada Dusun INACTIVE SHALL tetap dapat login dan mengelola dashboard Dusunnya sendiri. | AUTH-INV-006 |
| SRS-AUTH-007 | Data Soft Deleted SHALL NOT tampil pada endpoint atau halaman publik. | AUTH-INV-007 |
| SRS-AUTH-008 | Arsip Pengumuman publik SHALL diperlakukan terpisah dari Soft Delete. | AUTH-INV-008 |
| SRS-AUTH-009 | Homepage SHALL tersusun dari data tingkat Desa/Dusun tanpa permission page builder baru. | AUTH-INV-009 |
| SRS-AUTH-010 | Otorisasi lokasi, produk, dan media child SHALL mengikuti parent resource. | AUTH-INV-010 |
| SRS-AUTH-011 | Hanya Super Admin SHALL mengelola data tingkat Desa, kategori fasilitas, dan status Dusun. | AUTH-INV-011 |
| SRS-AUTH-012 | Perubahan Admin Dusun dalam scope yang sah SHALL langsung dipublikasikan tanpa approval Super Admin. | AUTH-INV-012 |

## 20. Admin Dusun CRUD Behavior

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-FR-045 | Dashboard Admin Dusun SHALL menyediakan pengelolaan profil Dusun, Kontak Pelayanan, UMKM beserta produk, fasilitas, Agenda/Kegiatan Dusun, Pengumuman Dusun, serta lokasi/media milik parent. | ROLE-003; UF-AD-002–006 |
| SRS-FR-046 | Admin Dusun SHALL dapat create/read/update data dalam `OWN_DUSUN`, serta menonaktifkan data operasional melalui Soft Delete. | ROLE-003; ROLE-006 |
| SRS-FR-047 | Admin Dusun SHALL NOT memiliki restore, hard delete, pengelolaan kategori, pengelolaan akun, perubahan status Dusun, atau prioritas manual. | ROLE-006–ROLE-007; AUTH-INV-003–005; AUTH-INV-011 |

## 21. Super Admin Management

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-FR-048 | Dashboard Super Admin SHALL menyediakan pengelolaan identitas Desa, seluruh Dusun/profil, data operasional dan lokasi lintas Dusun, Agenda/Pengumuman Desa/Dusun, kategori fasilitas, akun Admin Dusun, restore, dan hard delete non-Dusun. | ROLE-008–ROLE-011; UF-SA-002–009 |
| SRS-FR-049 | Super Admin SHALL dapat mengubah nama/profil Dusun serta status `ACTIVE`/`INACTIVE`, tetapi SHALL NOT membuat Dusun baru atau hard delete Dusun pada MVP. | ROLE-010; DATA-004; AUTH-INV-004 |
| SRS-FR-050 | Super Admin SHALL dapat restore data operasional Soft Deleted dan melakukan hard delete non-Dusun dengan konsekuensi permanen yang jelas. | ROLE-008; SEC-009; UF-SA-003–004 |

## 22. Admin Account Management

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-FR-051 | Super Admin SHALL dapat membuat, mengelola, dan logically remove akun Admin Dusun dengan username global-unique dan assignment tepat satu Dusun. | ROLE-009; ERD-DIR-008–010; UF-SA-007 |
| SRS-FR-052 | Super Admin SHALL dapat mereset password Admin Dusun tanpa menyediakan self-service recovery baru; pemulihan akun Super Admin tetap OPEN-010. | ROLE-009; SEC-008; OPEN-010; UF-SA-008 |
| SRS-FR-053 | Logical removal akun Admin Dusun SHALL mempertahankan row, username, dan UNIQUE constraint; username tersebut SHALL tetap RESERVED dan SHALL NOT dapat dipakai kembali selama retained identity record ada. | ERD-DIR-035; PDS-DEC-004 |
| SRS-FR-054 | Akun pengganti SHALL menggunakan username berbeda; logical removal SHALL NOT menciptakan restore/reactivate/undelete account, username recycling, merge identity, atau recovery permission baru. | PDS-DEC-004; Roles & Permissions |

## 23. Dusun Lifecycle

Empat axis lifecycle utama SHALL dibedakan secara eksplisit: status Dusun, Soft Delete data operasional, logical removal akun, dan expiry Pengumuman. Lifecycle Agenda adalah axis tanggal plus optional manual override yang berdiri sendiri. Ringkasan normatif lengkap terdapat pada Bagian 40.

- Perubahan Dusun `ACTIVE → INACTIVE` SHALL hanya memengaruhi visibilitas publik parent dan child; row, ownership, serta login Admin Dusun tetap ada.
- Soft Delete SHALL mengubah `deleted_at` dari null menjadi UTC datetime; restore oleh Super Admin SHALL mengembalikannya ke null.
- Logical removal akun SHALL mengubah `removed_at` dari null menjadi UTC datetime dan tidak mempunyai transisi restore.
- Pengumuman SHALL berpindah secara derived dari active menjadi archive ketika business date Asia/Jakarta melewati `tanggal_kedaluwarsa`.

Hanya Super Admin SHALL mengubah status Dusun. Reactivation Dusun SHALL menghitung ulang public eligibility child yang masih aktif, tetapi SHALL NOT otomatis me-restore child yang Soft Deleted.

## 24. Soft Delete Requirements

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-DATA-001 | Hanya `kontak_pelayanans`, `umkms`, `fasilitas`, `agenda_kegiatans`, dan `pengumumans` SHALL memiliki `deleted_at`. | PDS-DEC-005 |
| SRS-DATA-002 | `deleted_at IS NULL` SHALL berarti data operasional aktif; `deleted_at IS NOT NULL` SHALL berarti INACTIVE/NONAKTIF/SOFT_DELETED dan tidak publik. | DATA-007; ERD-DIR-031 |
| SRS-DATA-003 | Admin Dusun SHALL dapat melakukan Soft Delete dalam scope sendiri; hanya Super Admin SHALL dapat restore atau hard delete non-Dusun. | ROLE-006; AUTH-INV-003; AUTH-INV-005 |
| SRS-DATA-004 | `desas`, `dusuns`, `admin_accounts`, `produk_umkms`, `kategori_fasilitas`, dan `agenda_medias` SHALL NOT memperoleh `deleted_at`; lifecycle child SHALL mengikuti parent sesuai desain. | PDS-DEC-005; AUTH-INV-010 |

Soft Delete Kontak Pelayanan adalah physical representation tunggal untuk `DATA-007`. Sistem SHALL NOT menambahkan `status`, `is_active`, `active`, atau `status_kontak` karena akan menciptakan dua source-of-truth.

## 25. Data Requirements

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-DATA-005 | Persistence SHALL menggunakan MariaDB dan merepresentasikan tepat 11 application/domain physical tables, 11/11 conceptual entities, serta 13/13 domain relationships dari Physical Database Schema FROZEN. Atribut persistent authentication framework `remember_token VARCHAR(100) NULL` ditambahkan pada `admin_accounts` berdasarkan `PDS-CR-002`. Laravel MAY additionally contain tabel `migrations` semata-mata sebagai framework operational metadata untuk migration bookkeeping; tabel tersebut SHALL NOT dianggap sebagai product/domain entity. | Physical Schema §§3–5, §22; `PDS-CR-001`; `PDS-CR-002` |
| SRS-DATA-006 | Referential action SHALL menggunakan `RESTRICT` secara default; `CASCADE` hanya untuk `umkms → produk_umkms` dan `agenda_kegiatans → agenda_medias`. | PDS-DEC-011; Physical Schema §22 |
| SRS-DATA-007 | Business uniqueness SHALL terbatas pada `admin_accounts.username` global dan `(kategori_fasilitas.desa_id, nama_kategori)`; requirement ini SHALL NOT menambah uniqueness lain. | PDS-DEC-013; Physical Schema §23 |
| SRS-DATA-008 | Koordinat SHALL menggunakan `DECIMAL(9,6)` dengan latitude `-90..90` dan longitude `-180..180`; fasilitas wajib pair, UMKM dan Kontak Pelayanan optional pair. | PDS-DEC-007; ERD-DIR-015–016, 022; Physical Schema §18 |

Business date/time SHALL memakai Asia/Jakarta. Audit/lifecycle `DATETIME` SHALL ditulis dalam UTC. Sistem SHALL NOT menyimpan timezone per row. Source: `PDS-DEC-012`.

Expected implementation inventory setelah Laravel migration initialization adalah 11 domain tables + 1 framework metadata table (`migrations`) = 12 SQL tables. Exception ini tidak mengizinkan `users`, `password_reset_tokens`, `sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, atau framework table lain tanpa approved Change Request terpisah. Authentication persistence tetap `admin_accounts` (dengan kolom framework `remember_token`); session/cache/queue tetap menggunakan foundation non-database yang telah disetujui.

## 26. Data Validation Requirements

Application validation SHALL selalu diterapkan. CHECK database SHALL menjadi lapis integritas tambahan setelah versi MariaDB provider terbukti mendukung dan menegakkannya pada qualification `RND-OQ-002`.

| ID | Validation rule | Source CHECK direction |
| --- | --- | --- |
| SRS-VAL-001 | `status_dusun` SHALL hanya `ACTIVE` atau `INACTIVE`. | `chk_dusuns_status` |
| SRS-VAL-002 | `admin_accounts.role` SHALL hanya `ADMIN_DUSUN` atau `SUPER_ADMIN`. | `chk_admin_accounts_role` |
| SRS-VAL-003 | Admin Dusun SHALL memiliki non-null `dusun_id`; Super Admin SHALL memiliki null `dusun_id`. | `chk_admin_accounts_role_scope` |
| SRS-VAL-004 | `removed_at` SHALL hanya boleh non-null untuk role Admin Dusun. | `chk_admin_accounts_removed_role` |
| SRS-VAL-005 | Koordinat Kontak Pelayanan SHALL sama-sama null atau sama-sama non-null. | `chk_kontak_pelayanans_coordinate_pair` |
| SRS-VAL-006 | Latitude Kontak Pelayanan SHALL null atau berada pada `-90..90`. | `chk_kontak_pelayanans_latitude` |
| SRS-VAL-007 | Longitude Kontak Pelayanan SHALL null atau berada pada `-180..180`. | `chk_kontak_pelayanans_longitude` |
| SRS-VAL-008 | Koordinat UMKM SHALL sama-sama null atau sama-sama non-null. | `chk_umkms_coordinate_pair` |
| SRS-VAL-009 | Latitude UMKM SHALL null atau berada pada `-90..90`. | `chk_umkms_latitude` |
| SRS-VAL-010 | Longitude UMKM SHALL null atau berada pada `-180..180`. | `chk_umkms_longitude` |
| SRS-VAL-011 | Latitude fasilitas SHALL berada pada `-90..90`. | `chk_fasilitas_latitude` |
| SRS-VAL-012 | Longitude fasilitas SHALL berada pada `-180..180`. | `chk_fasilitas_longitude` |
| SRS-VAL-013 | Agenda scope DESA SHALL memiliki null `dusun_id`; scope DUSUN SHALL memiliki non-null `dusun_id`. | `chk_agenda_kegiatans_scope` |
| SRS-VAL-014 | `tanggal_selesai` Agenda SHALL null atau tidak sebelum `tanggal_mulai`. | `chk_agenda_kegiatans_dates` |
| SRS-VAL-015 | Agenda override SHALL null atau salah satu `AKAN_DATANG`, `BERLANGSUNG`, `SELESAI`. | `chk_agenda_kegiatans_override` |
| SRS-VAL-016 | Agenda media role SHALL hanya `POSTER_AWAL` atau `DOKUMENTASI`. | `chk_agenda_medias_role` |
| SRS-VAL-017 | Pengumuman scope DESA SHALL memiliki null `dusun_id`; scope DUSUN SHALL memiliki non-null `dusun_id`. | `chk_pengumumans_scope` |

Required input SHALL tidak kosong; optional blank SHALL dinormalisasi sesuai nullability physical schema; username SHALL lolos global uniqueness; media path SHALL storage-relative; nomor kontak SHALL mengikuti format implementation yang disepakati; conditional `dusun_id` SHALL berada dalam context `desa_id` yang sama. Upload media SHALL memvalidasi MIME/signature, jenis, ukuran, dan dimensi terhadap batas hosting yang telah dikualifikasi. Ketentuan ini tidak mengubah jumlah 17 CHECK directions.

## 27. Media Requirements

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-DATA-009 | Foto/media SHALL opsional; data tanpa foto SHALL memakai placeholder/ilustrasi yang sesuai kategori. | MEDIA-001–MEDIA-002 |
| SRS-DATA-010 | UMKM SHALL memiliki paling banyak satu foto utama; galeri multi-foto UMKM SHALL NOT termasuk MVP. | MEDIA-003–MEDIA-004; ERD-DIR-018 |
| SRS-DATA-011 | Gambar unggahan SHALL divalidasi, di-resize, dikompresi, dan dikonversi ke format web modern bila didukung; SVG SHALL dibatasi untuk aset vektor. | MEDIA-006; Technical R&D §11 |
| SRS-DATA-012 | Database SHALL menyimpan storage-relative reference/path, bukan binary blob; media SHALL berada pada hosting filesystem, masuk backup/export media, dan mewarisi visibility, privacy, lifecycle, serta authorization parent. Exact library, path, dan filename strategy belum ditetapkan. | PDS-DEC-010; Physical Schema §20; Technical R&D §§11, 14; ERD-DIR-034 |

## 28. Privacy Requirements

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-SEC-007 | Admin SHALL memastikan izin publikasi offline telah diperoleh sebelum memasukkan nomor WhatsApp, foto personal, rumah pribadi, atau lokasi privat ke sistem. | PRIV-001 |
| SRS-SEC-008 | Sistem SHALL NOT menyediakan consent field, upload surat persetujuan, digital consent management, atau workflow approval consent. | PRIV-001; ERD-DIR-014 |
| SRS-SEC-009 | Data privat SHALL ditampilkan hanya pada konteks dan tindakan yang diizinkan; lokasi privat SHALL NOT menghasilkan marker tanpa izin offline. | PRIV-001; MAP-010 |

## 29. External Interface Requirements

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-EXT-001 | Portal SHALL menggunakan Leaflet sebagai library peta frontend pada browser modern responsif. | RND-DEC-005 |
| SRS-EXT-002 | Provider/base map SHALL dapat diganti dan SHALL dipakai di produksi hanya setelah policy, attribution, quota, traffic, Terms, dan compatibility lulus qualification. | RND-OQ-003 |
| SRS-EXT-003 | Aksi navigasi lokasi SHALL membuka Google Maps sebagai tujuan eksternal menggunakan koordinat valid. | MAP-007 |
| SRS-EXT-004 | Aksi kontak WhatsApp SHALL membuka WhatsApp dengan nomor target dan template pesan awal yang tersedia. | FR-010; OPEN-002 |
| SRS-EXT-005 | Media SHALL menggunakan filesystem/storage Laravel yang kompatibel dengan shared hosting cPanel dan traffic/media sizing yang telah dikualifikasi. | RND-DEC-008; RND-OQ-002, 004 |
| SRS-EXT-006 | Semua external interaction pada MVP SHALL berupa browser handoff atau dependency peta/media; sistem SHALL NOT membuat internal messaging, internal routing, atau API protocol baru. | FR-010; MAP-007; RND-DEC-001 |

## 30. Error and Empty State Requirements

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-ERR-001 | Kredensial login tidak valid SHALL menghasilkan penolakan generik dan tidak membocorkan keberadaan username. | SEC-001; SEC-006 |
| SRS-ERR-002 | Akses di luar role/scope SHALL ditolak dan SHALL NOT menampilkan atau memutasi resource terlarang. | SEC-003; AUTH-INV-001–002 |
| SRS-ERR-003 | Input yang gagal validation SHALL ditolak tanpa persistensi parsial dan SHALL mempertahankan field-level context yang dapat dipahami. | 17 CHECK directions; User Flows CRUD |
| SRS-ERR-004 | Request resource yang tidak ada, Soft Deleted, atau tersembunyi oleh parent INACTIVE pada jalur publik SHALL menghasilkan respons non-public yang konsisten. | AUTH-INV-006–008 |
| SRS-ERR-005 | Kegagalan upload/processing media SHALL mempertahankan integritas parent dan SHALL tidak meninggalkan reference path yang tidak valid. | MEDIA-006; RND-OQ-004 |
| SRS-ERR-006 | Jika optional data untuk external handoff tidak ada, action SHALL tidak ditampilkan; kegagalan provider peta/eksternal SHALL tidak menghilangkan konten direktori non-peta yang tetap eligible. | DATA-012; MAP-009; BR-005–BR-007 |
| SRS-ERR-007 | Hard delete yang ditolak FK `RESTRICT` SHALL tidak menghapus parent maupun child dan SHALL memberi penjelasan yang dapat ditindaklanjuti kepada Super Admin. | PDS-DEC-011; Physical Schema §22; SEC-009 |
| SRS-ERR-008 | Operasi berisiko tinggi SHALL gagal tertutup bila authorization, target, atau lifecycle state tidak memenuhi precondition. | AUTH-INV-003–005; SEC-009 |

Semua error, termasuk generic server failure, SHALL tidak membocorkan stack trace, secret, query, path, atau detail implementasi sensitif. Exact wording/copy dan desain feedback menjadi keputusan UI/UX. Empty section SHALL menampilkan “Belum ada data” atau semantics equivalent dan SHALL NOT menciptakan data placeholder palsu.

## 31. Security Requirements

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-SEC-010 | Seluruh mutasi SHALL melewati server-side authentication dan Laravel Policies/Gates yang sesuai; production traffic SHALL menggunakan HTTPS. | SEC-001; SEC-003; Technical R&D §15 |
| SRS-SEC-011 | Query dan persistence SHALL menggunakan mekanisme parameter binding/ORM yang melindungi dari SQL injection. | SEC-004 |
| SRS-SEC-012 | Output dinamis SHALL di-escape secara default dan input rich content SHALL dibatasi/ditangani untuk melindungi dari XSS. | SEC-005 |
| SRS-SEC-013 | Request state-changing SHALL menggunakan perlindungan CSRF dari framework; session SHALL memakai secure framework behavior. | Technical R&D §15 |
| SRS-SEC-014 | Password, session token, credential, dan secret SHALL NOT ditulis ke source/log atau ditampilkan kembali; production debug SHALL dinonaktifkan. | SEC-001–002; Technical R&D §15 |
| SRS-SEC-015 | Login SHALL menerapkan rate limiting tanpa membuat lockout policy baru yang tidak disetujui. | SEC-006 |
| SRS-SEC-016 | Admin Dusun SHALL selalu dibatasi oleh authenticated `dusun_id`; client-supplied ownership SHALL NOT menjadi authority. | SEC-003; AUTH-INV-001 |
| SRS-SEC-017 | Hard delete Dusun SHALL tidak tersedia; hard delete non-Dusun SHALL hanya melalui Super Admin dan di luar mekanisme restore. | SEC-007; SEC-009 |
| SRS-SEC-018 | Logical removal account SHALL mempertahankan audit identity dan SHALL menolak login ketika `removed_at IS NOT NULL`. | ERD-DIR-035; PDS-DEC-004 |
| SRS-SEC-019 | Public routes SHALL mengekspos hanya projection data yang memenuhi lifecycle, parent status, scope, dan privacy precondition; upload SHALL divalidasi dan runtime/database credential SHALL memakai least privilege. | AUTH-INV-002, 006–010; PRIV-001; Technical R&D §15 |

## 32. Performance Requirements

Source tidak menetapkan angka SLA, waktu respons, bundle size, throughput, atau skor audit; SRS ini tidak menciptakan angka baru.

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-NFR-001 | Portal SHALL mobile-first, ringan, dan cepat digunakan pada target mobile dan koneksi lapangan yang wajar. | NFR-001; NFR-003–004 |
| SRS-NFR-002 | Server rendering Blade SHALL menyediakan konten inti tanpa menunggu eksekusi SPA client-side dan SHALL menghindari JavaScript yang tidak diperlukan. | RND-DEC-002; Technical R&D §18 |
| SRS-NFR-003 | Progressive JavaScript SHALL hanya meningkatkan interaksi yang memerlukannya, termasuk peta, tanpa mengubah portal menjadi SPA; resource peta/media yang tidak diperlukan SHALL tidak dimuat. | Technical R&D §§5, 18 |
| SRS-NFR-004 | Physical database schema SHALL menyediakan 12 non-unique secondary indexes dan dua business UNIQUE indexes sebagaimana Physical Schema v1.1. Application query design SHALL tetap compatible dengan access pattern yang menjadi alasan index tersebut. Query plan/`EXPLAIN` MAY diverifikasi pada implementation/testing bila diperlukan; SRS SHALL NOT memaksa MariaDB optimizer menggunakan index tertentu atau menambah index calculated status, removed username, maupun map constants. | PDS-DEC-013; Physical Schema §§23–24 |
| SRS-NFR-005 | Media processing SHALL menghasilkan aset web yang dioptimalkan dan tetap mematuhi limit hosting yang dikualifikasi. | MEDIA-006; RND-OQ-004 |

## 33. Accessibility / Usability Requirements

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-NFR-006 | Seluruh antarmuka publik dan dashboard SHALL menggunakan Bahasa Indonesia yang jelas. | NFR-002 |
| SRS-NFR-007 | Navigasi publik SHALL memberi akses cepat ke Dusun dan seksi informasi utama. | FR-007 |
| SRS-NFR-008 | Bagian tanpa data SHALL menampilkan empty state, bukan error atau konten rekaan. | FR-009 |
| SRS-NFR-009 | Aksi WhatsApp, detail, arsip, dan navigasi eksternal SHALL jelas teridentifikasi dan hanya ditampilkan ketika prerequisite datanya tersedia. | FR-010; DATA-012; Public User Flows |
| SRS-NFR-010 | Sistem SHALL NOT mengklaim conformance terhadap standar atau level accessibility yang belum ditetapkan source; detail accessibility MAY diusulkan pada fase UI/UX tanpa mengubah behavior FROZEN. | NFR-004; baseline accessibility boundary |

## 34. Compatibility Requirements

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-NFR-011 | Aplikasi SHALL kompatibel dengan Laravel 13 dan PHP 8.3+ pada target hosting yang lulus qualification. | RND-DEC-001–002; RND-OQ-002 |
| SRS-NFR-012 | Persistence SHALL kompatibel dengan MariaDB/InnoDB dan 17 CHECK directions yang benar-benar enforced oleh versi provider. | Approved Technical Baseline; RND-OQ-002 |
| SRS-NFR-013 | UI publik SHALL responsif dan dapat digunakan pada browser mobile modern yang didukung target pengguna. | NFR-003; BR-002 |
| SRS-NFR-014 | Dashboard SHALL tetap dapat digunakan pada viewport desktop dan mobile yang wajar tanpa menetapkan matriks browser baru. | NFR-003–004 |
| SRS-NFR-015 | Deployment SHALL kompatibel dengan shared hosting cPanel tanpa asumsi root access atau daemon permanen. | RND-DEC-008; RND-OQ-002 |
| SRS-NFR-016 | Storage path SHALL portable melalui abstraksi filesystem Laravel dan tidak bergantung pada absolute local path. | Technical R&D §§11, 18 |
| SRS-NFR-017 | Database behavior SHALL mengikuti collation/provider yang dikualifikasi; application normalization SHALL NOT mengubah semantic uniqueness yang telah dibekukan. | PDS-DEC-004; PDS-DEC-013; Physical Schema §23 |

## 35. Operational Requirements

| ID | Requirement normatif | Source |
| --- | --- | --- |
| SRS-OPS-001 | Sistem operasional SHALL mendukung logical database export dan separate media backup/export; backup dinilai siap handover hanya setelah restore capability diuji pada environment terpisah. | Technical R&D §14 |
| SRS-OPS-002 | Source repository, dependency lock file, configuration template tanpa secret, dan deployment/restore runbook SHALL dipertahankan sebagai handover assets. | Technical R&D §§14, 23 |
| SRS-OPS-003 | Data awal SHALL dikumpulkan bersama Tim KKN dan perangkat Dusun lalu diperiksa bersama Kepala Dusun, Pemerintah Desa, dan Tim KKN melalui proses offline. Website MAY diluncurkan dengan data belum lengkap bila kekosongan memakai empty state dan tidak ada fakta rekaan. | FR-009; OPS-003–004; OPEN-011 |
| SRS-OPS-004 | Setelah KKN, Admin Dusun SHALL memperbarui data dengan supervisi pihak Desa yang ditetapkan. | OPS-005; OPEN-006 |
| SRS-OPS-005 | Perangkat desa SHALL menerima pelatihan dan/atau panduan penggunaan dashboard; kebutuhan teknis final tetap RND-OQ-007. | OPS-006; RND-OQ-007 |
| SRS-OPS-006 | Hosting, domain, akun Super Admin, source, database/media export, dan production assets SHALL diserahterimakan melalui organization/team-controlled ownership sejauh disepakati dan SHALL NOT bergantung hanya pada akun personal mahasiswa. Retention, provider, dan pemilik individu tetap open. | OPS-007–008; OPEN-004/007; RND-DEC-007 |

## 36. Technical Open Question Boundary

Enam Technical R&D Open Questions tetap external dependency; tidak satu pun menjadi SRS software blocker saat ini.

| Upstream ID | Dependency/pre-production gate | Dampak SRS |
| --- | --- | --- |
| RND-OQ-002 | Qualification provider/paket shared hosting: PHP 8.3+, extensions, HTTPS, safe document root, deployment/cron, MariaDB CHECK enforcement, image/storage limits, backup/restore, secret/debug behavior. | NON-BLOCKING untuk SRS FREEZE; BLOCKING pre-production hosting approval/deployment. |
| RND-OQ-003 | Production tile provider setelah traffic dan Terms diverifikasi. | NON-BLOCKING / PRE-PRODUCTION; menentukan konfigurasi eksternal peta. |
| RND-OQ-004 | Estimasi traffic, jumlah media, ukuran upload, dan pertumbuhan tahunan. | NON-BLOCKING / PRE-PRODUCTION; menentukan cost/storage sizing dan limit upload. |
| RND-OQ-005 | Pemilik backup, retention, database/media export, dan restore drill. | NON-BLOCKING / PRE-HANDOVER; retention tidak dibuat di SRS. |
| RND-OQ-006 | Domain, billing contact, recovery contact, dan renewal process. | NON-BLOCKING / PRE-DEPLOYMENT; terkait OPEN-007. |
| RND-OQ-007 | Kompetensi operator/developer penerus dan kebutuhan pelatihan teknis. | NON-BLOCKING / PRE-HANDOVER; tidak mengubah behavior software. |

## 37. Product Open Questions

SRS SHALL mereferensikan sepuluh OPEN NON-BLOCKING berikut tanpa menyelesaikannya melalui asumsi.

| ID | Open item | Perlakuan di SRS |
| --- | --- | --- |
| OPEN-001 | Nama resmi keenam Dusun. | Dataset/bootstrap menunggu nama terverifikasi. |
| OPEN-002 | Redaksi final template pesan awal WhatsApp. | Exact wording belum final dan akan ditetapkan melalui keputusan OPEN-002; SRS tidak menetapkan template admin-editable, runtime-configurable, database-stored, atau settings page. |
| OPEN-004 | Identitas pemegang Super Admin setelah KKN. | Tidak mengubah role/permission software. |
| OPEN-005 | Calon Admin Dusun untuk seluruh enam Dusun. | Akun dibuat setelah identitas tersedia. |
| OPEN-006 | Personel/jabatan supervisor pasca-KKN. | Supervisi tetap proses operasional. |
| OPEN-007 | Provider, kepemilikan, biaya, dan prosedur handover hosting/domain. | Wajib diselesaikan sebelum handover produksi. |
| OPEN-008 | Konten/desain visual final papan QR fisik. | Di luar UI website dan tidak memblokir SRS. |
| OPEN-009 | Pemilihan tech stack, database, provider peta, hosting, dan deployment pada baseline historis. | Stack/database/hosting model telah diputus oleh Technical R&D v1.0; production provider/detail tetap pada technical qualification. |
| OPEN-010 | Mekanisme pemulihan akun Super Admin. | Tidak dibuat flow recovery baru. |
| OPEN-011 | Dataset aktual/placeholder saat peluncuran. | Empty state diperbolehkan; tidak mengarang data. |

## 38. Future — Out of MVP

Enam requirement berstatus FUTURE SHALL NOT diimplementasikan dalam MVP:

| Source ID | Future scope |
| --- | --- |
| FR-020 | QR khusus per Dusun yang langsung membuka Halaman Dusun. |
| DATA-004 | Menambah Dusun baru di luar enam Dusun awal. |
| MAP-011 | Pencarian lokasi berdasarkan nama; filter cukup untuk MVP. |
| MAP-012 | Garis atau bidang batas wilayah Dusun. |
| MEDIA-004 | Galeri beberapa foto per UMKM. |
| OPS-002 | Papan QR kecil di rumah Kepala Dusun atau Balai Dusun. |

## 39. Non-Goals

MVP SHALL NOT mencakup pengajuan surat online, pengaduan/form warga, pelayanan/pendaftaran warga online, akun/login warga, chat internal, transaksi/e-commerce, pemesanan, pembayaran, GPS tracking, routing portal, forum warga, notifikasi aplikasi, halaman khusus Tentang Desa, page builder, digital consent management, ACL/RBAC database tables, universal category table, generic map-points table, archive table, atau account restore/recycling.

Dokumen ini juga SHALL NOT menjadi SQL DDL, Laravel migration, Eloquent Model, API contract, UI design, test plan, deployment configuration, maupun implementation code.

## 40. State Model Summary

| ID | Axis | State/derivation normatif |
| --- | --- | --- |
| SRS-STATE-001 | Dusun | `ACTIVE` berarti eligible publik; `INACTIVE` menyembunyikan parent/child dari publik. |
| SRS-STATE-002 | Dusun | Transisi status tidak menghapus data, tidak mengubah ownership, dan tidak memblokir login Admin Dusun. |
| SRS-STATE-003 | Operational Soft Delete | `deleted_at IS NULL` = active; non-null = INACTIVE/NONAKTIF/SOFT_DELETED dan tidak publik. |
| SRS-STATE-004 | Operational restore | Hanya Super Admin dapat mengubah `deleted_at` kembali menjadi null; Admin Dusun tidak dapat restore. |
| SRS-STATE-005 | Admin identity | `removed_at IS NULL` = login-eligible setelah credential valid; non-null = logically removed dan tidak dapat login. |
| SRS-STATE-006 | Admin identity | Logical removal tidak mempunyai restore/reuse transition; retained username tetap reserved. |
| SRS-STATE-007 | Pengumuman | Active bila business date Asia/Jakarta `<= tanggal_kedaluwarsa` dan data/parent eligible. |
| SRS-STATE-008 | Pengumuman | Archive bila business date Asia/Jakarta `> tanggal_kedaluwarsa` dan data/parent eligible; archive bukan Soft Delete. |
| SRS-STATE-009 | Agenda | Effective end = `tanggal_selesai` bila ada, selain itu `tanggal_mulai`; calculated state berasal dari business date. |
| SRS-STATE-010 | Agenda | Effective state = manual override bila non-null, selain itu calculated state; tidak ada persisted calculated status. |

## 41. Functional Acceptance Criteria

| AC ID | Flow | Acceptance criterion testable |
| --- | --- | --- |
| AC-UF-PUB-001 | UF-PUB-001 | Given QR utama membuka Homepage, when pengguna memilih Dusun ACTIVE, then Halaman Dusun yang benar tampil; Dusun INACTIVE tidak ditawarkan. |
| AC-UF-PUB-002 | UF-PUB-002 | Given Homepage tersedia, when pengguna menelusuri halaman, then identitas Desa dan seksi data-driven yang eligible dapat dibaca tanpa login. |
| AC-UF-PUB-003 | UF-PUB-003 | Given Halaman Dusun ACTIVE, when pengguna memakai navigasi cepat, then viewport mencapai seksi tujuan dan seksi kosong tetap memiliki empty state. |
| AC-UF-PUB-004 | UF-PUB-004 | Given Peta Desa, when filter Dusun/kategori diterapkan, then marker mengikuti filter; given Peta Dusun, when halaman dibuka, then marker otomatis terbatas pada Dusun ACTIVE tersebut tanpa selector Dusun; when marker eligible dipilih, then konteks sumber dan aksi Google Maps tersedia. |
| AC-UF-PUB-005 | UF-PUB-005 | Given Kontak Pelayanan eligible, when tombol WhatsApp dipilih, then WhatsApp menerima nomor tujuan dan template pesan; kontak Soft Deleted/parent INACTIVE tidak tampil. |
| AC-UF-PUB-006 | UF-PUB-006 | Given UMKM eligible, when daftar/detail dibuka, then informasi dan produk terbaca serta external WhatsApp handoff menggunakan nomor yang tersimpan; marker hanya ada bila coordinate pair valid. |
| AC-UF-PUB-007 | UF-PUB-007 | Given fasilitas eligible dengan coordinate pair valid, when detail dibuka, then kategori, informasi, dan aksi arah tersedia; tombol kontak hanya muncul bila nomor ada. |
| AC-UF-PUB-008 | UF-PUB-008 | Given Agenda eligible, when detail dibuka, then tanggal, jam opsional, lokasi, media opsional, dan effective status yang benar ditampilkan. |
| AC-UF-PUB-009 | UF-PUB-009 | Given Pengumuman tidak Soft Deleted, when tanggal belum lewat then tampil aktif; when tanggal lewat then tersedia di Arsip; parent INACTIVE menyembunyikan scope Dusun. |
| AC-UF-PUB-010 | UF-PUB-010 | Given suatu seksi tidak memiliki data eligible, when halaman dibuka, then empty state tampil dan navigasi ke seksi lain tetap berfungsi. |
| AC-UF-AD-001 | UF-AD-001 | Given akun Admin Dusun aktif dengan kredensial valid, when login, then dashboard OWN_DUSUN tampil; kredensial invalid atau akun removed ditolak. |
| AC-UF-AD-002 | UF-AD-002 | Given Admin Dusun membuat data valid dalam scope sendiri, when simpan berhasil, then data tersimpan dan langsung eligible publik sesuai lifecycle/privacy tanpa approval. |
| AC-UF-AD-003 | UF-AD-003 | Given data OWN_DUSUN, when Admin Dusun menyimpan perubahan valid, then perubahan berlaku; resource Dusun lain tetap tidak dapat diakses atau diubah. |
| AC-UF-AD-004 | UF-AD-004 | Given data operasional OWN_DUSUN aktif, when dinonaktifkan, then `deleted_at` terisi, data tidak publik, dan Admin Dusun tidak memperoleh aksi restore/hard delete. |
| AC-UF-AD-005 | UF-AD-005 | Given Admin Dusun mengubah Profil Dusunnya, when input valid disimpan, then profil publik diperbarui tanpa memberi akses ke status Dusun atau profil Dusun lain. |
| AC-UF-AD-006 | UF-AD-006 | Given parent Dusun INACTIVE, when Admin Dusun aktif login, then dashboard OWN_DUSUN tetap tersedia sementara seluruh data Dusun tetap tersembunyi publik. |
| AC-UF-SA-001 | UF-SA-001 | Given akun Super Admin aktif dan kredensial valid, when login, then dashboard GLOBAL tampil tanpa binding Dusun. |
| AC-UF-SA-002 | UF-SA-002 | Given Super Admin memilih resource lintas Dusun/Desa, when operasi valid disimpan, then perubahan diterapkan sesuai global scope dan lifecycle resource. |
| AC-UF-SA-003 | UF-SA-003 | Given data operasional Soft Deleted, when Super Admin melakukan restore, then `deleted_at` menjadi null dan eligibility publik dihitung ulang dari parent/privacy/state lain. |
| AC-UF-SA-004 | UF-SA-004 | Given target non-Dusun yang boleh di-hard-delete dan dependency mengizinkan, when Super Admin mengonfirmasi operasi, then row terhapus permanen; Dusun atau target restricted ditolak. |
| AC-UF-SA-005 | UF-SA-005 | Given Dusun ACTIVE, when Super Admin mengubah ke INACTIVE, then parent/child hilang dari publik tanpa penghapusan row dan login Admin Dusun tetap bekerja. |
| AC-UF-SA-006 | UF-SA-006 | Given Dusun INACTIVE, when Super Admin mengubah ke ACTIVE, then data child non-Soft-Deleted yang memenuhi privacy/lifecycle kembali eligible publik. |
| AC-UF-SA-007 | UF-SA-007 | Given username baru belum dipakai, when Super Admin membuat Admin Dusun, then akun terikat tepat satu Dusun; username aktif/removed yang sudah ada ditolak. |
| AC-UF-SA-008 | UF-SA-008 | Given akun Admin Dusun aktif, when Super Admin mereset password, then hash credential diganti dan password baru dapat dipakai; self-service recovery tidak muncul. |
| AC-UF-SA-009 | UF-SA-009 | Given Super Admin memperbarui data sumber Homepage, when perubahan valid disimpan, then Homepage membaca data terbaru secara data-driven tanpa page builder. |

**Acceptance Criteria coverage:** 25/25 User Flows.

## 42. User Flow Traceability

| User Flow | SRS requirements | AC | Coverage |
| --- | --- | --- | --- |
| UF-PUB-001 | SRS-FR-001–002, SRS-FR-006, SRS-STATE-001–002 | AC-UF-PUB-001 | COVERED |
| UF-PUB-002 | SRS-FR-001, SRS-FR-003–005 | AC-UF-PUB-002 | COVERED |
| UF-PUB-003 | SRS-FR-006–009, SRS-NFR-007–009 | AC-UF-PUB-003 | COVERED |
| UF-PUB-004 | SRS-FR-035–040, SRS-EXT-002–003 | AC-UF-PUB-004 | COVERED |
| UF-PUB-005 | SRS-FR-010–013, SRS-EXT-004, SRS-SEC-007–009 | AC-UF-PUB-005 | COVERED |
| UF-PUB-006 | SRS-FR-014–017, SRS-DATA-008–010 | AC-UF-PUB-006 | COVERED |
| UF-PUB-007 | SRS-FR-018–024, SRS-DATA-008 | AC-UF-PUB-007 | COVERED |
| UF-PUB-008 | SRS-FR-025–030, SRS-STATE-009–010 | AC-UF-PUB-008 | COVERED |
| UF-PUB-009 | SRS-FR-031–034, SRS-STATE-007–008 | AC-UF-PUB-009 | COVERED |
| UF-PUB-010 | SRS-FR-005, SRS-NFR-008 | AC-UF-PUB-010 | COVERED |
| UF-AD-001 | SRS-FR-041–044, SRS-AUTH-001, SRS-AUTH-006 | AC-UF-AD-001 | COVERED |
| UF-AD-002 | SRS-FR-045–047, SRS-AUTH-012, SRS-VAL-001–017 | AC-UF-AD-002 | COVERED |
| UF-AD-003 | SRS-FR-045–047, SRS-AUTH-001, SRS-AUTH-012 | AC-UF-AD-003 | COVERED |
| UF-AD-004 | SRS-DATA-001–004, SRS-AUTH-003, SRS-AUTH-005, SRS-AUTH-007 | AC-UF-AD-004 | COVERED |
| UF-AD-005 | SRS-FR-007, SRS-FR-045–047, SRS-AUTH-011–012 | AC-UF-AD-005 | COVERED |
| UF-AD-006 | SRS-FR-009, SRS-AUTH-006, SRS-STATE-001–002 | AC-UF-AD-006 | COVERED |
| UF-SA-001 | SRS-FR-041–044, SRS-FR-048, SRS-SEC-001–006 | AC-UF-SA-001 | COVERED |
| UF-SA-002 | SRS-FR-048–050, SRS-AUTH-011, SRS-SEC-010 | AC-UF-SA-002 | COVERED |
| UF-SA-003 | SRS-FR-050, SRS-DATA-003, SRS-STATE-004 | AC-UF-SA-003 | COVERED |
| UF-SA-004 | SRS-FR-050, SRS-AUTH-004–005, SRS-ERR-007–008 | AC-UF-SA-004 | COVERED |
| UF-SA-005 | SRS-FR-049, SRS-AUTH-006, SRS-STATE-001–002 | AC-UF-SA-005 | COVERED |
| UF-SA-006 | SRS-FR-049, SRS-STATE-001–004 | AC-UF-SA-006 | COVERED |
| UF-SA-007 | SRS-FR-051, SRS-FR-053–054, SRS-VAL-002–004 | AC-UF-SA-007 | COVERED |
| UF-SA-008 | SRS-FR-052, SRS-SEC-002, SRS-SEC-005 | AC-UF-SA-008 | COVERED |
| UF-SA-009 | SRS-FR-003–004, SRS-FR-048, SRS-AUTH-009, SRS-AUTH-011 | AC-UF-SA-009 | COVERED |

**User Flow coverage:** 25/25 — 10 Public, 6 Admin Dusun, 9 Super Admin.

## 43. Authorization Traceability

| Authorization invariant | SRS requirement | Coverage |
| --- | --- | --- |
| AUTH-INV-001 | SRS-AUTH-001; SRS-SEC-016 | COVERED |
| AUTH-INV-002 | SRS-AUTH-002; SRS-SEC-019 | COVERED |
| AUTH-INV-003 | SRS-AUTH-003; SRS-DATA-003 | COVERED |
| AUTH-INV-004 | SRS-AUTH-004; SRS-SEC-017 | COVERED |
| AUTH-INV-005 | SRS-AUTH-005; SRS-SEC-017 | COVERED |
| AUTH-INV-006 | SRS-AUTH-006; SRS-FR-009 | COVERED |
| AUTH-INV-007 | SRS-AUTH-007; SRS-DATA-002 | COVERED |
| AUTH-INV-008 | SRS-AUTH-008; SRS-STATE-007–008 | COVERED |
| AUTH-INV-009 | SRS-AUTH-009; SRS-FR-003 | COVERED |
| AUTH-INV-010 | SRS-AUTH-010; SRS-DATA-004, SRS-DATA-012 | COVERED |
| AUTH-INV-011 | SRS-AUTH-011; SRS-FR-022, SRS-FR-048–049 | COVERED |
| AUTH-INV-012 | SRS-AUTH-012; SRS-FR-046 | COVERED |

**Authorization Invariant coverage:** 12/12.

## 44. Data Integrity Traceability

| Data Integrity Rule | SRS mapping | Coverage |
| --- | --- | --- |
| ERD-DIR-001 | SRS-DATA-005; one Desa context | COVERED |
| ERD-DIR-002 | SRS-FR-002; SRS-FR-049 | COVERED |
| ERD-DIR-003 | SRS-DATA-005; SRS-VAL context validation | COVERED |
| ERD-DIR-004 | SRS-VAL-001; SRS-STATE-001 | COVERED |
| ERD-DIR-005 | SRS-STATE-002; SRS-DATA-006 | COVERED |
| ERD-DIR-006 | SRS-AUTH-004; SRS-SEC-017 | COVERED |
| ERD-DIR-007 | SRS-FR-044; SRS-AUTH-002 | COVERED |
| ERD-DIR-008 | SRS-VAL-002 | COVERED |
| ERD-DIR-009 | SRS-VAL-003 | COVERED |
| ERD-DIR-010 | SRS-FR-051 | COVERED |
| ERD-DIR-011 | SRS-SEC-003; SRS-FR-053 | COVERED |
| ERD-DIR-012 | SRS-FR-010, SRS-FR-014, SRS-FR-018 | COVERED |
| ERD-DIR-013 | SRS-FR-011 | COVERED |
| ERD-DIR-014 | SRS-SEC-007–009 | COVERED |
| ERD-DIR-015 | SRS-FR-013; SRS-VAL-005–007 | COVERED |
| ERD-DIR-016 | SRS-FR-017; SRS-VAL-008–010 | COVERED |
| ERD-DIR-017 | SRS-FR-017 | COVERED |
| ERD-DIR-018 | SRS-DATA-010 | COVERED |
| ERD-DIR-019 | SRS-FR-016; SRS-DATA-004, 006 | COVERED |
| ERD-DIR-020 | SRS-FR-016; Explicit Non-Goals | COVERED |
| ERD-DIR-021 | SRS-FR-018, SRS-FR-022–024 | COVERED |
| ERD-DIR-022 | SRS-DATA-008; SRS-VAL-011–012 | COVERED |
| ERD-DIR-023 | SRS-FR-020 | COVERED |
| ERD-DIR-024 | SRS-FR-025; SRS-VAL-013 | COVERED |
| ERD-DIR-025 | SRS-FR-031; SRS-VAL-017 | COVERED |
| ERD-DIR-026 | SRS-FR-026–027; SRS-VAL-014 | COVERED |
| ERD-DIR-027 | SRS-FR-027–028; SRS-VAL-015; SRS-STATE-009–010 | COVERED |
| ERD-DIR-028 | SRS-FR-029; SRS-VAL-016 | COVERED |
| ERD-DIR-029 | SRS-FR-032; SRS-STATE-007–008 | COVERED |
| ERD-DIR-030 | SRS-FR-033; SRS-AUTH-008 | COVERED |
| ERD-DIR-031 | SRS-DATA-001–004; SRS-STATE-003–004 | COVERED |
| ERD-DIR-032 | SRS-AUTH-003–005; SRS-FR-047, 050 | COVERED |
| ERD-DIR-033 | SRS-FR-035–040 | COVERED |
| ERD-DIR-034 | SRS-DATA-012; SRS-AUTH-010 | COVERED |
| ERD-DIR-035 | SRS-FR-043, SRS-FR-053–054; SRS-STATE-005–006 | COVERED |

**Data Integrity Rule coverage:** 35/35. Clarification `DATA-007 → deleted_at` tidak membuat ERD-DIR baru.

## 45. Baseline / PRD Traceability

| Baseline IDs | Status | Count | SRS destination |
| --- | --- | ---: | --- |
| BR-001–BR-007 | CONFIRMED | 7 | Scope; SRS-FR-001–040; SRS-NFR-001–010 |
| FR-001–FR-019, FR-021–FR-022 | CONFIRMED | 21 | SRS-FR-001–054; SRS-AUTH-001–012; lifecycle |
| FR-020 | FUTURE | 1 | Future Requirements |
| NFR-001–NFR-004 | CONFIRMED | 4 | SRS-NFR-001–017 |
| DATA-001, 003, 005, 007, 009, 011, 013–014, 016 | CONFIRMED | 9 | SRS-FR; SRS-DATA; SRS-VAL; lifecycle |
| DATA-002, 006, 008, 010, 012, 015, 017 | OPTIONAL | 7 | Functional/data requirements; optionality retained |
| DATA-004 | FUTURE | 1 | Future Requirements |
| MAP-001–MAP-008 | CONFIRMED | 8 | SRS-FR-013, 017–024, 035–040; SRS-EXT |
| MAP-009–MAP-010 | OPTIONAL | 2 | Optional coordinate/location eligibility retained |
| MAP-011–MAP-012 | FUTURE | 2 | Future Requirements |
| MEDIA-002, MEDIA-006 | CONFIRMED | 2 | SRS-DATA-009, 011–012 |
| MEDIA-001, 003, 005, 007 | OPTIONAL | 4 | Media optionality retained; no mandatory media |
| MEDIA-004 | FUTURE | 1 | Future Requirements |
| ROLE-001–ROLE-011 | CONFIRMED | 11 | Actors; SRS-AUTH; admin functional requirements |
| SEC-001–SEC-009 | CONFIRMED | 9 | SRS-SEC-001–019; SRS-ERR; authorization |
| PRIV-001 | CONFIRMED | 1 | SRS-SEC-007–009 |
| OPS-001 | CONFIRMED | 1 | SRS-FR-001; one main QR board and Homepage destination |
| OPS-003–OPS-008 | CONFIRMED | 6 | SRS-OPS-003–006; collection, review, maintenance, training, handover |
| OPS-002 | FUTURE | 1 | Future Requirements |
| **Total** | **79 CONFIRMED / 13 OPTIONAL / 6 FUTURE** | **98** | **98/98 traced** |

PRD tidak membuat ID requirement baru; traceability PRD dipertahankan berdasarkan frozen product sections:

| PRD area | SRS requirement(s) | Status |
| --- | --- | --- |
| Product Goals, Principles, MVP Scope, Public Website | SRS-FR-001–040; SRS-NFR-001–010 | TRACED |
| Homepage, Halaman Dusun, Kontak, UMKM, Fasilitas | SRS-FR-001–024 | TRACED |
| Agenda/Kegiatan, Pengumuman, Map, QR experience | SRS-FR-025–040; Future Requirements | TRACED |
| Admin Dusun, Super Admin, Dusun lifecycle | SRS-FR-041–054; SRS-AUTH-001–012; SRS-STATE-001–006 | TRACED |
| Authentication, Privacy, Security | SRS-SEC-001–019; SRS-ERR-001–008 | TRACED |
| Media and Non-Functional requirements | SRS-DATA-009–012; SRS-NFR-001–017 | TRACED |
| Optional MVP, Future, Open Decisions, Dependencies/Risks | Classification retained in Sections 36–39 | TRACED |

OPTIONAL tidak diubah menjadi mandatory. FUTURE tidak dimasukkan ke MVP behavior. Sepuluh OPEN NON-BLOCKING dicatat pada Bagian 37.

## 46. Sitemap Traceability

| Sitemap node/area | SRS mapping | Coverage |
| --- | --- | --- |
| Homepage | SRS-FR-001–005 | COVERED |
| Halaman Dusun | SRS-FR-006–009 | COVERED |
| Arsip Pengumuman | SRS-FR-031–034; SRS-STATE-007–008 | COVERED |
| Detail UMKM | SRS-FR-014–017 | COVERED |
| Detail Agenda/Kegiatan | SRS-FR-025–030 | COVERED |
| Detail Fasilitas/Lokasi | SRS-FR-018–021 | COVERED |
| Detail Pengumuman | SRS-FR-031–033 | COVERED |
| Marker Pelayanan dalam konteks existing | SRS-FR-010–013; SRS-FR-035–040 | COVERED — tidak membuat page baru |
| Login Admin bersama | SRS-FR-041–044 | COVERED |
| Dashboard Admin Dusun + 6 management areas | SRS-FR-045–047 | COVERED |
| Dashboard Super Admin + 10 management areas | SRS-FR-048–054 | COVERED |

SRS tidak menambah page type atau hierarchy baru dan menyerahkan komposisi visual kepada fase UI/UX.

## 47. Requirement Conflict Rule

Urutan authority mengikuti Bagian 1. Requirement Baseline adalah source scope utama; PRD memperjelas product contract; Sitemap dan User Flows menetapkan struktur/navigasi/perjalanan; Roles & Permissions menetapkan authority; ERD dan Physical Schema menetapkan integritas/representasi data; Technical R&D menetapkan technical baseline dan external qualification.

Jika ditemukan contradiction pada review:

1. requirement yang bersumber dari authority lebih tinggi SHALL dipertahankan;
2. SRS SHALL NOT menyelesaikan contradiction melalui asumsi;
3. contradiction SHALL dicatat dengan label `SRS SOURCE CONFLICT` dan sebagai Change Request terhadap source yang tepat;
4. status freeze SRS SHALL ditahan sampai keputusan manusia tersedia.

Pada normalisasi v1.1 ini tidak ditemukan contradiction nyata. Historical approved/applied Physical Schema CR berjumlah 1 (`PDS-CR-001`) dan seluruh Open Change Request setelah aplikasi berjumlah 0.

## 48. SRS Open Questions

| Kategori | Count | Status |
| --- | ---: | --- |
| SRS Open Questions | 0 | Tidak ada pertanyaan baru yang lahir dari specification. |
| Blocking SRS Questions | 0 | Human review dan final validation telah selesai. |
| Upstream Product OPEN NON-BLOCKING | 10 | Direferensikan pada Bagian 37; tidak diselesaikan di SRS. |
| Technical R&D Open Questions | 6 | External dependency pada Bagian 36; `RND-OQ-002` tetap pre-production gate. |
| Physical Schema Open Questions | 0 | Source physical schema tetap FROZEN. |
| Blocking Physical Schema Questions | 0 | Tidak ada. |

## 49. Change Request Summary

| Change Request category | Count |
| --- | ---: |
| Baseline Change Request | 0 |
| PRD Change Request | 0 |
| Sitemap Change Request | 0 |
| User Flow Change Request | 0 |
| Roles/Permissions Change Request | 0 |
| ERD Change Request | 0 |
| Technical Baseline Change Request | 0 |
| Historical approved/applied Physical Schema CR | 1 — `PDS-CR-001` |
| Open Physical Schema Change Request | 0 |
| **Seluruh Open Change Request** | **0** |

## 50. SRS Review Checklist

- [x] CHK-001 — All frozen sources read.
- [x] CHK-002 — Source hierarchy preserved.
- [x] CHK-003 — No new product behavior introduced.
- [x] CHK-004 — Public behavior specified.
- [x] CHK-005 — Homepage specified.
- [x] CHK-006 — Dusun page specified.
- [x] CHK-007 — Kontak specified.
- [x] CHK-008 — UMKM specified.
- [x] CHK-009 — Fasilitas specified.
- [x] CHK-010 — Agenda/Kegiatan specified.
- [x] CHK-011 — Pengumuman specified.
- [x] CHK-012 — Peta specified.
- [x] CHK-013 — Login specified.
- [x] CHK-014 — Admin Dusun behavior specified.
- [x] CHK-015 — Super Admin behavior specified.
- [x] CHK-016 — Account logical removal specified.
- [x] CHK-017 — Dusun lifecycle specified.
- [x] CHK-018 — Soft Delete specified.
- [x] CHK-019 — Announcement archive semantics specified.
- [x] CHK-020 — Agenda lifecycle specified.
- [x] CHK-021 — Authentication specified.
- [x] CHK-022 — Authorization specified.
- [x] CHK-023 — Validation specified.
- [x] CHK-024 — Privacy specified.
- [x] CHK-025 — Media specified.
- [x] CHK-026 — Security specified.
- [x] CHK-027 — External handoffs specified.
- [x] CHK-028 — Error/empty state behavior specified.
- [x] CHK-029 — Non-goals explicit.
- [x] CHK-030 — FUTURE excluded from MVP behavior.
- [x] CHK-031 — Technical baseline preserved.
- [x] CHK-032 — Physical schema preserved dengan `PDS-CR-001` applied: 11 domain tables + `migrations` metadata exception.
- [x] CHK-033 — User Flow coverage 25/25.
- [x] CHK-034 — Authorization coverage 12/12.
- [x] CHK-035 — Data Integrity coverage 35/35.
- [x] CHK-036 — Upstream MVP requirements traceable.
- [x] CHK-037 — No API contract.
- [x] CHK-038 — No SQL/migration.
- [x] CHK-039 — No source code.
- [x] CHK-040 — No visual UI design.
- [x] CHK-041 — Source FROZEN lain tidak berubah; approved downstream clarification diterapkan hanya pada source yang diotorisasi.
- [x] CHK-042 — Human review completed and SRS freeze validated.
- [x] CHK-043 — UMKM product traceability menggunakan `FR-012` / `DATA-009`.
- [x] CHK-044 — Agenda tidak menggunakan `DATA-013` atau `DATA-016` sebagai source yang salah.
- [x] CHK-045 — Pengumuman scope/lifecycle menggunakan `FR-017` / `DATA-016` secara benar.
- [x] CHK-046 — Seluruh `PDS-DEC` reference telah diverifikasi.
- [x] CHK-047 — Peta Desa dan Peta Dusun filter semantics dibedakan secara benar.
- [x] CHK-048 — `OPEN-002` tidak menciptakan runtime-configurable template requirement.
- [x] CHK-049 — SRS tidak mewajibkan MariaDB optimizer menggunakan index tertentu.
- [x] CHK-050 — SRS ditetapkan Version 1.2 — FROZEN FOR MVP.

**Checklist result:** 50/50 PASS.

## 51. Final Validation

| No. | Validation | Result |
| ---: | --- | --- |
| 1 | Version | PASS — 1.2 |
| 2 | Status | PASS — FROZEN FOR MVP |
| 3 | Unique normative SRS IDs | PASS — 161 |
| 4 | Functional specifications | PASS — 54 |
| 5 | Authorization specifications | PASS — 12 |
| 6 | Validation specifications | PASS — 17 |
| 7 | Non-Functional specifications | PASS — 17 |
| 8 | Acceptance Criteria | PASS — 25 |
| 9 | User Flow coverage | PASS — 25/25 |
| 10 | Authorization Invariant coverage | PASS — 12/12 |
| 11 | Data Integrity Rule coverage | PASS — 35/35 |
| 12 | Baseline classified requirements | PASS — 98/98 |
| 13 | SRS Open Questions | PASS — 0 |
| 14 | Blocking SRS Questions | PASS — 0 |
| 15 | Upstream Product OPEN referenced | PASS — 10 |
| 16 | Technical R&D dependencies referenced | PASS — 6 |
| 17 | `DATA-010` tidak digunakan untuk Produk UMKM | PASS |
| 18 | `DATA-013` tidak digunakan sebagai Agenda scope | PASS |
| 19 | `DATA-016` tidak dimasukkan sebagai Agenda field source | PASS |
| 20 | `DATA-015` tidak digunakan sebagai Pengumuman scope | PASS |
| 21 | `PDS-DEC-007` = coordinates | PASS |
| 22 | `PDS-DEC-008` = Agenda | PASS |
| 23 | `PDS-DEC-009` = Pengumuman | PASS |
| 24 | `PDS-DEC-010` = media | PASS |
| 25 | `PDS-DEC-011` = FK referential action | PASS |
| 26 | `PDS-DEC-013` = UNIQUE/index | PASS |
| 27 | Map taxonomy tidak merujuk media decision | PASS |
| 28 | Peta Dusun tidak mempunyai requirement selector Dusun | PASS |
| 29 | `OPEN-002` tidak menambah settings/config feature | PASS |
| 30 | Index requirement tidak mengontrol optimizer | PASS |
| 31 | Optional/Future classifications tidak berubah | PASS — 13 OPTIONAL / 6 FUTURE |
| 32 | Technical baseline tidak berubah | PASS |
| 33 | Physical Schema clarification | PASS — 11 domain tables + only `migrations` metadata; total expected SQL tables 12 |
| 34 | Change Request status | PASS — historical approved/applied PDS CR 2 (`PDS-CR-001`, `PDS-CR-002`); seluruh Open Change Request 0 |
| 35 | No API contract | PASS |
| 36 | No SQL/migration | PASS |
| 37 | No implementation code | PASS |
| 38 | No visual UI design | PASS |

Mechanically verified document metrics: 161 unique normative IDs (54 Functional, 12 Authorization, 12 Data, 17 Validation, 10 State, 6 External Integration, 8 Error Handling, 19 Security/Authentication/Privacy, 17 Non-Functional, 6 Operational), 25 Acceptance Criteria, 51 required sections, and 50/50 checklist items.

**Conclusion:** SRS v1.2 telah menerapkan human decision `PDS-CR-001` dan `PDS-CR-002`, seluruh traceability dan final validation tetap lulus, dan dokumen tetap **FROZEN FOR MVP**. Tidak ada product behavior, ERD, authorization, UI/UX, atau frozen metric yang berubah; SRS siap menjadi source authority definitif implementasi.
