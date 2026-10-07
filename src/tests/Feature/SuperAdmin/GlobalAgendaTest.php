<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\AdminAccount;
use App\Models\AgendaKegiatan;
use App\Models\AgendaMedia;
use App\Models\Desa;
use App\Models\Dusun;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GlobalAgendaTest extends TestCase
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
            'username' => 'superadmin_agenda',
            'password_hash' => bcrypt('password123'),
            'role' => 'SUPER_ADMIN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_54_super_admin_can_view_global_agenda_list_with_filters(): void
    {
        AgendaKegiatan::forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Musrenbang Desa Tingkat Desa',
            'deskripsi_singkat' => 'Deskripsi',
            'tanggal_mulai' => now()->addDays(5)->format('Y-m-d'),
            'lokasi_text' => 'Balai Desa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        AgendaKegiatan::forceCreate([
            'desa_id' => $this->desa->id,
            'dusun_id' => $this->dusun1->id,
            'scope_level' => 'DUSUN',
            'judul' => 'Kerja Bakti Krajan',
            'deskripsi_singkat' => 'Deskripsi',
            'tanggal_mulai' => now()->addDays(3)->format('Y-m-d'),
            'lokasi_text' => 'Krajan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $responseDesa = $this->actingAs($this->superAdmin)->get(route('super-admin.agenda.index', ['scope_level' => 'DESA']));
        $responseDesa->assertStatus(200);
        $responseDesa->assertSee('Musrenbang Desa Tingkat Desa');
        $responseDesa->assertDontSee('Kerja Bakti Krajan');

        $responseDusun = $this->actingAs($this->superAdmin)->get(route('super-admin.agenda.index', ['scope_level' => 'DUSUN']));
        $responseDusun->assertStatus(200);
        $responseDusun->assertSee('Kerja Bakti Krajan');
        $responseDusun->assertDontSee('Musrenbang Desa Tingkat Desa');
    }

    public function test_55_super_admin_can_create_desa_scoped_agenda(): void
    {
        $payload = [
            'scope_level' => 'DESA',
            'judul' => 'Peringatan HUT RI ke-81 Tingkat Desa',
            'deskripsi_singkat' => 'Upacara bendera dan lomba desa.',
            'tanggal_mulai' => '2026-08-17',
            'tanggal_selesai' => '2026-08-17',
            'jam' => '08:00',
            'lokasi_text' => 'Lapangan Desa Bendung',
        ];

        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.agenda.store'), $payload);

        $response->assertRedirect(route('super-admin.agenda.index'));
        $this->assertDatabaseHas('agenda_kegiatans', [
            'scope_level' => 'DESA',
            'dusun_id' => null,
            'judul' => 'Peringatan HUT RI ke-81 Tingkat Desa',
        ]);
    }

    public function test_56_super_admin_can_create_dusun_scoped_agenda(): void
    {
        $payload = [
            'scope_level' => 'DUSUN',
            'dusun_id' => $this->dusun1->id,
            'judul' => 'Posyandu Lansia Krajan',
            'deskripsi_singkat' => 'Pemeriksaan tensi dan gula darah.',
            'tanggal_mulai' => '2026-08-20',
            'lokasi_text' => 'Balai Dusun Krajan',
        ];

        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.agenda.store'), $payload);

        $response->assertRedirect(route('super-admin.agenda.index'));
        $this->assertDatabaseHas('agenda_kegiatans', [
            'scope_level' => 'DUSUN',
            'dusun_id' => $this->dusun1->id,
            'judul' => 'Posyandu Lansia Krajan',
        ]);
    }

    public function test_57_scope_level_validation_rejects_inconsistent_dusun_id(): void
    {
        // 1. DESA with dusun_id specified
        $response1 = $this->actingAs($this->superAdmin)->post(route('super-admin.agenda.store'), [
            'scope_level' => 'DESA',
            'dusun_id' => $this->dusun1->id,
            'judul' => 'Invalid Agenda',
            'deskripsi_singkat' => 'Desc',
            'tanggal_mulai' => '2026-08-20',
            'lokasi_text' => 'Loc',
        ]);
        $response1->assertSessionHasErrors(['dusun_id']);

        // 2. DUSUN without dusun_id specified
        $response2 = $this->actingAs($this->superAdmin)->post(route('super-admin.agenda.store'), [
            'scope_level' => 'DUSUN',
            'dusun_id' => null,
            'judul' => 'Invalid Agenda 2',
            'deskripsi_singkat' => 'Desc',
            'tanggal_mulai' => '2026-08-20',
            'lokasi_text' => 'Loc',
        ]);
        $response2->assertSessionHasErrors(['dusun_id']);
    }

    public function test_58_super_admin_can_update_agenda_scope_and_dusun(): void
    {
        $agenda = AgendaKegiatan::forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'dusun_id' => null,
            'judul' => 'Agenda Semula Desa',
            'deskripsi_singkat' => 'Deskripsi',
            'tanggal_mulai' => '2026-08-20',
            'lokasi_text' => 'Balai',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $payload = [
            'scope_level' => 'DUSUN',
            'dusun_id' => $this->dusun2->id,
            'judul' => 'Agenda Diubah Jadi Dusun',
            'deskripsi_singkat' => 'Deskripsi baru',
            'tanggal_mulai' => '2026-08-21',
            'lokasi_text' => 'Banyuripan',
        ];

        $response = $this->actingAs($this->superAdmin)->put(route('super-admin.agenda.update', $agenda->id), $payload);

        $response->assertRedirect(route('super-admin.agenda.index'));
        $agenda->refresh();
        $this->assertEquals('DUSUN', $agenda->scope_level);
        $this->assertEquals($this->dusun2->id, $agenda->dusun_id);
    }

    public function test_59_super_admin_can_attach_media_when_creating_agenda(): void
    {
        $file = UploadedFile::fake()->image('pamflet.jpg');

        $payload = [
            'scope_level' => 'DESA',
            'judul' => 'Agenda Dengan Poster',
            'deskripsi_singkat' => 'Deskripsi',
            'tanggal_mulai' => '2026-08-25',
            'lokasi_text' => 'Balai Desa',
            'media' => [
                ['file' => $file, 'role' => 'POSTER_AWAL'],
            ],
        ];

        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.agenda.store'), $payload);

        $response->assertRedirect(route('super-admin.agenda.index'));
        $agenda = AgendaKegiatan::where('judul', 'Agenda Dengan Poster')->firstOrFail();
        $this->assertCount(1, $agenda->agendaMedias);
        Storage::disk('public')->assertExists($agenda->agendaMedias->first()->media_path);
    }

    public function test_60_super_admin_can_remove_selected_media_when_updating_agenda(): void
    {
        $agenda = AgendaKegiatan::forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Agenda Edit Media',
            'deskripsi_singkat' => 'Deskripsi',
            'tanggal_mulai' => '2026-08-25',
            'lokasi_text' => 'Balai Desa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $file = UploadedFile::fake()->image('old_doc.jpg');
        $path = $file->store('agenda', 'public');

        $media = AgendaMedia::forceCreate([
            'agenda_kegiatan_id' => $agenda->id,
            'media_path' => $path,
            'media_role' => 'DOKUMENTASI',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $payload = [
            'scope_level' => 'DESA',
            'judul' => 'Agenda Edit Media',
            'deskripsi_singkat' => 'Deskripsi',
            'tanggal_mulai' => '2026-08-25',
            'lokasi_text' => 'Balai Desa',
            'existing_media_remove' => [$media->id],
        ];

        $response = $this->actingAs($this->superAdmin)->put(route('super-admin.agenda.update', $agenda->id), $payload);

        $response->assertRedirect(route('super-admin.agenda.index'));
        $this->assertDatabaseMissing('agenda_medias', ['id' => $media->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_61_super_admin_can_soft_delete_agenda_retaining_media(): void
    {
        $agenda = AgendaKegiatan::forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Agenda Soft Delete',
            'deskripsi_singkat' => 'Deskripsi',
            'tanggal_mulai' => '2026-08-25',
            'lokasi_text' => 'Balai Desa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $file = UploadedFile::fake()->image('retained_doc.jpg');
        $path = $file->store('agenda', 'public');

        AgendaMedia::forceCreate([
            'agenda_kegiatan_id' => $agenda->id,
            'media_path' => $path,
            'media_role' => 'DOKUMENTASI',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->delete(route('super-admin.agenda.destroy', $agenda->id));

        $response->assertRedirect(route('super-admin.agenda.index'));
        $agenda->refresh();
        $this->assertTrue($agenda->trashed());
        Storage::disk('public')->assertExists($path); // Media retained
    }

    public function test_62_super_admin_can_restore_soft_deleted_agenda(): void
    {
        $agenda = AgendaKegiatan::forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Agenda Dipulihkan',
            'deskripsi_singkat' => 'Deskripsi',
            'tanggal_mulai' => '2026-08-25',
            'lokasi_text' => 'Balai Desa',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.agenda.restore', $agenda->id));

        $response->assertRedirect(route('super-admin.agenda.index', ['status' => 'trashed']));
        $agenda->refresh();
        $this->assertFalse($agenda->trashed());
    }

    public function test_63_super_admin_can_hard_delete_agenda_purging_media_and_cascading_db(): void
    {
        $agenda = AgendaKegiatan::forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Agenda Hard Delete',
            'deskripsi_singkat' => 'Deskripsi',
            'tanggal_mulai' => '2026-08-25',
            'lokasi_text' => 'Balai Desa',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => now(),
        ]);

        $file = UploadedFile::fake()->image('purged_doc.jpg');
        $path = $file->store('agenda', 'public');

        $media = AgendaMedia::forceCreate([
            'agenda_kegiatan_id' => $agenda->id,
            'media_path' => $path,
            'media_role' => 'DOKUMENTASI',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->delete(route('super-admin.agenda.force-delete', $agenda->id));

        $response->assertRedirect(route('super-admin.agenda.index', ['status' => 'trashed']));
        $this->assertDatabaseMissing('agenda_kegiatans', ['id' => $agenda->id]);
        $this->assertDatabaseMissing('agenda_medias', ['id' => $media->id]);
        Storage::disk('public')->assertMissing($path); // Media purged
    }

    public function test_64_attempting_to_hard_delete_active_agenda_returns_404(): void
    {
        $agenda = AgendaKegiatan::forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Agenda Aktif',
            'deskripsi_singkat' => 'Deskripsi',
            'tanggal_mulai' => '2026-08-25',
            'lokasi_text' => 'Balai Desa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->delete(route('super-admin.agenda.force-delete', $agenda->id));
        $response->assertStatus(404);
    }

    public function test_65_manual_status_override_is_saved_properly(): void
    {
        $payload = [
            'scope_level' => 'DESA',
            'judul' => 'Agenda Override',
            'deskripsi_singkat' => 'Deskripsi',
            'tanggal_mulai' => '2026-08-01',
            'lokasi_text' => 'Balai Desa',
            'manual_status_override' => 'BERLANGSUNG',
        ];

        $this->actingAs($this->superAdmin)->post(route('super-admin.agenda.store'), $payload);

        $agenda = AgendaKegiatan::where('judul', 'Agenda Override')->firstOrFail();
        $this->assertEquals('BERLANGSUNG', $agenda->manual_status_override);
    }

    public function test_66_validation_error_when_end_date_is_before_start_date(): void
    {
        $payload = [
            'scope_level' => 'DESA',
            'judul' => 'Agenda Invalid Dates',
            'deskripsi_singkat' => 'Deskripsi',
            'tanggal_mulai' => '2026-08-25',
            'tanggal_selesai' => '2026-08-20',
            'lokasi_text' => 'Balai Desa',
        ];

        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.agenda.store'), $payload);
        $response->assertSessionHasErrors(['tanggal_selesai']);
    }

    public function test_67_admin_dusun_is_blocked_from_super_admin_agenda_routes(): void
    {
        $adminDusun = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admindusun_agenda_sec',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $responseIndex = $this->actingAs($adminDusun)->get(route('super-admin.agenda.index'));
        $responseIndex->assertStatus(403);
    }
}
