<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AgendaKegiatan;
use Illuminate\Support\Carbon;

class AgendaController extends Controller
{
    /**
     * UX-SCR-006 — Detail Agenda/Kegiatan.
     *
     * Aborts 404 if:
     * - Agenda is soft-deleted (SoftDeletes default exclusion)
     * - DUSUN-scoped: parent Dusun is INACTIVE
     * - DESA-scoped: always public (no Dusun dependency)
     */
    public function show(int $id)
    {
        // Fetch without scope restriction first, then validate eligibility
        $agenda = AgendaKegiatan::with([
            'agendaMedias',
            'dusun:id,nama_dusun,status_dusun',
        ])->findOrFail($id);

        // DUSUN-scoped: enforce active parent Dusun
        if ($agenda->scope_level === 'DUSUN') {
            if ($agenda->dusun === null || $agenda->dusun->status_dusun !== 'ACTIVE') {
                abort(404);
            }
        }

        // Compute effective status using business date in Asia/Jakarta
        $now = Carbon::now(config('app.business_timezone', 'Asia/Jakarta'));
        $effectiveStatus = $agenda->effectiveStatusFor($now);

        return view('public.agenda-detail', compact('agenda', 'effectiveStatus'));
    }
}
