@extends('layouts.admin')

@section('title', 'Edit Agenda & Kegiatan')
@section('breadcrumb')
    <a href="{{ route('admin-dusun.dashboard') }}">Dashboard</a> /
    <a href="{{ route('admin-dusun.agenda.index') }}">Agenda & Kegiatan</a> /
    <span>Edit: {{ $agenda->judul }}</span>
@endsection

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Edit Agenda / Kegiatan Dusun</h1>
        <p class="admin-page-desc">Perbarui data agenda kegiatan di Dusun {{ $dusun->nama_dusun }}.</p>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Formulir Edit Agenda</h2>
        <span class="badge badge-info">Cakupan Wilayah: Dusun {{ $dusun->nama_dusun }}</span>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="{{ route('admin-dusun.agenda.update', $agenda->id) }}" enctype="multipart/form-data" class="admin-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <div class="form-label-row">
                    <label for="judul" class="form-label">
                        Judul Agenda / Kegiatan <span class="required-mark">*</span>
                    </label>
                    <button type="button" class="btn-ai-assist" data-ai-feature="agenda_draft" data-target-title="judul" data-target-text="deskripsi_singkat">
                        <span class="ai-sparkle-icon">✨</span> Bantu Tulis / Edit AI
                    </button>
                </div>
                <input
                    type="text"
                    name="judul"
                    id="judul"
                    class="form-input @error('judul') is-invalid @enderror"
                    value="{{ old('judul', $agenda->judul) }}"
                    required
                    maxlength="255"
                >
                @error('judul')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tanggal_mulai" class="form-label">
                        Tanggal Mulai <span class="required-mark">*</span>
                    </label>
                    <input
                        type="date"
                        name="tanggal_mulai"
                        id="tanggal_mulai"
                        class="form-input @error('tanggal_mulai') is-invalid @enderror"
                        value="{{ old('tanggal_mulai', $agenda->tanggal_mulai?->format('Y-m-d')) }}"
                        required
                    >
                    @error('tanggal_mulai')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tanggal_selesai" class="form-label">
                        Tanggal Selesai <span class="optional-tag">(Opsional, jika > 1 hari)</span>
                    </label>
                    <input
                        type="date"
                        name="tanggal_selesai"
                        id="tanggal_selesai"
                        class="form-input @error('tanggal_selesai') is-invalid @enderror"
                        value="{{ old('tanggal_selesai', $agenda->tanggal_selesai?->format('Y-m-d')) }}"
                    >
                    @error('tanggal_selesai')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jam" class="form-label">
                        Waktu Kegiatan <span class="optional-tag">(Opsional, JJ:MM)</span>
                    </label>
                    <input
                        type="time"
                        name="jam"
                        id="jam"
                        class="form-input @error('jam') is-invalid @enderror"
                        value="{{ old('jam', $agenda->jam ? substr($agenda->jam, 0, 5) : '') }}"
                    >
                    @error('jam')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="lokasi_text" class="form-label">
                        Lokasi Kegiatan <span class="required-mark">*</span>
                    </label>
                    <input
                        type="text"
                        name="lokasi_text"
                        id="lokasi_text"
                        class="form-input @error('lokasi_text') is-invalid @enderror"
                        value="{{ old('lokasi_text', $agenda->lokasi_text) }}"
                        required
                        maxlength="255"
                    >
                    @error('lokasi_text')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="manual_status_override" class="form-label">
                        Status Pelaksanaan <span class="optional-tag">(Opsional, default otomatis sesuai tanggal)</span>
                    </label>
                    <select name="manual_status_override" id="manual_status_override" class="form-select @error('manual_status_override') is-invalid @enderror">
                        <option value="">Otomatis (Berdasarkan Tanggal)</option>
                        <option value="AKAN_DATANG" {{ old('manual_status_override', $agenda->manual_status_override) === 'AKAN_DATANG' ? 'selected' : '' }}>Akan Datang</option>
                        <option value="BERLANGSUNG" {{ old('manual_status_override', $agenda->manual_status_override) === 'BERLANGSUNG' ? 'selected' : '' }}>Sedang Berlangsung</option>
                        <option value="SELESAI" {{ old('manual_status_override', $agenda->manual_status_override) === 'SELESAI' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('manual_status_override')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="deskripsi_singkat" class="form-label">
                    Deskripsi / Rincian Kegiatan <span class="required-mark">*</span>
                </label>
                <textarea
                    name="deskripsi_singkat"
                    id="deskripsi_singkat"
                    rows="4"
                    class="form-textarea @error('deskripsi_singkat') is-invalid @enderror"
                    required
                >{{ old('deskripsi_singkat', $agenda->deskripsi_singkat) }}</textarea>
                @error('deskripsi_singkat')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Media / Foto Kegiatan yang Sudah Ada -->
            @if($agenda->agendaMedias->isNotEmpty())
                <div class="form-section-box">
                    <h3 class="section-box-title">Media Tersimpan Saat Ini</h3>
                    <p class="section-box-desc">Centang kotak pada foto jika ingin menghapusnya.</p>
                    <div class="existing-media-grid">
                        @foreach($agenda->agendaMedias as $media)
                            <div class="existing-media-card">
                                <img src="{{ asset('storage/' . $media->media_path) }}" alt="Media Agenda" class="media-card-img">
                                <div class="media-card-meta">
                                    <span class="badge {{ $media->media_role === 'POSTER_AWAL' ? 'badge-primary' : 'badge-neutral' }}">
                                        {{ $media->media_role === 'POSTER_AWAL' ? 'Poster' : 'Dokumentasi' }}
                                    </span>
                                    <label class="delete-checkbox-label">
                                        <input type="checkbox" name="existing_media_remove[]" value="{{ $media->id }}">
                                        <span>Hapus Media</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Unggah Media Baru -->
            <div class="form-section-box">
                <div class="flex-between" style="margin-bottom: 0.75rem;">
                    <div>
                        <h3 class="section-box-title">Tambah Media / Foto Baru</h3>
                        <p class="section-box-desc">Unggah poster pamflet atau foto dokumentasi baru (Maks. 3MB per foto, JPG/PNG/WebP).</p>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary" id="addMediaBtn">+ Tambah Berkas Media</button>
                </div>

                <div id="mediaRowsContainer" class="media-rows-wrapper">
                    <div class="media-input-row">
                        <div class="form-group" style="flex: 2; margin-bottom: 0;">
                            <input type="file" name="media[0][file]" accept="image/jpeg,image/png,image/webp" class="form-file">
                        </div>
                        <div class="form-group" style="flex: 1; margin-bottom: 0;">
                            <select name="media[0][role]" class="form-select">
                                <option value="POSTER_AWAL">Poster Pamflet</option>
                                <option value="DOKUMENTASI" selected>Foto Dokumentasi</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-media-btn">Hapus</button>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin-dusun.agenda.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('mediaRowsContainer');
    const addBtn = document.getElementById('addMediaBtn');
    let nextIdx = container.querySelectorAll('.media-input-row').length + 100;

    addBtn.addEventListener('click', () => {
        const row = document.createElement('div');
        row.className = 'media-input-row';
        row.innerHTML = `
            <div class="form-group" style="flex: 2; margin-bottom: 0;">
                <input type="file" name="media[${nextIdx}][file]" accept="image/jpeg,image/png,image/webp" class="form-file">
            </div>
            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                <select name="media[${nextIdx}][role]" class="form-select">
                    <option value="POSTER_AWAL">Poster Pamflet</option>
                    <option value="DOKUMENTASI" selected>Foto Dokumentasi</option>
                </select>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger remove-media-btn">Hapus</button>
        `;
        container.appendChild(row);
        nextIdx++;
    });

    container.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-media-btn')) {
            const rows = container.querySelectorAll('.media-input-row');
            if (rows.length > 1) {
                e.target.closest('.media-input-row').remove();
            } else {
                const row = e.target.closest('.media-input-row');
                row.querySelector('input[type="file"]').value = '';
            }
        }
    });
});
</script>
@endpush
@endsection
