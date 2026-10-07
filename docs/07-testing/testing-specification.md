# Testing Specification

| Atribut | Nilai |
| --- | --- |
| Project | Portal Informasi Desa Bendung |
| Document | Testing Specification |
| Version | 1.2 |
| Status | FROZEN FOR MVP |
| Execution status | NOT RUN — 108/108 test case belum dieksekusi |
| Normative software contract | SRS v1.2 — FROZEN FOR MVP |

## 1. Tujuan dan Batas Dokumen

Dokumen ini mendefinisikan desain pengujian MVP: scope, level, test case, data uji sintetis, traceability, qualification pra-produksi, arah regresi, dan tata kelola defect. Dokumen ini dibuat sebelum implementasi dan tidak berisi hasil eksekusi.

Dokumen ini tidak membuat atau menjalankan PHPUnit, Pest, Playwright, Cypress, Selenium, migration, API, Blade, CSS, JavaScript, maupun kode implementasi. Seluruh status test case adalah `NOT RUN`.

## 2. Sumber, Precedence, dan Integritas

Urutan authority yang dipakai:

1. Requirements Baseline.
2. PRD.
3. Sitemap.
4. User Flows.
5. Roles & Permissions.
6. ERD / Data Model.
7. Technical R&D.
8. Physical Database Schema.
9. SRS.
10. UI/UX Specification.
11. Wireframe Specification.
12. Visual Design Specification.
13. High-Fidelity Public Core Reference.

SRS v1.2 menjadi kontrak software normatif. Sumber dengan precedence lebih tinggi menang bila ditemukan konflik. Testing Specification tidak mengubah sumber upstream di luar approved downstream clarification `PDS-CR-001` dan `PDS-CR-002`; kontradiksi lain harus menjadi Change Request, bukan asumsi test.

Sumber FROZEN FOR MVP yang dibaca:

- `docs/01-requirements/requirements-baseline.md`
- `docs/02-product/PRD.md`
- `docs/03-ux/sitemap.md`
- `docs/03-ux/user-flows.md`
- `docs/03-ux/ui-ux-specification.md`
- `docs/03-ux/wireframe-specification.md`
- `docs/03-ux/visual-design-specification.md`
- `docs/04-system/roles-permissions.md`
- `docs/04-system/erd-data-model.md`
- `docs/04-system/physical-database-schema.md`
- `docs/05-rnd/technical-rnd.md`
- `docs/06-specification/SRS.md`

## 3. Scope Pengujian

In scope:

- behavior fungsional Public, Admin Dusun, dan Super Admin;
- authentication, authorization server-side, `OWN_DUSUN`, dan `GLOBAL`;
- CRUD, validation, lifecycle/state, Soft Delete, restore, hard delete, dan logical removal akun;
- 11 tabel, 13 relationship, 17 CHECK direction, 35 Data Integrity Rules, dua business uniqueness constraint, dan referential action;
- 25 User Flow, 25 Acceptance Criteria, dan 12 Authorization Invariant;
- public flow, dashboard, responsive UI, accessibility direction, dan Warm Natural visual QA;
- WhatsApp, Google Maps, Leaflet/tile provider, media/storage, failure state, security, NFR, dan qualification environment;
- regression dan smoke direction.

Out of scope:

- eksekusi test dan pencatatan PASS/FAIL;
- automation code atau implementation code;
- Batch 2/3 atau mockup baru;
- fitur FUTURE/non-goal seperti marketplace, public account, map search, polygon, generic map-point CRUD, internal routing, dan page builder;
- keputusan provider, exact template WhatsApp, exact breakpoint, exact performance SLA, atau kebijakan yang masih OPEN.

## 4. Aktor dan Scope

Tepat tiga aktor digunakan:

| Actor | Scope | Batas utama |
| --- | --- | --- |
| PUBLIC USER | PUBLIC | Tanpa login; read-only atas projection yang eligible. |
| ADMIN DUSUN | OWN_DUSUN | Tepat satu Dusun dari authenticated `dusun_id`; tidak dapat memilih/mengubah Dusun lain. |
| SUPER ADMIN | GLOBAL | Lintas Desa/Dusun sesuai permission; tetap tidak dapat hard-delete Dusun. |

Tidak ada role Editor, Moderator, Kepala Dusun, Citizen, Developer, atau approval role.

## 5. Level Pengujian

| Level | Fokus |
| --- | --- |
| UNIT / DOMAIN | Kalkulasi lifecycle, validation, scope logic, state derivation, dan policy/helper. |
| INTEGRATION | Relationship database, CRUD, Soft Delete/restore/hard delete, FK, media reference, session, parent-child. |
| FEATURE / APPLICATION | Route/page, form submission, public visibility, role behavior, dashboard, cross-Dusun rejection. |
| UI / RESPONSIVE | Public/dashboard Desktop dan Mobile, form, map, dialog, state presentation. |
| END-TO-END / USER FLOW | Seluruh 25 User Flow FROZEN. |
| VISUAL QA | Visual Design; HF UX-SCR-001–009; Wireframe + Visual Design untuk UX-SCR-010–028. |
| PRE-PRODUCTION / DEPLOYMENT QUALIFICATION | Runtime, hosting, database, storage, backup, security configuration, dan external dependency. |

## 6. Priority, Automation, dan Status

| Nilai | Definisi |
| --- | --- |
| P0 — Critical | Authentication, authorization, cross-Dusun isolation, privacy/public visibility, destructive integrity, critical flow. |
| P1 — High | Core CRUD, lifecycle, map, external handoff, major validation. |
| P2 — Medium | Responsive/visual, secondary state, loading/empty/error presentation. |
| AUTOMATE | Stabil dan deterministik: authorization, validation, CRUD, lifecycle, database integrity. |
| MANUAL | Penilaian visual/usability atau kondisi operasional yang memerlukan observasi manusia. |
| HYBRID | Assertion otomatis disertai observasi browser, external handoff, atau visual. |

Status awal dan satu-satunya status dalam dokumen ini adalah `NOT RUN`.

## 7. Test Data Strategy

Semua fixture sintetis dan tidak boleh dianggap sebagai data produksi atau fakta Desa. Mockup placeholder bukan production data.

| Fixture | Variasi minimum |
| --- | --- |
| Desa | Satu Desa sintetis lengkap dan variasi field opsional kosong. |
| Dusun | Enam Dusun konseptual sintetis; minimal satu ACTIVE dan satu INACTIVE. |
| Account | Super Admin; Admin untuk beberapa Dusun; credential invalid; Admin logically removed. |
| Kontak | Active/Soft Deleted; koordinat pair/null; foto/alamat optional; izin offline represented sebagai precondition, bukan field. |
| UMKM/Produk | Multi-product; tanpa koordinat; dengan koordinat; foto optional; Soft Deleted. |
| Kategori/Fasilitas | Kategori terpakai/tidak terpakai; duplikat nama; fasilitas dengan koordinat batas/invalid; WhatsApp/foto optional. |
| Agenda/Media | DESA/DUSUN; AKAN_DATANG/BERLANGSUNG/SELESAI; override/null; end/null; jam/media optional; Soft Deleted. |
| Pengumuman | Active, expired/archive, Soft Deleted; scope DESA/DUSUN; parent ACTIVE/INACTIVE. |
| External/environment | Nomor/koordinat valid-invalid, provider tersedia/gagal, upload valid-invalid, storage penuh/permission error, configuration production-safe. |

Business date/time untuk lifecycle dibekukan per test dan dievaluasi pada zona `Asia/Jakarta`.

## 8. Format Test Case

Setiap baris katalog adalah satu test case dan memuat: ID, requirement/source, actor, preconditions, test data, steps, expected result, priority, level, automation candidate, dan status. Notasi `submit` berarti request harus diuji pada application/server boundary, bukan hanya melalui UI.

## 9. Katalog Test Case — User Flow dan Acceptance Criteria

### 9.1 Public User — 10/10 User Flow

