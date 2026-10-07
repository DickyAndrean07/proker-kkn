<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AgendaKegiatan;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\Fasilitas;
use App\Models\KontakPelayanan;
use App\Models\Pengumuman;
use App\Models\Umkm;
use Illuminate\Support\Js;

class HomeController extends Controller
{
    /**
     * UX-SCR-001 — Homepage Desa Bendung.
     * UX-SCR-008 — Peta Desa (as section #peta-desa within this page).
     */
    public function __invoke()
    {
        // Single Desa record (root context)
        $desa = Desa::query()->first();

        // Pilihan Dusun: ACTIVE only (DEV-04-DEC-001 Public Visibility Principle)
        $dusuns = Dusun::publicActive()->orderBy('nama_dusun')->get();

        // Pengumuman Desa terbaru (DESA scope, active, not soft-deleted)
        $pengumumans = Pengumuman::desaScope()
            ->activeAnnouncements()
            ->latest('created_at')
            ->take(5)
            ->get();

        // Agenda Desa terbaru (DESA scope, not soft-deleted)
        $agendas = AgendaKegiatan::desaScope()
            ->orderBy('tanggal_mulai', 'desc')
            ->take(5)
            ->get();

        $pengumumanCount = Pengumuman::desaScope()
            ->activeAnnouncements()
            ->count();

        $agendaCount = AgendaKegiatan::desaScope()
            ->count();

        // ── Peta Desa map marker data ────────────────────────────────────────
        // Markers: Fasilitas + UMKM (with coords) + KontakPelayanan (with coords)
        // All scoped to ACTIVE Dusuns; soft-deleted excluded by SoftDeletes default.

        $fasilitas = Fasilitas::withCoordinates()
            ->withinActiveDusun()
            ->with(['dusun:id,nama_dusun', 'kategoriFasilitas:id,nama_kategori'])
            ->get();

        $umkms = Umkm::withCoordinates()
            ->withinActiveDusun()
            ->with(['dusun:id,nama_dusun', 'produkUmkms:id,umkm_id,nama_produk'])
            ->get();

        $kontaks = KontakPelayanan::withCoordinates()
            ->withinActiveDusun()
            ->with(['dusun:id,nama_dusun'])
            ->get();

        $fasilitasMarkers = $fasilitas->map(fn ($f) => [
            'id' => 'fasilitas-'.$f->id,
            'lat' => (float) $f->latitude,
            'lng' => (float) $f->longitude,
            'name' => $f->nama,
            'category' => $f->kategoriFasilitas?->nama_kategori ?? 'Fasilitas',
            'address' => $f->alamat,
            'marker_type' => 'FASILITAS',
            'dusun_id' => $f->dusun_id,
            'detail_url' => route('fasilitas.show', $f->id),
            'photo_url' => $f->foto_path ? asset('storage/'.$f->foto_path) : null,
        ]);

        $umkmMarkers = $umkms->map(fn ($u) => [
            'id' => 'umkm-'.$u->id,
            'lat' => (float) $u->latitude,
            'lng' => (float) $u->longitude,
            'name' => $u->nama_umkm,
            'category' => 'UMKM',
            'address' => $u->alamat,
            'marker_type' => 'UMKM',
            'dusun_id' => $u->dusun_id,
            'detail_url' => route('umkm.show', $u->id),
            'photo_url' => $u->foto_utama_path ? asset('storage/'.$u->foto_utama_path) : null,
        ]);

        $kontakMarkers = $kontaks->map(fn ($k) => [
            'id' => 'kontak-'.$k->id,
            'lat' => (float) $k->latitude,
            'lng' => (float) $k->longitude,
            'name' => $k->nama,
            'category' => 'PELAYANAN',
            'address' => $k->alamat_pelayanan,
            'marker_type' => 'PELAYANAN',
            'dusun_id' => $k->dusun_id,
            // Pelayanan markers link to the Dusun's kontak section (no 5th detail route)
            'detail_url' => route('dusun.show', $k->dusun_id).'#kontak-pelayanan',
            'photo_url' => $k->foto_path ? asset('storage/'.$k->foto_path) : null,
        ]);

        $markers = $fasilitasMarkers->concat($umkmMarkers)->concat($kontakMarkers)->values();

        // Build unique Dusun list for map filter
        $dusunFilterOptions = $dusuns->map(fn ($d) => ['id' => $d->id, 'nama' => $d->nama_dusun]);

        // Build unique category list for map filter
        $categoryOptions = $markers->pluck('category')->unique()->values();

        $mapConfig = [
            'center' => [-7.6298, 110.8603], // default center Jawa Tengah
            'zoom' => 13,
        ];

        return view('public.home', [
            'desa' => $desa,
            'dusuns' => $dusuns,
            'pengumumans' => $pengumumans,
            'agendas' => $agendas,
            'pengumumanCount' => $pengumumanCount,
            'agendaCount' => $agendaCount,
            'dusunFilterOptions' => $dusunFilterOptions,
            'categoryOptions' => $categoryOptions,
            'petaFasilitas' => $fasilitas,
            'petaUmkms' => $umkms,
            'petaKontaks' => $kontaks,
            'petaTotalCount' => $fasilitas->count() + $umkms->count() + $kontaks->count(),
            'umkmCount' => $umkms->count(),
            'fasilitasCount' => $fasilitas->count(),
            'kontakCount' => $kontaks->count(),
            'mapConfigJson' => Js::from($mapConfig),
            'mapMarkersJson' => Js::from($markers->toArray()),
        ]);
    }
}
