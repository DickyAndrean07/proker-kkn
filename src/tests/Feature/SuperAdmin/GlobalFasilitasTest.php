<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\Fasilitas;
use App\Models\KategoriFasilitas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GlobalFasilitasTest extends TestCase
{
    use RefreshDatabase;

    private AdminAccount $superAdmin;

    private Desa $desa;

    private Dusun $dusun1;

    private Dusun $dusun2;

    private KategoriFasilitas $kategori;

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

        $this->kategori = KategoriFasilitas::forceCreate([
            'desa_id' => $this->desa->id,
            'nama_kategori' => 'Kesehatan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->superAdmin = AdminAccount::forceCreate([
            'dusun_id' => null,
            'username' => 'superadmin_fasilitas',
            'password_hash' => bcrypt('password123'),
            'role' => 'SUPER_ADMIN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_39_super_admin_can_view_global_fasilitas_list_with_filters(): void
    {
        Fasilitas::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Poskesdes Krajan',
            'deskripsi' => 'Layanan kesehatan warga',
            'alamat' => 'Krajan',
            'latitude' => -7.915,
            'longitude' => 110.575,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Fasilitas::forceCreate([
            'dusun_id' => $this->dusun2->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Posyandu Banyuripan Nonaktif',
            'deskripsi' => 'Posyandu balita',
            'alamat' => 'Banyuripan',
            'latitude' => -7.916,
            'longitude' => 110.576,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => now(),
        ]);

        $responseActive = $this->actingAs($this->superAdmin)->get(route('super-admin.fasilitas.index', ['status' => 'active']));
        $responseActive->assertStatus(200);
        $responseActive->assertSee('Poskesdes Krajan');
        $responseActive->assertDontSee('Posyandu Banyuripan Nonaktif');

        $responseTrashed = $this->actingAs($this->superAdmin)->get(route('super-admin.fasilitas.index', ['status' => 'trashed']));
        $responseTrashed->assertStatus(200);
        $responseTrashed->assertSee('Posyandu Banyuripan Nonaktif');
        $responseTrashed->assertDontSee('Poskesdes Krajan');
    }

    public function test_40_super_admin_can_create_fasilitas_with_required_coordinates(): void
    {
        $payload = [
            'dusun_id' => $this->dusun2->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Puskesmas Pembantu',
            'deskripsi' => 'Fasilitas kesehatan',
            'alamat' => 'Jl. Banyuripan Raya',
            'latitude' => -7.915432,
            'longitude' => 110.576543,
        ];

        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.fasilitas.store'), $payload);

        $response->assertRedirect(route('super-admin.fasilitas.index'));
        $this->assertDatabaseHas('fasilitas', [
            'dusun_id' => $this->dusun2->id,
            'nama' => 'Puskesmas Pembantu',
            'latitude' => -7.915432,
        ]);
    }

    public function test_41_super_admin_can_update_fasilitas(): void
    {
        $fasilitas = Fasilitas::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Nama Fasilitas Awal',
            'deskripsi' => 'Deskripsi',
            'alamat' => 'Krajan',
            'latitude' => -7.915,
            'longitude' => 110.575,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $payload = [
            'dusun_id' => $this->dusun2->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Nama Fasilitas Baru Diperbarui',
            'deskripsi' => 'Deskripsi baru',
            'alamat' => 'Banyuripan',
            'latitude' => -7.920,
            'longitude' => 110.580,
        ];

        $response = $this->actingAs($this->superAdmin)->put(route('super-admin.fasilitas.update', $fasilitas->id), $payload);

        $response->assertRedirect(route('super-admin.fasilitas.index'));
        $fasilitas->refresh();
        $this->assertEquals($this->dusun2->id, $fasilitas->dusun_id);
        $this->assertEquals('Nama Fasilitas Baru Diperbarui', $fasilitas->nama);
    }

    public function test_42_super_admin_can_soft_delete_fasilitas_retaining_media(): void
    {
        $image = UploadedFile::fake()->image('fasilitas_foto.jpg');
        $path = $image->store('fasilitas', 'public');

        $fasilitas = Fasilitas::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Fasilitas Soft Delete',
            'deskripsi' => 'Deskripsi',
            'alamat' => 'Krajan',
            'foto_path' => $path,
            'latitude' => -7.915,
            'longitude' => 110.575,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->delete(route('super-admin.fasilitas.destroy', $fasilitas->id));

        $response->assertRedirect(route('super-admin.fasilitas.index'));
        $fasilitas->refresh();
        $this->assertTrue($fasilitas->trashed());
        Storage::disk('public')->assertExists($path);
    }

    public function test_43_super_admin_can_restore_soft_deleted_fasilitas(): void
    {
        $fasilitas = Fasilitas::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Fasilitas Dipulihkan',
            'deskripsi' => 'Deskripsi',
            'alamat' => 'Krajan',
            'latitude' => -7.915,
            'longitude' => 110.575,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.fasilitas.restore', $fasilitas->id));

        $response->assertRedirect(route('super-admin.fasilitas.index', ['status' => 'trashed']));
        $fasilitas->refresh();
        $this->assertFalse($fasilitas->trashed());
    }

    public function test_44_super_admin_can_hard_delete_fasilitas_purging_media(): void
    {
        $image = UploadedFile::fake()->image('fasilitas_purge.jpg');
        $path = $image->store('fasilitas', 'public');

        $fasilitas = Fasilitas::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Fasilitas Hard Delete',
            'deskripsi' => 'Deskripsi',
            'alamat' => 'Krajan',
            'foto_path' => $path,
            'latitude' => -7.915,
            'longitude' => 110.575,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->delete(route('super-admin.fasilitas.force-delete', $fasilitas->id));

        $response->assertRedirect(route('super-admin.fasilitas.index', ['status' => 'trashed']));
        $this->assertDatabaseMissing('fasilitas', ['id' => $fasilitas->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_45_admin_dusun_is_blocked_from_super_admin_fasilitas_routes(): void
    {
        $adminDusun = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admindusun_fasilitas_sec',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $responseIndex = $this->actingAs($adminDusun)->get(route('super-admin.fasilitas.index'));
        $responseIndex->assertStatus(403);
    }
}