| Test Case ID | Requirement/Source | Actor | Preconditions | Test Data | Steps | Expected Result | Priority | Test Level | Automation Candidate | Status |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| TC-PUB-001 | UF-PUB-001; AC-UF-PUB-001; SRS-FR-001–002,006; STATE-001–002 | PUBLIC USER | Homepage tersedia | ACTIVE dan INACTIVE Dusun | Buka QR destination; pilih Dusun | Homepage tanpa login; hanya ACTIVE ditawarkan; Halaman Dusun benar terbuka | P0 | E2E | HYBRID | NOT RUN |
| TC-PUB-002 | UF-PUB-002; AC-UF-PUB-002; SRS-FR-001,003–005 | PUBLIC USER | Homepage tersedia | Data identitas; section populated/empty | Telusuri urutan Homepage | Identitas dan section data-driven eligible terbaca tanpa login; urutan frozen dipertahankan | P1 | E2E | HYBRID | NOT RUN |
| TC-PUB-003 | UF-PUB-003; AC-UF-PUB-003; SRS-FR-006–009; NFR-007–009 | PUBLIC USER | Halaman Dusun ACTIVE | Section populated/empty | Gunakan seluruh quick navigation | Target section/heading tercapai; empty state tetap ada; section lain dapat dinavigasi | P1 | E2E | HYBRID | NOT RUN |
| TC-PUB-004 | UF-PUB-004; AC-UF-PUB-004; SRS-FR-035–040; EXT-002–003 | PUBLIC USER | Peta dan provider/stub tersedia | Marker multi-Dusun/kategori | Filter Peta Desa; buka Peta Dusun; pilih marker/arah | Filter benar; Peta Dusun fixed scope tanpa selector Dusun; popup/context dan handoff Google Maps hanya untuk marker eligible | P0 | E2E | HYBRID | NOT RUN |
| TC-PUB-005 | UF-PUB-005; AC-UF-PUB-005; SRS-FR-010–013; EXT-004; SEC-007–009 | PUBLIC USER | Kontak eligible | Nomor tersimpan; Soft Deleted; parent INACTIVE | Buka kontak; pilih WhatsApp; cek variasi ineligible | Nomor/template dikirim ke handoff; ineligible tidak tampil; private location tidak diinferensikan | P0 | E2E | HYBRID | NOT RUN |
| TC-PUB-006 | UF-PUB-006; AC-UF-PUB-006; SRS-FR-014–017; DATA-008–010 | PUBLIC USER | UMKM eligible | Multi-product; coordinate pair/null | Buka daftar/detail; WhatsApp; cek marker | Informasi/produk terbaca; nomor tersimpan dipakai; marker hanya untuk pair valid; tanpa commerce UI | P1 | E2E | HYBRID | NOT RUN |
| TC-PUB-007 | UF-PUB-007; AC-UF-PUB-007; SRS-FR-018–024; DATA-008 | PUBLIC USER | Fasilitas eligible | Kategori; pair valid; WhatsApp null/value | Buka daftar/detail; arah; kontak | Kategori/informasi/arah tersedia; WhatsApp hanya saat nomor ada | P1 | E2E | HYBRID | NOT RUN |
| TC-PUB-008 | UF-PUB-008; AC-UF-PUB-008; SRS-FR-025–030; STATE-009–010 | PUBLIC USER | Agenda eligible | Tiga state; jam/end/media optional | Buka daftar/detail pada setiap variasi | Tanggal, optional data, lokasi, media, dan effective status tepat; SELESAI bukan Soft Deleted | P1 | E2E | AUTOMATE | NOT RUN |
| TC-PUB-009 | UF-PUB-009; AC-UF-PUB-009; SRS-FR-031–034; STATE-007–008 | PUBLIC USER | Pengumuman tersedia | Active; expired; Soft Deleted; parent INACTIVE | Buka aktif, detail, arsip | Active/arsip derived benar; arsip tetap public; Soft Deleted/parent INACTIVE tidak public | P0 | E2E | AUTOMATE | NOT RUN |
| TC-PUB-010 | UF-PUB-010; AC-UF-PUB-010; SRS-FR-005; NFR-008 | PUBLIC USER | Section tanpa eligible data | Empty dataset per section | Buka page dan lanjutkan navigasi | Empty state, bukan error/fake data; page dan section lain tetap berfungsi | P1 | E2E | HYBRID | NOT RUN |

### 9.2 Admin Dusun — 6/6 User Flow

| Test Case ID | Requirement/Source | Actor | Preconditions | Test Data | Steps | Expected Result | Priority | Test Level | Automation Candidate | Status |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| TC-AD-001 | UF-AD-001; AC-UF-AD-001; SRS-FR-041–044; AUTH-001,006; PDS-CR-002 | ADMIN DUSUN | Login tersedia | Valid, invalid, removed account; remember me flag | Login untuk tiap variasi | Valid diarahkan ke dashboard OWN_DUSUN; remember me menyimpan token persisten yang valid; invalid/removed ditolak generik | P0 | E2E | AUTOMATE | NOT RUN |
| TC-AD-002 | UF-AD-002; AC-UF-AD-002; SRS-FR-045–047; AUTH-012; VAL-001–017 | ADMIN DUSUN | Authenticated | Valid synthetic resource tiap modul | Create dan submit | Data tersimpan dalam OWN_DUSUN dan langsung eligible sesuai lifecycle/privacy tanpa approval | P0 | E2E | AUTOMATE | NOT RUN |
| TC-AD-003 | UF-AD-003; AC-UF-AD-003; SRS-FR-045–047; AUTH-001,012 | ADMIN DUSUN | Own/foreign records ada | Dua Dusun | Update own lalu submit foreign ID/request | Own berubah; foreign read/mutation ditolak server-side tanpa kebocoran | P0 | E2E | AUTOMATE | NOT RUN |
| TC-AD-004 | UF-AD-004; AC-UF-AD-004; SRS-DATA-001–004; AUTH-003,005,007 | ADMIN DUSUN | Own active operational records | Lima Soft Delete resource | Nonaktifkan; buka normal/public; cari restore/hard delete | `deleted_at` terisi; hilang normal/public; tidak ada restore browser/hard delete | P0 | E2E | AUTOMATE | NOT RUN |
| TC-AD-005 | UF-AD-005; AC-UF-AD-005; SRS-FR-007,045–047; AUTH-011–012 | ADMIN DUSUN | Authenticated | Own/foreign profile; status field | Update own; coba status/foreign | Profil own berubah/public; status dan profil foreign tidak dapat dimutasi | P0 | E2E | AUTOMATE | NOT RUN |
| TC-AD-006 | UF-AD-006; AC-UF-AD-006; SRS-FR-009; AUTH-006; STATE-001–002 | ADMIN DUSUN | Parent INACTIVE | Active Admin own Dusun | Login dan kelola; akses public parent/child | Dashboard tetap tersedia; seluruh parent/child tersembunyi public | P0 | E2E | AUTOMATE | NOT RUN |

### 9.3 Super Admin — 9/9 User Flow

| Test Case ID | Requirement/Source | Actor | Preconditions | Test Data | Steps | Expected Result | Priority | Test Level | Automation Candidate | Status |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| TC-SA-001 | UF-SA-001; AC-UF-SA-001; SRS-FR-041–044,048; SEC-001–006; PDS-CR-002 | SUPER ADMIN | Login tersedia | Valid Super Admin; remember me flag | Login | Dashboard GLOBAL tampil tanpa binding Dusun; persistent authentication bekerja bila opsi remember dipilih | P0 | E2E | AUTOMATE | NOT RUN |
| TC-SA-002 | UF-SA-002; AC-UF-SA-002; SRS-FR-048–050; AUTH-011; SEC-010 | SUPER ADMIN | Authenticated | Desa/multi-Dusun resources | CRUD lintas scope | Perubahan valid diterapkan sesuai GLOBAL dan lifecycle resource | P1 | E2E | AUTOMATE | NOT RUN |
| TC-SA-003 | UF-SA-003; AC-UF-SA-003; SRS-FR-050; DATA-003; STATE-004 | SUPER ADMIN | Soft Deleted records ada | Lima operational resource | Filter Record Status; restore | `deleted_at` null; eligibility dihitung ulang, bukan dipaksa public | P0 | E2E | AUTOMATE | NOT RUN |
| TC-SA-004 | UF-SA-004; AC-UF-SA-004; SRS-FR-050; AUTH-004–005; ERR-007–008 | SUPER ADMIN | Target deletable/restricted tersedia | Leaf, parent ber-child, Dusun | Konfirmasi hard delete tiap target | Leaf applicable terhapus; restricted/Dusun ditolak atomik dan jelas | P0 | E2E | AUTOMATE | NOT RUN |
| TC-SA-005 | UF-SA-005; AC-UF-SA-005; SRS-FR-049; AUTH-006; STATE-001–002 | SUPER ADMIN | Dusun ACTIVE | Parent beserta child/admin | Ubah INACTIVE | Row/child/binding retained; public hidden; Admin tetap login | P0 | E2E | AUTOMATE | NOT RUN |
| TC-SA-006 | UF-SA-006; AC-UF-SA-006; SRS-FR-049; STATE-001–004 | SUPER ADMIN | Dusun INACTIVE dengan active/Soft Deleted child | Mixed lifecycle child | Ubah ACTIVE | Hanya child eligible non-Soft-Deleted kembali public; Soft Deleted tidak auto-restore | P0 | E2E | AUTOMATE | NOT RUN |
| TC-SA-007 | UF-SA-007; AC-UF-SA-007; SRS-FR-051,053–054; VAL-002–004 | SUPER ADMIN | Authenticated | New, duplicate, removed username | Create/assign/remove; reuse username | Akun tepat satu Dusun; duplicate/reuse ditolak; removed retained read-only tanpa restore/reassign | P0 | E2E | AUTOMATE | NOT RUN |
| TC-SA-008 | UF-SA-008; AC-UF-SA-008; SRS-FR-052; SEC-002,005 | SUPER ADMIN | Active Admin Dusun | New password | Reset lalu login dengan credential lama/baru | Hash berubah; baru bekerja, lama gagal; tidak ada self-service recovery | P0 | E2E | AUTOMATE | NOT RUN |
| TC-SA-009 | UF-SA-009; AC-UF-SA-009; SRS-FR-003–004,048; AUTH-009,011 | SUPER ADMIN | Authenticated | Data sumber Homepage | Update source; buka Homepage | Homepage membaca data terbaru secara data-driven tanpa page builder/manual ordering | P1 | E2E | AUTOMATE | NOT RUN |

