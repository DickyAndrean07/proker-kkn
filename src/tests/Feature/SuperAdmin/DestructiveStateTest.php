<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\AdminAccount;
use App\Models\AgendaKegiatan;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\Fasilitas;
use App\Models\KategoriFasilitas;
use App\Models\KontakPelayanan;
use App\Models\Pengumuman;
use App\Models\Umkm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DestructiveStateTest extends TestCase
{
    use RefreshDatabase;

    private AdminAccount $superAdmin;

    private Desa $desa;

    private Dusun $dusun;

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

        $this->kategori = KategoriFasilitas::forceCreate([
            'desa_id' => $this->desa->id,
            'nama_kategori' => 'Kesehatan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->superAdmin = AdminAccount::forceCreate([
            'dusun_id' => null,
            'username' => 'superadmin_destructive',
            'password_hash' => bcrypt('password123'),
            'role' => 'SUPER_ADMIN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_121_complete_lifecycle_of_kontak_pelayanan(): void
    {
        $file = UploadedFile::fake()->image('kontak_lifecycle.jpg');

        // 1. Create
        $this->actingAs($this->superAdmin)->post(route('super-admin.kontak.store'), [
            'dusun_id' => $this->dusun->id,
            'nama' => 'Kontak Lifecycle',
            'jabatan' => 'Kasi',
            'nomor_whatsapp' => '08123456789',
            'foto' => $file,
        ]);
        $kontak = KontakPelayanan::where('nama', 'Kontak Lifecycle')->firstOrFail();
        $fotoPath = $kontak->foto_path;
        Storage::disk('public')->assertExists($fotoPath);

        // 2. Soft Delete
        $this->actingAs($this->superAdmin)->delete(route('super-admin.kontak.destroy', $kontak->id));
        $kontak->refresh();
        $this->assertTrue($kontak->trashed());
        Storage::disk('public')->assertExists($fotoPath); // Still exists

        // 3. Restore
        $this->actingAs($this->superAdmin)->post(route('super-admin.kontak.restore', $kontak->id));
        $kontak->refresh();
        $this->assertFalse($kontak->trashed());

        // 4. Soft Delete again
        $this->actingAs($this->superAdmin)->delete(route('super-admin.kontak.destroy', $kontak->id));
        $kontak->refresh();
        $this->assertTrue($kontak->trashed());

        // 5. Hard Delete
        $this->actingAs($this->superAdmin)->delete(route('super-admin.kontak.force-delete', $kontak->id));
        $this->assertDatabaseMissing('kontak_pelayanans', ['id' => $kontak->id]);
        Storage::disk('public')->assertMissing($fotoPath); // Now removed
    }

    public function test_122_complete_lifecycle_of_umkm_with_cascading_products(): void
    {
        $file = UploadedFile::fake()->image('umkm_lifecycle.jpg');

        // 1. Create
        $this->actingAs($this->superAdmin)->post(route('super-admin.umkm.store'), [
            'dusun_id' => $this->dusun->id,
            'nama_umkm' => 'UMKM Lifecycle',
            'nama_pemilik' => 'Pak Lifecycle',
            'jenis_usaha' => 'Makanan',
            'deskripsi' => 'Enak',
            'alamat' => 'Krajan',
            'nomor_whatsapp' => '081111111',
            'jam_operasional' => '08:00 - 17:00',
            'foto_utama' => $file,
            'produk' => [
                ['nama_produk' => 'Produk A'],
            ],
        ]);
        $umkm = Umkm::where('nama_umkm', 'UMKM Lifecycle')->firstOrFail();
        $prodId = $umkm->produkUmkms->first()->id;
        $fotoPath = $umkm->foto_utama_path;
        Storage::disk('public')->assertExists($fotoPath);

        // 2. Soft Delete
        $this->actingAs($this->superAdmin)->delete(route('super-admin.umkm.destroy', $umkm->id));
        $umkm->refresh();
        $this->assertTrue($umkm->trashed());
        $this->assertDatabaseHas('produk_umkms', ['id' => $prodId]);
        Storage::disk('public')->assertExists($fotoPath);

        // 3. Restore
        $this->actingAs($this->superAdmin)->post(route('super-admin.umkm.restore', $umkm->id));
        $umkm->refresh();
        $this->assertFalse($umkm->trashed());

        // 4. Soft Delete again
        $this->actingAs($this->superAdmin)->delete(route('super-admin.umkm.destroy', $umkm->id));

        // 5. Hard Delete
        $this->actingAs($this->superAdmin)->delete(route('super-admin.umkm.force-delete', $umkm->id));
        $this->assertDatabaseMissing('umkms', ['id' => $umkm->id]);
        $this->assertDatabaseMissing('produk_umkms', ['id' => $prodId]);
        Storage::disk('public')->assertMissing($fotoPath);
    }

    public function test_123_complete_lifecycle_of_fasilitas(): void
    {
        $file = UploadedFile::fake()->image('fasilitas_lifecycle.jpg');

        // 1. Create
        $this->actingAs($this->superAdmin)->post(route('super-admin.fasilitas.store'), [
            'dusun_id' => $this->dusun->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Fasilitas Lifecycle',
            'deskripsi' => 'Deskripsi',
            'alamat' => 'Krajan',
            'foto' => $file,
            'latitude' => -7.915,
            'longitude' => 110.575,
        ]);
        $fasilitas = Fasilitas::where('nama', 'Fasilitas Lifecycle')->firstOrFail();
        $fotoPath = $fasilitas->foto_path;
        Storage::disk('public')->assertExists($fotoPath);

        // 2. Soft Delete
        $this->actingAs($this->superAdmin)->delete(route('super-admin.fasilitas.destroy', $fasilitas->id));
        $fasilitas->refresh();
        $this->assertTrue($fasilitas->trashed());

        // 3. Restore
        $this->actingAs($this->superAdmin)->post(route('super-admin.fasilitas.restore', $fasilitas->id));
        $fasilitas->refresh();
        $this->assertFalse($fasilitas->trashed());

        // 4. Soft Delete again
        $this->actingAs($this->superAdmin)->delete(route('super-admin.fasilitas.destroy', $fasilitas->id));

        // 5. Hard Delete
        $this->actingAs($this->superAdmin)->delete(route('super-admin.fasilitas.force-delete', $fasilitas->id));
        $this->assertDatabaseMissing('fasilitas', ['id' => $fasilitas->id]);
        Storage::disk('public')->assertMissing($fotoPath);
    }

    public function test_124_complete_lifecycle_of_agenda_with_cascade_media(): void
    {
        $file = UploadedFile::fake()->image('agenda_lifecycle.jpg');

        // 1. Create
        $this->actingAs($this->superAdmin)->post(route('super-admin.agenda.store'), [
            'scope_level' => 'DESA',
            'judul' => 'Agenda Lifecycle',
            'deskripsi_singkat' => 'Deskripsi',
            'tanggal_mulai' => '2026-08-20',
            'lokasi_text' => 'Balai Desa',
            'media' => [
                ['file' => $file, 'role' => 'POSTER_AWAL'],
            ],
        ]);
        $agenda = AgendaKegiatan::where('judul', 'Agenda Lifecycle')->firstOrFail();
        $media = $agenda->agendaMedias->first();
        $mediaPath = $media->media_path;
        Storage::disk('public')->assertExists($mediaPath);

        // 2. Soft Delete
        $this->actingAs($this->superAdmin)->delete(route('super-admin.agenda.destroy', $agenda->id));
        $agenda->refresh();
        $this->assertTrue($agenda->trashed());
        $this->assertDatabaseHas('agenda_medias', ['id' => $media->id]);
        Storage::disk('public')->assertExists($mediaPath);

        // 3. Restore
        $this->actingAs($this->superAdmin)->post(route('super-admin.agenda.restore', $agenda->id));
        $agenda->refresh();
        $this->assertFalse($agenda->trashed());

        // 4. Soft Delete again
        $this->actingAs($this->superAdmin)->delete(route('super-admin.agenda.destroy', $agenda->id));

        // 5. Hard Delete
        $this->actingAs($this->superAdmin)->delete(route('super-admin.agenda.force-delete', $agenda->id));
        $this->assertDatabaseMissing('agenda_kegiatans', ['id' => $agenda->id]);
        $this->assertDatabaseMissing('agenda_medias', ['id' => $media->id]);
        Storage::disk('public')->assertMissing($mediaPath);
    }

    public function test_125_complete_lifecycle_of_pengumuman(): void
    {
        // 1. Create
        $this->actingAs($this->superAdmin)->post(route('super-admin.pengumuman.store'), [
            'scope_level' => 'DESA',
            'judul' => 'Pengumuman Lifecycle',
            'isi' => 'Isi',
            'tanggal_kedaluwarsa' => '2026-09-01',
        ]);
        $pengumuman = Pengumuman::where('judul', 'Pengumuman Lifecycle')->firstOrFail();

        // 2. Soft Delete
        $this->actingAs($this->superAdmin)->delete(route('super-admin.pengumuman.destroy', $pengumuman->id));
        $pengumuman->refresh();
        $this->assertTrue($pengumuman->trashed());

        // 3. Restore
        $this->actingAs($this->superAdmin)->post(route('super-admin.pengumuman.restore', $pengumuman->id));
        $pengumuman->refresh();
        $this->assertFalse($pengumuman->trashed());

        // 4. Soft Delete again
        $this->actingAs($this->superAdmin)->delete(route('super-admin.pengumuman.destroy', $pengumuman->id));

        // 5. Hard Delete
        $this->actingAs($this->superAdmin)->delete(route('super-admin.pengumuman.force-delete', $pengumuman->id));
        $this->assertDatabaseMissing('pengumumans', ['id' => $pengumuman->id]);
    }

    public function test_126_hard_delete_on_already_purged_id_returns_404(): void
    {
        $response = $this->actingAs($this->superAdmin)->delete(route('super-admin.kontak.force-delete', 999999));
        $response->assertStatus(404);
    }
}
