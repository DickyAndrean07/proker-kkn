<?php

namespace Tests\Feature\Public;

use App\Models\AgendaKegiatan;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\Pengumuman;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    private Desa $desa;

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
    }

    /**
     * TC1: Accessible without login (HTTP 200).
     */
    public function test_homepage_is_accessible_without_authentication(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Desa Bendung');
        $response->assertSee('Portal Informasi');
    }

    /**
     * TC2: Frozen section order present in response.
     */
    public function test_homepage_contains_all_frozen_sections_in_order(): void
    {
        $activeDusun = Dusun::query()->forceCreate([
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

        $response = $this->get('/');

        $response->assertOk();
        $content = $response->getContent();

        // Check for sections in frozen order
        $posHero = strpos($content, 'id="beranda"');
        $posDusun = strpos($content, 'id="dusun"');
        $posInfo = strpos($content, 'id="informasi-desa"');
        $posPengumuman = strpos($content, 'id="pengumuman"');
        $posAgenda = strpos($content, 'id="agenda"');
        $posPeta = strpos($content, 'id="peta-desa"');
        $posKontak = strpos($content, 'id="kontak-desa"');

        $this->assertNotFalse($posHero, 'Hero section must exist');
        $this->assertNotFalse($posDusun, 'Pilihan Dusun section must exist');
        $this->assertNotFalse($posInfo, 'Informasi Desa section must exist');
        $this->assertNotFalse($posPengumuman, 'Pengumuman section must exist');
        $this->assertNotFalse($posAgenda, 'Agenda section must exist');
        $this->assertNotFalse($posPeta, 'Peta Desa section must exist');
        $this->assertNotFalse($posKontak, 'Kontak Desa section must exist');

        $this->assertTrue($posHero < $posDusun);
        $this->assertTrue($posDusun < $posInfo);
        $this->assertTrue($posInfo < $posPengumuman);
        $this->assertTrue($posPengumuman < $posAgenda);
        $this->assertTrue($posAgenda < $posPeta);
        $this->assertTrue($posPeta < $posKontak);
    }

    /**
     * TC3: ACTIVE Dusun displayed in Pilihan Dusun.
     */
    public function test_active_dusun_displayed_in_pilihan_dusun(): void
    {
        $activeDusun = Dusun::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Karanganyar',
            'deskripsi_singkat' => 'Dusun Karanganyar yang asri.',
            'nama_kepala_dusun' => 'Bapak Kepala Karanganyar',
            'jumlah_rt' => 4,
            'jumlah_rw' => 2,
            'status_dusun' => 'ACTIVE',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Dusun Karanganyar');
        $response->assertSee(route('dusun.show', $activeDusun->id));
    }

    /**
     * TC4: INACTIVE Dusun excluded from Pilihan Dusun.
     */
    public function test_inactive_dusun_excluded_from_pilihan_dusun(): void
    {
        $inactiveDusun = Dusun::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Nonaktif',
            'deskripsi_singkat' => 'Dusun Nonaktif.',
            'nama_kepala_dusun' => 'Bapak Kepala Nonaktif',
            'jumlah_rt' => 2,
            'jumlah_rw' => 1,
            'status_dusun' => 'INACTIVE',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('Dusun Nonaktif');
        $response->assertDontSee(route('dusun.show', $inactiveDusun->id));
    }

    /**
     * TC5: Soft-deleted resources excluded from homepage.
     */
    public function test_soft_deleted_resources_excluded_from_homepage(): void
    {
        $activeDusun = Dusun::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Krajan',
            'deskripsi_singkat' => 'Dusun Krajan.',
            'nama_kepala_dusun' => 'Bapak Kepala Krajan',
            'jumlah_rt' => 5,
            'jumlah_rw' => 2,
            'status_dusun' => 'ACTIVE',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $pengumuman = Pengumuman::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Pengumuman Terhapus',
            'isi' => 'Isi rahasia yang sudah dihapus.',
            'tanggal_kedaluwarsa' => CarbonImmutable::now()->addDays(7)->toDateString(),
            'deleted_at' => CarbonImmutable::now(),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $agenda = AgendaKegiatan::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Agenda Terhapus',
            'deskripsi_singkat' => 'Deskripsi agenda terhapus.',
            'lokasi_text' => 'Lokasi terhapus',
            'tanggal_mulai' => CarbonImmutable::now()->addDays(2)->toDateString(),
            'deleted_at' => CarbonImmutable::now(),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('Pengumuman Terhapus');
        $response->assertDontSee('Agenda Terhapus');
    }

    /**
     * TC6: Empty state renders when no data is present.
     */
    public function test_empty_state_renders_when_no_records_exist(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Belum ada Dusun aktif yang terdaftar.');
        $response->assertSee('Belum ada pengumuman aktif.');
        $response->assertSee('Belum ada agenda atau kegiatan.');
    }

    /**
     * TC7: No marketplace or commerce elements in response.
     */
    public function test_no_marketplace_or_commerce_elements_on_homepage(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('Keranjang');
        $response->assertDontSee('Checkout');
        $response->assertDontSee('Beli Sekarang');
        $response->assertDontSee('Rp ');
    }
}