## 10. Katalog Test Case — Authorization dan Authentication

Setiap invariant diuji melalui request langsung/modified URL serta UI. UI hiding saja tidak cukup.

| Test Case ID | Requirement/Source | Actor | Preconditions | Test Data | Steps | Expected Result | Priority | Test Level | Automation Candidate | Status |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| TC-AUTH-001 | AUTH-INV-001; SRS-AUTH-001; SEC-016 | ADMIN DUSUN | Admin A login; record Dusun B ada | Foreign IDs/routes/payload | GET/POST/PATCH/DELETE dengan ID/payload foreign | Seluruh read/mutation ditolak server-side; tidak ada data/mutation leakage | P0 | FEATURE | AUTOMATE | NOT RUN |
| TC-AUTH-002 | AUTH-INV-002; SRS-AUTH-002; SEC-019 | PUBLIC USER | Public session | Dashboard/write/account routes | Akses route dan submit mutation | Ditolak; public tidak memperoleh account/dashboard/write access | P0 | FEATURE | AUTOMATE | NOT RUN |
| TC-AUTH-003 | AUTH-INV-003; SRS-AUTH-003; DATA-003 | ADMIN DUSUN | Soft Deleted own record | Restore route/request | Panggil restore secara langsung | Ditolak server-side; row tetap Soft Deleted | P0 | FEATURE | AUTOMATE | NOT RUN |
| TC-AUTH-004 | AUTH-INV-004; SRS-AUTH-004; SEC-017 | ADMIN DUSUN; SUPER ADMIN | Dusun ada | Delete Dusun route/request | Coba hard delete dari kedua role | Aksi tidak tersedia dan request ditolak; Dusun/child retained | P0 | FEATURE | AUTOMATE | NOT RUN |
| TC-AUTH-005 | AUTH-INV-005; SRS-AUTH-005; SEC-017 | ADMIN DUSUN; SUPER ADMIN | Non-Dusun target ada | Hard-delete request | Coba kedua role | Hanya Super Admin pada resource/state applicable dapat meneruskan | P0 | FEATURE | AUTOMATE | NOT RUN |
| TC-AUTH-006 | AUTH-INV-006; SRS-AUTH-006; FR-009 | ADMIN DUSUN | Own Dusun INACTIVE | Valid credential | Login; kelola own; akses public | Admin tetap mengelola; public tidak melihat parent/child | P0 | FEATURE | AUTOMATE | NOT RUN |
| TC-AUTH-007 | AUTH-INV-007; SRS-AUTH-007; DATA-002 | PUBLIC USER | Soft Deleted record ada | Semua lima resource | Akses list/detail/map/archive secara langsung | Record tidak pernah public dan response konsisten | P0 | FEATURE | AUTOMATE | NOT RUN |
| TC-AUTH-008 | AUTH-INV-008; SRS-AUTH-008; STATE-007–008 | PUBLIC USER | Expired dan Soft Deleted announcement | Mixed states | Buka active/archive/direct detail | Expired eligible ada di arsip; Soft Deleted tidak; archive bukan delete | P0 | FEATURE | AUTOMATE | NOT RUN |
| TC-AUTH-009 | AUTH-INV-009; SRS-AUTH-009; FR-003 | SUPER ADMIN | Homepage sources tersedia | Unsupported page-builder payload/route | Kelola source; coba page-builder/manual order | Data source berubah; capability unsupported tidak tersedia/ditolak | P1 | FEATURE | AUTOMATE | NOT RUN |
| TC-AUTH-010 | AUTH-INV-010; SRS-AUTH-010; DATA-004,012 | ADMIN DUSUN; SUPER ADMIN | Parent dan child tersedia | Product/media/location child | Mutasi child own/foreign; lifecycle parent | Child mewarisi ownership, visibility, privacy, lifecycle parent; tidak ada permission independen | P0 | INTEGRATION | AUTOMATE | NOT RUN |
| TC-AUTH-011 | AUTH-INV-011; SRS-AUTH-011; FR-022,048–049 | ADMIN DUSUN; SUPER ADMIN | Global resources ada | Desa/category/Dusun status requests | Coba kelola dengan kedua role | Hanya Super Admin dapat mengelola; Admin ditolak server-side | P0 | FEATURE | AUTOMATE | NOT RUN |
| TC-AUTH-012 | AUTH-INV-012; SRS-AUTH-012; FR-046 | ADMIN DUSUN | Valid own resource | Create/update | Simpan lalu akses public | Perubahan langsung eligible sesuai state/privacy tanpa approval workflow | P1 | FEATURE | AUTOMATE | NOT RUN |

Authentication direction tambahan dicakup oleh TC-AD-001, TC-SA-001, TC-SA-008, TC-AUTH-001–012, TC-ERR-001, TC-ERR-008, dan TC-ENV-004: username/password saja; tidak ada public registration, citizen login, email-required login, atau self-service forgot-password; invalid credential menghasilkan penolakan generik; logically removed account ditolak; session dan security behavior dicakup; serta rate limiting diverifikasi.

## 11. Katalog Test Case — Validation 17/17

Application validation dan database constraint diuji terpisah bila keduanya tersedia. Setiap invalid submit harus atomik, mempertahankan field-level context, dan tidak mempersistensi sebagian data.

| Test Case ID | Requirement/Source | Actor | Preconditions | Test Data | Steps | Expected Result | Priority | Test Level | Automation Candidate | Status |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| TC-VAL-001 | SRS-VAL-001; chk_dusuns_status | SUPER ADMIN | Dusun ada | ACTIVE, INACTIVE, nilai lain | Submit/status constraint | Dua nilai diterima; nilai lain ditolak atomik | P1 | UNIT / INTEGRATION | AUTOMATE | NOT RUN |
| TC-VAL-002 | SRS-VAL-002; chk_admin_accounts_role | SUPER ADMIN | Admin account persistence/model/schema validation tersedia | ADMIN_DUSUN, SUPER_ADMIN, invalid role value | Exercise model/database validation atau controlled integration fixture; tidak memakai normal Admin Dusun account-creation UI untuk SUPER_ADMIN | Integrity menerima hanya dua schema-valid role dan menolak nilai lain. Test ini tidak memberi UI, route, atau business workflow untuk membuat SUPER_ADMIN; normal product account creation tetap ADMIN_DUSUN dengan role fixed dan tanpa role selector | P0 | UNIT / INTEGRATION | AUTOMATE | NOT RUN |
| TC-VAL-003 | SRS-VAL-003; chk_admin_accounts_role_scope | SUPER ADMIN | Admin account persistence/model/schema validation tersedia | ADMIN_DUSUN dengan dusun_id null/value; existing SUPER_ADMIN dengan dusun_id null/value | Exercise controlled model/database fixture untuk seluruh kombinasi; normal Admin Dusun account-management workflow tetap role ADMIN_DUSUN dan mewajibkan assignment Dusun | ADMIN_DUSUN wajib dusun_id non-null; existing SUPER_ADMIN wajib dusun_id null. Ini persistence integrity, bukan capability UI/business untuk membuat Super Admin; tidak ada create/recovery flow baru dan OPEN-010 tetap unchanged | P0 | UNIT / INTEGRATION | AUTOMATE | NOT RUN |
| TC-VAL-004 | SRS-VAL-004; chk_admin_accounts_removed_role | SUPER ADMIN | Account ada | removed_at pada dua role | Submit/constraint | removed_at hanya non-null untuk Admin Dusun | P0 | INTEGRATION | AUTOMATE | NOT RUN |
| TC-VAL-005 | SRS-VAL-005; chk_kontak_pelayanans_coordinate_pair | ADMIN DUSUN | Own contact form | both-null, both-value, half-pair | Submit | Pair/null diterima; half-pair ditolak | P1 | UNIT / INTEGRATION | AUTOMATE | NOT RUN |
| TC-VAL-006 | SRS-VAL-006; kontak latitude | ADMIN DUSUN | Own contact form | null, -90,90,out-of-range | Submit | Null/bounds diterima; di luar rentang ditolak | P1 | UNIT / INTEGRATION | AUTOMATE | NOT RUN |
| TC-VAL-007 | SRS-VAL-007; kontak longitude | ADMIN DUSUN | Own contact form | null, -180,180,out-of-range | Submit | Null/bounds diterima; di luar rentang ditolak | P1 | UNIT / INTEGRATION | AUTOMATE | NOT RUN |
| TC-VAL-008 | SRS-VAL-008; chk_umkms_coordinate_pair | ADMIN DUSUN | Own UMKM form | both-null, both-value, half-pair | Submit | Pair/null diterima; half-pair ditolak | P1 | UNIT / INTEGRATION | AUTOMATE | NOT RUN |
| TC-VAL-009 | SRS-VAL-009; UMKM latitude | ADMIN DUSUN | Own UMKM form | null, -90,90,out-of-range | Submit | Null/bounds diterima; di luar rentang ditolak | P1 | UNIT / INTEGRATION | AUTOMATE | NOT RUN |
| TC-VAL-010 | SRS-VAL-010; UMKM longitude | ADMIN DUSUN | Own UMKM form | null, -180,180,out-of-range | Submit | Null/bounds diterima; di luar rentang ditolak | P1 | UNIT / INTEGRATION | AUTOMATE | NOT RUN |
| TC-VAL-011 | SRS-VAL-011; fasilitas latitude | ADMIN DUSUN | Own fasilitas form | -90,90,null,out-of-range | Submit | Bounds diterima; null/out-of-range ditolak | P1 | UNIT / INTEGRATION | AUTOMATE | NOT RUN |
| TC-VAL-012 | SRS-VAL-012; fasilitas longitude | ADMIN DUSUN | Own fasilitas form | -180,180,null,out-of-range | Submit | Bounds diterima; null/out-of-range ditolak | P1 | UNIT / INTEGRATION | AUTOMATE | NOT RUN |
| TC-VAL-013 | SRS-VAL-013; agenda scope | ADMIN DUSUN; SUPER ADMIN | Agenda form | DESA/DUSUN × dusun_id | Submit all combinations | DESA=null dan DUSUN=non-null saja valid; Admin scope implicit OWN_DUSUN | P0 | UNIT / INTEGRATION | AUTOMATE | NOT RUN |
| TC-VAL-014 | SRS-VAL-014; agenda dates | ADMIN DUSUN | Agenda form | end null/equal/after/before start | Submit | Null/equal/after diterima; before ditolak | P1 | UNIT / INTEGRATION | AUTOMATE | NOT RUN |
| TC-VAL-015 | SRS-VAL-015; agenda override | ADMIN DUSUN | Agenda form | null, tiga status, nilai lain | Submit | Hanya null/tiga status diterima | P1 | UNIT / INTEGRATION | AUTOMATE | NOT RUN |
| TC-VAL-016 | SRS-VAL-016; agenda media role | ADMIN DUSUN | Agenda media form | POSTER_AWAL, DOKUMENTASI, nilai lain | Submit | Hanya dua role media diterima | P1 | UNIT / INTEGRATION | AUTOMATE | NOT RUN |
| TC-VAL-017 | SRS-VAL-017; pengumuman scope | ADMIN DUSUN; SUPER ADMIN | Pengumuman form | DESA/DUSUN × dusun_id | Submit all combinations | DESA=null dan DUSUN=non-null saja valid; Admin scope implicit OWN_DUSUN | P0 | UNIT / INTEGRATION | AUTOMATE | NOT RUN |

