<?php

namespace Tests\Feature\Admin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\Pengumuman;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PengumumanTest extends TestCase
{
    use RefreshDatabase;

    private Desa $desa;

    private Dusun $dusunA;

    private Dusun $dusunB;

    private AdminAccount $adminA;

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
    }

    /**
     * 54. Admin creates DUSUN announcement.
     */
    public function test_admin_creates_dusun_announcement(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/pengumuman', [
            'judul' => 'Pengumuman Kerja Bakti RT',
            'isi' => 'Diharapkan seluruh warga membawa cangkul.',
            'tanggal_kedaluwarsa' => CarbonImmutable::now()->addDays(7)->toDateString(),
        ]);

        $response->assertRedirect('/admin-dusun/pengumuman');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pengumumans', [
            'dusun_id' => $this->dusunA->id,
            'desa_id' => $this->desa->id,
            'scope_level' => 'DUSUN',
            'judul' => 'Pengumuman Kerja Bakti RT',
        ]);
    }

    /**
     * 55. DESA scope injection denied/ignored safely.
     */
    public function test_desa_scope_injection_ignored_safely(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/pengumuman', [
            'scope_level' => 'DESA', // Injection attempt
            'judul' => 'Pengumuman Desa Palsu',
            'isi' => 'Isi palsu.',
            'tanggal_kedaluwarsa' => CarbonImmutable::now()->addDays(7)->toDateString(),
        ]);

        $response->assertRedirect('/admin-dusun/pengumuman');

        $pengumuman = Pengumuman::where('judul', 'Pengumuman Desa Palsu')->first();
        $this->assertNotNull($pengumuman);
        $this->assertSame('DUSUN', $pengumuman->scope_level);
        $this->assertSame($this->dusunA->id, $pengumuman->dusun_id);
    }

    /**
     * 56. Foreign Dusun injection denied.
     */
    public function test_foreign_dusun_injection_denied(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/pengumuman', [
            'dusun_id' => $this->dusunB->id,
            'judul' => 'Pengumuman Injeksi Dusun Lain',
            'isi' => 'Isi.',
            'tanggal_kedaluwarsa' => CarbonImmutable::now()->addDays(7)->toDateString(),
        ]);

        $response->assertRedirect('/admin-dusun/pengumuman');

        $pengumuman = Pengumuman::where('judul', 'Pengumuman Injeksi Dusun Lain')->first();
        $this->assertNotNull($pengumuman);
        $this->assertSame($this->dusunA->id, $pengumuman->dusun_id);
    }

    /**
     * 57. Expiry boundary uses Asia/Jakarta.
     */
    public function test_expiry_boundary_uses_asia_jakarta(): void
    {
        config()->set('app.business_timezone', 'Asia/Jakarta');

        $pengumuman = (new Pengumuman)->forceFill([
            'tanggal_kedaluwarsa' => '2026-08-20',
        ]);

        $this->assertFalse($pengumuman->isArchivedFor(CarbonImmutable::parse('2026-08-20 23:59:59', 'Asia/Jakarta')));
        $this->assertTrue($pengumuman->isArchivedFor(CarbonImmutable::parse('2026-08-21 00:00:00', 'Asia/Jakarta')));
    }

    /**
     * 58. Active/Archive label derived.
     */
    public function test_active_archive_label_derived(): void
    {
        $activePengumuman = Pengumuman::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'dusun_id' => $this->dusunA->id,
            'scope_level' => 'DUSUN',
            'judul' => 'Pengumuman Masih Aktif',
            'isi' => 'Isi aktif.',
            'tanggal_kedaluwarsa' => CarbonImmutable::now('Asia/Jakarta')->addDays(3)->toDateString(),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $archivedPengumuman = Pengumuman::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'dusun_id' => $this->dusunA->id,
            'scope_level' => 'DUSUN',
            'judul' => 'Pengumuman Sudah Usang',
            'isi' => 'Isi usang.',
            'tanggal_kedaluwarsa' => CarbonImmutable::now('Asia/Jakarta')->subDays(2)->toDateString(),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->actingAs($this->adminA)->get('/admin-dusun/pengumuman');

        $response->assertOk();
        $response->assertSee('Pengumuman Masih Aktif');
        $response->assertSee('Aktif Publik');
        $response->assertSee('Pengumuman Sudah Usang');
        $response->assertSee('Kedaluwarsa (Arsip)');
    }

    /**
     * 59. No Archive mutation action.
     */
    public function test_no_archive_mutation_action(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/pengumuman/1/archive');
        $response->assertNotFound();
    }

    /**
     * 60. Soft Delete independent from Archive.
     */
    public function test_soft_delete_independent_from_archive(): void
    {
        $pengumuman = Pengumuman::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'dusun_id' => $this->dusunA->id,
            'scope_level' => 'DUSUN',
            'judul' => 'Pengumuman Soft Delete',
            'isi' => 'Isi.',
            'tanggal_kedaluwarsa' => CarbonImmutable::now('Asia/Jakarta')->addDays(5)->toDateString(),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->actingAs($this->adminA)->delete('/admin-dusun/pengumuman/'.$pengumuman->id);
        $response->assertRedirect('/admin-dusun/pengumuman');

        $pengumuman->refresh();
        $this->assertNotNull($pengumuman->deleted_at);
        $this->assertFalse($pengumuman->isArchivedFor(CarbonImmutable::now('Asia/Jakarta')));
    }

    /**
     * 61. Soft Deleted record leaves normal list/public archive.
     */
    public function test_soft_deleted_record_leaves_normal_list_and_public_archive(): void
    {
        $pengumuman = Pengumuman::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'dusun_id' => $this->dusunA->id,
            'scope_level' => 'DUSUN',
            'judul' => 'Pengumuman Dihapus Normal',
            'isi' => 'Isi rahasia.',
            'tanggal_kedaluwarsa' => CarbonImmutable::now('Asia/Jakarta')->subDays(5)->toDateString(),
            'deleted_at' => CarbonImmutable::now(),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        // Admin list
        $adminResponse = $this->actingAs($this->adminA)->get('/admin-dusun/pengumuman');
        $adminResponse->assertDontSee('Pengumuman Dihapus Normal');

        // Public archive
        $publicArchive = $this->get('/pengumuman/arsip');
        $publicArchive->assertDontSee('Pengumuman Dihapus Normal');
    }

    /**
     * 62. No restore/hard delete.
     */
    public function test_no_restore_or_hard_delete_pengumuman(): void
    {
        $response1 = $this->actingAs($this->adminA)->post('/admin-dusun/pengumuman/1/restore');
        $response1->assertNotFound();

        $response2 = $this->actingAs($this->adminA)->delete('/admin-dusun/pengumuman/1/force');
        $response2->assertNotFound();
    }
}
