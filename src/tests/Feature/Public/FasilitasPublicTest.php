<?php

namespace Tests\Feature\Public;

use App\Models\Desa;
use App\Models\Dusun;
use App\Models\Fasilitas;
use App\Models\KategoriFasilitas;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FasilitasPublicTest extends TestCase
{
    use RefreshDatabase;

    private Desa $desa;

    private Dusun $activeDusun;

    private Dusun $inactiveDusun;

    private KategoriFasilitas $kategori;

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

        $this->kategori = KategoriFasilitas::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'nama_kategori' => 'Kesehatan',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);
    }

    /**
     * TC19: Eligible Fasilitas detail renders (HTTP 200).
     */
    public function test_eligible_fasilitas_detail_renders_successfully(): void
    {
        $fasilitas = Fasilitas::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Posyandu Melati',
            'alamat' => 'Jl. Mawar No. 5',
            'latitude' => -7.629800,
            'longitude' => 110.860300,
            'deskripsi' => 'Pelayanan kesehatan ibu dan anak.',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get(route('fasilitas.show', $fasilitas->id));

        $response->assertOk();
        $response->assertSee('Posyandu Melati');
        $response->assertSee('Jl. Mawar No. 5');
        $response->assertSee('Buka Petunjuk Arah');
        $response->assertSee('google.com/maps/dir');
    }

    /**
     * TC20: Dynamic category renders on detail page.
     */
    public function test_dynamic_category_renders_on_detail_page(): void
    {
        $fasilitas = Fasilitas::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Puskesmas Pembantu',
            'alamat' => 'Jl. Sehat No. 1',
            'deskripsi' => 'Layanan rawat jalan.',
            'latitude' => -7.629900,
            'longitude' => 110.860400,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get(route('fasilitas.show', $fasilitas->id));

        $response->assertOk();
        $response->assertSee('Kesehatan');
    }

    /**
     * TC21: Optional WhatsApp conditionally rendered.
     */
    public function test_optional_whatsapp_conditionally_rendered(): void
    {
        $fasilitasWithWa = Fasilitas::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Klinik Bersalin',
            'alamat' => 'Jl. Ibu No. 2',
            'deskripsi' => 'Klinik ibu dan anak.',
            'latitude' => -7.630000,
            'longitude' => 110.860500,
            'nomor_whatsapp' => '081234567899',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $fasilitasWithoutWa = Fasilitas::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Posyandu Anggrek',
            'alamat' => 'Jl. Bunga No. 3',
            'deskripsi' => 'Posyandu rutin.',
            'latitude' => -7.630100,
            'longitude' => 110.860600,
            'nomor_whatsapp' => null,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $responseWithWa = $this->get(route('fasilitas.show', $fasilitasWithWa->id));
        $responseWithWa->assertOk();
        $responseWithWa->assertSee('Hubungi via WhatsApp');

        $responseWithoutWa = $this->get(route('fasilitas.show', $fasilitasWithoutWa->id));
        $responseWithoutWa->assertOk();
        $responseWithoutWa->assertDontSee('Hubungi via WhatsApp');
    }

    /**
     * TC22: Soft-deleted or inactive-parent Fasilitas returns 404.
     */
    public function test_soft_deleted_or_inactive_parent_fasilitas_returns_404(): void
    {
        $softDeleted = Fasilitas::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Fasilitas Terhapus',
            'alamat' => 'Jl. Hapus No. 1',
            'deskripsi' => 'Deskripsi.',
            'latitude' => -7.630000,
            'longitude' => 110.860000,
            'deleted_at' => CarbonImmutable::now(),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $inactiveParent = Fasilitas::query()->forceCreate([
            'dusun_id' => $this->inactiveDusun->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Fasilitas Dusun Inaktif',
            'alamat' => 'Jl. Inaktif No. 2',
            'deskripsi' => 'Deskripsi.',
            'latitude' => -7.630000,
            'longitude' => 110.860000,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->get(route('fasilitas.show', $softDeleted->id))->assertNotFound();
        $this->get(route('fasilitas.show', $inactiveParent->id))->assertNotFound();
    }
}