Validation application-boundary tambahan: username required/global-unique dan normalization compatible dengan collation; password required/hashed; semua field required/optional menurut schema; non-empty required string; conditional scope/Dusun satu context Desa; media path storage-relative; category/parent reference valid; coordinate precision/range/pair; date/expiry; dan tidak ada business uniqueness selain dua constraint frozen. Exact nomor/kontak format yang belum ditetapkan tidak diasumsikan.

## 12. Katalog Test Case — Data Integrity dan Relationship

| Test Case ID | Requirement/Source | Actor | Preconditions | Test Data | Steps | Expected Result | Priority | Test Level | Automation Candidate | Status |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| TC-DATA-001 | ERD-DIR-001–003; SRS-DATA-005 | SUPER ADMIN | Seed/migration tersedia | 1 Desa, 6 Dusun | Verifikasi bootstrap dan FK; coba orphan/add-Dusun flow | Satu context Desa; enam awal; tiap Dusun tepat satu Desa; no Add Dusun capability | P1 | INTEGRATION | AUTOMATE | NOT RUN |
| TC-DATA-002 | ERD-DIR-004–006; STATE-001–002 | SUPER ADMIN | Dusun beserta child ada | ACTIVE/INACTIVE/delete | Transisi status; coba delete | Hanya dua status; child/admin retained; hard delete Dusun ditolak | P0 | INTEGRATION | AUTOMATE | NOT RUN |
| TC-DATA-003 | ERD-DIR-007–011,035; PDS-CR-002 | SUPER ADMIN | Account fixtures | Public/admin/super/removed; duplicate username; remember token | Persist/login/remove/reuse/remember | No public account; role-scope benar; multi-admin allowed; global username reserved; remember token nullable attribute terisi saat remember me; removed retained/tidak login | P0 | INTEGRATION | AUTOMATE | NOT RUN |
| TC-DATA-004 | ERD-DIR-012–015 | ADMIN DUSUN | Kontak form/store | Owner, WhatsApp, consent absence, coordinate variants | Create invalid/valid; inspect schema/public projection | Owner/WhatsApp/pair integrity; tidak ada consent field; privacy precondition tidak diinferensikan | P0 | INTEGRATION | AUTOMATE | NOT RUN |
| TC-DATA-005 | ERD-DIR-016–020 | ADMIN DUSUN | UMKM/product fixtures | Pair/null; multi-product; photo; commerce payload | Persist/query/delete parent | Directory eligibility benar; max one main photo; products inherit; cascade; commerce columns/behavior absent | P1 | INTEGRATION | AUTOMATE | NOT RUN |
| TC-DATA-006 | ERD-DIR-021–023 | ADMIN DUSUN; SUPER ADMIN | Category/facility fixtures | Used category; pair coordinates; WhatsApp null/value | Persist; delete category; render action | Category required; pair required; used category RESTRICT; WhatsApp conditional | P0 | INTEGRATION | AUTOMATE | NOT RUN |
| TC-DATA-007 | ERD-DIR-024,026–028 | ADMIN DUSUN; SUPER ADMIN | Agenda/media fixtures | DESA/DUSUN; date/override/media variants | Persist/calculate; delete parent | Scope/date/status/media integrity; effective end correct; Agenda→media CASCADE only | P1 | INTEGRATION | AUTOMATE | NOT RUN |
| TC-DATA-008 | ERD-DIR-025,029–030 | ADMIN DUSUN; SUPER ADMIN | Pengumuman fixtures | Scope; active/expired/Soft Deleted | Persist; advance business date; query archive | Scope tepat; expiry derives archive; Soft Deleted excluded; no archive row/enum/action | P0 | INTEGRATION | AUTOMATE | NOT RUN |
| TC-DATA-009 | ERD-DIR-031–032; SRS-DATA-001–004 | ADMIN DUSUN; SUPER ADMIN | Lima resource ada | active/deleted/restored/hard-delete | Jalankan lifecycle per role | Tepat lima deleted_at; Admin soft-delete only; Super restore/hard-delete applicable; child lifecycle inherited | P0 | INTEGRATION | AUTOMATE | NOT RUN |
| TC-DATA-010 | ERD-DIR-033; SRS-FR-035–040 | PUBLIC USER; SUPER ADMIN | Eligible/ineligible location data | Parent status, category, coordinate cases | Build/query projections and filters | Marker derived only; taxonomy UMKM/PELAYANAN/dynamic facility; SEMUA query-only; no generic tables | P1 | INTEGRATION | AUTOMATE | NOT RUN |
| TC-DATA-011 | ERD-DIR-034; SRS-DATA-009–012 | ADMIN DUSUN; SUPER ADMIN | Media storage stub | Relative/absolute/dangling path; parent states; failed operation; absent/broken optional media | Upload/persist/query/delete; inject media-operation failure | Reference storage-relative; no BLOB; media follows parent authorization/visibility/lifecycle; invalid/failed operation leaves no persisted invalid public media reference and parent remains consistent; absent/broken optional media degrades safely through applicable placeholder/fallback. Filesystem cleanup remains application/operations responsibility where required, while exact cleanup algorithm, filename strategy, library, dan physical path remain implementation details | P1 | INTEGRATION | AUTOMATE | NOT RUN |
| TC-DATA-012 | SRS-DATA-005–008; Physical Schema §§4,22–24; PDS-CR-001 | SUPER ADMIN | Full schema tersedia setelah Laravel migration initialization | 11 domain/application tables; `migrations` metadata; 13 FKs; two business unique; cascade/restrict; prohibited framework-table names | Inspect schema; exercise every domain FK/delete/unique; verify `migrations` classification; negatively inspect `users`, password-reset, sessions, cache, jobs, dan framework tables lain | 11/11 domain tables dan 13/13 domain relationships ada; exactly two business UNIQUE constraints; RESTRICT default; CASCADE hanya UMKM→Produk dan Agenda→Media; Laravel `migrations` metadata table MAY additionally exist sehingga expected SQL inventory 12; tidak ada unauthorized framework tables | P0 | INTEGRATION | AUTOMATE | NOT RUN |

