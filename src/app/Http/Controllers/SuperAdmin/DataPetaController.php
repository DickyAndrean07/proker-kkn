<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Dusun;
use App\Models\Fasilitas;
use App\Models\KategoriFasilitas;
use App\Models\KontakPelayanan;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DataPetaController extends Controller
{
    public function __invoke(Request $request): View
    {
        $dusunFilter = $request->query('dusun_id');
        $kategoriFilter = $request->query('kategori');

        $dusuns = Dusun::orderBy('nama_dusun')->get();
        $kategoriFasilitas = KategoriFasilitas::orderBy('nama_kategori')->get();

        $markers = [];

        // 1. Fasilitas (all active non-deleted with coordinates)
        if (empty($kategoriFilter) || str_starts_with($kategoriFilter, 'fasilitas_') || $kategoriFilter === 'fasilitas') {
            $fasilitasQuery = Fasilitas::with(['dusun', 'kategoriFasilitas'])
                ->whereNotNull('latitude')
                ->whereNotNull('longitude');

            if (! empty($dusunFilter)) {
                $fasilitasQuery->where('dusun_id', $dusunFilter);
            }

            if (! empty($kategoriFilter) && str_starts_with($kategoriFilter, 'fasilitas_')) {
                $katId = (int) substr($kategoriFilter, 10);
                $fasilitasQuery->where('kategori_fasilitas_id', $katId);
            }

            foreach ($fasilitasQuery->get() as $f) {
                $markers[] = [
                    'id' => $f->id,
                    'type' => 'fasilitas',
                    'title' => $f->nama,
                    'category' => $f->kategoriFasilitas?->nama_kategori ?? 'Fasilitas Umum',
                    'dusun' => $f->dusun?->nama_dusun ?? 'Desa',
                    'latitude' => (float) $f->latitude,
                    'longitude' => (float) $f->longitude,
                    'edit_url' => route('super-admin.fasilitas.edit', $f->id),
                    'color' => '#2e5e3e',
                ];
            }
        }

        // 2. UMKM (all active non-deleted with coordinates)
        if (empty($kategoriFilter) || $kategoriFilter === 'umkm') {
            $umkmQuery = Umkm::with('dusun')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude');

            if (! empty($dusunFilter)) {
                $umkmQuery->where('dusun_id', $dusunFilter);
            }

            foreach ($umkmQuery->get() as $u) {
                $markers[] = [
                    'id' => $u->id,
                    'type' => 'umkm',
                    'title' => $u->nama_umkm,
                    'category' => 'UMKM: '.$u->jenis_usaha,
                    'dusun' => $u->dusun?->nama_dusun ?? 'Desa',
                    'latitude' => (float) $u->latitude,
                    'longitude' => (float) $u->longitude,
                    'edit_url' => route('super-admin.umkm.edit', $u->id),
                    'color' => '#c27d38',
                ];
            }
        }

        // 3. Kontak Pelayanan (all active non-deleted with coordinates)
        if (empty($kategoriFilter) || $kategoriFilter === 'pelayanan') {
            $kontakQuery = KontakPelayanan::with('dusun')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude');

            if (! empty($dusunFilter)) {
                $kontakQuery->where('dusun_id', $dusunFilter);
            }

            foreach ($kontakQuery->get() as $k) {
                $markers[] = [
                    'id' => $k->id,
                    'type' => 'kontak',
                    'title' => $k->nama.' ('.$k->jabatan.')',
                    'category' => 'Kontak Pelayanan',
                    'dusun' => $k->dusun?->nama_dusun ?? 'Desa',
                    'latitude' => (float) $k->latitude,
                    'longitude' => (float) $k->longitude,
                    'edit_url' => route('super-admin.kontak.edit', $k->id),
                    'color' => '#1565c0',
                ];
            }
        }

        return view('super-admin.peta.index', compact(
            'dusuns',
            'kategoriFasilitas',
            'markers',
            'dusunFilter',
            'kategoriFilter'
        ));
    }
}
