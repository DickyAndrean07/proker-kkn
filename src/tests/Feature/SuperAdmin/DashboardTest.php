<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private AdminAccount $superAdmin;

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
            'nama_kepala_desa' => 'Kepala Desa Bendung',
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
            'username' => 'superadmin_main',
            'password_hash' => bcrypt('password123'),
            'role' => 'SUPER_ADMIN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_01_super_admin_can_access_dashboard_and_view_operational_stats(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('super-admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard Super Administrator');
        $response->assertSee('Desa Bendung');
    }

    public function test_02_admin_dusun_is_blocked_from_super_admin_dashboard(): void
    {
        $adminDusun = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admindusun_krajan',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($adminDusun)->get(route('super-admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_03_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get(route('super-admin.dashboard'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_04_dashboard_displays_10_navigation_areas_and_global_badge(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('super-admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Akses Global Seluruh Desa');
        $response->assertSee('1. Identitas Desa');
        $response->assertSee('2. Kelola Dusun');
        $response->assertSee('3. Kontak Pelayanan');
        $response->assertSee('4. Kelola UMKM');
        $response->assertSee('5. Kelola Fasilitas');
        $response->assertSee('6. Kategori Fasilitas');
        $response->assertSee('7. Agenda & Kegiatan', false);
        $response->assertSee('8. Pengumuman');
        $response->assertSee('9. Data / Peta');
        $response->assertSee('10. Admin Dusun');
    }
}
