<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Umkm;

class UmkmController extends Controller
{
    /**
     * UX-SCR-004 — Detail UMKM.
     *
     * Aborts 404 if:
     * - UMKM is soft-deleted (SoftDeletes default exclusion)
     * - Parent Dusun is INACTIVE
     */
    public function show(int $id)
    {
        $umkm = Umkm::withinActiveDusun()
            ->with(['produkUmkms', 'dusun:id,nama_dusun,status_dusun'])
            ->findOrFail($id);

        return view('public.umkm-detail', compact('umkm'));
    }
}
