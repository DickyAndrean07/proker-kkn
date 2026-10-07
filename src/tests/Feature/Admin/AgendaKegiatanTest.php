<?php

namespace Tests\Feature\Admin;

use App\Models\AdminAccount;
use App\Models\AgendaKegiatan;
use App\Models\AgendaMedia;
use App\Models\Desa;
use App\Models\Dusun;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AgendaKegiatanTest extends TestCase
{
    use RefreshDatabase;

    private Desa $desa;

    private Dusun $dusunA;

    private Dusun $dusunB;

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
     * 43. Admin creates Agenda with server-forced DUSUN scope.
     */
    public function test_admin_creates_agenda_with_server_forced_dusun_scope(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/agenda', [
            'judul' => 'Kerja Bakti Akbar',
            'deskripsi_singkat' => 'Pembersihan jalan lingkungan.',
            'tanggal_mulai' => CarbonImmutable::now()->addDays(3)->toDateString(),
            'lokasi_text' => 'Sepanjang Jalan Dusun Krajan',
        ]);

        $response->assertRedirect('/admin-dusun/agenda');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('agenda_kegiatans', [
            'dusun_id' => $this->dusunA->id,
            'desa_id' => $this->desa->id,
            'scope_level' => 'DUSUN',
            'judul' => 'Kerja Bakti Akbar',
        ]);
    }

    /**
     * 44. Malicious DESA scope payload cannot create DESA Agenda.
     */
    public function test_malicious_desa_scope_payload_cannot_create_desa_agenda(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/agenda', [
            'scope_level' => 'DESA', // Malicious attempt to escalate scope
            'judul' => 'Agenda Desa Palsu',
            'deskripsi_singkat' => 'Deskripsi.',
            'tanggal_mulai' => CarbonImmutable::now()->addDays(5)->toDateString(),
            'lokasi_text' => 'Kantor Desa',
        ]);

        $response->assertRedirect('/admin-dusun/agenda');

        $agenda = AgendaKegiatan::where('judul', 'Agenda Desa Palsu')->first();
        $this->assertNotNull($agenda);
        $this->assertSame('DUSUN', $agenda->scope_level);
        $this->assertSame($this->dusunA->id, $agenda->dusun_id);
    }

    /**
     * 45. Malicious foreign dusun_id ignored/rejected.
     */
    public function test_malicious_foreign_dusun_id_cannot_create_foreign_agenda(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/agenda', [
            'dusun_id' => $this->dusunB->id, // Malicious attempt
            'judul' => 'Agenda di Dusun Lain',
            'deskripsi_singkat' => 'Deskripsi.',
            'tanggal_mulai' => CarbonImmutable::now()->addDays(5)->toDateString(),
            'lokasi_text' => 'Lokasi Dusun B',
        ]);

        $response->assertRedirect('/admin-dusun/agenda');

        $agenda = AgendaKegiatan::where('judul', 'Agenda di Dusun Lain')->first();
        $this->assertNotNull($agenda);
        $this->assertSame($this->dusunA->id, $agenda->dusun_id); // Forced to Admin A's own dusun!
    }

    /**
     * 46. Date validation (end date >= start date).
     */
    public function test_date_validation_end_date_must_be_after_or_equal_start_date(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/agenda', [
            'judul' => 'Kegiatan Invalid',
            'deskripsi_singkat' => 'Deskripsi.',
            'tanggal_mulai' => '2026-08-20',
            'tanggal_selesai' => '2026-08-18', // Invalid: before start
            'lokasi_text' => 'Lokasi',
        ]);

        $response->assertSessionHasErrors(['tanggal_selesai']);
    }

    /**
     * 47. Nullable end semantics.
     */
    public function test_nullable_end_date_semantics(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/agenda', [
            'judul' => 'Kegiatan 1 Hari',
            'deskripsi_singkat' => 'Deskripsi.',
            'tanggal_mulai' => '2026-08-20',
            'tanggal_selesai' => '',
            'lokasi_text' => 'Lokasi',
        ]);

        $response->assertRedirect('/admin-dusun/agenda');
        $agenda = AgendaKegiatan::where('judul', 'Kegiatan 1 Hari')->first();
        $this->assertNotNull($agenda);
        $this->assertNull($agenda->tanggal_selesai);
    }

    /**
     * 48. Manual override valid values only.
     */
    public function test_manual_override_valid_values_only(): void
    {
        // Invalid override
        $response1 = $this->actingAs($this->adminA)->post('/admin-dusun/agenda', [
            'judul' => 'Kegiatan Override Invalid',
            'deskripsi_singkat' => 'Deskripsi.',
            'tanggal_mulai' => '2026-08-20',
            'lokasi_text' => 'Lokasi',
            'manual_status_override' => 'STATUS_PALSU',
        ]);
        $response1->assertSessionHasErrors(['manual_status_override']);

        // Valid override
        $response2 = $this->actingAs($this->adminA)->post('/admin-dusun/agenda', [
            'judul' => 'Kegiatan Override Valid',
            'deskripsi_singkat' => 'Deskripsi.',
            'tanggal_mulai' => '2026-08-20',
            'lokasi_text' => 'Lokasi',
            'manual_status_override' => 'SELESAI',
        ]);
        $response2->assertRedirect('/admin-dusun/agenda');
    }

    /**
     * 49. Effective status remains derived.
     */
    public function test_effective_status_remains_derived(): void
    {
        $agenda = AgendaKegiatan::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'dusun_id' => $this->dusunA->id,
            'scope_level' => 'DUSUN',
            'judul' => 'Musyawarah Warga',
            'deskripsi_singkat' => 'Deskripsi.',
            'tanggal_mulai' => '2026-08-10',
            'tanggal_selesai' => '2026-08-11',
            'lokasi_text' => 'Balai',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->assertSame('SELESAI', $agenda->effectiveStatusFor(CarbonImmutable::parse('2026-08-15', 'Asia/Jakarta')));
    }

    /**
     * 50. Agenda media valid roles only.
     */
    public function test_agenda_media_valid_roles_only(): void
    {
        // Valid roles: POSTER_AWAL, DOKUMENTASI
        $image = UploadedFile::fake()->image('poster.jpg', 600, 400);

        $response = $this->actingAs($this->adminA)->post('/admin-dusun/agenda', [
            'judul' => 'Kegiatan Bergambar',
            'deskripsi_singkat' => 'Deskripsi.',
            'tanggal_mulai' => '2026-08-20',
            'lokasi_text' => 'Lokasi',
            'media' => [
                ['file' => $image, 'role' => 'POSTER_AWAL'],
            ],
        ]);

        $response->assertRedirect('/admin-dusun/agenda');
        $agenda = AgendaKegiatan::where('judul', 'Kegiatan Bergambar')->first();
        $this->assertNotNull($agenda);
        $this->assertCount(1, $agenda->agendaMedias);
        $this->assertSame('POSTER_AWAL', $agenda->agendaMedias->first()->media_role);
    }

    /**
     * 51. Parent/child media authorization.
     */
    public function test_parent_child_media_authorization(): void
    {
        $agendaB = AgendaKegiatan::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'dusun_id' => $this->dusunB->id,
            'scope_level' => 'DUSUN',
            'judul' => 'Agenda Dusun Seberang',
            'deskripsi_singkat' => 'Deskripsi.',
            'tanggal_mulai' => '2026-08-20',
            'lokasi_text' => 'Lokasi B',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        // Admin A tries to edit Dusun B's agenda
        $response = $this->actingAs($this->adminA)->get('/admin-dusun/agenda/'.$agendaB->id.'/edit');
        $response->assertNotFound();
    }

    /**
     * 52. Soft Delete retains Agenda media.
     */
    public function test_soft_delete_retains_agenda_media(): void
    {
        $agenda = AgendaKegiatan::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'dusun_id' => $this->dusunA->id,
            'scope_level' => 'DUSUN',
            'judul' => 'Agenda Dengan Foto',
            'deskripsi_singkat' => 'Deskripsi.',
            'tanggal_mulai' => '2026-08-20',
            'lokasi_text' => 'Lokasi',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $media = AgendaMedia::query()->forceCreate([
            'agenda_kegiatan_id' => $agenda->id,
            'media_path' => 'agenda/foto_kegiatan.webp',
            'media_role' => 'POSTER_AWAL',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        Storage::disk('public')->put('agenda/foto_kegiatan.webp', 'dummy image content');

        $response = $this->actingAs($this->adminA)->delete('/admin-dusun/agenda/'.$agenda->id);
        $response->assertRedirect('/admin-dusun/agenda');

        $agenda->refresh();
        $this->assertNotNull($agenda->deleted_at);

        // Media row and file must be retained!
        $this->assertDatabaseHas('agenda_medias', ['id' => $media->id]);
        $this->assertTrue(Storage::disk('public')->exists('agenda/foto_kegiatan.webp'));
    }

    /**
     * 53. No restore/hard delete.
     */
    public function test_no_restore_or_hard_delete_agenda(): void
    {
        $response1 = $this->actingAs($this->adminA)->post('/admin-dusun/agenda/1/restore');
        $response1->assertNotFound();

        $response2 = $this->actingAs($this->adminA)->delete('/admin-dusun/agenda/1/force');
        $response2->assertNotFound();
    }
}