Coverage relationship: `desas`→`dusuns`, `dusuns`→`admin_accounts`, `kontak_pelayanans`, `umkms`, `fasilitas`, `agenda_kegiatans`, `pengumumans`; `umkms`→`produk_umkms`; `desas`→`kategori_fasilitas`, `agenda_kegiatans`, `pengumumans`; `kategori_fasilitas`→`fasilitas`; `agenda_kegiatans`→`agenda_medias`. Target: 11/11 domain/application tables dan 13/13 domain relationships. Tabel `migrations` tidak termasuk domain coverage dan tidak memiliki domain relationship.

## 13. Katalog Test Case — Lifecycle, Error, dan Security

| Test Case ID | Requirement/Source | Actor | Preconditions | Test Data | Steps | Expected Result | Priority | Test Level | Automation Candidate | Status |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| TC-LIFE-001 | SRS-STATE-009–010; FR-027–028 | PUBLIC USER | Business date fixed | start/end/null end; override/null | Calculate around boundaries | Effective end uses end or start; three calculated states; override wins; calculated status not persisted | P1 | UNIT | AUTOMATE | NOT RUN |
| TC-LIFE-002 | SRS-STATE-007–008; FR-032–034 | PUBLIC USER | Business date fixed | expiry today/past/future | Query active/archive across boundary | Asia/Jakarta rule correct; no archive persistence/action; archive remains public when eligible | P1 | UNIT | AUTOMATE | NOT RUN |
| TC-LIFE-003 | SRS-STATE-003–004 | ADMIN DUSUN; SUPER ADMIN | Operational record active | deleted_at null/value | Soft delete/restore by role | Record lifecycle correct; only Super restores | P0 | FEATURE | AUTOMATE | NOT RUN |
| TC-LIFE-004 | SRS-STATE-005–006; FR-053–054 | SUPER ADMIN | Active Admin account | removed account | Remove; login; inspect; attempt every forbidden action | Login rejected; row/username retained read-only; no restore/reactivate/reset/reassign/remove again/reuse/merge | P0 | FEATURE | AUTOMATE | NOT RUN |
| TC-LIFE-005 | SRS-STATE-001–004 | SUPER ADMIN | Dusun with mixed children | ACTIVE/INACTIVE; active/deleted child | Deactivate/reactivate | Parent visibility toggles; data retained; deleted child never auto-restored | P0 | FEATURE | AUTOMATE | NOT RUN |
| TC-LIFE-006 | SRS-FR-025–034 | ADMIN DUSUN; SUPER ADMIN | Agenda/Pengumuman screens | State-axis combinations | Apply status/record filters | Agenda status vs record status and archive vs record status remain separate and correctly labeled | P1 | FEATURE | AUTOMATE | NOT RUN |
| TC-ERR-001 | SRS-ERR-001; SEC-001,004–006 | ADMIN DUSUN | Login endpoint | Existing/nonexisting username; wrong password; burst | Submit attempts | Generic response; no enumeration; rate limit; no unsupported lockout assumption | P0 | FEATURE | AUTOMATE | NOT RUN |
| TC-ERR-002 | SRS-ERR-002,008 | ADMIN DUSUN; SUPER ADMIN | Protected targets | Unauthorized role/scope/state | Send direct risky requests | Fail closed; no read/mutation; response non-sensitive | P0 | FEATURE | AUTOMATE | NOT RUN |
| TC-ERR-003 | SRS-ERR-003 | ADMIN DUSUN | Form/API available | Multiple invalid fields | Submit | No partial persistence; messages associated with fields; valid previous context retained | P1 | FEATURE | AUTOMATE | NOT RUN |
| TC-ERR-004 | SRS-ERR-004 | PUBLIC USER | Missing/deleted/inactive-parent targets | Direct identifiers | Request public paths | Consistent non-public response; no lifecycle/identity leakage | P0 | FEATURE | AUTOMATE | NOT RUN |
| TC-ERR-005 | SRS-ERR-005; DATA-011–012 | ADMIN DUSUN | Media processing stub | Invalid type/size/process/storage failure | Upload/create/update | Parent integrity retained; no invalid reference/orphan; actionable safe feedback | P1 | INTEGRATION | AUTOMATE | NOT RUN |
| TC-ERR-006 | SRS-ERR-006 | PUBLIC USER | Page has directory content | Missing optional handoff; map/provider failure | Load and interact | Unsupported action absent; non-map content/navigation remain usable | P1 | FEATURE | HYBRID | NOT RUN |
| TC-ERR-007 | SRS-ERR-007; DATA-006 | SUPER ADMIN | FK-restricted parent | Used category/parent | Confirm delete | Parent/child retained atomically; message actionable without constraint detail | P0 | INTEGRATION | AUTOMATE | NOT RUN |
| TC-ERR-008 | SRS-SEC-010–019 | ADMIN DUSUN; SUPER ADMIN | Security test environment | CSRF, XSS, SQLi-like, session, secret/debug cases | Exercise controls and inspect output/config | Policies, binding/ORM, escaping, CSRF, HTTPS/session, least privilege, no secret/trace exposure | P0 | FEATURE / QUALIFICATION | HYBRID | NOT RUN |

## 14. Katalog Test Case — UI, Responsive, Accessibility, dan Visual QA

| Test Case ID | Requirement/Source | Actor | Preconditions | Test Data | Steps | Expected Result | Priority | Test Level | Automation Candidate | Status |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| TC-UI-001 | UI/UX §§5–10; UX-SCR-001–003 | PUBLIC USER | Public UI implemented | Mobile/Desktop; empty/populated | Inspect Homepage/Dusun/archive and navigate keyboard/touch | Hierarchy/order/navigation frozen; no header CTA requirement, bottom nav, marketplace, atau fake data | P2 | UI / RESPONSIVE | HYBRID | NOT RUN |
| TC-UI-002 | UI/UX §§11–17; UX-SCR-004–007 | PUBLIC USER | Public details implemented | Four detail types; optional data | Inspect cards/details/actions/back context | Exactly four details; Contact has no fifth detail; prerequisite actions only; lifecycle wording correct | P1 | UI / RESPONSIVE | HYBRID | NOT RUN |
| TC-UI-003 | UI/UX §19; UX-SCR-008–009 | PUBLIC USER | Map UI implemented | Mobile/Desktop; filters/provider error | Operate Peta Desa/Dusun | Desa: Dusun+category; Dusun: category only/no Dusun selector; usable map/popup/fallback | P1 | UI / RESPONSIVE | HYBRID | NOT RUN |
| TC-UI-004 | Wireframe UX-SCR-010–017 | ADMIN DUSUN | Dashboard implemented | Mobile/Desktop; all modules | Navigate/login/list/form/dialog/state | Fixed OWN_DUSUN context; six management areas; mobile stacked rows; no forbidden controls | P1 | UI / RESPONSIVE | HYBRID | NOT RUN |
| TC-UI-005 | Wireframe UX-SCR-018–028 | SUPER ADMIN | Dashboard implemented | Mobile/Desktop; all modules | Navigate/list/form/dialog/map/account state | GLOBAL context; ten management areas; Data/Peta map-centric; removed account read-only | P1 | UI / RESPONSIVE | HYBRID | NOT RUN |
| TC-UI-006 | UI/UX states; Visual §§35–48 | ALL | State UI implemented | empty/loading/error/success/confirm/read-only | Trigger every state | Context retained; duplicate action prevented; no technical data; destructive hierarchy explicit; labels not color-only | P1 | UI / RESPONSIVE | HYBRID | NOT RUN |
| TC-UI-007 | Visual §49; SRS-NFR-013–014 | ALL | Responsive implementation | Representative narrow/wide viewport | Exercise navigation, table/card, forms, maps, dialogs | Public/dashboard usable; no horizontal-loss of critical action; no exact breakpoint requirement invented | P2 | UI / RESPONSIVE | MANUAL | NOT RUN |
| TC-UI-008 | Visual §56; accessibility direction | ALL | Rendered implementation | Keyboard, screen-reader semantics, touch | Traverse page/form/dialog/map alternatives | Visible focus, labels, heading hierarchy, field errors, status text, distinguishable actions, dialog focus, usable touch, alt/placeholder semantics | P1 | UI / ACCESSIBILITY | HYBRID | NOT RUN |

Accessibility result nanti merupakan verification atas rendered implementation, bukan klaim sertifikasi atau WCAG conformance.

