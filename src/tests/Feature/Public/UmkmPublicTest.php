<?php

namespace Tests\Feature\Public;

use App\Models\Desa;
use App\Models\Dusun;
use App\Models\ProdukUmkm;
use App\Models\Umkm;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UmkmPublicTest extends TestCase
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
     * TC13: Eligible UMKM detail renders (HTTP 200).
     */
    public function test_eligible_umkm_detail_renders_successfully(): void
    {
        $umkm = Umkm::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'nama_umkm' => 'Keripik Singkong Barokah',
            'nama_pemilik' => 'Ibu Barokah',
            'jenis_usaha' => 'Makanan Ringan',
            'alamat' => 'RT 01 RW 02 Krajan',
            'nomor_whatsapp' => '081234567890',
            'deskripsi' => 'Keripik renyah rasa original dan balado.',
            'jam_operasional' => '08.00 - 17.00 WIB',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get(route('umkm.show', $umkm->id));

        $response->assertOk();
        $response->assertSee('Keripik Singkong Barokah');
        $response->assertSee('Ibu Barokah');
        $response->assertSee('Makanan Ringan');
        $response->assertSee('RT 01 RW 02 Krajan');
        $response->assertSee('Hubungi via WhatsApp');
    }

    /**
     * TC14: Soft-deleted UMKM returns 404.
     */
    public function test_soft_deleted_umkm_returns_404(): void
    {
        $umkm = Umkm::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'nama_umkm' => 'UMKM Terhapus',
            'nama_pemilik' => 'Pak Hapus',
            'jenis_usaha' => 'Jasa',
            'deskripsi' => 'Deskripsi terhapus.',
            'alamat' => 'Alamat terhapus',
            'nomor_whatsapp' => '081234567891',
            'jam_operasional' => '08.00 - 17.00 WIB',
            'deleted_at' => CarbonImmutable::now(),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get(route('umkm.show', $umkm->id));

        $response->assertNotFound();
    }

    /**
     * TC15: UMKM under INACTIVE Dusun returns 404.
     */
    public function test_umkm_under_inactive_dusun_returns_404(): void
    {
        $umkm = Umkm::query()->forceCreate([
            'dusun_id' => $this->inactiveDusun->id,
            'nama_umkm' => 'UMKM Dusun Inaktif',
            'nama_pemilik' => 'Pak Inaktif',
            'jenis_usaha' => 'Kerajinan',
            'deskripsi' => 'Deskripsi inaktif.',
            'alamat' => 'Alamat inaktif',
            'nomor_whatsapp' => '081234567892',
            'jam_operasional' => '08.00 - 17.00 WIB',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get(route('umkm.show', $umkm->id));

        $response->assertNotFound();
    }

    /**
     * TC16: Multiple ProdukUmkm displayed on detail page.
     */
    public function test_multiple_produk_umkm_displayed_on_detail_page(): void
    {
        $umkm = Umkm::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'nama_umkm' => 'Batik Tulis Krajan',
            'nama_pemilik' => 'Ibu Sri',
            'jenis_usaha' => 'Fashion',
            'deskripsi' => 'Batik tulis khas Krajan.',
            'alamat' => 'Jl. Batik No. 1',
            'nomor_whatsapp' => '081234567893',
            'jam_operasional' => '08.00 - 17.00 WIB',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        ProdukUmkm::query()->forceCreate([
            'umkm_id' => $umkm->id,
            'nama_produk' => 'Batik Motif Parang',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        ProdukUmkm::query()->forceCreate([
            'umkm_id' => $umkm->id,
            'nama_produk' => 'Batik Motif Kawung',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get(route('umkm.show', $umkm->id));

        $response->assertOk();
        $response->assertSee('Batik Motif Parang');
        $response->assertSee('Batik Motif Kawung');
    }

    /**
     * TC17: No commerce UI (no price/cart/buy/checkout).
     */
    public function test_no_commerce_ui_present_on_umkm_detail(): void
    {
        $umkm = Umkm::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'nama_umkm' => 'Toko Sederhana',
            'nama_pemilik' => 'Pak Sederhana',
            'jenis_usaha' => 'Kelontong',
            'deskripsi' => 'Toko serba ada.',
            'alamat' => 'Jl. Pasar No. 10',
            'nomor_whatsapp' => '081234567894',
            'jam_operasional' => '08.00 - 21.00 WIB',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get(route('umkm.show', $umkm->id));

        $response->assertOk();
        $response->assertDontSee('Keranjang');
        $response->assertDontSee('Beli Sekarang');
        $response->assertDontSee('Checkout');
        $response->assertDontSee('Harga:');
    }

    /**
     * TC18: UMKM without coordinates does not render direction link.
     */
    public function test_umkm_without_coordinates_does_not_render_direction_link(): void
    {
        $umkm = Umkm::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'nama_umkm' => 'UMKM Tanpa Koordinat',
            'nama_pemilik' => 'Pak Tanpa Peta',
            'jenis_usaha' => 'Jasa',
            'deskripsi' => 'Jasa reparasi.',
            'alamat' => 'Jl. Bengkel No. 2',
            'nomor_whatsapp' => '081234567895',
            'jam_operasional' => '09.00 - 17.00 WIB',
            'latitude' => null,
            'longitude' => null,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get(route('umkm.show', $umkm->id));

        $response->assertOk();
        $response->assertDontSee('Lihat Lokasi →');
        $response->assertDontSee('google.com/maps');
    }
}
