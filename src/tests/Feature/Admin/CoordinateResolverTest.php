<?php

namespace Tests\Feature\Admin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CoordinateResolverTest extends TestCase
{
    use RefreshDatabase;

    private AdminAccount $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $desa = Desa::query()->forceCreate([
            'nama_desa' => 'Desa Bendung',
            'deskripsi_singkat' => 'Deskripsi Desa.',
            'alamat_kantor' => 'Jl. Desa No. 1',
            'nomor_kontak' => '081234567890',
            'nama_kepala_desa' => 'Kepala Desa',
            'jam_pelayanan' => '08.00 - 15.00',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $dusun = Dusun::query()->forceCreate([
            'desa_id' => $desa->id,
            'nama_dusun' => 'Dusun Bendung I',
            'status_dusun' => 'ACTIVE',
            'deskripsi_singkat' => 'Deskripsi.',
            'nama_kepala_dusun' => 'Kepala Dusun',
            'jumlah_rt' => 4,
            'jumlah_rw' => 2,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->admin = AdminAccount::query()->forceCreate([
            'dusun_id' => $dusun->id,
            'username' => 'admin_test',
            'password_hash' => Hash::make('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);
    }

    public function test_guest_cannot_resolve_coordinates(): void
    {
        $response = $this->postJson(route('admin.resolve-coordinate'), [
            'url' => 'https://maps.app.goo.gl/dy6sw1HGsAQ83Jtj7',
        ]);

        $response->assertStatus(401);
    }

    public function test_admin_can_resolve_desktop_google_maps_url(): void
    {
        $response = $this->actingAs($this->admin)->postJson(route('admin.resolve-coordinate'), [
            'url' => 'https://www.google.com/maps?q=-7.400772571563721,112.45203399658203&z=17&hl=en',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'lat' => -7.400772571563721,
                'lng' => 112.45203399658203,
                'type' => 'Google Maps Link',
            ]);
    }

    public function test_admin_can_resolve_decimal_coordinates(): void
    {
        $response = $this->actingAs($this->admin)->postJson(route('admin.resolve-coordinate'), [
            'url' => '-7.400772, 112.452033',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'lat' => -7.400772,
                'lng' => 112.452033,
                'type' => 'Koordinat Desimal',
            ]);
    }

    public function test_admin_can_resolve_mobile_maps_app_goo_gl_shortlink(): void
    {
        $response = $this->actingAs($this->admin)->postJson(route('admin.resolve-coordinate'), [
            'url' => 'https://maps.app.goo.gl/dy6sw1HGsAQ83Jtj7',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'lat' => -7.4580291,
                'lng' => 112.4301953,
                'type' => 'Google Maps Sharelink HP',
            ]);
    }

    public function test_invalid_input_returns_422(): void
    {
        $response = $this->actingAs($this->admin)->postJson(route('admin.resolve-coordinate'), [
            'url' => 'teks biasa tanpa ada angka koordinat',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }
}
