<?php

namespace Tests\Feature\Admin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfilDusunTest extends TestCase
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
     * 8. Admin can view own profile form.
     */
    public function test_admin_can_view_own_profile_form(): void
    {
        $response = $this->actingAs($this->adminA)->get('/admin-dusun/profil');

        $response->assertOk();
        $response->assertSee('Kelola Profil Dusun');
        $response->assertSee('Dusun Krajan');
        $response->assertSee('Kepala Krajan');
    }

    /**
     * 9. Admin can update supported own profile fields.
     */
    public function test_admin_can_update_supported_own_profile_fields(): void
    {
        $response = $this->actingAs($this->adminA)->put('/admin-dusun/profil', [
            'nama_dusun' => 'Dusun Krajan Makmur',
            'deskripsi_singkat' => 'Deskripsi baru yang diperbarui.',
            'nama_kepala_dusun' => 'Bapak Kepala Baru',
            'jumlah_rt' => 6,
            'jumlah_rw' => 3,
        ]);

        $response->assertRedirect('/admin-dusun/profil');
        $response->assertSessionHas('success');

        $this->dusunA->refresh();
        $this->assertSame('Dusun Krajan Makmur', $this->dusunA->nama_dusun);
        $this->assertSame('Deskripsi baru yang diperbarui.', $this->dusunA->deskripsi_singkat);
        $this->assertSame('Bapak Kepala Baru', $this->dusunA->nama_kepala_dusun);
        $this->assertSame(6, $this->dusunA->jumlah_rt);
        $this->assertSame(3, $this->dusunA->jumlah_rw);
    }

    /**
     * 10. Foreign Dusun profile update denied/cannot be affected.
     */
    public function test_foreign_dusun_profile_cannot_be_updated_by_admin_a(): void
    {
        $originalNameB = $this->dusunB->nama_dusun;

        // Admin A tries to update profile but passes dusun_id of Dusun B in payload
        $response = $this->actingAs($this->adminA)->put('/admin-dusun/profil', [
            'dusun_id' => $this->dusunB->id,
            'nama_dusun' => 'Dusun B Diubah Hacker',
            'deskripsi_singkat' => 'Deskripsi hacker.',
            'nama_kepala_dusun' => 'Kepala Hacker',
            'jumlah_rt' => 10,
            'jumlah_rw' => 5,
        ]);

        $response->assertRedirect('/admin-dusun/profil');

        $this->dusunB->refresh();
        $this->assertSame($originalNameB, $this->dusunB->nama_dusun); // Dusun B remains unchanged!

        $this->dusunA->refresh();
        $this->assertSame('Dusun B Diubah Hacker', $this->dusunA->nama_dusun); // Update applied to Admin's OWN dusun only
    }

    /**
     * 11. status_dusun cannot be changed through Admin profile request.
     */
    public function test_status_dusun_cannot_be_changed_through_admin_request(): void
    {
        $this->assertSame('ACTIVE', $this->dusunA->status_dusun);

        $response = $this->actingAs($this->adminA)->put('/admin-dusun/profil', [
            'nama_dusun' => 'Dusun Krajan',
            'deskripsi_singkat' => 'Deskripsi.',
            'nama_kepala_dusun' => 'Kepala Krajan',
            'jumlah_rt' => 4,
            'jumlah_rw' => 2,
            'status_dusun' => 'INACTIVE', // Malicious attempt to change status
        ]);

        $this->dusunA->refresh();
        $this->assertSame('ACTIVE', $this->dusunA->status_dusun);
    }

    /**
     * 12. Client-supplied desa_id/dusun_id cannot change binding.
     */
    public function test_client_supplied_desa_id_cannot_change_binding(): void
    {
        $originalDesaId = $this->dusunA->desa_id;

        $response = $this->actingAs($this->adminA)->put('/admin-dusun/profil', [
            'desa_id' => 9999, // Malicious binding attempt
            'nama_dusun' => 'Dusun Krajan',
            'deskripsi_singkat' => 'Deskripsi.',
            'nama_kepala_dusun' => 'Kepala Krajan',
            'jumlah_rt' => 4,
            'jumlah_rw' => 2,
        ]);

        $this->dusunA->refresh();
        $this->assertSame($originalDesaId, $this->dusunA->desa_id);
    }

    /**
     * 13. Public Halaman Dusun reflects valid update when parent ACTIVE.
     */
    public function test_public_halaman_dusun_reflects_valid_update(): void
    {
        $this->actingAs($this->adminA)->put('/admin-dusun/profil', [
            'nama_dusun' => 'Dusun Krajan Bersatu',
            'deskripsi_singkat' => 'Dusun yang sangat maju dan sejahtera.',
            'nama_kepala_dusun' => 'Bapak Subur Makmur',
            'jumlah_rt' => 8,
            'jumlah_rw' => 4,
        ]);

        $publicResponse = $this->get('/dusun/'.$this->dusunA->id);

        $publicResponse->assertOk();
        $publicResponse->assertSee('Dusun Krajan Bersatu');
        $publicResponse->assertSee('Dusun yang sangat maju dan sejahtera.');
        $publicResponse->assertSee('Bapak Subur Makmur');
    }
}
