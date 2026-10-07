<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\Fasilitas;
use App\Models\KategoriFasilitas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KategoriFasilitasTest extends TestCase
{
    use RefreshDatabase;

    private AdminAccount $superAdmin;

    private Desa $desa;

    private Dusun $dusun;

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
            'username' => 'superadmin_kategori',
            'password_hash' => bcrypt('password123'),
            'role' => 'SUPER_ADMIN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_46_super_admin_can_list_kategori_fasilitas_with_count(): void
    {
        $kategori = KategoriFasilitas::forceCreate([
            'desa_id' => $this->desa->id,
            'nama_kategori' => 'Pendidikan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Fasilitas::forceCreate([
            'dusun_id' => $this->dusun->id,
            'kategori_fasilitas_id' => $kategori->id,
            'nama' => 'SD Negeri Bendung 1',
            'deskripsi' => 'Sekolah Dasar',
            'alamat' => 'Krajan',
            'latitude' => -7.915,
            'longitude' => 110.575,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('super-admin.kategori-fasilitas.index'));

        $response->assertStatus(200);
        $response->assertSee('Pendidikan');
        $response->assertSee('1 Fasilitas');
    }

    public function test_47_super_admin_can_create_new_kategori_fasilitas(): void
    {
        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.kategori-fasilitas.store'), [
            'nama_kategori' => 'Sarana Olahraga',
        ]);

        $response->assertRedirect(route('super-admin.kategori-fasilitas.index'));
        $this->assertDatabaseHas('kategori_fasilitas', [
            'desa_id' => $this->desa->id,
            'nama_kategori' => 'Sarana Olahraga',
        ]);
    }

    public function test_48_duplicate_kategori_in_same_desa_is_rejected(): void
    {
        KategoriFasilitas::forceCreate([
            'desa_id' => $this->desa->id,
            'nama_kategori' => 'Kesehatan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.kategori-fasilitas.store'), [
            'nama_kategori' => 'Kesehatan',
        ]);

        $response->assertSessionHasErrors(['nama_kategori']);
    }

    public function test_49_super_admin_can_update_kategori_name(): void
    {
        $kategori = KategoriFasilitas::forceCreate([
            'desa_id' => $this->desa->id,
            'nama_kategori' => 'Tempat Ibadah',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->put(route('super-admin.kategori-fasilitas.update', $kategori->id), [
            'nama_kategori' => 'Sarana Keagamaan & Ibadah',
        ]);

        $response->assertRedirect(route('super-admin.kategori-fasilitas.index'));
        $kategori->refresh();
        $this->assertEquals('Sarana Keagamaan & Ibadah', $kategori->nama_kategori);
    }

    public function test_50_super_admin_can_delete_unused_kategori(): void
    {
        $kategori = KategoriFasilitas::forceCreate([
            'desa_id' => $this->desa->id,
            'nama_kategori' => 'Kategori Tidak Terpakai',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->delete(route('super-admin.kategori-fasilitas.destroy', $kategori->id));

        $response->assertRedirect(route('super-admin.kategori-fasilitas.index'));
        $this->assertDatabaseMissing('kategori_fasilitas', ['id' => $kategori->id]);
    }

    public function test_51_deleting_in_use_kategori_is_blocked_safely_without_leaking_sql(): void
    {
        $kategori = KategoriFasilitas::forceCreate([
            'desa_id' => $this->desa->id,
            'nama_kategori' => 'Kesehatan In Use',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Fasilitas::forceCreate([
            'dusun_id' => $this->dusun->id,
            'kategori_fasilitas_id' => $kategori->id,
            'nama' => 'Poskesdes',
            'deskripsi' => 'Kesehatan',
            'alamat' => 'Krajan',
            'latitude' => -7.915,
            'longitude' => 110.575,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->delete(route('super-admin.kategori-fasilitas.destroy', $kategori->id));

        $response->assertRedirect(route('super-admin.kategori-fasilitas.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('kategori_fasilitas', ['id' => $kategori->id]);
    }

    public function test_52_admin_dusun_is_blocked_from_kategori_crud(): void
    {
        $adminDusun = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun->id,
            'username' => 'admindusun_kat_sec',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $responseIndex = $this->actingAs($adminDusun)->get(route('super-admin.kategori-fasilitas.index'));
        $responseIndex->assertStatus(403);

        $responseCreate = $this->actingAs($adminDusun)->post(route('super-admin.kategori-fasilitas.store'), [
            'nama_kategori' => 'Illegal',
        ]);
        $responseCreate->assertStatus(403);
    }

    public function test_53_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get(route('super-admin.kategori-fasilitas.index'));
        $response->assertRedirect(route('admin.login'));
    }
}
