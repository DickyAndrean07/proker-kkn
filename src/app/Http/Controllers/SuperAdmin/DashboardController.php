<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AdminAccount;
use App\Models\AgendaKegiatan;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\Fasilitas;
use App\Models\KategoriFasilitas;
use App\Models\KontakPelayanan;
use App\Models\Pengumuman;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $desa = Desa::first();

        $stats = [
            'dusun_total' => Dusun::count(),
            'dusun_active' => Dusun::where('status_dusun', 'ACTIVE')->count(),
            'dusun_inactive' => Dusun::where('status_dusun', 'INACTIVE')->count(),
            'kontak_active' => KontakPelayanan::count(),
            'umkm_active' => Umkm::count(),
            'fasilitas_active' => Fasilitas::count(),
            'kategori_total' => KategoriFasilitas::count(),
            'agenda_active' => AgendaKegiatan::count(),
            'pengumuman_active' => Pengumuman::count(),
            'admin_dusun_active' => AdminAccount::where('role', 'ADMIN_DUSUN')->whereNull('removed_at')->count(),
            'admin_dusun_removed' => AdminAccount::where('role', 'ADMIN_DUSUN')->whereNotNull('removed_at')->count(),
        ];

        return view('super-admin.dashboard', compact('desa', 'stats'));
    }
}