| Test Case ID | Requirement/Source | Actor | Preconditions | Test Data | Steps | Expected Result | Priority | Test Level | Automation Candidate | Status |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| TC-VIS-001 | Visual §§3–5,58 | ALL | Rendered UI | All representative components/states | Sample/measure canonical colors and contrast context | Enam canonical colors saja; actual pairing readable; state bukan color-only; tidak ada seventh canonical color tanpa CR | P2 | VISUAL QA | HYBRID | NOT RUN |
| TC-VIS-002 | Visual §§6–8,58 | ALL | Fonts loaded/fallback qualified | Public/admin headings/body/actions | Inspect computed typography/hierarchy | Lora heading/subheading dan Inter body/UI/action dipakai sesuai direction; hierarchy tetap jelas | P2 | VISUAL QA | MANUAL | NOT RUN |
| TC-VIS-003 | Visual §§9–12,58 | ALL | UI rendered | Cards/forms/dialogs/tables | Inspect spacing/radius/border/shadow | 8px rhythm; radius 8/12/16/24; border 1/2/3; subtle shadow direction konsisten | P2 | VISUAL QA | MANUAL | NOT RUN |
| TC-VIS-004 | Visual §§20–21,35–48 | ALL | Interactive states implemented | Button/input/default-hover-focus-active-disabled-loading/error | Exercise all states | State visible, readable, stable, explicit; destructive action tidak setara Save; no unsupported action | P2 | VISUAL QA | HYBRID | NOT RUN |
| TC-VIS-005 | HF Public Core UX-SCR-001–009 | PUBLIC USER | Frozen specs pass; implementation + 9 PNG tersedia | Desktop kiri/Mobile kanan references | Compare composition/component/state after normative check | Implementation konsisten dengan frozen specs dan 9 visual references tanpa menjadikan gambar authority lebih tinggi | P2 | VISUAL QA | MANUAL | NOT RUN |
| TC-VIS-006 | Wireframe + Visual UX-SCR-010–028 | ADMIN DUSUN; SUPER ADMIN | Implementation tersedia | 19 uncovered screens | Compare each screen to normative Wireframe+Visual | Semua screen dapat diuji tanpa image mockup tambahan; tidak menginferensikan behavior dari Public Core | P2 | VISUAL QA | MANUAL | NOT RUN |

## 15. High-Fidelity Reference Coverage

High-Fidelity images: **9/28 screens**.

Covered: `UX-SCR-001–009` — Homepage, Halaman Dusun, Arsip Pengumuman, Detail UMKM, Detail Fasilitas/Lokasi, Detail Agenda/Kegiatan, Detail Pengumuman, Peta Desa, dan Peta Dusun.

Not covered by image mockup: `UX-SCR-010–028`.

Scope 9/28 ini disengaja dan bukan missing documentation. Untuk 19 screen tanpa image mockup, Wireframe Specification v1.0 + Visual Design Specification v1.0 adalah sumber visual normatif yang cukup. Tidak dibuat High-Fidelity Change Request hanya karena Batch 2/3 tidak ada. Jika image bertentangan dengan sumber FROZEN, sumber FROZEN menang.

## 16. Katalog Test Case — External Integration dan Environment

| Test Case ID | Requirement/Source | Actor | Preconditions | Test Data | Steps | Expected Result | Priority | Test Level | Automation Candidate | Status |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| TC-EXT-001 | SRS-EXT-004; OPEN-002 | PUBLIC USER | WhatsApp client/stub | Stored numbers; applicable/inapplicable CTA | Activate handoff | Stored target used; CTA conditional; exact template treated as config/content dependency while OPEN-002 unresolved | P1 | FEATURE / E2E | HYBRID | NOT RUN |
| TC-EXT-002 | SRS-EXT-003 | PUBLIC USER | Browser external navigation enabled | Valid/invalid/missing coordinates | Activate directions | Google Maps opens only with valid coordinates; no internal routing | P1 | FEATURE / E2E | HYBRID | NOT RUN |
| TC-EXT-003 | SRS-EXT-001–002 | PUBLIC USER | Leaflet + qualified provider/stub | Markers/filters | Load maps and interact | Leaflet loads, markers/filter/popup work, attribution/provider qualification honored | P1 | UI / E2E | HYBRID | NOT RUN |
| TC-EXT-004 | SRS-ERR-006; EXT-002 | PUBLIC USER | Provider failure injectable | Timeout/offline/quota/error | Fail tile/provider | Map fallback tampil tanpa crash; directory/page/navigation tetap berfungsi; Leaflet tidak disamakan dengan provider | P0 | FEATURE / E2E | HYBRID | NOT RUN |
| TC-EXT-005 | SRS-EXT-005; DATA-009–012 | ADMIN DUSUN | Storage/image pipeline stub | Valid/invalid images; storage/process failure | Upload/replace/remove | Validation/resize/compression/format direction; safe fallback; relative path; parent remains consistent on failure | P1 | INTEGRATION | HYBRID | NOT RUN |
| TC-EXT-006 | SRS-EXT-006; non-goals | ALL | Integrated app | Unsupported internal messaging/routing/API paths | Inspect routes/UI and submit requests | External interaction remains browser handoff/map/media dependency; unsupported internal systems absent | P1 | FEATURE | AUTOMATE | NOT RUN |

Pre-production dependencies are qualifications, not product ambiguities:

- hosting provider/package and exact server configuration;
- production tile provider, policy, attribution, quota, traffic, Terms, dan compatibility;
- exact image-processing library, filesystem path/disk, session storage, deployment mechanism;
- domain, billing, recovery, ownership, retention, backup product, storage/bandwidth limits;
- Super Admin recovery procedure (`OPEN-010`), WhatsApp template (`OPEN-002`), dan handover/operational owners yang masih OPEN.

| Test Case ID | Requirement/Source | Actor | Preconditions | Test Data | Steps | Expected Result | Priority | Test Level | Automation Candidate | Status |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| TC-ENV-001 | SRS-NFR-011; RND-DEC-001–002 | SUPER ADMIN | Candidate hosting | PHP 8.3+; Laravel 13; required extensions | Deploy diagnostic/build | Runtime/framework/extensions compatible; relevant build deploys successfully | P0 | QUALIFICATION | HYBRID | NOT RUN |
| TC-ENV-002 | SRS-NFR-012,017; SRS-DATA-005–008 | SUPER ADMIN | Candidate MariaDB/InnoDB | Provider version/collation/schema | Migrate; exercise 17 checks, FK, unique | Enforcement/collation compatible; no semantic weakening | P0 | QUALIFICATION | AUTOMATE | NOT RUN |
| TC-ENV-003 | SRS-NFR-015; RND-DEC-008 | SUPER ADMIN | Candidate cPanel hosting | Document root; Composer/deploy; cron if used | Deploy without root/daemon assumption | Safe document root, dependency/deploy access, scheduler option, and runtime routing work | P0 | QUALIFICATION | HYBRID | NOT RUN |
| TC-ENV-004 | SRS-SEC-010,013–014,019 | SUPER ADMIN | Production-like config | HTTPS, debug, secret, DB user | Inspect/penetration sanity | HTTPS/session/CSRF work; debug off; secrets absent from source/output; DB/runtime least privilege | P0 | QUALIFICATION | HYBRID | NOT RUN |
| TC-ENV-005 | SRS-EXT-005; NFR-005,016 | SUPER ADMIN | Candidate filesystem | Upload volume; permissions; portable paths | Upload/read/replace/delete and redeploy | Media durable/portable; no absolute local dependency; limits recorded; filesystem cleanup after eligible successful destructive operations remains application responsibility and is handled consistently without prescribing a cleanup algorithm | P1 | QUALIFICATION | HYBRID | NOT RUN |
| TC-ENV-006 | SRS-OPS-001–002 | SUPER ADMIN | Backup/runbook available | DB + media + config template | Backup then restore to separate environment | Restored application/data/media verifiably usable; secrets excluded; runbook sufficient | P0 | QUALIFICATION | MANUAL | NOT RUN |
| TC-ENV-007 | SRS-EXT-002; RND-OQ-003 | PUBLIC USER | Candidate tile provider | Expected traffic/quota/attribution/failure | Qualification run | Policy, Terms, quota, attribution, traffic, fallback, dan browser compatibility recorded acceptable before production | P1 | QUALIFICATION | MANUAL | NOT RUN |
| TC-ENV-008 | SRS-NFR-001–005,013–014; OPS-003–006 | ALL | Production-like build/data | Mobile connection; empty data; media; handover assets | Run lightweight/performance/operability observations | Core server-rendered content usable; progressive JS; no invented SLA; operational assets/training/ownership gaps recorded | P1 | QUALIFICATION | HYBRID | NOT RUN |

## 17. NFR Coverage

| NFR group | Test coverage | Direction |
| --- | --- | --- |
| SRS-NFR-001–005 Performance/lightweight | TC-ENV-005,008; TC-EXT-003–005 | Mobile-first, server-rendered core, progressive JS, schema indexes, optimized media; tidak ada angka SLA baru. |
| SRS-NFR-006–010 Accessibility/usability | TC-PUB-003,010; TC-UI-001–008 | Bahasa Indonesia, quick navigation, honest empty state, conditional actions, accessibility direction tanpa certification claim. |
| SRS-NFR-011–017 Compatibility | TC-ENV-001–005,008 | Laravel/PHP, MariaDB checks/collation, responsive browser, cPanel, portable filesystem. |
| SRS-OPS-001–006 Operability | TC-ENV-006,008 | Backup/restore, repository/runbook, incomplete-data empty state, training dan organization-controlled handover. |

NFR coverage target: **17/17 SRS-NFR** dan **6/6 SRS-OPS** melalui qualification/direction yang relevan.

## 18. Traceability

### 18.1 User Flow dan Acceptance Criteria — 25/25

