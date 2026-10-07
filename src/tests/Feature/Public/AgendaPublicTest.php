<?php

namespace Tests\Feature\Public;

use App\Models\AgendaKegiatan;
use App\Models\Desa;
use App\Models\Dusun;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgendaPublicTest extends TestCase
{
    use RefreshDatabase;

    private Desa $desa;

    private Dusun $activeDusun;

    private Dusun $inactiveDusun;

    protected function setUp(): void
    {
        parent::setUp();

        $this->desa = Desa::query()->forceCreate([
            'nama_desa' => 'Desa Bendung',
            'deskripsi_singkat' => 'Portal informasi resmi Desa Bendung.',
            'alamat_kantor' => 'Jl. Raya Bendung No. 1',
            'nomor_kontak' => '081234567890',
            'email' => 'info@desabendung.id',
            'nama_kepala_desa' => 'Bapak Kepala Desa',
            'jam_pelayanan' => 'Senin - Jumat, 08.00 - 15.00 WIB',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->activeDusun = Dusun::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Krajan',
            'deskripsi_singkat' => 'Dusun Krajan.',
            'nama_kepala_dusun' => 'Bapak Kepala Krajan',
            'jumlah_rt' => 5,
            'jumlah_rw' => 2,
            'status_dusun' => 'ACTIVE',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->inactiveDusun = Dusun::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Inaktif',
            'deskripsi_singkat' => 'Dusun Inaktif.',
            'nama_kepala_dusun' => 'Bapak Kepala Inaktif',
            'jumlah_rt' => 2,
            'jumlah_rw' => 1,
            'status_dusun' => 'INACTIVE',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);
    }

    /**
     * TC23: Agenda detail renders effective lifecycle.
     */
    public function test_agenda_detail_renders_effective_lifecycle(): void
    {
        $futureAgenda = AgendaKegiatan::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Kerja Bakti Akbar',
            'deskripsi_singkat' => 'Kerja bakti membersihkan saluran air.',
            'tanggal_mulai' => CarbonImmutable::now()->addDays(5)->toDateString(),
            'tanggal_selesai' => CarbonImmutable::now()->addDays(5)->toDateString(),
            'jam' => '07:00:00',
            'lokasi_text' => 'Balai Desa',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get(route('agenda.show', $futureAgenda->id));

        $response->assertOk();
        $response->assertSee('Kerja Bakti Akbar');
        $response->assertSee('Akan Datang');
        $response->assertSee('Balai Desa');
        $response->assertSee('07:00:00');
    }

    /**
     * TC24: Nullable end-date semantics correct.
     */
    public function test_nullable_end_date_semantics_correct(): void
    {
        $singleDayAgenda = AgendaKegiatan::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Senam Pagi Bersama',
            'deskripsi_singkat' => 'Senam pagi warga.',
            'tanggal_mulai' => CarbonImmutable::now()->addDays(2)->toDateString(),
            'tanggal_selesai' => null,
            'lokasi_text' => 'Lapangan Desa',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get(route('agenda.show', $singleDayAgenda->id));

        $response->assertOk();
        $response->assertSee('Senam Pagi Bersama');
        $response->assertSee('Akan Datang');
    }

    /**
     * TC25: Manual override representation is respected in effective status.
     */
    public function test_manual_override_status_is_respected(): void
    {
        // A past date agenda with status SELESAI override
        $overrideAgenda = AgendaKegiatan::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Rapat Koordinasi KKN',
            'deskripsi_singkat' => 'Rapat evaluasi.',
            'tanggal_mulai' => CarbonImmutable::now()->subDays(5)->toDateString(),
            'tanggal_selesai' => CarbonImmutable::now()->subDays(4)->toDateString(),
            'lokasi_text' => 'Posko KKN',
            'manual_status_override' => 'SELESAI',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get(route('agenda.show', $overrideAgenda->id));

        $response->assertOk();
        $response->assertSee('Rapat Koordinasi KKN');
        $response->assertSee('Selesai');
    }

    /**
     * TC26: Soft-deleted or inactive-parent Agenda returns 404.
     */
    public function test_soft_deleted_or_inactive_parent_agenda_returns_404(): void
    {
        $softDeleted = AgendaKegiatan::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'scope_level' => 'DESA',
            'judul' => 'Agenda Terhapus',
            'deskripsi_singkat' => 'Deskripsi.',
            'tanggal_mulai' => CarbonImmutable::now()->addDays(3)->toDateString(),
            'lokasi_text' => 'Lokasi',
            'deleted_at' => CarbonImmutable::now(),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $inactiveParent = AgendaKegiatan::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'dusun_id' => $this->inactiveDusun->id,
            'scope_level' => 'DUSUN',
            'judul' => 'Agenda Dusun Inaktif',
            'deskripsi_singkat' => 'Deskripsi.',
            'tanggal_mulai' => CarbonImmutable::now()->addDays(3)->toDateString(),
            'lokasi_text' => 'Lokasi',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->get(route('agenda.show', $softDeleted->id))->assertNotFound();
        $this->get(route('agenda.show', $inactiveParent->id))->assertNotFound();
    }
}
