<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\KategoriFasilitasRequest;
use App\Models\Desa;
use App\Models\KategoriFasilitas;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KategoriFasilitasController extends Controller
{
    public function index(Request $request): View
    {
        $kategoriList = KategoriFasilitas::withCount('fasilitas')
            ->orderBy('nama_kategori')
            ->get();

        return view('super-admin.kategori.index', compact('kategoriList'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', KategoriFasilitas::class);

        return view('super-admin.kategori.create');
    }

    public function store(KategoriFasilitasRequest $request): RedirectResponse
    {
        $this->authorize('create', KategoriFasilitas::class);

        $desa = Desa::firstOrFail();
        $validated = $request->validated();

        KategoriFasilitas::forceCreate([
            'desa_id' => $desa->id,
            'nama_kategori' => $validated['nama_kategori'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('super-admin.kategori-fasilitas.index')
            ->with('success', 'Kategori fasilitas berhasil ditambahkan.');
    }

    public function edit(Request $request, int $id): View
    {
        $kategori = KategoriFasilitas::findOrFail($id);
        $this->authorize('update', $kategori);

        return view('super-admin.kategori.edit', compact('kategori'));
    }

    public function update(KategoriFasilitasRequest $request, int $id): RedirectResponse
    {
        $kategori = KategoriFasilitas::findOrFail($id);
        $this->authorize('update', $kategori);

        $validated = $request->validated();

        $kategori->nama_kategori = $validated['nama_kategori'];
        $kategori->save();

        return redirect()->route('super-admin.kategori-fasilitas.index')
            ->with('success', 'Kategori fasilitas berhasil diperbarui.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $kategori = KategoriFasilitas::withCount('fasilitas')->findOrFail($id);
        $this->authorize('delete', $kategori);

        if ($kategori->fasilitas_count > 0) {
            return redirect()->route('super-admin.kategori-fasilitas.index')
                ->with('error', "Kategori '{$kategori->nama_kategori}' tidak dapat dihapus karena masih digunakan oleh {$kategori->fasilitas_count} fasilitas.");
        }

        try {
            $kategori->delete();
        } catch (QueryException $e) {
            return redirect()->route('super-admin.kategori-fasilitas.index')
                ->with('error', "Kategori '{$kategori->nama_kategori}' sedang digunakan dan tidak dapat dihapus.");
        }

        return redirect()->route('super-admin.kategori-fasilitas.index')
            ->with('success', 'Kategori fasilitas berhasil dihapus.');
    }
}
