<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\ProdukUmkm;
use App\Models\Umkm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GlobalUmkmTest extends TestCase
{
    use RefreshDatabase;

    private AdminAccount $superAdmin;

    private Desa $desa;

    private Dusun $dusun1;

    private Dusun $dusun2;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->desa = Desa::forceCreate([
            'nama_desa' => 'Desa Bendung',
            'deskripsi_singkat' => 'Profil Desa Bendung',
            'alamat_kantor' => 'Jl. Balai Desa No. 1',
            'nomor_kontak' => '08123456789',
            'nama_kepala_desa' => 'Lurah Sutrisno',
            'jam_pelayanan' => 'Senin - Jumat 08.00 - 15.00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->dusun1 = Dusun::forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Krajan',
            'deskripsi_singkat' => 'Profil Dusun Krajan',
            'nama_kepala_dusun' => 'Kadus Krajan',
            'jumlah_rt' => 4,
            'jumlah_rw' => 2,
            'status_dusun' => 'ACTIVE',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->dusun2 = Dusun::forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Banyuripan',
            'deskripsi_singkat' => 'Profil Dusun Banyuripan',
            'nama_kepala_dusun' => 'Kadus Banyuripan',
            'jumlah_rt' => 3,
            'jumlah_rw' => 1,
            'status_dusun' => 'ACTIVE',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->superAdmin = AdminAccount::forceCreate([
            'dusun_id' => null,
            'username' => 'superadmin_umkm',
            'password_hash' => bcrypt('password123'),
            'role' => 'SUPER_ADMIN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_31_super_admin_can_view_global_umkm_list_with_filters(): void
    {
        Umkm::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'nama_umkm' => 'Keripik Krajan Aktif',
            'nama_pemilik' => 'Bu Joko',
            'jenis_usaha' => 'Makanan',
            'deskripsi' => 'Enak',
            'alamat' => 'Krajan',
            'nomor_whatsapp' => '081111111',
            'jam_operasional' => '08:00 - 17:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Umkm::forceCreate([
            'dusun_id' => $this->dusun2->id,
            'nama_umkm' => 'Batik Banyuripan Nonaktif',
            'nama_pemilik' => 'Pak Joko',
            'jenis_usaha' => 'Kerajinan',
            'deskripsi' => 'Bagus',
            'alamat' => 'Banyuripan',
            'nomor_whatsapp' => '082222222',
            'jam_operasional' => '08:00 - 17:00',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => now(),
        ]);

        $responseActive = $this->actingAs($this->superAdmin)->get(route('super-admin.umkm.index', ['status' => 'active']));
        $responseActive->assertStatus(200);
        $responseActive->assertSee('Keripik Krajan Aktif');
        $responseActive->assertDontSee('Batik Banyuripan Nonaktif');

        $responseTrashed = $this->actingAs($this->superAdmin)->get(route('super-admin.umkm.index', ['status' => 'trashed']));
        $responseTrashed->assertStatus(200);
        $responseTrashed->assertSee('Batik Banyuripan Nonaktif');
        $responseTrashed->assertDontSee('Keripik Krajan Aktif');
    }

    public function test_32_super_admin_can_create_umkm_with_products_for_any_dusun(): void
    {
        $payload = [
            'dusun_id' => $this->dusun2->id,
            'nama_umkm' => 'Madu Murni Banyuripan',
            'nama_pemilik' => 'Pak Budi Madu',
            'jenis_usaha' => 'Hasil Tani',
            'deskripsi' => 'Madu ternak murni 100%',
            'alamat' => 'Dusun Banyuripan No. 45',
            'nomor_whatsapp' => '08123456789',
            'jam_operasional' => '08.00 - 20.00',
            'produk' => [
                ['nama_produk' => 'Madu Randu 500ml'],
                ['nama_produk' => 'Madu Klanceng 250ml'],
            ],
        ];

        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.umkm.store'), $payload);

        $response->assertRedirect(route('super-admin.umkm.index'));
        $this->assertDatabaseHas('umkms', [
            'dusun_id' => $this->dusun2->id,
            'nama_umkm' => 'Madu Murni Banyuripan',
        ]);

        $umkm = Umkm::where('nama_umkm', 'Madu Murni Banyuripan')->firstOrFail();
        $this->assertCount(2, $umkm->produkUmkms);
    }

    public function test_33_super_admin_can_update_umkm_and_reconcile_products(): void
    {
        $umkm = Umkm::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'nama_umkm' => 'Tahu Krajan',
            'nama_pemilik' => 'Bu Tahu',
            'jenis_usaha' => 'Makanan',
            'deskripsi' => 'Tahu Gurih',
            'alamat' => 'Krajan',
            'nomor_whatsapp' => '081111111',
            'jam_operasional' => '08:00 - 17:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $p1 = ProdukUmkm::forceCreate(['umkm_id' => $umkm->id, 'nama_produk' => 'Tahu Putih', 'created_at' => now(), 'updated_at' => now()]);
        $p2 = ProdukUmkm::forceCreate(['umkm_id' => $umkm->id, 'nama_produk' => 'Tahu Kuning', 'created_at' => now(), 'updated_at' => now()]);

        $payload = [
            'dusun_id' => $this->dusun2->id,
            'nama_umkm' => 'Tahu & Tempe Krajan',
            'nama_pemilik' => 'Bu Tahu',
            'jenis_usaha' => 'Makanan',
            'deskripsi' => 'Tahu dan Tempe Segar',
            'alamat' => 'Krajan',
            'nomor_whatsapp' => '081111111',
            'jam_operasional' => '08:00 - 17:00',
            'produk' => [
                ['id' => $p1->id, 'nama_produk' => 'Tahu Sutra Putih'], // Updated
                ['nama_produk' => 'Tempe Mendoan'], // Added new, $p2 is removed
            ],
        ];

        $response = $this->actingAs($this->superAdmin)->put(route('super-admin.umkm.update', $umkm->id), $payload);

        $response->assertRedirect(route('super-admin.umkm.index'));
        $umkm->refresh();
        $this->assertEquals($this->dusun2->id, $umkm->dusun_id);
        $this->assertEquals('Tahu & Tempe Krajan', $umkm->nama_umkm);

        $this->assertDatabaseHas('produk_umkms', ['id' => $p1->id, 'nama_produk' => 'Tahu Sutra Putih']);
        $this->assertDatabaseMissing('produk_umkms', ['id' => $p2->id]);
        $this->assertDatabaseHas('produk_umkms', ['umkm_id' => $umkm->id, 'nama_produk' => 'Tempe Mendoan']);
    }

    public function test_34_super_admin_can_soft_delete_umkm_retaining_media(): void
    {
        $image = UploadedFile::fake()->image('umkm_foto.jpg');
        $path = $image->store('umkm', 'public');

        $umkm = Umkm::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'nama_umkm' => 'UMKM Soft Delete',
            'nama_pemilik' => 'Pak Soft',
            'jenis_usaha' => 'Makanan',
            'deskripsi' => 'Deskripsi',
            'alamat' => 'Krajan',
            'nomor_whatsapp' => '081111111',
            'jam_operasional' => '08:00 - 17:00',
            'foto_utama_path' => $path,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->delete(route('super-admin.umkm.destroy', $umkm->id));

        $response->assertRedirect(route('super-admin.umkm.index'));
        $umkm->refresh();
        $this->assertTrue($umkm->trashed());
        Storage::disk('public')->assertExists($path); // Media retained
    }

    public function test_35_super_admin_can_restore_soft_deleted_umkm(): void
    {
        $umkm = Umkm::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'nama_umkm' => 'UMKM Dipulihkan',
            'nama_pemilik' => 'Pak Pulih',
            'jenis_usaha' => 'Makanan',
            'deskripsi' => 'Deskripsi',
            'alamat' => 'Krajan',
            'nomor_whatsapp' => '081111111',
            'jam_operasional' => '08:00 - 17:00',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.umkm.restore', $umkm->id));

        $response->assertRedirect(route('super-admin.umkm.index', ['status' => 'trashed']));
        $umkm->refresh();
        $this->assertFalse($umkm->trashed());
    }

    public function test_36_super_admin_can_hard_delete_umkm_purging_media_and_cascading_products(): void
    {
        $image = UploadedFile::fake()->image('umkm_purge.jpg');
        $path = $image->store('umkm', 'public');

        $umkm = Umkm::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'nama_umkm' => 'UMKM Dihapus Permanen',
            'nama_pemilik' => 'Pak Purge',
            'jenis_usaha' => 'Makanan',
            'deskripsi' => 'Deskripsi',
            'alamat' => 'Krajan',
            'nomor_whatsapp' => '081111111',
            'jam_operasional' => '08:00 - 17:00',
            'foto_utama_path' => $path,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => now(),
        ]);

        $prod = ProdukUmkm::forceCreate([
            'umkm_id' => $umkm->id,
            'nama_produk' => 'Produk Cascade Target',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->delete(route('super-admin.umkm.force-delete', $umkm->id));

        $response->assertRedirect(route('super-admin.umkm.index', ['status' => 'trashed']));
        $this->assertDatabaseMissing('umkms', ['id' => $umkm->id]);
        $this->assertDatabaseMissing('produk_umkms', ['id' => $prod->id]);
        Storage::disk('public')->assertMissing($path); // Media purged
    }

    public function test_37_attempting_to_hard_delete_active_umkm_returns_404(): void
    {
        $umkm = Umkm::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'nama_umkm' => 'UMKM Aktif',
            'nama_pemilik' => 'Pak Aktif',
            'jenis_usaha' => 'Makanan',
            'deskripsi' => 'Deskripsi',
            'alamat' => 'Krajan',
            'nomor_whatsapp' => '081111111',
            'jam_operasional' => '08:00 - 17:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->delete(route('super-admin.umkm.force-delete', $umkm->id));
        $response->assertStatus(404);
    }

    public function test_38_admin_dusun_is_blocked_from_super_admin_global_umkm_routes(): void
    {
        $adminDusun = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admindusun_umkm_sec',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $responseIndex = $this->actingAs($adminDusun)->get(route('super-admin.umkm.index'));
        $responseIndex->assertStatus(403);
    }
}
