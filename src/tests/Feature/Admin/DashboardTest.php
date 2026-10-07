<?php

namespace Tests\Feature\Admin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private Desa $desa;

    private Dusun $activeDusun;

    private Dusun $inactiveDusun;

    private AdminAccount $adminActive;

    private AdminAccount $adminInactive;

    private AdminAccount $superAdmin;

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

        $this->activeDusun = Dusun::query()->forceCreate([
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

        $this->inactiveDusun = Dusun::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Inaktif',
            'status_dusun' => 'INACTIVE',
            'deskripsi_singkat' => 'Deskripsi Inaktif.',
            'nama_kepala_dusun' => 'Kepala Inaktif',
            'jumlah_rt' => 2,
            'jumlah_rw' => 1,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->adminActive = AdminAccount::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'username' => 'admin_krajan',
            'password_hash' => Hash::make('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->adminInactive = AdminAccount::query()->forceCreate([
            'dusun_id' => $this->inactiveDusun->id,
            'username' => 'admin_inaktif',
            'password_hash' => Hash::make('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->superAdmin = AdminAccount::query()->forceCreate([
            'dusun_id' => null,
            'username' => 'superadmin',
            'password_hash' => Hash::make('password123'),
            'role' => 'SUPER_ADMIN',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);
    }

    /**
     * 1. ADMIN_DUSUN accesses real Dashboard (UX-SCR-011).
     */
    public function test_admin_dusun_accesses_real_dashboard(): void
    {
        $response = $this->actingAs($this->adminActive)->get('/admin-dusun/dashboard');

        $response->assertOk();
        $response->assertSee('Dashboard Admin Dusun');
        $response->assertSee('Dusun Krajan');
    }

    /**
     * 2. SUPER_ADMIN cannot access Admin Dusun dashboard as if it were ADMIN_DUSUN.
     */
    public function test_super_admin_cannot_access_admin_dusun_dashboard(): void
    {
        $response = $this->actingAs($this->superAdmin)->get('/admin-dusun/dashboard');

        $response->assertForbidden();
    }

    /**
     * 3. Fixed Dusun context displayed.
     */
    public function test_fixed_dusun_context_displayed(): void
    {
        $response = $this->actingAs($this->adminActive)->get('/admin-dusun/dashboard');

        $response->assertOk();
        $response->assertSee('DUSUN TERDAFTAR');
        $response->assertSee('Dusun Krajan');
    }

    /**
     * 4. No Dusun selector.
     */
    public function test_no_dusun_selector_rendered(): void
    {
        $response = $this->actingAs($this->adminActive)->get('/admin-dusun/dashboard');

        $response->assertOk();
        $response->assertDontSee('select name="dusun_id"', false);
        $response->assertDontSee('Pilih Dusun');
    }

    /**
     * 5. Exactly six management navigation areas.
     */
    public function test_exactly_six_management_navigation_areas_present(): void
    {
        $response = $this->actingAs($this->adminActive)->get('/admin-dusun/dashboard');

        $response->assertOk();
        $response->assertSee(route('admin-dusun.profil.edit'));
        $response->assertSee(route('admin-dusun.kontak.index'));
        $response->assertSee(route('admin-dusun.umkm.index'));
        $response->assertSee(route('admin-dusun.fasilitas.index'));
        $response->assertSee(route('admin-dusun.agenda.index'));
        $response->assertSee(route('admin-dusun.pengumuman.index'));
    }

    /**
     * 6. INACTIVE parent notice shown.
     */
    public function test_inactive_parent_notice_shown_for_inactive_dusun_admin(): void
    {
        $response = $this->actingAs($this->adminInactive)->get('/admin-dusun/dashboard');

        $response->assertOk();
        $response->assertSee('Status Wilayah Nonaktif Publik');
        $response->assertSee('Status: Nonaktif Publik');
    }

    /**
     * 7. INACTIVE parent does not prevent management access.
     */
    public function test_inactive_parent_does_not_prevent_management_access(): void
    {
        $response = $this->actingAs($this->adminInactive)->get('/admin-dusun/kontak');
        $response->assertOk();

        $response = $this->actingAs($this->adminInactive)->get('/admin-dusun/umkm');
        $response->assertOk();

        $response = $this->actingAs($this->adminInactive)->get('/admin-dusun/fasilitas');
        $response->assertOk();

        $response = $this->actingAs($this->adminInactive)->get('/admin-dusun/agenda');
        $response->assertOk();

        $response = $this->actingAs($this->adminInactive)->get('/admin-dusun/pengumuman');
        $response->assertOk();

        $response = $this->actingAs($this->adminInactive)->get('/admin-dusun/profil');
        $response->assertOk();
    }
}
