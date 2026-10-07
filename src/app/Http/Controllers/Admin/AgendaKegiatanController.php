<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AgendaKegiatanRequest;
use App\Models\AgendaKegiatan;
use App\Models\AgendaMedia;
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
        $dusun = $request->user()->dusun;
        $agendaList = AgendaKegiatan::where('dusun_id', $dusun->id)
            ->with('agendaMedias')
            ->orderByDesc('tanggal_mulai')
            ->paginate(15);

        return view('admin.agenda.index', compact('agendaList', 'dusun'));
    }

    public function create(Request $request): View
    {
        $dusun = $request->user()->dusun;
        $this->authorize('create', [AgendaKegiatan::class, 'DUSUN', $dusun->id]);

        return view('admin.agenda.create', compact('dusun'));
    }

    public function store(AgendaKegiatanRequest $request, MediaService $mediaService): RedirectResponse
    {
        $dusun = $request->user()->dusun;
        $this->authorize('create', [AgendaKegiatan::class, 'DUSUN', $dusun->id]);

        $validated = $request->validated();

        DB::transaction(function () use ($dusun, $validated, $request, $mediaService): void {
            $agenda = AgendaKegiatan::forceCreate([
                'desa_id' => $dusun->desa_id,
                'dusun_id' => $dusun->id,
                'scope_level' => 'DUSUN',
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

        return redirect()->route('admin-dusun.agenda.index')
            ->with('success', 'Data agenda kegiatan berhasil disimpan.');
    }

    public function edit(Request $request, int $id): View
    {
        $dusun = $request->user()->dusun;
        $agenda = AgendaKegiatan::where('dusun_id', $dusun->id)
            ->with('agendaMedias')
            ->findOrFail($id);
        $this->authorize('view', $agenda);

        return view('admin.agenda.edit', compact('agenda', 'dusun'));
    }

    public function update(AgendaKegiatanRequest $request, int $id, MediaService $mediaService): RedirectResponse
    {
        $dusun = $request->user()->dusun;
        $agenda = AgendaKegiatan::where('dusun_id', $dusun->id)
            ->with('agendaMedias')
            ->findOrFail($id);
        $this->authorize('update', $agenda);

        $validated = $request->validated();
        $deletedMediaPaths = [];

        DB::transaction(function () use ($agenda, $validated, $request, $mediaService, &$deletedMediaPaths): void {
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

        return redirect()->route('admin-dusun.agenda.index')
            ->with('success', 'Data agenda kegiatan berhasil disimpan.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $dusun = $request->user()->dusun;
        $agenda = AgendaKegiatan::where('dusun_id', $dusun->id)->findOrFail($id);
        $this->authorize('delete', $agenda);

        $agenda->delete(); // Soft delete. Media retained.

        return redirect()->route('admin-dusun.agenda.index')
            ->with('success', 'Data berhasil dinonaktifkan.');
    }
}
