<?php

namespace Tests\Feature\Public;

use App\Models\AgendaKegiatan;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\KontakPelayanan;
use App\Models\Pengumuman;
use App\Models\Umkm;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DusunPageTest extends TestCase
{
    use RefreshDatabase;

    private Desa $desa;

    private Dusun $activeDusun;

    private Dusun $otherDusun;

    private Dusun $inactiveDusun;

    protected function setUp(): void
    {
        parent::setUp();

        $this->desa = Desa::query()->forceCreate([
            'nama_desa' => 'Desa Bendung',
            'deskripsi_singkat' => 'Portal informasi resmi Desa Bendung.',
            'alamat_kantor' => 'Jl. Raya Bendung No. 1',
            'nomor_kontak' => '081234567890',
            'email' => 'info@desabendung.id',
            'nama_kepala_desa' => 'Bapak Kepala Desa',
            'jam_pelayanan' => 'Senin - Jumat, 08.00 - 15.00 WIB',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->activeDusun = Dusun::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Krajan',
            'deskripsi_singkat' => 'Dusun Krajan adalah pusat kegiatan warga.',
            'nama_kepala_dusun' => 'Bapak Kepala Krajan',
            'jumlah_rt' => 5,
            'jumlah_rw' => 2,
            'status_dusun' => 'ACTIVE',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->otherDusun = Dusun::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Seberang',
            'deskripsi_singkat' => 'Dusun Seberang yang terpisah.',
            'nama_kepala_dusun' => 'Bapak Kepala Seberang',
            'jumlah_rt' => 3,
            'jumlah_rw' => 1,
            'status_dusun' => 'ACTIVE',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->inactiveDusun = Dusun::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Ditutup',
            'deskripsi_singkat' => 'Dusun yang tidak aktif.',
            'nama_kepala_dusun' => 'Bapak Inaktif',
            'jumlah_rt' => 2,
            'jumlah_rw' => 1,
            'status_dusun' => 'INACTIVE',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);
    }

    /**
     * TC8: ACTIVE Dusun page accessible without login (HTTP 200).
     */
    public function test_active_dusun_page_is_accessible_without_authentication(): void
    {
        $response = $this->get(route('dusun.show', $this->activeDusun->id));

        $response->assertOk();
        $response->assertSee('Dusun Krajan');
        $response->assertSee('Bapak Kepala Krajan');
        $response->assertSee('Dusun Krajan adalah pusat kegiatan warga.');
    }

    /**
     * TC9: INACTIVE Dusun public page returns 404.
     */
    public function test_inactive_dusun_public_page_returns_404(): void
    {
        $response = $this->get(route('dusun.show', $this->inactiveDusun->id));

        $response->assertNotFound();
    }

    /**
     * TC10: Child content properly scoped to requested Dusun.
     */
    public function test_child_content_is_scoped_to_requested_dusun(): void
    {
        KontakPelayanan::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'nama' => 'Petugas Krajan',
            'jabatan' => 'Pelayanan Surat',
            'nomor_whatsapp' => '081234567891',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        Umkm::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'nama_umkm' => 'Warung Krajan',
            'nama_pemilik' => 'Pak Krajan',
            'jenis_usaha' => 'Kuliner',
            'deskripsi' => 'Warung makan lezat.',
            'alamat' => 'Jl. Krajan No. 1',
            'nomor_whatsapp' => '081234567892',
            'jam_operasional' => '08.00 - 20.00 WIB',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get(route('dusun.show', $this->activeDusun->id));

        $response->assertOk();
        $response->assertSee('Petugas Krajan');
        $response->assertSee('Pelayanan Surat');
        $response->assertSee('Warung Krajan');
    }

    /**
     * TC11: Foreign Dusun content does not leak into requested Dusun page.
     */
    public function test_foreign_dusun_content_does_not_leak(): void
    {
        KontakPelayanan::query()->forceCreate([
            'dusun_id' => $this->otherDusun->id,
            'nama' => 'Petugas Seberang Rahasia',
            'jabatan' => 'Pelayanan Seberang',
            'nomor_whatsapp' => '081234567899',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        Umkm::query()->forceCreate([
            'dusun_id' => $this->otherDusun->id,
            'nama_umkm' => 'Toko Seberang Asing',
            'nama_pemilik' => 'Pak Seberang',
            'jenis_usaha' => 'Kelontong',
            'deskripsi' => 'Toko kelontong seberang.',
            'alamat' => 'Jl. Seberang No. 9',
            'nomor_whatsapp' => '081234567898',
            'jam_operasional' => '07.00 - 21.00 WIB',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get(route('dusun.show', $this->activeDusun->id));

        $response->assertOk();
        $response->assertDontSee('Petugas Seberang Rahasia');
        $response->assertDontSee('Toko Seberang Asing');
    }

    /**
     * TC12: Empty sections render appropriate empty states.
     */
    public function test_empty_sections_render_empty_states(): void
    {
        $response = $this->get(route('dusun.show', $this->activeDusun->id));

        $response->assertOk();
        $response->assertSee('Belum ada kontak pelayanan yang terdaftar.');
        $response->assertSee('Belum ada UMKM yang terdaftar.');
        $response->assertSee('Belum ada fasilitas yang terdaftar.');
        $response->assertSee('Belum ada agenda atau kegiatan.');
        $response->assertSee('Belum ada pengumuman aktif.');
        $response->assertDontSee('opt1-badge');
    }

    /**
     * TC13: Real database counts for agenda and pengumuman render notification badges.
     */
    public function test_agenda_and_pengumuman_notification_badges_reflect_real_database_counts(): void
    {
        $now = CarbonImmutable::now();

        // 2 Agendas for active dusun
        AgendaKegiatan::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'dusun_id' => $this->activeDusun->id,
            'scope_level' => 'DUSUN',
            'judul' => 'Rapat Pemuda Dusun Krajan',
            'deskripsi_singkat' => 'Rapat karang taruna dusun.',
            'lokasi_text' => 'Balai Dusun Krajan',
            'jam' => '19:30:00',
            'tanggal_mulai' => $now->addDays(2),
            'tanggal_selesai' => $now->addDays(2),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        AgendaKegiatan::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'dusun_id' => $this->activeDusun->id,
            'scope_level' => 'DUSUN',
            'judul' => 'Kerja Bakti Minggu Pagi',
            'deskripsi_singkat' => 'Kerja bakti pembersihan lingkungan.',
            'lokasi_text' => 'Lingkungan RW 01',
            'jam' => '07:00:00',
            'tanggal_mulai' => $now->addDays(4),
            'tanggal_selesai' => $now->addDays(4),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 1 Active Announcement for active dusun
        Pengumuman::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'dusun_id' => $this->activeDusun->id,
            'scope_level' => 'DUSUN',
            'judul' => 'Pengumuman Kerja Bakti Bersama',
            'isi' => 'Harap hadir tepat waktu.',
            'tanggal_kedaluwarsa' => $now->addDays(7),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $response = $this->get(route('dusun.show', $this->activeDusun->id));

        $response->assertOk();
        // Badge 2 for Agenda
        $response->assertSee('<span class="opt1-badge" aria-label="2 agenda">2</span>', false);
        // Badge 1 for Pengumuman
        $response->assertSee('<span class="opt1-badge" aria-label="1 pengumuman">1</span>', false);
    }
}
