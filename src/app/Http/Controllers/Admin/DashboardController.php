<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgendaKegiatan;
use App\Models\Fasilitas;
use App\Models\KontakPelayanan;
use App\Models\Pengumuman;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $dusun = $user->dusun;

        $kontakCount = KontakPelayanan::where('dusun_id', $dusun->id)->count();
        $umkmCount = Umkm::where('dusun_id', $dusun->id)->count();
        $fasilitasCount = Fasilitas::where('dusun_id', $dusun->id)->count();
        $agendaCount = AgendaKegiatan::where('dusun_id', $dusun->id)->count();
        $pengumumanCount = Pengumuman::where('dusun_id', $dusun->id)->count();

        return view('admin.dashboard', compact(
            'user',
            'dusun',
            'kontakCount',
            'umkmCount',
            'fasilitasCount',
            'agendaCount',
            'pengumumanCount',
        ));
    }
}
