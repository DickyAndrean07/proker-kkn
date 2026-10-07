<?php

namespace Tests\Feature\Admin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\KontakPelayanan;
use App\Models\Umkm;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaUploadTest extends TestCase
{
    use RefreshDatabase;

    private Desa $desa;

    private Dusun $dusunA;

    private AdminAccount $adminA;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

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

        $this->adminA = AdminAccount::query()->forceCreate([
            'dusun_id' => $this->dusunA->id,
            'username' => 'admin_krajan',
            'password_hash' => Hash::make('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);
    }

    /**
     * 63. Valid image accepted.
     */
    public function test_valid_image_accepted(): void
    {
        $image = UploadedFile::fake()->image('banner.jpg', 800, 600);

        $response = $this->actingAs($this->adminA)->put('/admin-dusun/profil', [
            'nama_dusun' => 'Dusun Krajan',
            'deskripsi_singkat' => 'Deskripsi.',
            'nama_kepala_dusun' => 'Kepala Krajan',
            'jumlah_rt' => 4,
            'jumlah_rw' => 2,
            'banner' => $image,
        ]);

        $response->assertRedirect('/admin-dusun/profil');
        $this->dusunA->refresh();
        $this->assertNotNull($this->dusunA->banner_path);
        $this->assertTrue(Storage::disk('public')->exists($this->dusunA->banner_path));
    }

    /**
     * 64. Invalid MIME/signature rejected.
     */
    public function test_invalid_mime_rejected(): void
    {
        $fakePdf = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->adminA)->put('/admin-dusun/profil', [
            'nama_dusun' => 'Dusun Krajan',
            'deskripsi_singkat' => 'Deskripsi.',
            'nama_kepala_dusun' => 'Kepala Krajan',
            'jumlah_rt' => 4,
            'jumlah_rw' => 2,
            'banner' => $fakePdf,
        ]);

        $response->assertSessionHasErrors(['banner']);
    }

    /**
     * 65. Oversized image rejected (> 3072 KB).
     */
    public function test_oversized_image_rejected(): void
    {
        $oversized = UploadedFile::fake()->create('large.jpg', 4000, 'image/jpeg');

        $response = $this->actingAs($this->adminA)->put('/admin-dusun/profil', [
            'nama_dusun' => 'Dusun Krajan',
            'deskripsi_singkat' => 'Deskripsi.',
            'nama_kepala_dusun' => 'Kepala Krajan',
            'jumlah_rt' => 4,
            'jumlah_rw' => 2,
            'banner' => $oversized,
        ]);

        $response->assertSessionHasErrors(['banner']);
    }

    /**
     * 66. Stored DB reference is storage-relative.
     */
    public function test_stored_db_reference_is_storage_relative(): void
    {
        $image = UploadedFile::fake()->image('foto_kontak.png', 400, 400);

        $response = $this->actingAs($this->adminA)->post('/admin-dusun/kontak', [
            'nama' => 'Petugas Foto',
            'jabatan' => 'Staff',
            'nomor_whatsapp' => '081234567890',
            'foto' => $image,
        ]);

        $response->assertRedirect('/admin-dusun/kontak');
        $kontak = KontakPelayanan::where('nama', 'Petugas Foto')->first();
        $this->assertNotNull($kontak);
        $this->assertStringStartsWith('kontak/', $kontak->foto_path);
        $this->assertStringNotContainsString('C:\\', $kontak->foto_path);
    }

    /**
     * 67. No BLOB stored in database.
     */
    public function test_no_blob_stored_in_database(): void
    {
        $image = UploadedFile::fake()->image('umkm.webp', 500, 500);

        $response = $this->actingAs($this->adminA)->post('/admin-dusun/umkm', [
            'nama_umkm' => 'UMKM WebP',
            'nama_pemilik' => 'Pemilik',
            'jenis_usaha' => 'Usaha',
            'deskripsi' => 'Deskripsi.',
            'alamat' => 'Alamat.',
            'nomor_whatsapp' => '081234567890',
            'jam_operasional' => '08.00 - 17.00',
            'foto_utama' => $image,
        ]);

        $umkm = Umkm::where('nama_umkm', 'UMKM WebP')->first();
        $this->assertIsString($umkm->foto_utama_path);
        $this->assertLessThan(255, strlen($umkm->foto_utama_path)); // Stored path is string path, not binary
    }

    /**
     * 68. Optional media may be absent.
     */
    public function test_optional_media_may_be_absent(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/kontak', [
            'nama' => 'Petugas Tanpa Foto',
            'jabatan' => 'Staff',
            'nomor_whatsapp' => '081234567890',
            'foto' => null,
        ]);

        $response->assertRedirect('/admin-dusun/kontak');
        $kontak = KontakPelayanan::where('nama', 'Petugas Tanpa Foto')->first();
        $this->assertNull($kontak->foto_path);
    }

    /**
     * 69. Replacement keeps parent consistent and deletes old file.
     */
    public function test_media_replacement_deletes_old_file(): void
    {
        $image1 = UploadedFile::fake()->image('foto1.jpg', 400, 400);

        $this->actingAs($this->adminA)->put('/admin-dusun/profil', [
            'nama_dusun' => 'Dusun Krajan',
            'deskripsi_singkat' => 'Deskripsi.',
            'nama_kepala_dusun' => 'Kepala Krajan',
            'jumlah_rt' => 4,
            'jumlah_rw' => 2,
            'banner' => $image1,
        ]);

        $this->dusunA->refresh();
        $firstPath = $this->dusunA->banner_path;
        $this->assertTrue(Storage::disk('public')->exists($firstPath));

        // Now replace with image2
        $image2 = UploadedFile::fake()->image('foto2.png', 500, 500);

        $this->actingAs($this->adminA)->put('/admin-dusun/profil', [
            'nama_dusun' => 'Dusun Krajan',
            'deskripsi_singkat' => 'Deskripsi.',
            'nama_kepala_dusun' => 'Kepala Krajan',
            'jumlah_rt' => 4,
            'jumlah_rw' => 2,
            'banner' => $image2,
        ]);

        $this->dusunA->refresh();
        $secondPath = $this->dusunA->banner_path;
        $this->assertNotSame($firstPath, $secondPath);
        $this->assertTrue(Storage::disk('public')->exists($secondPath));
        $this->assertFalse(Storage::disk('public')->exists($firstPath)); // Old file removed
    }

    /**
     * 70. Failed processing does not persist broken reference.
     */
    public function test_failed_processing_does_not_persist_broken_reference(): void
    {
        $originalBanner = $this->dusunA->banner_path;

        $fakeBrokenFile = UploadedFile::fake()->createWithContent('corrupt.jpg', 'invalid byte stream');

        $response = $this->actingAs($this->adminA)->put('/admin-dusun/profil', [
            'nama_dusun' => 'Dusun Krajan',
            'deskripsi_singkat' => 'Deskripsi.',
            'nama_kepala_dusun' => 'Kepala Krajan',
            'jumlah_rt' => 4,
            'jumlah_rw' => 2,
            'banner' => $fakeBrokenFile,
        ]);

        $this->dusunA->refresh();
        $this->assertSame($originalBanner, $this->dusunA->banner_path);
    }

    /**
     * 71. Soft Delete parent retains media.
     */
    public function test_soft_delete_parent_retains_media(): void
    {
        $kontak = KontakPelayanan::query()->forceCreate([
            'dusun_id' => $this->dusunA->id,
            'nama' => 'Kontak Berfoto',
            'jabatan' => 'Staff',
            'nomor_whatsapp' => '081234567890',
            'foto_path' => 'kontak/berfoto.webp',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        Storage::disk('public')->put('kontak/berfoto.webp', 'image content');

        $this->actingAs($this->adminA)->delete('/admin-dusun/kontak/'.$kontak->id);

        $kontak->refresh();
        $this->assertNotNull($kontak->deleted_at);
        $this->assertTrue(Storage::disk('public')->exists('kontak/berfoto.webp'));
    }

    /**
     * 72. Public eligible parent renders processed media.
     */
    public function test_public_eligible_parent_renders_processed_media(): void
    {
        $umkm = Umkm::query()->forceCreate([
            'dusun_id' => $this->dusunA->id,
            'nama_umkm' => 'UMKM Tampil Publik',
            'nama_pemilik' => 'Pemilik',
            'jenis_usaha' => 'Kuliner',
            'deskripsi' => 'Deskripsi.',
            'alamat' => 'Alamat.',
            'nomor_whatsapp' => '081234567890',
            'jam_operasional' => '08.00 - 17.00',
            'foto_utama_path' => 'umkm/foto_tampil.webp',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        Storage::disk('public')->put('umkm/foto_tampil.webp', 'image content');

        $response = $this->get('/umkm/'.$umkm->id);
        $response->assertOk();
        $response->assertSee('storage/umkm/foto_tampil.webp');
    }
}
