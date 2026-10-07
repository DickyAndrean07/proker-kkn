<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\KontakPelayanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DusunTest extends TestCase
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
            'username' => 'superadmin_dusun',
            'password_hash' => bcrypt('password123'),
            'role' => 'SUPER_ADMIN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_11_super_admin_can_list_all_dusuns(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('super-admin.dusun.index'));

        $response->assertStatus(200);
        $response->assertSee('Dusun Krajan');
        $response->assertSee('Dusun Banyuripan');
    }

    public function test_12_super_admin_can_view_dusun_edit_profile_form(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('super-admin.dusun.edit', $this->dusun1->id));

        $response->assertStatus(200);
        $response->assertSee('Edit Profil Dusun Krajan');
        $response->assertSee('Kadus Krajan');
    }

    public function test_13_super_admin_can_update_dusun_profile_fields(): void
    {
        $payload = [
            'nama_dusun' => 'Dusun Krajan Makmur',
            'deskripsi_singkat' => 'Profil Krajan Makmur baru diperbarui.',
            'nama_kepala_dusun' => 'Kadus Sugeng',
            'jumlah_rt' => 5,
            'jumlah_rw' => 2,
        ];

        $response = $this->actingAs($this->superAdmin)->put(route('super-admin.dusun.update', $this->dusun1->id), $payload);

        $response->assertRedirect(route('super-admin.dusun.index'));
        $response->assertSessionHas('success');

        $this->dusun1->refresh();
        $this->assertEquals('Dusun Krajan Makmur', $this->dusun1->nama_dusun);
        $this->assertEquals('Kadus Sugeng', $this->dusun1->nama_kepala_dusun);
        $this->assertEquals(5, $this->dusun1->jumlah_rt);
    }

    public function test_14_validation_errors_when_updating_dusun_with_missing_fields(): void
    {
        $response = $this->actingAs($this->superAdmin)->put(route('super-admin.dusun.update', $this->dusun1->id), [
            'nama_dusun' => '',
            'jumlah_rt' => 'not-an-int',
        ]);

        $response->assertSessionHasErrors(['nama_dusun', 'deskripsi_singkat', 'nama_kepala_dusun', 'jumlah_rt', 'jumlah_rw']);
    }

    public function test_15_super_admin_can_update_dusun_banner_image(): void
    {
        $file = UploadedFile::fake()->image('krajan_banner.jpg', 1200, 600);

        $payload = [
            'nama_dusun' => 'Dusun Krajan',
            'deskripsi_singkat' => 'Profil Dusun Krajan',
            'nama_kepala_dusun' => 'Kadus Krajan',
            'jumlah_rt' => 4,
            'jumlah_rw' => 2,
            'banner' => $file,
        ];

        $response = $this->actingAs($this->superAdmin)->put(route('super-admin.dusun.update', $this->dusun1->id), $payload);

        $response->assertRedirect(route('super-admin.dusun.index'));
        $this->dusun1->refresh();
        $this->assertNotNull($this->dusun1->banner_path);
        Storage::disk('public')->assertExists($this->dusun1->banner_path);
    }

    public function test_16_super_admin_can_deactivate_dusun(): void
    {
        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.dusun.deactivate', $this->dusun1->id));

        $response->assertRedirect(route('super-admin.dusun.index'));
        $response->assertSessionHas('success');

        $this->dusun1->refresh();
        $this->assertEquals('INACTIVE', $this->dusun1->status_dusun);
    }

    public function test_17_super_admin_can_activate_dusun(): void
    {
        $this->dusun1->status_dusun = 'INACTIVE';
        $this->dusun1->save();

        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.dusun.activate', $this->dusun1->id));

        $response->assertRedirect(route('super-admin.dusun.index'));
        $response->assertSessionHas('success');

        $this->dusun1->refresh();
        $this->assertEquals('ACTIVE', $this->dusun1->status_dusun);
    }

    public function test_18_admin_dusun_is_blocked_from_super_admin_dusun_routes(): void
    {
        $adminDusun = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admindusun_krajan_sec',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $responseIndex = $this->actingAs($adminDusun)->get(route('super-admin.dusun.index'));
        $responseIndex->assertStatus(403);

        $responseDeactivate = $this->actingAs($adminDusun)->post(route('super-admin.dusun.deactivate', $this->dusun1->id));
        $responseDeactivate->assertStatus(403);
    }

    public function test_19_no_create_delete_or_restore_routes_exist_for_dusun(): void
    {
        $this->assertFalse(Route::has('super-admin.dusun.create'));
        $this->assertFalse(Route::has('super-admin.dusun.destroy'));
        $this->assertFalse(Route::has('super-admin.dusun.restore'));
    }

    public function test_20_reactivating_dusun_does_not_auto_restore_soft_deleted_children(): void
    {
        // Deactivate dusun
        $this->dusun1->status_dusun = 'INACTIVE';
        $this->dusun1->save();

        // Create a soft deleted child
        $softDeletedKontak = KontakPelayanan::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'nama' => 'Pak Kadus Lama',
            'jabatan' => 'Mantan Kadus',
            'nomor_whatsapp' => '08123456789',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => now(),
        ]);

        // Reactivate dusun
        $this->actingAs($this->superAdmin)->post(route('super-admin.dusun.activate', $this->dusun1->id));

        $this->dusun1->refresh();
        $this->assertEquals('ACTIVE', $this->dusun1->status_dusun);

        // Verify child remains soft deleted
        $softDeletedKontak->refresh();
        $this->assertTrue($softDeletedKontak->trashed());
    }
}
