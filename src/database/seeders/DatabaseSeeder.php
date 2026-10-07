<?php

namespace Database\Seeders;

use App\Models\AdminAccount;
use App\Models\AgendaKegiatan;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\Fasilitas;
use App\Models\KategoriFasilitas;
use App\Models\KontakPelayanan;
use App\Models\Pengumuman;
use App\Models\ProdukUmkm;
use App\Models\Umkm;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with development & preview data.
     */
    public function run(): void
    {
        $now = CarbonImmutable::now();

        // 1. Identitas Desa (Singleton)
        $desa = Desa::query()->updateOrCreate(
            ['id' => 1],
            [
                'nama_desa' => 'Desa Bendung',
                'deskripsi_singkat' => 'Portal Informasi Resmi Pemerintah Desa Bendung, Kecamatan Semin, Kabupaten Gunungkidul, D.I. Yogyakarta.',
                'alamat_kantor' => 'Jl. Raya Semin - Bendung KM 2, Semin, Gunungkidul, D.I. Yogyakarta 55854',
                'nomor_kontak' => '081234567890',
                'email' => 'pemdes@bendung.desa.id',
                'nama_kepala_desa' => 'Bapak Kepala Desa Bendung',
                'jam_pelayanan' => 'Senin - Jumat: 08:00 - 15:00 WIB',
                'logo_path' => 'desa/logo-desa-bendung.png',
                'banner_path' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        // 2. Fixed 6 Dusun
        $dusunData = [
            [
                'id' => 1,
                'nama_dusun' => 'Dusun Bendung I',
                'status_dusun' => 'ACTIVE',
                'deskripsi_singkat' => 'Sentra pertanian padi dan kebun buah, pusat kegiatan kemasyarakatan bagian barat.',
                'nama_kepala_dusun' => 'Sutrisno, S.Pd.',
                'jumlah_rt' => 4,
                'jumlah_rw' => 2,
            ],
            [
                'id' => 2,
                'nama_dusun' => 'Dusun Bendung II',
                'status_dusun' => 'ACTIVE',
                'deskripsi_singkat' => 'Wilayah pemukiman produktif dengan kelompok tani mandiri dan industri olahan pangan.',
                'nama_kepala_dusun' => 'Bambang Irawan',
                'jumlah_rt' => 4,
                'jumlah_rw' => 2,
            ],
            [
                'id' => 3,
                'nama_dusun' => 'Dusun Gatak',
                'status_dusun' => 'ACTIVE',
                'deskripsi_singkat' => 'Kawasan perbukitan asri dengan potensi peternakan kambing dan pengrajin anyaman bambu.',
                'nama_kepala_dusun' => 'Supriyadi',
                'jumlah_rt' => 3,
                'jumlah_rw' => 1,
            ],
            [
                'id' => 4,
                'nama_dusun' => 'Dusun Karangsawo',
                'status_dusun' => 'ACTIVE',
                'deskripsi_singkat' => 'Pusat pengembangan UMKM makanan tradisional khas Gunungkidul dan sentra bibit tanaman.',
                'nama_kepala_dusun' => 'Agus Wahyudi',
                'jumlah_rt' => 3,
                'jumlah_rw' => 1,
            ],
            [
                'id' => 5,
                'nama_dusun' => 'Dusun Banyuripan',
                'status_dusun' => 'ACTIVE',
                'deskripsi_singkat' => 'Kawasan sumber mata air desa dengan keasrian alam dan perikanan air tawar.',
                'nama_kepala_dusun' => 'Haryanto',
                'jumlah_rt' => 4,
                'jumlah_rw' => 2,
            ],
            [
                'id' => 6,
                'nama_dusun' => 'Dusun Plosorejo',
                'status_dusun' => 'ACTIVE',
                'deskripsi_singkat' => 'Wilayah timur desa yang dinamis dengan perkebunan palawija dan kerajinan kayu.',
                'nama_kepala_dusun' => 'Danang Kusuma',
                'jumlah_rt' => 3,
                'jumlah_rw' => 1,
            ],
        ];

        foreach ($dusunData as $d) {
            Dusun::query()->updateOrCreate(
                ['id' => $d['id']],
                array_merge($d, [
                    'desa_id' => $desa->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }

        // 3. Akun Pengguna (Super Admin + 6 Admin Dusun)
        // Super Admin (dusun_id = NULL)
        AdminAccount::query()->updateOrCreate(
            ['username' => 'superadmin'],
            [
                'dusun_id' => null,
                'password_hash' => Hash::make('SuperAdmin123!'),
                'role' => AdminAccount::ROLE_SUPER_ADMIN,
                'removed_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        // 6 Admin Dusun
        for ($i = 1; $i <= 6; $i++) {
            AdminAccount::query()->updateOrCreate(
                ['username' => 'admindusun' . $i],
                [
                    'dusun_id' => $i,
                    'password_hash' => Hash::make('AdminDusun123!'),
                    'role' => AdminAccount::ROLE_ADMIN_DUSUN,
                    'removed_at' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        // 4. Kategori Fasilitas
        $kategoriList = [
            'Balai Pertemuan & Pos',
            'Sarana Ibadah',
            'Pendidikan & PAUD',
            'Kesehatan & Posyandu',
            'Olahraga & Lapangan',
        ];

        $kategoriMap = [];
        foreach ($kategoriList as $namaKat) {
            $kat = KategoriFasilitas::query()->updateOrCreate(
                ['desa_id' => $desa->id, 'nama_kategori' => $namaKat],
                [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
            $kategoriMap[$namaKat] = $kat->id;
        }

        // 5. Sample Fasilitas
        $fasilitasData = [
            [
                'dusun_id' => 1,
                'kategori_fasilitas_id' => $kategoriMap['Balai Pertemuan & Pos'],
                'nama' => 'Balai Dusun Bendung I',
                'deskripsi' => 'Gedung pertemuan warga untuk musyawarah dusun dan kegiatan sosial.',
                'alamat' => 'RT 01 / RW 01, Dusun Bendung I',
                'latitude' => -7.834120,
                'longitude' => 110.718450,
                'nomor_whatsapp' => '081234567891',
            ],
            [
                'dusun_id' => 1,
                'kategori_fasilitas_id' => $kategoriMap['Kesehatan & Posyandu'],
                'nama' => 'Posyandu Melati Dusun Bendung I',
                'deskripsi' => 'Pos pelayanan terpadu kesehatan balita dan lansia.',
                'alamat' => 'RT 02 / RW 01, Dusun Bendung I',
                'latitude' => -7.834850,
                'longitude' => 110.719200,
                'nomor_whatsapp' => '081234567892',
            ],
            [
                'dusun_id' => 4,
                'kategori_fasilitas_id' => $kategoriMap['Sarana Ibadah'],
                'nama' => 'Masjid Al-Barokah Karangsawo',
                'deskripsi' => 'Masjid utama kegiatan ibadah dan pembinaan santri TPA.',
                'alamat' => 'RT 01 / RW 01, Dusun Karangsawo',
                'latitude' => -7.832900,
                'longitude' => 110.723100,
                'nomor_whatsapp' => '081234567893',
            ],
            [
                'dusun_id' => 5,
                'kategori_fasilitas_id' => $kategoriMap['Olahraga & Lapangan'],
                'nama' => 'Lapangan Voli Banyuripan',
                'deskripsi' => 'Sarana olahraga pemuda dusun dan turnamen antar-RT.',
                'alamat' => 'RT 03 / RW 02, Dusun Banyuripan',
                'latitude' => -7.836100,
                'longitude' => 110.725400,
                'nomor_whatsapp' => null,
            ],
        ];

        foreach ($fasilitasData as $fas) {
            Fasilitas::query()->updateOrCreate(
                ['nama' => $fas['nama'], 'dusun_id' => $fas['dusun_id']],
                array_merge($fas, [
                    'foto_path' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ])
            );
        }

        // 6. Sample Kontak Pelayanan
        $kontakData = [
            [
                'dusun_id' => 1,
                'nama' => 'Sutrisno, S.Pd.',
                'jabatan' => 'Kepala Dusun Bendung I',
                'nomor_whatsapp' => '081298765431',
                'alamat_pelayanan' => 'Kediaman Kadus Bendung I, RT 01 / RW 01',
                'latitude' => -7.834150,
                'longitude' => 110.718500,
            ],
            [
                'dusun_id' => 2,
                'nama' => 'Bambang Irawan',
                'jabatan' => 'Kepala Dusun Bendung II',
                'nomor_whatsapp' => '081298765432',
                'alamat_pelayanan' => 'Kediaman Kadus Bendung II, RT 02 / RW 01',
                'latitude' => -7.835200,
                'longitude' => 110.720100,
            ],
            [
                'dusun_id' => 4,
                'nama' => 'Agus Wahyudi',
                'jabatan' => 'Kepala Dusun Karangsawo',
                'nomor_whatsapp' => '081298765434',
                'alamat_pelayanan' => 'Kediaman Kadus Karangsawo, RT 01 / RW 01',
                'latitude' => -7.832950,
                'longitude' => 110.723150,
            ],
            [
                'dusun_id' => 1,
                'nama' => 'Ibu Siti Aminah',
                'jabatan' => 'Kader Posyandu & Kesehatan',
                'nomor_whatsapp' => '081298765499',
                'alamat_pelayanan' => 'Poskesdes / RT 02 Dusun Bendung I',
                'latitude' => -7.834900,
                'longitude' => 110.719250,
            ],
        ];

        foreach ($kontakData as $k) {
            KontakPelayanan::query()->updateOrCreate(
                ['dusun_id' => $k['dusun_id'], 'nama' => $k['nama']],
                array_merge($k, [
                    'foto_path' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ])
            );
        }

        // 7. Sample UMKM
        $umkm1 = Umkm::query()->updateOrCreate(
            ['nama_umkm' => 'Keripik Tempe & Pisang Bu Sri', 'dusun_id' => 1],
            [
                'nama_pemilik' => 'Ibu Sri Rahayu',
                'jenis_usaha' => 'Makanan Ringan & Olahan',
                'deskripsi' => 'Produksi aneka keripik renyah gurih khas Gunungkidul tanpa bahan pengawet.',
                'alamat' => 'RT 03 / RW 01, Dusun Bendung I',
                'nomor_whatsapp' => '081388776655',
                'jam_operasional' => '08:00 - 17:00 WIB',
                'foto_utama_path' => null,
                'latitude' => -7.834500,
                'longitude' => 110.719800,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]
        );

        ProdukUmkm::query()->updateOrCreate(
            ['umkm_id' => $umkm1->id, 'nama_produk' => 'Keripik Tempe Gurih 250gr'],
            ['created_at' => $now, 'updated_at' => $now]
        );
        ProdukUmkm::query()->updateOrCreate(
            ['umkm_id' => $umkm1->id, 'nama_produk' => 'Keripik Pisang Manis 200gr'],
            ['created_at' => $now, 'updated_at' => $now]
        );

        $umkm2 = Umkm::query()->updateOrCreate(
            ['nama_umkm' => 'Madu Alami Karangsawo', 'dusun_id' => 4],
            [
                'nama_pemilik' => 'Pak Joko Santoso',
                'jenis_usaha' => 'Hasil Hutan & Pertanian',
                'deskripsi' => 'Madu murni alami hasil budidaya lebah klanceng dan lebah hutan Karangsawo.',
                'alamat' => 'RT 02 / RW 01, Dusun Karangsawo',
                'nomor_whatsapp' => '081399887766',
                'jam_operasional' => 'Setiap Hari: 07:00 - 19:00 WIB',
                'foto_utama_path' => null,
                'latitude' => -7.833100,
                'longitude' => 110.723500,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]
        );

        ProdukUmkm::query()->updateOrCreate(
            ['umkm_id' => $umkm2->id, 'nama_produk' => 'Madu Klanceng Murni 250ml'],
            ['created_at' => $now, 'updated_at' => $now]
        );
        ProdukUmkm::query()->updateOrCreate(
            ['umkm_id' => $umkm2->id, 'nama_produk' => 'Madu Bunga Randu 500ml'],
            ['created_at' => $now, 'updated_at' => $now]
        );

        // 8. Sample Agenda Kegiatan (Desa & Dusun Scope)
        AgendaKegiatan::query()->updateOrCreate(
            ['judul' => 'Musyawarah Perencanaan Pembangunan Desa (Musrenbangdes)', 'desa_id' => $desa->id],
            [
                'dusun_id' => null,
                'scope_level' => 'DESA',
                'deskripsi_singkat' => 'Pembahasan dan penetapan rencana kerja pemerintah desa tahun anggaran berikutnya bersama seluruh tokoh masyarakat.',
                'tanggal_mulai' => $now->addDays(5)->format('Y-m-d'),
                'tanggal_selesai' => $now->addDays(5)->format('Y-m-d'),
                'jam' => '09:00:00',
                'lokasi_text' => 'Balai Desa Bendung',
                'manual_status_override' => 'AKAN_DATANG',
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]
        );

        AgendaKegiatan::query()->updateOrCreate(
            ['judul' => 'Kerja Bakti Bersih Saluran Air Dusun Bendung I', 'desa_id' => $desa->id],
            [
                'dusun_id' => 1,
                'scope_level' => 'DUSUN',
                'deskripsi_singkat' => 'Gotong royong warga membersihkan saluran irigasi menjelang musim tanam.',
                'tanggal_mulai' => $now->addDays(2)->format('Y-m-d'),
                'tanggal_selesai' => $now->addDays(2)->format('Y-m-d'),
                'jam' => '07:00:00',
                'lokasi_text' => 'Sepanjang Saluran Utama RW 01 Dusun Bendung I',
                'manual_status_override' => 'AKAN_DATANG',
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]
        );

        // 9. Sample Pengumuman (Desa & Dusun Scope)
        Pengumuman::query()->updateOrCreate(
            ['judul' => 'Jadwal Pelayanan Administrasi Kependudukan Khusus Bulan Ini', 'desa_id' => $desa->id],
            [
                'dusun_id' => null,
                'scope_level' => 'DESA',
                'isi' => 'Diberitahukan kepada seluruh warga Desa Bendung bahwa pelayanan perekaman KTP-el dan pembaruan Kartu Keluarga akan dilaksanakan keliling setiap hari Rabu.',
                'tanggal_kedaluwarsa' => $now->addDays(30)->format('Y-m-d'),
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]
        );

        Pengumuman::query()->updateOrCreate(
            ['judul' => 'Pemberitahuan Vaksinasi Balita & Imunisasi Posyandu Bendung I', 'desa_id' => $desa->id],
            [
                'dusun_id' => 1,
                'scope_level' => 'DUSUN',
                'isi' => 'Diharapkan para orang tua yang memiliki balita usia 0-5 tahun untuk hadir dalam kegiatan imunisasi rutin di Posyandu Melati hari Sabtu pekan ini.',
                'tanggal_kedaluwarsa' => $now->addDays(14)->format('Y-m-d'),
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]
        );
    }
}
