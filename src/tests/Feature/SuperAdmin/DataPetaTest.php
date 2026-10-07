<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\Fasilitas;
use App\Models\KategoriFasilitas;
use App\Models\KontakPelayanan;
use App\Models\Umkm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class DataPetaTest extends TestCase
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
            'username' => 'superadmin_peta',
            'password_hash' => bcrypt('password123'),
            'role' => 'SUPER_ADMIN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_79_super_admin_can_access_data_peta_view(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('super-admin.data-peta'));

        $response->assertStatus(200);
        $response->assertSee('Peta Persebaran Sarana &amp; Usaha', false);
        $response->assertSee('superAdminMapCanvas');
    }

    public function test_80_markers_rendered_for_fasilitas_with_coordinates(): void
    {
        Fasilitas::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Puskesmas Krajan Map',
            'deskripsi' => 'Deskripsi',
            'alamat' => 'Krajan',
            'latitude' => -7.915123,
            'longitude' => 110.575123,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('super-admin.data-peta'));
        $response->assertStatus(200);
        $response->assertSee('Puskesmas Krajan Map');
    }

    public function test_81_markers_rendered_for_umkm_with_coordinates(): void
    {
        Umkm::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'nama_umkm' => 'Warung Bakso Krajan Map',
            'nama_pemilik' => 'Pak Bakso',
            'jenis_usaha' => 'Kuliner',
            'deskripsi' => 'Deskripsi',
            'alamat' => 'Krajan',
            'nomor_whatsapp' => '081111111',
            'jam_operasional' => '08:00 - 20:00',
            'latitude' => -7.915234,
            'longitude' => 110.575234,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('super-admin.data-peta'));
        $response->assertStatus(200);
        $response->assertSee('Warung Bakso Krajan Map');
    }

    public function test_82_markers_rendered_for_kontak_with_coordinates(): void
    {
        KontakPelayanan::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'nama' => 'Pak Kadus Krajan Map',
            'jabatan' => 'Kadus',
            'nomor_whatsapp' => '081111111',
            'latitude' => -7.915345,
            'longitude' => 110.575345,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('super-admin.data-peta'));
        $response->assertStatus(200);
        $response->assertSee('Pak Kadus Krajan Map');
    }

    public function test_83_dusun_filter_narrows_marker_dataset(): void
    {
        Fasilitas::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Fasilitas Krajan Unik',
            'deskripsi' => 'Deskripsi',
            'alamat' => 'Krajan',
            'latitude' => -7.915,
            'longitude' => 110.575,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Fasilitas::forceCreate([
            'dusun_id' => $this->dusun2->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Fasilitas Banyuripan Unik',
            'deskripsi' => 'Deskripsi',
            'alamat' => 'Banyuripan',
            'latitude' => -7.920,
            'longitude' => 110.580,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('super-admin.data-peta', ['dusun_id' => $this->dusun1->id]));
        $response->assertStatus(200);
        $response->assertSee('Fasilitas Krajan Unik');
        $response->assertDontSee('Fasilitas Banyuripan Unik');
    }

    public function test_84_category_filter_narrows_marker_dataset(): void
    {
        $katOlahraga = KategoriFasilitas::forceCreate([
            'desa_id' => $this->desa->id,
            'nama_kategori' => 'Olahraga',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Fasilitas::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Poskesdes Kesehatan',
            'deskripsi' => 'Deskripsi',
            'alamat' => 'Krajan',
            'latitude' => -7.915,
            'longitude' => 110.575,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Fasilitas::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'kategori_fasilitas_id' => $katOlahraga->id,
            'nama' => 'Lapangan Bola Olahraga',
            'deskripsi' => 'Deskripsi',
            'alamat' => 'Krajan',
            'latitude' => -7.916,
            'longitude' => 110.576,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('super-admin.data-peta', ['kategori' => 'fasilitas_'.$katOlahraga->id]));
        $response->assertStatus(200);
        $response->assertSee('Lapangan Bola Olahraga');
        $response->assertDontSee('Poskesdes Kesehatan');
    }

    public function test_85_trashed_resources_are_excluded_from_peta_markers(): void
    {
        Fasilitas::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Fasilitas Terhapus Peta',
            'deskripsi' => 'Deskripsi',
            'alamat' => 'Krajan',
            'latitude' => -7.915,
            'longitude' => 110.575,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('super-admin.data-peta'));
        $response->assertStatus(200);
        $response->assertDontSee('Fasilitas Terhapus Peta');
    }

    public function test_86_marker_popups_contain_edit_link_target(): void
    {
        $fasilitas = Fasilitas::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Fasilitas Popup Link',
            'deskripsi' => 'Deskripsi',
            'alamat' => 'Krajan',
            'latitude' => -7.915,
            'longitude' => 110.575,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('super-admin.data-peta'));
        $response->assertStatus(200);
        $expectedUrl = str_replace('/', '\/', route('super-admin.fasilitas.edit', $fasilitas->id));
        $response->assertSee($expectedUrl, false);
    }

    public function test_87_zero_crud_or_mutation_routes_exist_on_data_peta(): void
    {
        $this->assertFalse(Route::has('super-admin.data-peta.store'));
        $this->assertFalse(Route::has('super-admin.data-peta.update'));
        $this->assertFalse(Route::has('super-admin.data-peta.destroy'));
    }

    public function test_88_admin_dusun_is_blocked_from_super_admin_data_peta(): void
    {
        $adminDusun = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admindusun_peta_sec',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($adminDusun)->get(route('super-admin.data-peta'));
        $response->assertStatus(403);
    }

    public function test_89_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get(route('super-admin.data-peta'));
        $response->assertRedirect(route('admin.login'));
    }
}
