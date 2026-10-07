<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\Pengumuman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalPengumumanTest extends TestCase
{
    use RefreshDatabase;

    private AdminAccount $superAdmin;

    private Desa $desa;

    private Dusun $dusun1;

    private Dusun $dusun2;

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

        $this->superAdmin = AdminAccount::forceCreate([
            'dusun_id' => null,
            'username' => 'superadmin_pengumuman',
            'password_hash' => bcrypt('password123'),
            'role' => 'SUPER_ADMIN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_68_super_admin_can_view_global_pengumuman_list_with_filters(): void
    {
        Pengumuman::forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Pengumuman Desa PBB',
            'isi' => 'Isi pengumuman pajak',
            'tanggal_kedaluwarsa' => now()->addDays(10)->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Pengumuman::forceCreate([
            'desa_id' => $this->desa->id,
            'dusun_id' => $this->dusun1->id,
            'scope_level' => 'DUSUN',
            'judul' => 'Pengumuman Dusun Krajan Posyandu',
            'isi' => 'Isi pengumuman posyandu',
            'tanggal_kedaluwarsa' => now()->addDays(5)->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $responseDesa = $this->actingAs($this->superAdmin)->get(route('super-admin.pengumuman.index', ['scope_level' => 'DESA']));
        $responseDesa->assertStatus(200);
        $responseDesa->assertSee('Pengumuman Desa PBB');
        $responseDesa->assertDontSee('Pengumuman Dusun Krajan Posyandu');
    }

    public function test_69_super_admin_can_create_desa_scoped_pengumuman(): void
    {
        $payload = [
            'scope_level' => 'DESA',
            'judul' => 'Himbauan Kebersihan Lingkungan Desa',
            'isi' => 'Seluruh warga diharapkan menjaga kebersihan.',
            'tanggal_kedaluwarsa' => now()->addMonths(1)->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.pengumuman.store'), $payload);

        $response->assertRedirect(route('super-admin.pengumuman.index'));
        $this->assertDatabaseHas('pengumumans', [
            'scope_level' => 'DESA',
            'dusun_id' => null,
            'judul' => 'Himbauan Kebersihan Lingkungan Desa',
        ]);
    }

    public function test_70_super_admin_can_create_dusun_scoped_pengumuman(): void
    {
        $payload = [
            'scope_level' => 'DUSUN',
            'dusun_id' => $this->dusun1->id,
            'judul' => 'Jadwal Ronda Malam Krajan',
            'isi' => 'Jadwal ronda malam warga dusun krajan.',
            'tanggal_kedaluwarsa' => now()->addMonths(1)->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.pengumuman.store'), $payload);

        $response->assertRedirect(route('super-admin.pengumuman.index'));
        $this->assertDatabaseHas('pengumumans', [
            'scope_level' => 'DUSUN',
            'dusun_id' => $this->dusun1->id,
            'judul' => 'Jadwal Ronda Malam Krajan',
        ]);
    }

    public function test_71_inconsistent_scope_and_dusun_id_is_rejected(): void
    {
        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.pengumuman.store'), [
            'scope_level' => 'DESA',
            'dusun_id' => $this->dusun1->id,
            'judul' => 'Invalid Pengumuman',
            'isi' => 'Isi',
            'tanggal_kedaluwarsa' => now()->addDays(5)->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors(['dusun_id']);
    }

    public function test_72_super_admin_can_update_pengumuman(): void
    {
        $pengumuman = Pengumuman::forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Pengumuman Awal',
            'isi' => 'Isi awal',
            'tanggal_kedaluwarsa' => now()->addDays(10)->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $payload = [
            'scope_level' => 'DUSUN',
            'dusun_id' => $this->dusun2->id,
            'judul' => 'Pengumuman Diperbarui',
            'isi' => 'Isi baru',
            'tanggal_kedaluwarsa' => now()->addDays(20)->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->superAdmin)->put(route('super-admin.pengumuman.update', $pengumuman->id), $payload);

        $response->assertRedirect(route('super-admin.pengumuman.index'));
        $pengumuman->refresh();
        $this->assertEquals('DUSUN', $pengumuman->scope_level);
        $this->assertEquals($this->dusun2->id, $pengumuman->dusun_id);
    }

    public function test_73_super_admin_can_soft_delete_pengumuman(): void
    {
        $pengumuman = Pengumuman::forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Pengumuman Soft Delete',
            'isi' => 'Isi',
            'tanggal_kedaluwarsa' => now()->addDays(10)->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->delete(route('super-admin.pengumuman.destroy', $pengumuman->id));

        $response->assertRedirect(route('super-admin.pengumuman.index'));
        $pengumuman->refresh();
        $this->assertTrue($pengumuman->trashed());
    }

    public function test_74_super_admin_can_restore_soft_deleted_pengumuman(): void
    {
        $pengumuman = Pengumuman::forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Pengumuman Dipulihkan',
            'isi' => 'Isi',
            'tanggal_kedaluwarsa' => now()->addDays(10)->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.pengumuman.restore', $pengumuman->id));

        $response->assertRedirect(route('super-admin.pengumuman.index', ['status' => 'trashed']));
        $pengumuman->refresh();
        $this->assertFalse($pengumuman->trashed());
    }

    public function test_75_super_admin_can_hard_delete_pengumuman(): void
    {
        $pengumuman = Pengumuman::forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Pengumuman Hard Delete',
            'isi' => 'Isi',
            'tanggal_kedaluwarsa' => now()->addDays(10)->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->delete(route('super-admin.pengumuman.force-delete', $pengumuman->id));

        $response->assertRedirect(route('super-admin.pengumuman.index', ['status' => 'trashed']));
        $this->assertDatabaseMissing('pengumumans', ['id' => $pengumuman->id]);
    }

    public function test_76_attempting_to_hard_delete_active_pengumuman_returns_404(): void
    {
        $pengumuman = Pengumuman::forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Pengumuman Aktif',
            'isi' => 'Isi',
            'tanggal_kedaluwarsa' => now()->addDays(10)->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->delete(route('super-admin.pengumuman.force-delete', $pengumuman->id));
        $response->assertStatus(404);
    }

    public function test_77_expiry_date_determines_active_vs_archive_state(): void
    {
        $activePengumuman = Pengumuman::forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Pengumuman Masih Aktif',
            'isi' => 'Isi',
            'tanggal_kedaluwarsa' => now()->addDays(10)->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $expiredPengumuman = Pengumuman::forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Pengumuman Sudah Kedaluwarsa',
            'isi' => 'Isi',
            'tanggal_kedaluwarsa' => now()->subDays(2)->format('Y-m-d'),
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(10),
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('super-admin.pengumuman.index'));
        $response->assertStatus(200);
        $response->assertSee('Aktif Publik');
        $response->assertSee('Arsip (Kedaluwarsa)');
    }

    public function test_78_admin_dusun_is_blocked_from_super_admin_pengumuman_routes(): void
    {
        $adminDusun = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admindusun_peng_sec',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $responseIndex = $this->actingAs($adminDusun)->get(route('super-admin.pengumuman.index'));
        $responseIndex->assertStatus(403);
    }
}
