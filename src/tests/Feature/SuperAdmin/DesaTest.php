<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DesaTest extends TestCase
{
    use RefreshDatabase;

    private AdminAccount $superAdmin;

    private Desa $desa;

    private Dusun $dusun;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->desa = Desa::forceCreate([
            'nama_desa' => 'Desa Bendung',
            'deskripsi_singkat' => 'Profil Desa Bendung Asli',
            'alamat_kantor' => 'Jl. Balai Desa No. 1',
            'nomor_kontak' => '08123456789',
            'nama_kepala_desa' => 'Lurah Sutrisno',
            'jam_pelayanan' => 'Senin - Jumat 08.00 - 15.00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->dusun = Dusun::forceCreate([
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
            'username' => 'superadmin_desa',
            'password_hash' => bcrypt('password123'),
            'role' => 'SUPER_ADMIN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_05_super_admin_can_view_desa_edit_form(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('super-admin.desa.edit'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Identitas Desa');
        $response->assertSee('Lurah Sutrisno');
    }

    public function test_06_super_admin_can_update_desa_profile_fields(): void
    {
        $payload = [
            'nama_desa' => 'Desa Bendung Maju',
            'deskripsi_singkat' => 'Deskripsi desa baru diperbarui.',
            'alamat_kantor' => 'Jl. Raya Bendung No. 99',
            'nomor_kontak' => '08987654321',
            'nama_kepala_desa' => 'Lurah Bambang',
            'jam_pelayanan' => 'Senin - Sabtu 08.00 - 14.00',
        ];

        $response = $this->actingAs($this->superAdmin)->put(route('super-admin.desa.update'), $payload);

        $response->assertRedirect(route('super-admin.desa.edit'));
        $response->assertSessionHas('success');

        $this->desa->refresh();
        $this->assertEquals('Desa Bendung Maju', $this->desa->nama_desa);
        $this->assertEquals('Lurah Bambang', $this->desa->nama_kepala_desa);
    }

    public function test_07_validation_errors_when_updating_desa_with_missing_fields(): void
    {
        $response = $this->actingAs($this->superAdmin)->put(route('super-admin.desa.update'), [
            'nama_desa' => '',
            'nama_kepala_desa' => '',
        ]);

        $response->assertSessionHasErrors(['nama_desa', 'nama_kepala_desa', 'deskripsi_singkat', 'alamat_kantor', 'nomor_kontak', 'jam_pelayanan']);
    }

    public function test_08_super_admin_can_update_desa_banner_image(): void
    {
        $file = UploadedFile::fake()->image('desa_banner.jpg', 1200, 600);

        $payload = [
            'nama_desa' => 'Desa Bendung',
            'deskripsi_singkat' => 'Profil Desa Bendung',
            'alamat_kantor' => 'Jl. Balai Desa No. 1',
            'nomor_kontak' => '08123456789',
            'nama_kepala_desa' => 'Lurah Sutrisno',
            'jam_pelayanan' => 'Senin - Jumat 08.00 - 15.00',
            'banner' => $file,
        ];

        $response = $this->actingAs($this->superAdmin)->put(route('super-admin.desa.update'), $payload);

        $response->assertRedirect(route('super-admin.desa.edit'));
        $this->desa->refresh();
        $this->assertNotNull($this->desa->banner_path);
        Storage::disk('public')->assertExists($this->desa->banner_path);
    }

    public function test_09_admin_dusun_is_blocked_from_desa_edit_and_update(): void
    {
        $adminDusun = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun->id,
            'username' => 'admindusun_desa',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $responseGet = $this->actingAs($adminDusun)->get(route('super-admin.desa.edit'));
        $responseGet->assertStatus(403);

        $responsePut = $this->actingAs($adminDusun)->put(route('super-admin.desa.update'), [
            'nama_desa' => 'Hacked Desa',
        ]);
        $responsePut->assertStatus(403);
    }

    public function test_10_no_routes_exist_for_creating_or_deleting_desa(): void
    {
        $this->assertFalse(Route::has('super-admin.desa.create'));
        $this->assertFalse(Route::has('super-admin.desa.destroy'));
    }
}