| Source | Test Case | Source | Test Case | Source | Test Case |
| --- | --- | --- | --- | --- | --- |
| UF-PUB-001 / AC-UF-PUB-001 | TC-PUB-001 | UF-PUB-010 / AC-UF-PUB-010 | TC-PUB-010 | UF-SA-006 / AC-UF-SA-006 | TC-SA-006 |
| UF-PUB-002 / AC-UF-PUB-002 | TC-PUB-002 | UF-AD-001 / AC-UF-AD-001 | TC-AD-001 | UF-SA-007 / AC-UF-SA-007 | TC-SA-007 |
| UF-PUB-003 / AC-UF-PUB-003 | TC-PUB-003 | UF-AD-002 / AC-UF-AD-002 | TC-AD-002 | UF-SA-008 / AC-UF-SA-008 | TC-SA-008 |
| UF-PUB-004 / AC-UF-PUB-004 | TC-PUB-004 | UF-AD-003 / AC-UF-AD-003 | TC-AD-003 | UF-SA-009 / AC-UF-SA-009 | TC-SA-009 |
| UF-PUB-005 / AC-UF-PUB-005 | TC-PUB-005 | UF-AD-004 / AC-UF-AD-004 | TC-AD-004 |  |  |
| UF-PUB-006 / AC-UF-PUB-006 | TC-PUB-006 | UF-AD-005 / AC-UF-AD-005 | TC-AD-005 |  |  |
| UF-PUB-007 / AC-UF-PUB-007 | TC-PUB-007 | UF-AD-006 / AC-UF-AD-006 | TC-AD-006 |  |  |
| UF-PUB-008 / AC-UF-PUB-008 | TC-PUB-008 | UF-SA-001 / AC-UF-SA-001 | TC-SA-001 |  |  |
| UF-PUB-009 / AC-UF-PUB-009 | TC-PUB-009 | UF-SA-002 / AC-UF-SA-002 | TC-SA-002 |  |  |
|  |  | UF-SA-003 / AC-UF-SA-003 | TC-SA-003 |  |  |
|  |  | UF-SA-004 / AC-UF-SA-004 | TC-SA-004 |  |  |
|  |  | UF-SA-005 / AC-UF-SA-005 | TC-SA-005 |  |  |

Coverage: Public **10/10**, Admin Dusun **6/6**, Super Admin **9/9**, User Flow **25/25**, Acceptance Criteria **25/25**.

### 18.2 Authorization Invariant — 12/12

`AUTH-INV-001`–`AUTH-INV-012` dipetakan satu-ke-satu ke `TC-AUTH-001`–`TC-AUTH-012`. Semua menguji server-side outcome; invariant visibility/lifecycle juga diperkuat TC-AD/SA/DATA/LIFE.

### 18.3 Data Integrity Rule — 35/35

| Rules | Test Case | Rules | Test Case |
| --- | --- | --- | --- |
| ERD-DIR-001–003 | TC-DATA-001 | ERD-DIR-021–023 | TC-DATA-006 |
| ERD-DIR-004–006 | TC-DATA-002 | ERD-DIR-024,026–028 | TC-DATA-007 |
| ERD-DIR-007–011,035 | TC-DATA-003 | ERD-DIR-025,029–030 | TC-DATA-008 |
| ERD-DIR-012–015 | TC-DATA-004 | ERD-DIR-031–032 | TC-DATA-009 |
| ERD-DIR-016–020 | TC-DATA-005 | ERD-DIR-033 | TC-DATA-010 |
| ERD-DIR-034 | TC-DATA-011 | Physical aggregate | TC-DATA-012 |

Coverage: **35/35**. `DATA-007 → deleted_at` tetap bagian ERD-DIR-031 dan tidak membuat rule baru.

### 18.4 SRS Group Coverage

| SRS group | Coverage cases |
| --- | --- |
| SRS-FR-001–040 Public/domain/map | TC-PUB-001–010; TC-DATA-004–011; TC-LIFE-001–002; TC-EXT-001–004 |
| SRS-FR-041–054 Auth/admin/account | TC-AD-001–006; TC-SA-001–009; TC-AUTH-001–012; TC-LIFE-004–005 |
| SRS-AUTH-001–012 | TC-AUTH-001–012 |
| SRS-DATA-001–012 | TC-DATA-001–012; TC-EXT-005; TC-ENV-002,005–006 |
| SRS-VAL-001–017 | TC-VAL-001–017 |
| SRS-EXT-001–006 | TC-EXT-001–006 |
| SRS-ERR-001–008 | TC-ERR-001–008 |
| SRS-SEC-001–019 | TC-AD-001; TC-SA-001,007–008; TC-AUTH-001–012; TC-ERR-001–008; TC-ENV-004 |
| SRS-NFR-001–017 | TC-UI-001–008; TC-VIS-001–006; TC-ENV-001–008 |
| SRS-OPS-001–006 | TC-ENV-006,008 |
| SRS-STATE-001–010 | TC-LIFE-001–006; TC-PUB-008–009; TC-SA-003,005–006 |

## 19. Public Functional Boundary

- Homepage order: Hero/Identity → Dusun choices → Informasi Desa → Pengumuman → Agenda → Peta Desa → Kontak Desa → Footer.
- Halaman Dusun order: banner/name → quick navigation → profil → Kepala Dusun → Kontak → UMKM → Fasilitas → Agenda → Pengumuman → Peta Dusun.
- Exactly four detail types; no Kontak detail.
- UMKM: directory and products only; no price requirement, stock, SKU, cart, checkout, transaction, or marketplace flow.
- Map: Peta Desa has Dusun+category filters; Peta Dusun fixed scope and category-only; Data/Peta Super Admin map-centric without Soft Deleted browser/restore/hard delete/generic map-point CRUD.
- Map taxonomy: `SEMUA` filter-only; `UMKM`; `PELAYANAN`; dynamic Fasilitas category. No universal persisted MapCategory.
- Map/provider failure must not break non-map page content.

## 20. Regression dan Smoke Direction

Regression groups:

| Group | Isi utama |
| --- | --- |
| REG-PUBLIC | TC-PUB, public UI, empty/public eligibility. |
| REG-AUTH | TC-AUTH, login/session, security, privacy. |
| REG-ADMIN | TC-AD, OWN_DUSUN CRUD/forms. |
| REG-SUPERADMIN | TC-SA, GLOBAL management/account/destructive actions. |
| REG-LIFECYCLE | TC-LIFE, TC-DATA-002,008–009. |
| REG-MAP | TC-PUB-004, TC-DATA-010, TC-UI-003, TC-EXT-002–004. |
| REG-VISUAL | TC-UI-001–008, TC-VIS-001–006. |

Future P0/P1 smoke suite (14 tests):

1. Homepage loads.
2. ACTIVE Dusun opens.
3. Admin Dusun login.
4. Super Admin login.
5. Admin `OWN_DUSUN` isolation including modified request.
6. Create/update one representative resource.
7. Admin Soft Delete.
8. Super Admin restore.
9. Agenda lifecycle.
10. Pengumuman archive lifecycle.
11. Peta Desa filters/marker.
12. Peta Dusun fixed scope/no Dusun selector.
13. WhatsApp handoff.
14. Logically removed account cannot login.

## 21. Entry dan Proposed Exit Criteria

Execution entry criteria:

- implementation, migration/schema, seed/fixture, dan configured environment tersedia;
- relevant build/deployment berhasil;
- external dependencies configured atau stubbed;
- test account/data isolated dan business date controllable.

Proposed MVP exit direction — subject to human review:

- semua P0 dieksekusi dan PASS;
- tidak ada open CRITICAL authorization/security/privacy/data-integrity defect;
- core P1 Acceptance Flow PASS;
- 25/25 Acceptance Criteria telah diuji;
- tiga role/scope serta `OWN_DUSUN` isolation terverifikasi server-side;
- regression smoke PASS;
- environment, backup/restore, external dependency, dan production configuration qualified;
- known non-critical issues terdokumentasi dan diterima melalui keputusan manusia.

Tidak ada release SLA numerik baru yang ditetapkan.

## 22. Defect Severity

| Severity | Definisi/contoh |
| --- | --- |
| CRITICAL | Cross-Dusun access; unauthorized destructive operation; removed account login; private/non-public data exposure; corruption/loss kritis. |
| HIGH | Core CRUD/lifecycle gagal; archive salah; public map/core navigation gagal tanpa fallback; major validation/integration failure. |
| MEDIUM | Responsive issue; non-critical feedback/secondary state; material visual inconsistency. |
| LOW | Minor spacing, copy, polish tanpa dampak behavior/access. |

## 23. Testing Open Questions dan Dependency

Blocking Test Design Questions: **0**.

Testing Open Questions: **0 baru**. Testing tidak membuka kembali keputusan FROZEN.

Item seperti exact WhatsApp message (`OPEN-002`), recovery Super Admin (`OPEN-010`), hosting/package, tile provider, image library, storage/media sizing, session/deployment detail, backup/restore ownership dan procedure, domain, billing, recovery, operational/handover ownership, serta actual launch dataset dicatat sebagai **PRE-PRODUCTION DEPENDENCY** atau upstream non-blocking OPEN. Test menggunakan konfigurasi/stub yang eksplisit dan tidak menetapkan pilihannya.

