<?php

namespace Tests\Feature\Admin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\ProdukUmkm;
use App\Models\Umkm;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UmkmTest extends TestCase
{
    use RefreshDatabase;

    private Desa $desa;

    private Dusun $dusunA;

    private Dusun $dusunB;

    private AdminAccount $adminA;

    protected function setUp(): void
    {
        parent::setUp();

        $this->desa = Desa::query()->forceCreate([
            'nama_desa' => 'Desa Bendung',
            'deskripsi_singkat' => 'Deskripsi Desa.',
            'alamat_kantor' => 'Jl. Desa No. 1',
            'nomor_kontak' => '081234567890',
            'nama_kepala_desa' => 'Kepala Desa',
            'jam_pelayanan' => '08.00 - 15.00',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->dusunA = Dusun::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Krajan',
            'status_dusun' => 'ACTIVE',
            'deskripsi_singkat' => 'Deskripsi Krajan.',
            'nama_kepala_dusun' => 'Kepala Krajan',
            'jumlah_rt' => 4,
            'jumlah_rw' => 2,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->dusunB = Dusun::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Seberang',
            'status_dusun' => 'ACTIVE',
            'deskripsi_singkat' => 'Deskripsi Seberang.',
            'nama_kepala_dusun' => 'Kepala Seberang',
            'jumlah_rt' => 3,
            'jumlah_rw' => 1,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->adminA = AdminAccount::query()->forceCreate([
            'dusun_id' => $this->dusunA->id,
            'username' => 'admin_krajan',
            'password_hash' => Hash::make('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);
    }

    /**
     * 25. Create own UMKM.
     */
    public function test_admin_can_create_own_umkm(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/umkm', [
            'nama_umkm' => 'Keripik Singkong Barokah',
            'nama_pemilik' => 'Ibu Siti',
            'jenis_usaha' => 'Makanan Ringan',
            'deskripsi' => 'Keripik gurih dan renyah khas Dusun Krajan.',
            'alamat' => 'RT 01 / RW 01',
            'nomor_whatsapp' => '081234567892',
            'jam_operasional' => '08.00 - 17.00 WIB',
            'latitude' => '-7.223456',
            'longitude' => '110.223456',
            'produk' => [
                ['nama_produk' => 'Keripik Singkong Pedas'],
                ['nama_produk' => 'Keripik Singkong Keju'],
            ],
        ]);

        $response->assertRedirect('/admin-dusun/umkm');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('umkms', [
            'dusun_id' => $this->dusunA->id,
            'nama_umkm' => 'Keripik Singkong Barokah',
            'nama_pemilik' => 'Ibu Siti',
            'deleted_at' => null,
        ]);

        $umkm = Umkm::where('nama_umkm', 'Keripik Singkong Barokah')->first();
        $this->assertNotNull($umkm);
        $this->assertCount(2, $umkm->produkUmkms);
    }

    /**
     * 26. Update own UMKM.
     */
    public function test_admin_can_update_own_umkm(): void
    {
        $umkm = Umkm::query()->forceCreate([
            'dusun_id' => $this->dusunA->id,
            'nama_umkm' => 'Warung Kopi Krajan',
            'nama_pemilik' => 'Pak Joko',
            'jenis_usaha' => 'Minuman',
            'deskripsi' => 'Kopi mantap.',
            'alamat' => 'Jl. Krajan 5',
            'nomor_whatsapp' => '081111111111',
            'jam_operasional' => '10.00 - 22.00',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->actingAs($this->adminA)->put('/admin-dusun/umkm/'.$umkm->id, [
            'nama_umkm' => 'Warung Kopi Krajan Nusantara',
            'nama_pemilik' => 'Pak Joko Santoso',
            'jenis_usaha' => 'Kedai Kopi & Makanan',
            'deskripsi' => 'Kopi tubruk dan camilan tradisional.',
            'alamat' => 'Jl. Krajan 5 No. 10',
            'nomor_whatsapp' => '082222222222',
            'jam_operasional' => '09.00 - 23.00',
        ]);

        $response->assertRedirect('/admin-dusun/umkm');
        $umkm->refresh();
        $this->assertSame('Warung Kopi Krajan Nusantara', $umkm->nama_umkm);
        $this->assertSame('Pak Joko Santoso', $umkm->nama_pemilik);
        $this->assertSame('082222222222', $umkm->nomor_whatsapp);
    }

    /**
     * 27. Multiple product rows persist.
     */
    public function test_multiple_product_rows_persist(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/umkm', [
            'nama_umkm' => 'Batik Krajan',
            'nama_pemilik' => 'Ibu Rahayu',
            'jenis_usaha' => 'Kerajinan',
            'deskripsi' => 'Batik tulis dan cap.',
            'alamat' => 'RT 02 / RW 01',
            'nomor_whatsapp' => '081234567899',
            'jam_operasional' => '08.00 - 16.00',
            'produk' => [
                ['nama_produk' => 'Batik Tulis Motif Daun'],
                ['nama_produk' => 'Batik Cap Tradisional'],
                ['nama_produk' => 'Syal Batik Sutra'],
            ],
        ]);

        $response->assertRedirect('/admin-dusun/umkm');
        $umkm = Umkm::where('nama_umkm', 'Batik Krajan')->first();
        $this->assertNotNull($umkm);
        $this->assertCount(3, $umkm->produkUmkms);
    }

    /**
     * 28. Product add/update/remove reconciles transactionally.
     */
    public function test_product_add_update_remove_reconciles_transactionally(): void
    {
        $umkm = Umkm::query()->forceCreate([
            'dusun_id' => $this->dusunA->id,
            'nama_umkm' => 'Madu Krajan',
            'nama_pemilik' => 'Pak Madu',
            'jenis_usaha' => 'Peternakan',
            'deskripsi' => 'Madu murni.',
            'alamat' => 'RT 01',
            'nomor_whatsapp' => '081234567890',
            'jam_operasional' => '08.00 - 17.00',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $prod1 = ProdukUmkm::query()->forceCreate([
            'umkm_id' => $umkm->id,
            'nama_produk' => 'Madu Murni 250ml',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);
        $prod2 = ProdukUmkm::query()->forceCreate([
            'umkm_id' => $umkm->id,
            'nama_produk' => 'Madu Hutan 500ml',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        // Submit update: keep and update $prod1, remove $prod2, add $prod3
        $response = $this->actingAs($this->adminA)->put('/admin-dusun/umkm/'.$umkm->id, [
            'nama_umkm' => 'Madu Krajan Alami',
            'nama_pemilik' => 'Pak Madu',
            'jenis_usaha' => 'Peternakan',
            'deskripsi' => 'Madu murni kualitas super.',
            'alamat' => 'RT 01',
            'nomor_whatsapp' => '081234567890',
            'jam_operasional' => '08.00 - 17.00',
            'produk' => [
                ['id' => $prod1->id, 'nama_produk' => 'Madu Murni Premium 250ml'],
                ['nama_produk' => 'Madu Sarang Asli 300gr'], // new product
            ],
        ]);

        $response->assertRedirect('/admin-dusun/umkm');

        $umkm->refresh();
        $this->assertCount(2, $umkm->produkUmkms);
        $this->assertTrue($umkm->produkUmkms->contains('nama_produk', 'Madu Murni Premium 250ml'));
        $this->assertTrue($umkm->produkUmkms->contains('nama_produk', 'Madu Sarang Asli 300gr'));
        $this->assertFalse($umkm->produkUmkms->contains('nama_produk', 'Madu Hutan 500ml'));
        $this->assertNull(ProdukUmkm::find($prod2->id));
    }

    /**
     * 29. Foreign parent denied.
     */
    public function test_foreign_parent_umkm_denied(): void
    {
        $umkmB = Umkm::query()->forceCreate([
            'dusun_id' => $this->dusunB->id,
            'nama_umkm' => 'Toko Seberang',
            'nama_pemilik' => 'Pak Seberang',
            'jenis_usaha' => 'Kelontong',
            'deskripsi' => 'Toko di dusun B.',
            'alamat' => 'RT 01 Seberang',
            'nomor_whatsapp' => '081234567890',
            'jam_operasional' => '08.00 - 17.00',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $readResponse = $this->actingAs($this->adminA)->get('/admin-dusun/umkm/'.$umkmB->id.'/edit');
        $readResponse->assertNotFound();

        $updateResponse = $this->actingAs($this->adminA)->put('/admin-dusun/umkm/'.$umkmB->id, [
            'nama_umkm' => 'Retas Toko Seberang',
            'nama_pemilik' => 'Hacker',
            'jenis_usaha' => 'Kelontong',
            'deskripsi' => 'Deskripsi.',
            'alamat' => 'Alamat.',
            'nomor_whatsapp' => '081234567890',
            'jam_operasional' => '08.00 - 17.00',
        ]);
        $updateResponse->assertNotFound();
    }

    /**
     * 30. Optional coordinate pair accepted.
     */
    public function test_optional_coordinate_pair_accepted(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/umkm', [
            'nama_umkm' => 'Bakso Krajan',
            'nama_pemilik' => 'Pak Baso',
            'jenis_usaha' => 'Kuliner',
            'deskripsi' => 'Bakso sapi lezat.',
            'alamat' => 'Jl. Krajan No. 3',
            'nomor_whatsapp' => '081234567890',
            'jam_operasional' => '10.00 - 20.00',
            'latitude' => '-7.123456',
            'longitude' => '110.654321',
        ]);

        $response->assertRedirect('/admin-dusun/umkm');
        $this->assertDatabaseHas('umkms', [
            'nama_umkm' => 'Bakso Krajan',
            'latitude' => '-7.123456',
            'longitude' => '110.654321',
        ]);
    }

    /**
     * 31. Half coordinate rejected.
     */
    public function test_half_coordinate_rejected(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/umkm', [
            'nama_umkm' => 'Bakso Krajan',
            'nama_pemilik' => 'Pak Baso',
            'jenis_usaha' => 'Kuliner',
            'deskripsi' => 'Bakso sapi lezat.',
            'alamat' => 'Jl. Krajan No. 3',
            'nomor_whatsapp' => '081234567890',
            'jam_operasional' => '10.00 - 20.00',
            'latitude' => '-7.123456',
            'longitude' => '',
        ]);

        $response->assertSessionHasErrors(['longitude']);
    }

    /**
     * 32. No commerce field/action.
     */
    public function test_no_commerce_field_or_action_in_umkm(): void
    {
        $response = $this->actingAs($this->adminA)->get('/admin-dusun/umkm/create');
        $response->assertOk();
        $response->assertDontSee('price');
        $response->assertDontSee('harga');
        $response->assertDontSee('stock');
        $response->assertDontSee('stok');
        $response->assertDontSee('checkout');
    }

    /**
     * 33. Soft Delete hides Admin/Public.
     */
    public function test_soft_delete_hides_admin_and_public(): void
    {
        $umkm = Umkm::query()->forceCreate([
            'dusun_id' => $this->dusunA->id,
            'nama_umkm' => 'Warung Dinonaktifkan',
            'nama_pemilik' => 'Pak Nonaktif',
            'jenis_usaha' => 'Kuliner',
            'deskripsi' => 'Deskripsi.',
            'alamat' => 'Alamat.',
            'nomor_whatsapp' => '081234567890',
            'jam_operasional' => '08.00 - 17.00',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->actingAs($this->adminA)->delete('/admin-dusun/umkm/'.$umkm->id);
        $response->assertRedirect('/admin-dusun/umkm');

        $umkm->refresh();
        $this->assertNotNull($umkm->deleted_at);

        // Not in admin index
        $adminIndex = $this->actingAs($this->adminA)->get('/admin-dusun/umkm');
        $adminIndex->assertDontSee('Warung Dinonaktifkan');

        // Not in public detail
        $publicDetail = $this->get('/umkm/'.$umkm->id);
        $publicDetail->assertNotFound();
    }

    /**
     * 34. No restore/hard delete Admin action.
     */
    public function test_no_restore_or_hard_delete_admin_action(): void
    {
        $response1 = $this->actingAs($this->adminA)->post('/admin-dusun/umkm/1/restore');
        $response1->assertNotFound();

        $response2 = $this->actingAs($this->adminA)->delete('/admin-dusun/umkm/1/force');
        $response2->assertNotFound();
    }
}
