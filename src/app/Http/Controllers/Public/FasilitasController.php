<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;

class FasilitasController extends Controller
{
    /**
     * UX-SCR-005 — Detail Fasilitas/Lokasi.
     *
     * Aborts 404 if:
     * - Fasilitas is soft-deleted (SoftDeletes default exclusion)
     * - Parent Dusun is INACTIVE
     */
    public function show(int $id)
    {
        $fasilitas = Fasilitas::withinActiveDusun()
            ->with(['kategoriFasilitas:id,nama_kategori', 'dusun:id,nama_dusun'])
            ->findOrFail($id);

        // Build Google Maps directions URL if coordinates are present
        $directionsUrl = null;
        if ($fasilitas->latitude !== null && $fasilitas->longitude !== null) {
            $directionsUrl = 'https://www.google.com/maps/dir/?api=1&destination='
                .urlencode($fasilitas->latitude.','.$fasilitas->longitude);
        }

        return view('public.fasilitas-detail', compact('fasilitas', 'directionsUrl'));
    }
}
