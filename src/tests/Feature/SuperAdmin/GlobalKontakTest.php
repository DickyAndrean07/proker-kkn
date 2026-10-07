<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\KontakPelayanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GlobalKontakTest extends TestCase
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
            'username' => 'superadmin_kontak',
            'password_hash' => bcrypt('password123'),
            'role' => 'SUPER_ADMIN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_21_super_admin_can_view_global_kontak_list_with_filters(): void
    {
        KontakPelayanan::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'nama' => 'Kontak Krajan',
            'jabatan' => 'Kasi Krajan',
            'nomor_whatsapp' => '0811111111',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $trashedKontak = KontakPelayanan::forceCreate([
            'dusun_id' => $this->dusun2->id,
            'nama' => 'Kontak Banyuripan Nonaktif',
            'jabatan' => 'Staf Banyuripan',
            'nomor_whatsapp' => '0822222222',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => now(),
        ]);

        $responseActive = $this->actingAs($this->superAdmin)->get(route('super-admin.kontak.index', ['status' => 'active']));
        $responseActive->assertStatus(200);
        $responseActive->assertSee('Kontak Krajan');
        $responseActive->assertDontSee('Kontak Banyuripan Nonaktif');

        $responseTrashed = $this->actingAs($this->superAdmin)->get(route('super-admin.kontak.index', ['status' => 'trashed']));
        $responseTrashed->assertStatus(200);
        $responseTrashed->assertSee('Kontak Banyuripan Nonaktif');
        $responseTrashed->assertDontSee('Kontak Krajan');

        $responseAll = $this->actingAs($this->superAdmin)->get(route('super-admin.kontak.index', ['status' => 'all']));
        $responseAll->assertStatus(200);
        $responseAll->assertSee('Kontak Krajan');
        $responseAll->assertSee('Kontak Banyuripan Nonaktif');
    }

    public function test_22_super_admin_can_create_kontak_for_any_dusun(): void
    {
        $payload = [
            'dusun_id' => $this->dusun2->id,
            'nama' => 'Pak Budi Kontak',
            'jabatan' => 'Ketua RW 01',
            'nomor_whatsapp' => '081234567890',
            'alamat_pelayanan' => 'Rumah Ketua RW',
        ];

        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.kontak.store'), $payload);

        $response->assertRedirect(route('super-admin.kontak.index'));
        $this->assertDatabaseHas('kontak_pelayanans', [
            'dusun_id' => $this->dusun2->id,
            'nama' => 'Pak Budi Kontak',
        ]);
    }

    public function test_23_super_admin_can_update_kontak_of_any_dusun(): void
    {
        $kontak = KontakPelayanan::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'nama' => 'Nama Awal',
            'jabatan' => 'Jabatan Awal',
            'nomor_whatsapp' => '0811111111',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $payload = [
            'dusun_id' => $this->dusun2->id, // Reassigned to dusun 2
            'nama' => 'Nama Baru Diperbarui',
            'jabatan' => 'Jabatan Baru',
            'nomor_whatsapp' => '0899999999',
        ];

        $response = $this->actingAs($this->superAdmin)->put(route('super-admin.kontak.update', $kontak->id), $payload);

        $response->assertRedirect(route('super-admin.kontak.index'));
        $kontak->refresh();
        $this->assertEquals($this->dusun2->id, $kontak->dusun_id);
        $this->assertEquals('Nama Baru Diperbarui', $kontak->nama);
    }

    public function test_24_super_admin_can_soft_delete_kontak_and_retains_media(): void
    {
        $image = UploadedFile::fake()->image('kontak_foto.jpg');
        $path = $image->store('kontak', 'public');

        $kontak = KontakPelayanan::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'nama' => 'Kontak Dengan Foto',
            'jabatan' => 'Staf',
            'nomor_whatsapp' => '08123456789',
            'foto_path' => $path,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->delete(route('super-admin.kontak.destroy', $kontak->id));

        $response->assertRedirect(route('super-admin.kontak.index'));
        $kontak->refresh();
        $this->assertTrue($kontak->trashed());
        Storage::disk('public')->assertExists($path); // Media retained!
    }

    public function test_25_super_admin_can_restore_soft_deleted_kontak(): void
    {
        $kontak = KontakPelayanan::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'nama' => 'Kontak Dipulihkan',
            'jabatan' => 'Staf',
            'nomor_whatsapp' => '08123456789',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.kontak.restore', $kontak->id));

        $response->assertRedirect(route('super-admin.kontak.index', ['status' => 'trashed']));
        $kontak->refresh();
        $this->assertFalse($kontak->trashed());
    }

    public function test_26_super_admin_can_hard_delete_soft_deleted_kontak_and_purges_media(): void
    {
        $image = UploadedFile::fake()->image('kontak_purge.jpg');
        $path = $image->store('kontak', 'public');

        $kontak = KontakPelayanan::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'nama' => 'Kontak Dihapus Permanen',
            'jabatan' => 'Staf',
            'nomor_whatsapp' => '08123456789',
            'foto_path' => $path,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->delete(route('super-admin.kontak.force-delete', $kontak->id));

        $response->assertRedirect(route('super-admin.kontak.index', ['status' => 'trashed']));
        $this->assertDatabaseMissing('kontak_pelayanans', ['id' => $kontak->id]);
        Storage::disk('public')->assertMissing($path); // Media purged!
    }

    public function test_27_attempting_to_hard_delete_an_active_kontak_returns_404(): void
    {
        $kontak = KontakPelayanan::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'nama' => 'Kontak Aktif Tidak Boleh Hard Delete Langsung',
            'jabatan' => 'Staf',
            'nomor_whatsapp' => '08123456789',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->delete(route('super-admin.kontak.force-delete', $kontak->id));
        $response->assertStatus(404);
    }

    public function test_28_super_admin_can_filter_kontak_by_dusun(): void
    {
        KontakPelayanan::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'nama' => 'Kontak Dusun Krajan Eksklusif',
            'jabatan' => 'Staf',
            'nomor_whatsapp' => '0811111111',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        KontakPelayanan::forceCreate([
            'dusun_id' => $this->dusun2->id,
            'nama' => 'Kontak Dusun Banyuripan Eksklusif',
            'jabatan' => 'Staf',
            'nomor_whatsapp' => '0822222222',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('super-admin.kontak.index', ['dusun_id' => $this->dusun1->id]));
        $response->assertStatus(200);
        $response->assertSee('Kontak Dusun Krajan Eksklusif');
        $response->assertDontSee('Kontak Dusun Banyuripan Eksklusif');
    }

    public function test_29_admin_dusun_is_blocked_from_super_admin_global_kontak_routes(): void
    {
        $adminDusun = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admindusun_kontak_sec',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $responseIndex = $this->actingAs($adminDusun)->get(route('super-admin.kontak.index'));
        $responseIndex->assertStatus(403);

        $responseCreate = $this->actingAs($adminDusun)->get(route('super-admin.kontak.create'));
        $responseCreate->assertStatus(403);
    }

    public function test_30_validation_errors_when_creating_kontak_with_invalid_dusun_or_missing_fields(): void
    {
        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.kontak.store'), [
            'dusun_id' => 999999,
            'nama' => '',
        ]);

        $response->assertSessionHasErrors(['dusun_id', 'nama', 'jabatan', 'nomor_whatsapp']);
    }
}
