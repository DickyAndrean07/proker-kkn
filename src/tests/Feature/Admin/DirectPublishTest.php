<?php

namespace Tests\Feature\Admin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\Umkm;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DirectPublishTest extends TestCase
{
    use RefreshDatabase;

    private Desa $desa;

    private Dusun $activeDusun;

    private Dusun $inactiveDusun;

    private AdminAccount $adminActive;

    private AdminAccount $adminInactive;

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

        $this->activeDusun = Dusun::query()->forceCreate([
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

        $this->inactiveDusun = Dusun::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Inaktif',
            'status_dusun' => 'INACTIVE',
            'deskripsi_singkat' => 'Deskripsi Inaktif.',
            'nama_kepala_dusun' => 'Kepala Inaktif',
            'jumlah_rt' => 2,
            'jumlah_rw' => 1,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->adminActive = AdminAccount::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'username' => 'admin_krajan',
            'password_hash' => Hash::make('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->adminInactive = AdminAccount::query()->forceCreate([
            'dusun_id' => $this->inactiveDusun->id,
            'username' => 'admin_inaktif',
            'password_hash' => Hash::make('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);
    }

    /**
     * 78. Create/update eligible resource becomes public immediately when parent ACTIVE.
     */
    public function test_resource_becomes_public_immediately_when_parent_active(): void
    {
        $this->actingAs($this->adminActive)->post('/admin-dusun/kontak', [
            'nama' => 'Petugas Langsung Terbit',
            'jabatan' => 'Staff Humas',
            'nomor_whatsapp' => '081234567890',
        ]);

        $publicResponse = $this->get('/dusun/'.$this->activeDusun->id);

        $publicResponse->assertOk();
        $publicResponse->assertSee('Petugas Langsung Terbit');
        $publicResponse->assertSee('Staff Humas');
    }

    /**
     * 79. No approval state/route exists.
     */
    public function test_no_approval_state_or_route_exists(): void
    {
        $response1 = $this->actingAs($this->adminActive)->post('/admin-dusun/kontak/1/approve');
        $response1->assertNotFound();

        $response2 = $this->actingAs($this->adminActive)->post('/admin-dusun/kontak/1/reject');
        $response2->assertNotFound();

        $response3 = $this->actingAs($this->adminActive)->get('/admin-dusun/moderation');
        $response3->assertNotFound();
    }

    /**
     * 80. When parent Dusun INACTIVE, Admin write succeeds but public remains hidden.
     */
    public function test_when_parent_dusun_inactive_admin_write_succeeds_but_public_remains_hidden(): void
    {
        $response = $this->actingAs($this->adminInactive)->post('/admin-dusun/umkm', [
            'nama_umkm' => 'UMKM Dusun Inaktif',
            'nama_pemilik' => 'Pemilik Inaktif',
            'jenis_usaha' => 'Jasa',
            'deskripsi' => 'Deskripsi.',
            'alamat' => 'Alamat.',
            'nomor_whatsapp' => '081234567890',
            'jam_operasional' => '08.00 - 17.00',
        ]);

        $response->assertRedirect('/admin-dusun/umkm');

        $umkm = Umkm::where('nama_umkm', 'UMKM Dusun Inaktif')->first();
        $this->assertNotNull($umkm);

        // Dusun page is 404 because parent is INACTIVE
        $publicDusun = $this->get('/dusun/'.$this->inactiveDusun->id);
        $publicDusun->assertNotFound();

        // UMKM detail page is 404 because parent is INACTIVE
        $publicUmkm = $this->get('/umkm/'.$umkm->id);
        $publicUmkm->assertNotFound();
    }
}
