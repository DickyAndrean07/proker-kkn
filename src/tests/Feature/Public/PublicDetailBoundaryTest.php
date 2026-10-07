<?php

namespace Tests\Feature\Public;

use App\Models\Desa;
use App\Models\Dusun;
use App\Models\KontakPelayanan;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PublicDetailBoundaryTest extends TestCase
{
    use RefreshDatabase;

    private Desa $desa;

    private Dusun $activeDusun;

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
    }

    /**
     * TC45: Exactly 4 public detail route families exist.
     */
    public function test_exactly_four_public_detail_routes_exist(): void
    {
        $this->assertTrue(Route::has('umkm.show'), 'Route umkm.show must exist');
        $this->assertTrue(Route::has('fasilitas.show'), 'Route fasilitas.show must exist');
        $this->assertTrue(Route::has('agenda.show'), 'Route agenda.show must exist');
        $this->assertTrue(Route::has('pengumuman.show'), 'Route pengumuman.show must exist');

        // Check that no 5th detail route exists
        $this->assertFalse(Route::has('kontak.show'), 'Route kontak.show must NOT exist');
        $this->assertFalse(Route::has('kontak-pelayanan.show'), 'Route kontak-pelayanan.show must NOT exist');
        $this->assertFalse(Route::has('pelayanan.show'), 'Route pelayanan.show must NOT exist');
    }

    /**
     * TC46: No Kontak detail route.
     */
    public function test_no_kontak_detail_route_accessible(): void
    {
        $kontak = KontakPelayanan::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'nama' => 'Petugas Rahasia',
            'jabatan' => 'Pelayanan Umum',
            'nomor_whatsapp' => '081234567891',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->get('/kontak/'.$kontak->id)->assertNotFound();
        $this->get('/kontak-pelayanan/'.$kontak->id)->assertNotFound();
    }

    /**
     * TC47: Direct request to non-public or invalid resource ID fails safely (404).
     */
    public function test_non_existent_resources_fail_safely(): void
    {
        $this->get('/dusun/999999')->assertNotFound();
        $this->get('/umkm/999999')->assertNotFound();
        $this->get('/fasilitas/999999')->assertNotFound();
        $this->get('/agenda/999999')->assertNotFound();
        $this->get('/pengumuman/999999')->assertNotFound();

        // Non-numeric ID fails route constraint (404)
        $this->get('/dusun/abc')->assertNotFound();
        $this->get('/umkm/xyz')->assertNotFound();
        $this->get('/fasilitas/invalid')->assertNotFound();
    }
}
