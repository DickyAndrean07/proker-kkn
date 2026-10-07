<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CrossRoleSecurityTest extends TestCase
{
    use RefreshDatabase;

    private AdminAccount $superAdmin;

    private AdminAccount $adminDusun;

    private Desa $desa;

    private Dusun $dusun1;

    protected function setUp(): void
    {
        parent::setUp();

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

        $this->superAdmin = AdminAccount::forceCreate([
            'dusun_id' => null,
            'username' => 'superadmin_cross',
            'password_hash' => bcrypt('password123'),
            'role' => 'SUPER_ADMIN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->adminDusun = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admindusun_cross',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_113_admin_dusun_accessing_super_admin_paths_receives_403(): void
    {
        $urls = [
            '/super-admin/dashboard',
            '/super-admin/desa',
            '/super-admin/dusun',
            '/super-admin/kontak',
            '/super-admin/umkm',
            '/super-admin/fasilitas',
            '/super-admin/kategori-fasilitas',
            '/super-admin/agenda',
            '/super-admin/pengumuman',
            '/super-admin/data-peta',
            '/super-admin/admin-dusun',
        ];

        foreach ($urls as $url) {
            $response = $this->actingAs($this->adminDusun)->get($url);
            $response->assertStatus(403);
        }
    }

    public function test_114_unauthenticated_user_accessing_super_admin_paths_is_redirected_to_login(): void
    {
        $response = $this->get('/super-admin/dashboard');
        $response->assertRedirect(route('admin.login'));
    }

    public function test_115_super_admin_accessing_admin_dusun_scoped_area_receives_403(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin-dusun.dashboard'));
        $response->assertStatus(403);
    }

    public function test_116_super_admin_cannot_delete_dusun_via_url_forging(): void
    {
        $response = $this->actingAs($this->superAdmin)->delete("/super-admin/dusun/{$this->dusun1->id}");
        $response->assertStatus(405); // Method Not Allowed (Route does not exist)
    }

    public function test_117_super_admin_cannot_delete_desa(): void
    {
        $response = $this->actingAs($this->superAdmin)->delete('/super-admin/desa');
        $response->assertStatus(405);
    }

    public function test_118_csrf_protection_is_active_on_mutation_endpoints(): void
    {
        // Calling post without CSRF token in an unauthenticated or external state is protected by Laravel's VerifyCsrfToken
        $this->assertTrue(in_array('web', Route::getRoutes()->getByName('super-admin.desa.update')->middleware()));
    }

    public function test_119_active_session_of_removed_admin_dusun_is_invalidated_by_admin_active_middleware(): void
    {
        // Mark as removed while simulated session is active
        $this->adminDusun->removed_at = now();
        $this->adminDusun->save();

        $response = $this->actingAs($this->adminDusun)->get(route('admin-dusun.dashboard'));
        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_120_inactive_dusun_admin_can_still_login_and_access_admin_dusun_area(): void
    {
        $this->dusun1->status_dusun = 'INACTIVE';
        $this->dusun1->save();

        $response = $this->actingAs($this->adminDusun)->get(route('admin-dusun.dashboard'));
        $response->assertStatus(200);
    }
}
