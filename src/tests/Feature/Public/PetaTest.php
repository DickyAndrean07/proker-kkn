<?php

namespace Tests\Feature\Public;

use App\Models\Desa;
use App\Models\Dusun;
use App\Models\Fasilitas;
use App\Models\KategoriFasilitas;
use App\Models\KontakPelayanan;
use App\Models\Umkm;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetaTest extends TestCase
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
            'nama_kategori' => 'Pendidikan',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);
    }

    /**
     * TC32: Peta Desa section present on Homepage with #peta-desa anchor.
     */
    public function test_peta_desa_section_present_on_homepage(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('id="peta-desa"', false);
        $response->assertSee('Peta Desa');
    }

    /**
     * TC33: Peta Dusun section present on Halaman Dusun with #peta-dusun anchor.
     */
    public function test_peta_dusun_section_present_on_halaman_dusun(): void
    {
        $response = $this->get(route('dusun.show', $this->activeDusun->id));

        $response->assertOk();
        $response->assertSee('id="peta-dusun"', false);
        $response->assertSee('Peta Dusun');
    }

    /**
     * TC34: Peta Desa includes Dusun and Category filter selects.
     */
    public function test_peta_desa_includes_dusun_and_category_filters(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('id="map-desa-filter-dusun"', false);
        $response->assertSee('id="map-desa-filter-cat"', false);
    }

    /**
     * TC35: Peta Dusun includes Category filter select only (no Dusun select).
     */
    public function test_peta_dusun_includes_category_filter_only_without_dusun_selector(): void
    {
        $response = $this->get(route('dusun.show', $this->activeDusun->id));

        $response->assertOk();
        $response->assertSee('id="map-dusun-filter-cat"', false);
        $response->assertDontSee('id="map-dusun-filter-dusun"', false);
    }

    /**
     * TC36: Map markers data correctly passed via JSON to Homepage.
     */
    public function test_map_markers_json_passed_to_homepage(): void
    {
        $fasilitas = Fasilitas::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'SD Negeri 1 Bendung',
            'deskripsi' => 'Sekolah dasar.',
            'alamat' => 'Jl. Pendidikan No. 1',
            'latitude' => -7.629800,
            'longitude' => 110.860300,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('SD Negeri 1 Bendung');
        $response->assertSee('window.MAP_MARKERS', false);
    }

    /**
     * TC37: Map markers data correctly passed via JSON to Halaman Dusun.
     */
    public function test_map_markers_json_passed_to_halaman_dusun(): void
    {
        $fasilitas = Fasilitas::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'SMP Negeri Krajan',
            'deskripsi' => 'Sekolah menengah.',
            'alamat' => 'Jl. Krajan No. 2',
            'latitude' => -7.629900,
            'longitude' => 110.860400,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get(route('dusun.show', $this->activeDusun->id));

        $response->assertOk();
        $response->assertSee('SMP Negeri Krajan');
        $response->assertSee('window.MAP_MARKERS', false);
    }

    /**
     * TC38: Fasilitas with coordinates included in map markers.
     */
    public function test_fasilitas_with_coordinates_included_in_map_markers(): void
    {
        $fasilitas = Fasilitas::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Balai Warga Krajan',
            'deskripsi' => 'Balai pertemuan.',
            'alamat' => 'Jl. Balai No. 3',
            'latitude' => -7.630100,
            'longitude' => 110.860500,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Balai Warga Krajan');
        $response->assertSee('-7.6301');
        $response->assertSee('110.8605');
    }

    /**
     * TC39: UMKM with coordinates included in map markers.
     */
    public function test_umkm_with_coordinates_included_in_map_markers(): void
    {
        $umkm = Umkm::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'nama_umkm' => 'Kopi Krajan Asli',
            'nama_pemilik' => 'Pak Joko',
            'jenis_usaha' => 'Minuman',
            'deskripsi' => 'Kedai kopi.',
            'alamat' => 'Jl. Kopi No. 1',
            'nomor_whatsapp' => '081234567890',
            'jam_operasional' => '08.00 - 22.00 WIB',
            'latitude' => -7.630200,
            'longitude' => 110.860600,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Kopi Krajan Asli');
        $response->assertSee('-7.6302');
    }

    /**
     * TC40: KontakPelayanan with coordinates included and links to #kontak-pelayanan.
     */
    public function test_kontak_pelayanan_marker_links_to_kontak_section(): void
    {
        $kontak = KontakPelayanan::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'nama' => 'Bidan Desa Krajan',
            'jabatan' => 'Kesehatan Ibu & Anak',
            'nomor_whatsapp' => '081234567891',
            'latitude' => -7.630300,
            'longitude' => 110.860700,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Bidan Desa Krajan');
        $this->assertStringContainsString('#kontak-pelayanan', $response->getContent());
        $this->assertStringContainsString('dusun', $response->getContent());
    }

    /**
     * TC41: Items with NULL coordinates excluded from map markers.
     */
    public function test_items_with_null_coordinates_excluded_from_map_markers(): void
    {
        $umkmNoCoords = Umkm::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'nama_umkm' => 'UMKM Online Saja',
            'nama_pemilik' => 'Pak Maya',
            'jenis_usaha' => 'Online',
            'deskripsi' => 'Toko online.',
            'alamat' => 'Jl. Online No. 1',
            'nomor_whatsapp' => '081234567892',
            'jam_operasional' => '24 Jam',
            'latitude' => null,
            'longitude' => null,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get('/');

        $response->assertOk();
        // Since it's only in map markers, and has no coords, it should not appear in MAP_MARKERS json
        $content = $response->getContent();
        $mapDataPos = strpos($content, 'window.MAP_MARKERS');
        $this->assertNotFalse($mapDataPos);
        $mapDataString = substr($content, $mapDataPos, 2000);
        $this->assertStringNotContainsString('UMKM Online Saja', $mapDataString);
    }

    /**
     * TC42: Soft-deleted items excluded from map markers.
     */
    public function test_soft_deleted_items_excluded_from_map_markers(): void
    {
        $softDeletedFasilitas = Fasilitas::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Fasilitas Ditutup Permanen',
            'deskripsi' => 'Deskripsi tutup.',
            'alamat' => 'Jl. Bekas No. 9',
            'latitude' => -7.630400,
            'longitude' => 110.860800,
            'deleted_at' => CarbonImmutable::now(),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('Fasilitas Ditutup Permanen');
    }

    /**
     * TC43: Items from INACTIVE Dusun excluded from map markers.
     */
    public function test_items_from_inactive_dusun_excluded_from_map_markers(): void
    {
        $inactiveFasilitas = Fasilitas::query()->forceCreate([
            'dusun_id' => $this->inactiveDusun->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Fasilitas Dusun Inaktif Map',
            'deskripsi' => 'Deskripsi inaktif.',
            'alamat' => 'Jl. Inaktif No. 8',
            'latitude' => -7.630500,
            'longitude' => 110.860900,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('Fasilitas Dusun Inaktif Map');
    }

    /**
     * TC44: Safe JSON serialization escaping prevents XSS injection.
     */
    public function test_safe_json_serialization_prevents_xss(): void
    {
        $xssFasilitas = Fasilitas::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => '</script><script>alert("XSS")</script>',
            'deskripsi' => 'XSS test.',
            'alamat' => 'Jl. XSS',
            'latitude' => -7.630600,
            'longitude' => 110.861000,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get('/');

        $response->assertOk();
        // Js::from encodes </script> safely (e.g. \u003C\/script\u003E)
        $response->assertDontSee('</script><script>alert("XSS")</script>', false);
    }
}
