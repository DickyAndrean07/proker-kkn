<?php

namespace Tests\Feature\Admin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\Fasilitas;
use App\Models\KategoriFasilitas;
use App\Models\KontakPelayanan;
use App\Models\Pengumuman;
use App\Models\Umkm;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CoordinatePickerSecurityTest extends TestCase
{
    use RefreshDatabase;

    private Desa $desa;

    private Dusun $dusunA;

    private Dusun $dusunB;

    private AdminAccount $adminA;

    private KategoriFasilitas $kategori;

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

        $this->dusunA = Dusun::query()->forceCreate([
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

        $this->dusunB = Dusun::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Seberang',
            'status_dusun' => 'ACTIVE',
            'deskripsi_singkat' => 'Deskripsi Seberang.',
            'nama_kepala_dusun' => 'Kepala Seberang',
            'jumlah_rt' => 3,
            'jumlah_rw' => 1,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->adminA = AdminAccount::query()->forceCreate([
            'dusun_id' => $this->dusunA->id,
            'username' => 'admin_krajan',
            'password_hash' => Hash::make('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->kategori = KategoriFasilitas::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'nama_kategori' => 'Kesehatan',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);
    }

    /**
     * 73. Manual coordinates persist.
     */
    public function test_manual_coordinates_persist(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/fasilitas', [
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Puskesmas Pembantu',
            'deskripsi' => 'Deskripsi.',
            'alamat' => 'Alamat.',
            'latitude' => '-7.629800',
            'longitude' => '110.860300',
        ]);

        $response->assertRedirect('/admin-dusun/fasilitas');
        $this->assertDatabaseHas('fasilitas', [
            'nama' => 'Puskesmas Pembantu',
            'latitude' => '-7.629800',
            'longitude' => '110.860300',
        ]);
    }

    /**
     * 74. Map-selected coordinates submit same validated fields.
     */
    public function test_map_selected_coordinates_submit_same_validated_fields(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/kontak', [
            'nama' => 'Petugas Peta',
            'jabatan' => 'Staff',
            'nomor_whatsapp' => '081234567890',
            'latitude' => '-7.500000',
            'longitude' => '110.500000',
        ]);

        $response->assertRedirect('/admin-dusun/kontak');
        $this->assertDatabaseHas('kontak_pelayanans', [
            'nama' => 'Petugas Peta',
            'latitude' => '-7.500000',
            'longitude' => '110.500000',
        ]);
    }

    /**
     * 75. No search/geocoder UI in create form.
     */
    public function test_no_search_or_geocoder_ui_in_coordinate_picker(): void
    {
        $response = $this->actingAs($this->adminA)->get('/admin-dusun/fasilitas/create');

        $response->assertOk();
        $response->assertDontSee('leaflet-control-geocoder');
        $response->assertDontSee('Cari Lokasi');
        $response->assertDontSee('Search place');
    }

    /**
     * 76. No polygon UI.
     */
    public function test_no_polygon_ui_in_coordinate_picker(): void
    {
        $response = $this->actingAs($this->adminA)->get('/admin-dusun/fasilitas/create');

        $response->assertOk();
        $response->assertDontSee('leaflet-draw');
        $response->assertDontSee('polygon');
    }

    /**
     * 77. Admin cannot use coordinate payload to alter ownership.
     */
    public function test_admin_cannot_use_coordinate_payload_to_alter_ownership(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/fasilitas', [
            'dusun_id' => $this->dusunB->id, // payload injection
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Fasilitas Krajan Peta',
            'deskripsi' => 'Deskripsi.',
            'alamat' => 'Alamat.',
            'latitude' => '-7.100000',
            'longitude' => '110.100000',
        ]);

        $response->assertRedirect('/admin-dusun/fasilitas');

        $fasilitas = Fasilitas::where('nama', 'Fasilitas Krajan Peta')->first();
        $this->assertSame($this->dusunA->id, $fasilitas->dusun_id);
    }

    /**
     * 81. Modified foreign ID cannot update.
     */
    public function test_modified_foreign_id_cannot_update(): void
    {
        $foreignKontak = KontakPelayanan::query()->forceCreate([
            'dusun_id' => $this->dusunB->id,
            'nama' => 'Kontak Asing',
            'jabatan' => 'Staff',
            'nomor_whatsapp' => '081234567890',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->actingAs($this->adminA)->put('/admin-dusun/kontak/'.$foreignKontak->id, [
            'nama' => 'Kontak Diubah Asing',
            'jabatan' => 'Staff',
            'nomor_whatsapp' => '081234567890',
        ]);

        $response->assertNotFound();
    }

    /**
     * 82. Modified foreign ID cannot Soft Delete.
     */
    public function test_modified_foreign_id_cannot_soft_delete(): void
    {
        $foreignFasilitas = Fasilitas::query()->forceCreate([
            'dusun_id' => $this->dusunB->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Fasilitas Asing',
            'deskripsi' => 'Deskripsi.',
            'alamat' => 'Alamat.',
            'latitude' => '-7.123456',
            'longitude' => '110.123456',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->actingAs($this->adminA)->delete('/admin-dusun/fasilitas/'.$foreignFasilitas->id);

        $response->assertNotFound();
        $foreignFasilitas->refresh();
        $this->assertNull($foreignFasilitas->deleted_at);
    }

    /**
     * 83. Client-supplied dusun_id is not authority.
     */
    public function test_client_supplied_dusun_id_is_never_authority(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/umkm', [
            'dusun_id' => $this->dusunB->id,
            'nama_umkm' => 'Usaha Injeksi Dusun',
            'nama_pemilik' => 'Pemilik',
            'jenis_usaha' => 'Usaha',
            'deskripsi' => 'Deskripsi.',
            'alamat' => 'Alamat.',
            'nomor_whatsapp' => '081234567890',
            'jam_operasional' => '08.00 - 17.00',
        ]);

        $response->assertRedirect('/admin-dusun/umkm');

        $umkm = Umkm::where('nama_umkm', 'Usaha Injeksi Dusun')->first();
        $this->assertSame($this->dusunA->id, $umkm->dusun_id);
    }

    /**
     * 84. Client-supplied scope_level cannot escalate Agenda/Pengumuman.
     */
    public function test_client_supplied_scope_level_cannot_escalate_pengumuman(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/pengumuman', [
            'scope_level' => 'DESA',
            'judul' => 'Pengumuman Eskalasi',
            'isi' => 'Isi.',
            'tanggal_kedaluwarsa' => CarbonImmutable::now()->addDays(3)->toDateString(),
        ]);

        $response->assertRedirect('/admin-dusun/pengumuman');

        $pengumuman = Pengumuman::where('judul', 'Pengumuman Eskalasi')->first();
        $this->assertSame('DUSUN', $pengumuman->scope_level);
        $this->assertSame($this->dusunA->id, $pengumuman->dusun_id);
    }

    /**
     * 85. Validation failure exposes no SQL/constraint names.
     */
    public function test_validation_failure_exposes_no_sql_or_constraint_names(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/kontak', [
            'nama' => '',
            'jabatan' => '',
            'nomor_whatsapp' => '',
        ]);

        $content = $response->getContent();
        $this->assertStringNotContainsString('SQLSTATE', $content);
        $this->assertStringNotContainsString('chk_kontak_pelayanans', $content);
        $this->assertStringNotContainsString('fk_kontak_pelayanans', $content);
    }

    /**
     * 86. Upload filename cannot create path traversal.
     */
    public function test_upload_filename_cannot_create_path_traversal(): void
    {
        $maliciousFile = UploadedFile::fake()->image('../../etc/passwd.jpg', 200, 200);

        $response = $this->actingAs($this->adminA)->post('/admin-dusun/kontak', [
            'nama' => 'Petugas Traversal',
            'jabatan' => 'Staff',
            'nomor_whatsapp' => '081234567890',
            'foto' => $maliciousFile,
        ]);

        $response->assertRedirect('/admin-dusun/kontak');

        $kontak = KontakPelayanan::where('nama', 'Petugas Traversal')->first();
        $this->assertNotNull($kontak);
        $this->assertStringStartsWith('kontak/', $kontak->foto_path);
        $this->assertStringNotContainsString('..', $kontak->foto_path);
        $this->assertStringNotContainsString('passwd', $kontak->foto_path);
    }

    /**
     * 87. Stored user text remains Blade-escaped.
     */
    public function test_stored_user_text_remains_blade_escaped(): void
    {
        $kontak = KontakPelayanan::query()->forceCreate([
            'dusun_id' => $this->dusunA->id,
            'nama' => '<script>alert("XSS")</script>',
            'jabatan' => '<b>Kepala</b>',
            'nomor_whatsapp' => '081234567890',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->actingAs($this->adminA)->get('/admin-dusun/kontak');
        $response->assertOk();
        $response->assertDontSee('<script>alert("XSS")</script>', false);
        $response->assertSee('&lt;script&gt;alert(&quot;XSS&quot;)&lt;/script&gt;', false);
    }
}
