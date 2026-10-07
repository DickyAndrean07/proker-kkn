<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AgendaKegiatan;
use App\Models\Dusun;
use App\Models\Fasilitas;
use App\Models\KontakPelayanan;
use App\Models\Pengumuman;
use App\Models\Umkm;
use Illuminate\Support\Js;

class DusunController extends Controller
{
    /**
     * UX-SCR-002 — Halaman Dusun (single page/scroll).
     * UX-SCR-009 — Peta Dusun (as section #peta-dusun within this page).
     *
     * Aborts 404 if the Dusun is not found or is INACTIVE.
     */
    public function show(int $id)
    {
        // Public Visibility Principle: INACTIVE Dusun not accessible on public site
        $dusun = Dusun::publicActive()->where('id', $id)->firstOrFail();

        // Section data — each ordered for display
        $kontaks = KontakPelayanan::where('dusun_id', $dusun->id)
            ->orderBy('nama')
            ->get();

        $umkms = Umkm::where('dusun_id', $dusun->id)
            ->with('produkUmkms')
            ->orderBy('nama_umkm')
            ->get();

        $fasilitas = Fasilitas::where('dusun_id', $dusun->id)
            ->with('kategoriFasilitas')
            ->orderBy('nama')
            ->get();

        $agendas = AgendaKegiatan::dusunScope()
            ->where('dusun_id', $dusun->id)
            ->orderBy('tanggal_mulai', 'desc')
            ->with('agendaMedias')
            ->take(5)
            ->get();

        $pengumumans = Pengumuman::dusunScope()
            ->where('dusun_id', $dusun->id)
            ->activeAnnouncements()
            ->latest('created_at')
            ->take(5)
            ->get();

        $agendaCount = AgendaKegiatan::dusunScope()
            ->where('dusun_id', $dusun->id)
            ->count();

        $pengumumanCount = Pengumuman::dusunScope()
            ->where('dusun_id', $dusun->id)
            ->activeAnnouncements()
            ->count();

        // ── Peta Dusun map marker data ───────────────────────────────────────
        $fasilitasMarkers = $fasilitas
            ->filter(fn ($f) => $f->latitude !== null && $f->longitude !== null)
            ->toBase()
            ->map(fn ($f) => [
                'id' => 'fasilitas-'.$f->id,
                'lat' => (float) $f->latitude,
                'lng' => (float) $f->longitude,
                'name' => $f->nama,
                'category' => $f->kategoriFasilitas?->nama_kategori ?? 'Fasilitas',
                'address' => $f->alamat,
                'marker_type' => 'FASILITAS',
                'dusun_id' => $dusun->id,
                'detail_url' => route('fasilitas.show', $f->id),
                'photo_url' => $f->foto_path ? asset('storage/'.$f->foto_path) : null,
            ]);

        $umkmMarkers = $umkms
            ->filter(fn ($u) => $u->latitude !== null && $u->longitude !== null)
            ->toBase()
            ->map(fn ($u) => [
                'id' => 'umkm-'.$u->id,
                'lat' => (float) $u->latitude,
                'lng' => (float) $u->longitude,
                'name' => $u->nama_umkm,
                'category' => 'UMKM',
                'address' => $u->alamat,
                'marker_type' => 'UMKM',
                'dusun_id' => $dusun->id,
                'detail_url' => route('umkm.show', $u->id),
                'photo_url' => $u->foto_utama_path ? asset('storage/'.$u->foto_utama_path) : null,
            ]);

        $kontakMarkers = $kontaks
            ->filter(fn ($k) => $k->latitude !== null && $k->longitude !== null)
            ->toBase()
            ->map(fn ($k) => [
                'id' => 'kontak-'.$k->id,
                'lat' => (float) $k->latitude,
                'lng' => (float) $k->longitude,
                'name' => $k->nama,
                'category' => 'PELAYANAN',
                'address' => $k->alamat_pelayanan,
                'marker_type' => 'PELAYANAN',
                'dusun_id' => $dusun->id,
                'detail_url' => route('dusun.show', $dusun->id).'#kontak-pelayanan',
                'photo_url' => $k->foto_path ? asset('storage/'.$k->foto_path) : null,
            ]);

        $markers = $fasilitasMarkers->concat($umkmMarkers)->concat($kontakMarkers)->values();

        $categoryOptions = $markers->pluck('category')->unique()->values();

        $mapConfig = ['center' => [-7.6298, 110.8603], 'zoom' => 14];

        return view('public.dusun', [
            'dusun' => $dusun,
            'kontaks' => $kontaks,
            'umkms' => $umkms,
            'fasilitas' => $fasilitas,
            'agendas' => $agendas,
            'pengumumans' => $pengumumans,
            'agendaCount' => $agendaCount,
            'pengumumanCount' => $pengumumanCount,
            'categoryOptions' => $categoryOptions,
            'mapConfigJson' => Js::from($mapConfig),
            'mapMarkersJson' => Js::from($markers->toArray()),
        ]);
    }
}
