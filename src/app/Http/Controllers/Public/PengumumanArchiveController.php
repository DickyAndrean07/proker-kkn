<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Dusun;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanArchiveController extends Controller
{
    /**
     * UX-SCR-003 — Arsip Pengumuman (public child page).
     *
     * Default context: DESA archive.
     * With ?dusun={id}: DUSUN archive for that specific ACTIVE Dusun.
     *
     * Archive = expired (tanggal_kedaluwarsa < today) + NOT soft-deleted.
     * Soft-deleted Pengumuman are excluded by SoftDeletes default.
     */
    public function index(Request $request)
    {
        $dusunId = $request->query('dusun');
        $contextDusun = null;

        if ($dusunId !== null) {
            // Validate: must be an existing ACTIVE Dusun (non-numeric or INACTIVE → 404)
            if (! ctype_digit((string) $dusunId)) {
                abort(404);
            }

            $contextDusun = Dusun::publicActive()->where('id', $dusunId)->firstOrFail();

            // DUSUN archive
            $pengumumans = Pengumuman::dusunScope()
                ->where('dusun_id', $contextDusun->id)
                ->archivedAnnouncements()
                ->latest('tanggal_kedaluwarsa')
                ->paginate(12)
                ->withQueryString();

            $contextLabel = 'Dusun '.$contextDusun->nama_dusun;
            $backUrl = route('dusun.show', $contextDusun->id).'#pengumuman';
        } else {
            // DESA archive
            $pengumumans = Pengumuman::desaScope()
                ->archivedAnnouncements()
                ->latest('tanggal_kedaluwarsa')
                ->paginate(12)
                ->withQueryString();

            $contextLabel = 'Desa';
            $backUrl = route('home').'#pengumuman';
        }

        return view('public.pengumuman-arsip', compact(
            'pengumumans',
            'contextLabel',
            'contextDusun',
            'backUrl',
        ));
    }
}
