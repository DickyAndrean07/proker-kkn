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
use Tests\TestCase;

class PublicRegressionTest extends TestCase
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
            'username' => 'superadmin_regress',
            'password_hash' => bcrypt('password123'),
            'role' => 'SUPER_ADMIN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_127_deactivated_dusun_is_hidden_from_public_homepage(): void
    {
        // Deactivate dusun 2 via Super Admin action
        $this->actingAs($this->superAdmin)->post(route('super-admin.dusun.deactivate', $this->dusun2->id));

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Dusun Krajan');
        $response->assertDontSee('Dusun Banyuripan');
    }

    public function test_128_deactivated_dusun_detail_returns_404_for_public(): void
    {
        $this->actingAs($this->superAdmin)->post(route('super-admin.dusun.deactivate', $this->dusun2->id));

        $response = $this->get(route('dusun.show', $this->dusun2->id));
        $response->assertStatus(404);
    }

    public function test_129_restored_resource_becomes_visible_on_public_page(): void
    {
        $kontak = KontakPelayanan::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'nama' => 'Pak Kadus Dipulihkan',
            'jabatan' => 'Kadus',
            'nomor_whatsapp' => '08123456789',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => now(),
        ]);

        // Initially not visible
        $response1 = $this->get(route('dusun.show', $this->dusun1->id));
        $response1->assertDontSee('Pak Kadus Dipulihkan');

        // Restore via Super Admin
        $this->actingAs($this->superAdmin)->post(route('super-admin.kontak.restore', $kontak->id));

        // Now visible
        $response2 = $this->get(route('dusun.show', $this->dusun1->id));
        $response2->assertSee('Pak Kadus Dipulihkan');
    }

    public function test_130_hard_deleted_resource_is_not_found_on_public_pages(): void
    {
        $umkm = Umkm::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'nama_umkm' => 'UMKM Dihapus Permanen',
            'nama_pemilik' => 'Pak Purge',
            'jenis_usaha' => 'Makanan',
            'deskripsi' => 'Deskripsi',
            'alamat' => 'Krajan',
            'nomor_whatsapp' => '081111111',
            'jam_operasional' => '08:00 - 17:00',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => now(),
        ]);

        $this->actingAs($this->superAdmin)->delete(route('super-admin.umkm.force-delete', $umkm->id));

        $response = $this->get(route('umkm.show', $umkm->id));
        $response->assertStatus(404);
    }

    public function test_131_desa_scoped_agenda_and_pengumuman_appear_on_public_homepage(): void
    {
        AgendaKegiatan::forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Pesta Rakyat Desa Bendung',
            'deskripsi_singkat' => 'Pesta rakyat tahunan.',
            'tanggal_mulai' => now()->addDays(5)->format('Y-m-d'),
            'lokasi_text' => 'Alun-alun Desa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Pengumuman::forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Pengumuman Resmi Lurah',
            'isi' => 'Isi pengumuman resmi.',
            'tanggal_kedaluwarsa' => now()->addMonths(1)->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Pesta Rakyat Desa Bendung');
        $response->assertSee('Pengumuman Resmi Lurah');
        $response->assertSee('<span class="dock-badge" aria-label="1 agenda">1</span>', false);
        $response->assertSee('<span class="dock-badge" aria-label="1 pengumuman">1</span>', false);
    }

    public function test_132_public_map_displays_newly_created_fasilitas_and_umkm_accurately(): void
    {
        Fasilitas::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'kategori_fasilitas_id' => $this->kategori->id,
            'nama' => 'Puskesdes Krajan Spasial',
            'deskripsi' => 'Deskripsi',
            'alamat' => 'Krajan',
            'latitude' => -7.915555,
            'longitude' => 110.575555,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Puskesdes Krajan Spasial');
    }
}
