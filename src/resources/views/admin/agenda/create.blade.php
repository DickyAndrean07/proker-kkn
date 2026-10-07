@extends('layouts.admin')

@section('title', 'Tambah Agenda & Kegiatan')
@section('breadcrumb')
    <a href="{{ route('admin-dusun.dashboard') }}">Dashboard</a> /
    <a href="{{ route('admin-dusun.agenda.index') }}">Agenda & Kegiatan</a> /
    <span>Tambah Baru</span>
@endsection

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Tambah Agenda / Kegiatan Dusun</h1>
        <p class="admin-page-desc">Jadwalkan kegiatan masyarakat di Dusun {{ $dusun->nama_dusun }}.</p>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Formulir Agenda Baru</h2>
        <span class="badge badge-info">Cakupan Wilayah: Dusun {{ $dusun->nama_dusun }}</span>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="{{ route('admin-dusun.agenda.store') }}" enctype="multipart/form-data" class="admin-form">
            @csrf

            <div class="form-group">
                <div class="form-label-row">
                    <label for="judul" class="form-label">
                        Judul Agenda / Kegiatan <span class="required-mark">*</span>
                    </label>
                    <button type="button" class="btn-ai-assist" data-ai-feature="agenda_draft" data-target-title="judul" data-target-text="deskripsi_singkat">
                        <span class="ai-sparkle-icon">✨</span> Bantu Tulis Agenda
                    </button>
                </div>
                <input
                    type="text"
                    name="judul"
                    id="judul"
                    class="form-input @error('judul') is-invalid @enderror"
                    value="{{ old('judul') }}"
                    required
                    maxlength="255"
                    placeholder="Contoh: Kerja Bakti Pembersihan Saluran Irigasi"
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
                        value="{{ old('tanggal_mulai') }}"
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
                        value="{{ old('tanggal_selesai') }}"
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
                        value="{{ old('jam') }}"
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
                        value="{{ old('lokasi_text') }}"
                        required
                        maxlength="255"
                        placeholder="Contoh: Balai Pertemuan RT 02"
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
                        <option value="AKAN_DATANG" {{ old('manual_status_override') === 'AKAN_DATANG' ? 'selected' : '' }}>Akan Datang</option>
                        <option value="BERLANGSUNG" {{ old('manual_status_override') === 'BERLANGSUNG' ? 'selected' : '' }}>Sedang Berlangsung</option>
                        <option value="SELESAI" {{ old('manual_status_override') === 'SELESAI' ? 'selected' : '' }}>Selesai</option>
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
                    placeholder="Tuliskan rincian kegiatan, tujuan, susunan acara, atau perlengkapan yang perlu dibawa warga..."
                >{{ old('deskripsi_singkat') }}</textarea>
                @error('deskripsi_singkat')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Media / Foto Kegiatan (Repeatable) -->
            <div class="form-section-box">
                <div class="flex-between" style="margin-bottom: 0.75rem;">
                    <div>
                        <h3 class="section-box-title">Media & Foto Kegiatan</h3>
                        <p class="section-box-desc">Unggah poster pamflet atau foto dokumentasi (Maks. 3MB per foto, JPG/PNG/WebP).</p>
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
                                <option value="DOKUMENTASI">Foto Dokumentasi</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-media-btn">Hapus</button>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin-dusun.agenda.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Agenda</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('mediaRowsContainer');
    const addBtn = document.getElementById('addMediaBtn');
    let nextIdx = container.querySelectorAll('.media-input-row').length;

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
