<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Support\Carbon;

class PengumumanController extends Controller
{
    /**
     * UX-SCR-007 — Detail Pengumuman.
     *
     * Aborts 404 if:
     * - Pengumuman is soft-deleted (SoftDeletes default exclusion)
     * - DUSUN-scoped: parent Dusun is INACTIVE
     *
     * Active and archived Pengumuman are both publicly readable (archive ≠ soft-delete).
     */
    public function show(int $id)
    {
        $pengumuman = Pengumuman::with([
            'dusun:id,nama_dusun,status_dusun',
        ])->findOrFail($id);

        // DUSUN-scoped: enforce active parent Dusun
        if ($pengumuman->scope_level === 'DUSUN') {
            if ($pengumuman->dusun === null || $pengumuman->dusun->status_dusun !== 'ACTIVE') {
                abort(404);
            }
        }

        // Determine active vs archived status using Asia/Jakarta business date
        $now = Carbon::now(config('app.business_timezone', 'Asia/Jakarta'));
        $isArchived = $pengumuman->isArchivedFor($now);

        return view('public.pengumuman-detail', compact('pengumuman', 'isArchived'));
    }
}
