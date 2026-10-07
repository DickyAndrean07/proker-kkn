<?php

namespace Tests\Feature\Public;

use App\Models\Desa;
use App\Models\Dusun;
use App\Models\Pengumuman;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengumumanPublicTest extends TestCase
{
    use RefreshDatabase;

    private Desa $desa;

    private Dusun $activeDusun;

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
            'deskripsi_singkat' => 'Dusun Krajan.',
            'nama_kepala_dusun' => 'Bapak Kepala Krajan',
            'jumlah_rt' => 5,
            'jumlah_rw' => 2,
            'status_dusun' => 'ACTIVE',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->inactiveDusun = Dusun::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Inaktif',
            'deskripsi_singkat' => 'Dusun Inaktif.',
            'nama_kepala_dusun' => 'Bapak Kepala Inaktif',
            'jumlah_rt' => 2,
            'jumlah_rw' => 1,
            'status_dusun' => 'INACTIVE',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);
    }

    /**
     * TC27: Active Pengumuman is public.
     */
    public function test_active_pengumuman_is_accessible_to_public(): void
    {
        $pengumuman = Pengumuman::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Jadwal Ronda Malam',
            'isi' => 'Diharapkan seluruh warga hadir tepat waktu.',
            'tanggal_kedaluwarsa' => CarbonImmutable::now('Asia/Jakarta')->addDays(10)->toDateString(),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get(route('pengumuman.show', $pengumuman->id));

        $response->assertOk();
        $response->assertSee('Jadwal Ronda Malam');
        $response->assertSee('Aktif');
        $response->assertSee('Diharapkan seluruh warga hadir tepat waktu.');
    }

    /**
     * TC28: Date boundary under Asia/Jakarta: today=ACTIVE, yesterday=ARCHIVE, tomorrow=ACTIVE.
     */
    public function test_announcement_date_boundary_in_asia_jakarta(): void
    {
        $tz = 'Asia/Jakarta';
        $today = CarbonImmutable::now($tz)->toDateString();
        $yesterday = CarbonImmutable::now($tz)->subDay()->toDateString();
        $tomorrow = CarbonImmutable::now($tz)->addDay()->toDateString();

        // Expiring today -> ACTIVE
        $pToday = Pengumuman::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Pengumuman Berakhir Hari Ini',
            'isi' => 'Masih aktif hari ini.',
            'tanggal_kedaluwarsa' => $today,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        // Expired yesterday -> ARCHIVE
        $pYesterday = Pengumuman::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Pengumuman Kedaluwarsa Kemarin',
            'isi' => 'Sudah masuk arsip.',
            'tanggal_kedaluwarsa' => $yesterday,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        // Expiring tomorrow -> ACTIVE
        $pTomorrow = Pengumuman::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Pengumuman Berakhir Besok',
            'isi' => 'Masih aktif besok.',
            'tanggal_kedaluwarsa' => $tomorrow,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        // Homepage should show today and tomorrow (active), but NOT yesterday (archived)
        $homeResponse = $this->get('/');
        $homeResponse->assertOk();
        $homeResponse->assertSee('Pengumuman Berakhir Hari Ini');
        $homeResponse->assertSee('Pengumuman Berakhir Besok');
        $homeResponse->assertDontSee('Pengumuman Kedaluwarsa Kemarin');

        // Archive page should show yesterday (archived), but NOT today or tomorrow (active)
        $archiveResponse = $this->get(route('pengumuman.arsip'));
        $archiveResponse->assertOk();
        $archiveResponse->assertSee('Pengumuman Kedaluwarsa Kemarin');
        $archiveResponse->assertDontSee('Pengumuman Berakhir Hari Ini');
        $archiveResponse->assertDontSee('Pengumuman Berakhir Besok');
    }

    /**
     * TC29: Arsip detail remains public and readable.
     */
    public function test_archived_pengumuman_detail_remains_publicly_readable(): void
    {
        $yesterday = CarbonImmutable::now('Asia/Jakarta')->subDays(2)->toDateString();

        $archived = Pengumuman::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Pengumuman Lampau',
            'isi' => 'Arsip pengumuman masa lalu.',
            'tanggal_kedaluwarsa' => $yesterday,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get(route('pengumuman.show', $archived->id));

        $response->assertOk();
        $response->assertSee('Pengumuman Lampau');
        $response->assertSee('Arsip');
        $response->assertSee('Arsip pengumuman masa lalu.');
    }

    /**
     * TC30: Soft-deleted Pengumuman absent from active and archive lists and returns 404.
     */
    public function test_soft_deleted_pengumuman_absent_from_active_and_archive_and_returns_404(): void
    {
        $softDeleted = Pengumuman::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Pengumuman Dihapus Total',
            'isi' => 'Tidak boleh tampil.',
            'tanggal_kedaluwarsa' => CarbonImmutable::now()->subDays(5)->toDateString(),
            'deleted_at' => CarbonImmutable::now(),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->get('/')->assertDontSee('Pengumuman Dihapus Total');
        $this->get(route('pengumuman.arsip'))->assertDontSee('Pengumuman Dihapus Total');
        $this->get(route('pengumuman.show', $softDeleted->id))->assertNotFound();
    }

    /**
     * TC31: Inactive-parent Pengumuman not public (404 and absent from listings).
     */
    public function test_inactive_parent_pengumuman_not_public(): void
    {
        $inactiveDusunPengumuman = Pengumuman::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'dusun_id' => $this->inactiveDusun->id,
            'scope_level' => 'DUSUN',
            'judul' => 'Pengumuman Dusun Inaktif',
            'isi' => 'Dusun tidak aktif.',
            'tanggal_kedaluwarsa' => CarbonImmutable::now()->addDays(5)->toDateString(),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->get(route('pengumuman.show', $inactiveDusunPengumuman->id))->assertNotFound();
        $this->get(route('pengumuman.arsip', ['dusun' => $this->inactiveDusun->id]))->assertNotFound();
    }
}
