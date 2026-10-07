<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\AgendaKegiatanRequest;
use App\Models\AgendaKegiatan;
use App\Models\AgendaMedia;
use App\Models\Desa;
use App\Models\Dusun;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AgendaKegiatanController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'active');
        $scopeFilter = $request->query('scope_level');
        $dusunFilter = $request->query('dusun_id');

        $query = AgendaKegiatan::with(['dusun', 'agendaMedias']);

        if ($statusFilter === 'trashed') {
            $query->onlyTrashed();
        } elseif ($statusFilter === 'all') {
            $query->withTrashed();
        }

        if (! empty($scopeFilter)) {
            $query->where('scope_level', $scopeFilter);
        }

        if (! empty($dusunFilter)) {
            $query->where('dusun_id', $dusunFilter);
        }

        $agendaList = $query->orderByDesc('tanggal_mulai')->paginate(15)->withQueryString();
        $dusuns = Dusun::orderBy('nama_dusun')->get();

        return view('super-admin.agenda.index', compact(
            'agendaList',
            'dusuns',
            'statusFilter',
            'scopeFilter',
            'dusunFilter'
        ));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', [AgendaKegiatan::class, 'DESA']);
        $dusuns = Dusun::orderBy('nama_dusun')->get();

        return view('super-admin.agenda.create', compact('dusuns'));
    }

    public function store(AgendaKegiatanRequest $request, MediaService $mediaService): RedirectResponse
    {
        $this->authorize('create', [AgendaKegiatan::class, $request->input('scope_level'), $request->input('dusun_id')]);

        $desa = Desa::firstOrFail();
        $validated = $request->validated();

        DB::transaction(function () use ($desa, $validated, $request, $mediaService): void {
            $agenda = AgendaKegiatan::forceCreate([
                'desa_id' => $desa->id,
                'dusun_id' => $validated['scope_level'] === 'DUSUN' ? $validated['dusun_id'] : null,
                'scope_level' => $validated['scope_level'],
                'judul' => $validated['judul'],
                'deskripsi_singkat' => $validated['deskripsi_singkat'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
                'jam' => $validated['jam'] ?? null,
                'lokasi_text' => $validated['lokasi_text'],
                'manual_status_override' => $validated['manual_status_override'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $allFiles = $request->allFiles();
            $mediaFiles = $allFiles['media'] ?? [];
            if (! empty($mediaFiles)) {
                foreach ($mediaFiles as $index => $item) {
                    $uploadedFile = $item['file'] ?? (is_a($item, UploadedFile::class) ? $item : null);
                    if ($uploadedFile instanceof UploadedFile) {
                        $mediaPath = $mediaService->storeImage($uploadedFile, 'agenda');
                        $role = $validated['media'][$index]['role'] ?? 'POSTER_AWAL';

                        AgendaMedia::forceCreate([
                            'agenda_kegiatan_id' => $agenda->id,
                            'media_path' => $mediaPath,
                            'media_role' => $role,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        });

        return redirect()->route('super-admin.agenda.index')
            ->with('success', 'Data agenda kegiatan berhasil disimpan.');
    }

    public function edit(Request $request, int $id): View
    {
        $agenda = AgendaKegiatan::withTrashed()->with('agendaMedias')->findOrFail($id);
        $this->authorize('view', $agenda);

        $dusuns = Dusun::orderBy('nama_dusun')->get();

        return view('super-admin.agenda.edit', compact('agenda', 'dusuns'));
    }

    public function update(AgendaKegiatanRequest $request, int $id, MediaService $mediaService): RedirectResponse
    {
        $agenda = AgendaKegiatan::withTrashed()->with('agendaMedias')->findOrFail($id);
        $this->authorize('update', $agenda);

        $validated = $request->validated();
        $deletedMediaPaths = [];

        DB::transaction(function () use ($agenda, $validated, $request, $mediaService, &$deletedMediaPaths): void {
            $agenda->scope_level = $validated['scope_level'];
            $agenda->dusun_id = $validated['scope_level'] === 'DUSUN' ? $validated['dusun_id'] : null;
            $agenda->judul = $validated['judul'];
            $agenda->deskripsi_singkat = $validated['deskripsi_singkat'];
            $agenda->tanggal_mulai = $validated['tanggal_mulai'];
            $agenda->tanggal_selesai = $validated['tanggal_selesai'] ?? null;
            $agenda->jam = $validated['jam'] ?? null;
            $agenda->lokasi_text = $validated['lokasi_text'];
            $agenda->manual_status_override = $validated['manual_status_override'] ?? null;
            $agenda->save();

            // Handle media removal
            if (! empty($validated['existing_media_remove'])) {
                $mediasToRemove = AgendaMedia::where('agenda_kegiatan_id', $agenda->id)
                    ->whereIn('id', $validated['existing_media_remove'])
                    ->get();

                foreach ($mediasToRemove as $m) {
                    $deletedMediaPaths[] = $m->media_path;
                    $m->delete();
                }
            }

            // Handle new media uploads
            $allFiles = $request->allFiles();
            $mediaFiles = $allFiles['media'] ?? [];
            if (! empty($mediaFiles)) {
                foreach ($mediaFiles as $index => $item) {
                    $uploadedFile = $item['file'] ?? (is_a($item, UploadedFile::class) ? $item : null);
                    if ($uploadedFile instanceof UploadedFile) {
                        $mediaPath = $mediaService->storeImage($uploadedFile, 'agenda');
                        $role = $validated['media'][$index]['role'] ?? 'DOKUMENTASI';

                        AgendaMedia::forceCreate([
                            'agenda_kegiatan_id' => $agenda->id,
                            'media_path' => $mediaPath,
                            'media_role' => $role,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        });

        foreach ($deletedMediaPaths as $path) {
            $mediaService->deleteImage($path);
        }

        return redirect()->route('super-admin.agenda.index')
            ->with('success', 'Data agenda kegiatan berhasil diperbarui.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $agenda = AgendaKegiatan::findOrFail($id);
        $this->authorize('delete', $agenda);

        $agenda->delete(); // Soft delete. Media retained.

        return redirect()->route('super-admin.agenda.index')
            ->with('success', 'Data agenda kegiatan berhasil dinonaktifkan (Soft Delete).');
    }

    public function restore(Request $request, int $id): RedirectResponse
    {
        $agenda = AgendaKegiatan::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $agenda);

        $agenda->restore();

        return redirect()->route('super-admin.agenda.index', ['status' => 'trashed'])
            ->with('success', 'Data agenda kegiatan berhasil dipulihkan (Restore).');
    }

    public function forceDelete(Request $request, int $id, MediaService $mediaService): RedirectResponse
    {
        $agenda = AgendaKegiatan::onlyTrashed()->with('agendaMedias')->findOrFail($id);
        $this->authorize('forceDelete', $agenda);

        $mediaPaths = $agenda->agendaMedias->pluck('media_path')->all();

        $agenda->forceDelete(); // DB CASCADE removes agenda_medias

        foreach ($mediaPaths as $path) {
            $mediaService->deleteImage($path);
        }

        return redirect()->route('super-admin.agenda.index', ['status' => 'trashed'])
            ->with('success', 'Data agenda kegiatan beserta seluruh foto berhasil dihapus permanen.');
    }
}