## 24. Change Request Summary

| Category | Count |
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
| SRS Change Request | 0 |
| UI/UX Specification Change Request | 0 |
| Wireframe Specification Change Request | 0 |
| Visual Design Specification Change Request | 0 |

High-Fidelity Batch 1 adalah reference, bukan upstream behavior contract atau CR category.

## 25. Review Checklist

- [x] All frozen sources read.
- [x] SRS v1.1 used as software contract.
- [x] UI/UX v1.0 included.
- [x] Wireframe v1.0 included.
- [x] Visual Design v1.0 included.
- [x] High-Fidelity Public Core boundary documented.
- [x] High-Fidelity coverage correctly stated as 9/28.
- [x] Missing Batch 2/3 not treated as defect.
- [x] Three actors only.
- [x] Public tests covered.
- [x] Admin Dusun tests covered.
- [x] Super Admin tests covered.
- [x] OWN_DUSUN isolation explicitly tested.
- [x] Server-side authorization explicitly tested.
- [x] All 5 operational Soft Delete resources tested.
- [x] Admin account Logical Removal tested separately.
- [x] Dusun lifecycle tested.
- [x] Agenda two lifecycle axes separated.
- [x] Announcement Archive and Soft Delete separated.
- [x] Four public Detail types preserved.
- [x] UMKM no commerce tests included.
- [x] Peta Desa filter behavior tested.
- [x] Peta Dusun no Dusun selector tested.
- [x] Data/Peta remains map-centric.
- [x] Validation coverage mapped 17/17 plus application rules.
- [x] 35/35 Data Integrity Rules mapped; TC-DATA-012 membedakan 11 domain tables dari `migrations` metadata dan menolak framework tables yang tidak diotorisasi.
- [x] 12/12 Authorization Invariants mapped.
- [x] 25/25 User Flows mapped.
- [x] 25/25 Acceptance Criteria mapped.
- [x] Responsive testing included.
- [x] Public Core visual QA included.
- [x] UX-SCR-010–028 visual QA based on Wireframe + Visual Spec.
- [x] Accessibility test direction included without certification claim.
- [x] External integration testing included.
- [x] Environment qualification included.
- [x] Regression direction included.
- [x] Smoke suite defined.
- [x] Test cases remain NOT RUN.
- [x] No tests executed.
- [x] No automation code generated.
- [x] No implementation code generated.
- [x] Human review completed — approved with minor normalization before freeze.
- [x] Undefined TC reference count = 0.
- [x] `TC-SEC-*` dangling reference removed.
- [x] TC-VAL-002 distinguishes schema-valid roles from product account creation.
- [x] TC-VAL-003 distinguishes persistence role-scope invariant from account UI capability.
- [x] No Create Super Admin capability introduced.
- [x] OPEN-010 remains unresolved/non-blocking.
- [x] Media tests do not freeze an unsupported cleanup algorithm.
- [x] Test case count remains 108.
- [x] P0/P1/P2 = 53/47/8.
- [x] AUTOMATE/MANUAL/HYBRID = 72/7/29.
- [x] NOT RUN = 108/108.
- [x] User Flow = 25/25.
- [x] Acceptance Criteria = 25/25.
- [x] Authorization Invariants = 12/12.
- [x] Data Integrity Rules = 35/35.
- [x] High-Fidelity reference = 9/28.
- [x] No Batch 2/3.
- [x] Historical approved/applied Physical Schema CR = 1 (`PDS-CR-001`); seluruh Open Change Request = 0.
- [x] Upstream source integrity PASS.
- [x] Version = 1.1.
- [x] Status = FROZEN FOR MVP.

Checklist result: **63/63 PASS**.

## 26. Final Validation

| Item | Result |
| --- | --- |
| Document / Version / Status | PASS — Testing Specification / 1.1 / FROZEN FOR MVP |
| High-Fidelity boundary | PASS — exactly 9/28; UX-SCR-001–009 reference; UX-SCR-010–028 require no mockup |
| Actor coverage | PASS — Public, Admin Dusun, Super Admin only |
| User Flow / Acceptance Criteria | PASS — 25/25 / 25/25 |
| Authorization Invariant | PASS — 12/12 |
| Data Integrity Rule | PASS — 35/35 |
| Validation | PASS — 17/17 SRS validation plus application-boundary direction |
| NFR / Operations | PASS — 17/17 SRS-NFR / 6/6 SRS-OPS |
| Tables / relationships | PASS — 11/11 domain tables / 13/13 domain relationships; only `migrations` metadata permitted; expected SQL total 12 |
| Operational Soft Delete | PASS — 5/5 |
| Account logical removal | PASS — separate from Soft Delete; no restore/reuse |
| Dusun lifecycle | PASS — no hard delete; Admin login retained while INACTIVE |
| Agenda / Pengumuman lifecycle | PASS — separated from Soft Delete |
| UMKM / Map boundaries | PASS — no commerce; Peta Desa correct; Peta Dusun no selector; Data/Peta map-centric |
| Responsive / Visual / Accessibility | PASS — directions defined; no certification claim |
| External / Environment | PASS — coverage and pre-production qualification defined |
| Regression / Smoke | PASS — 7 groups / 14 smoke tests |
| Execution boundary | PASS — all cases NOT RUN; no test or implementation code |
| Change Requests | PASS — historical approved/applied Physical Schema CR 1 (`PDS-CR-001`); all Open Change Requests 0 |
| Blocking Testing Questions | PASS — 0 |
| Test Case count / priority | PASS — 108 total; P0 53, P1 47, P2 8 |
| Automation candidate | PASS — AUTOMATE 72, MANUAL 7, HYBRID 29 |
| Execution count | PASS — 108/108 NOT RUN |
| Reference integrity | PASS — undefined TC references 0; `TC-SEC-*` references 0; no duplicate or accidental gap |
| TC-VAL-002/003 boundary | PASS — schema/persistence integrity separated from normal Admin Dusun account workflow; no Create Super Admin capability |
| Media wording | PASS — required outcome retained without freezing cleanup algorithm/library/path/filename strategy |
| Testing Open Questions | PASS — 0; OPEN-010 remains unresolved/non-blocking |
| Source integrity | PASS — hanya Physical Database Schema, SRS, dan Testing Specification dinormalisasi; source code, source FROZEN lain, dan High-Fidelity PNG tidak berubah |

Final validation result before execution: **34/34 PASS**.

Testing Specification v1.2 telah menerapkan human decision `PDS-CR-001` dan `PDS-CR-002`, mempertahankan 108 test cases yang terstruktur, dan tetap **FROZEN FOR MVP**. Project tetap siap memasuki tahapan deployment pre-produksi menggunakan rangkaian specification FROZEN yang lengkap.

## 27. Testing Specification Summary

| Metric | Result |
| --- | --- |
| Version / Status | 1.2 / FROZEN FOR MVP |
| Testing scope | Functional, auth, validation, lifecycle, integrity, UI/responsive/visual, external, environment, regression |
| Test levels | 7 conceptual levels |
| Test Case count | 108 |
| Priority | P0 53 / P1 47 / P2 8 |
| Automation candidate | AUTOMATE 72 / MANUAL 7 / HYBRID 29 |
| Public / Admin / Super Admin User Flow | 10/10 / 6/6 / 9/9 |
| User Flow / Acceptance Criteria | 25/25 / 25/25 |
| Authorization Invariant | 12/12 |
| Data Integrity Rule | 35/35 |
| Validation | 17/17 SRS rules + application-boundary direction |
| NFR / Operations | 17/17 SRS-NFR + 6/6 SRS-OPS direction |
| Responsive/UI | Mobile + Desktop; Tablet derived; UX-SCR-001–028 covered |
| High-Fidelity reference | 9/28 — UX-SCR-001–009 only |
| Visual QA | Public Core 9 + Wireframe/Visual 19 uncovered screens |
| External integration | 6 test cases |
| Environment qualification | 8 test cases |
| Regression / Smoke | 7 groups / 14 smoke tests |
| Testing / Blocking Questions | 0 / 0 |
| Pre-production dependencies | Documented; no provider/product choice inferred |
| Change Requests | Historical approved/applied Physical Schema CR 2 (`PDS-CR-001`, `PDS-CR-002`); all Open Change Requests 0 |
| Undefined TC references / `TC-SEC-*` | 0 / 0 |
| Boundary normalization | TC-VAL-002/003 PASS; media wording PASS; TC-DATA-012 framework metadata boundary PASS |
| Checklist / Final validation | 63/63 PASS / 34/34 PASS |
| Source integrity | PASS |
| Human review | COMPLETED — `PDS-CR-001` & `PDS-CR-002` APPROVED / APPLIED |
| Testing Specification freeze | FROZEN FOR MVP |
| Development readiness | READY FOR DEPLOYMENT / OPERATIONS |
