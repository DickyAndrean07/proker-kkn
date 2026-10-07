@extends('layouts.admin')

@section('title', 'Kelola Profil Dusun')
@section('breadcrumb')
    <a href="{{ route('admin-dusun.dashboard') }}">Dashboard</a> / <span>Profil Dusun</span>
@endsection

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Kelola Profil Dusun</h1>
        <p class="admin-page-desc">Perbarui informasi data profil dan kontak kepala dusun untuk ditampilkan di halaman publik.</p>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Formulir Profil Dusun</h2>
        <span class="badge {{ $dusun->status_dusun === 'ACTIVE' ? 'badge-success' : 'badge-danger' }}">
            Status: {{ $dusun->status_dusun === 'ACTIVE' ? 'Aktif' : 'Nonaktif' }}
        </span>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="{{ route('admin-dusun.profil.update') }}" enctype="multipart/form-data" class="admin-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nama_dusun" class="form-label">
                    Nama Dusun <span class="required-mark">*</span>
                </label>
                <input
                    type="text"
                    name="nama_dusun"
                    id="nama_dusun"
                    class="form-input @error('nama_dusun') is-invalid @enderror"
                    value="{{ old('nama_dusun', $dusun->nama_dusun) }}"
                    required
                    maxlength="150"
                >
                @error('nama_dusun')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <div class="form-label-row">
                    <label for="deskripsi_singkat" class="form-label">
                        Deskripsi Singkat Dusun <span class="required-mark">*</span>
                    </label>
                    <button type="button" class="btn-ai-assist" data-ai-feature="dusun_draft" data-target-text="deskripsi_singkat" data-entity-name="{{ $dusun->nama_dusun }}">
                        <span class="ai-sparkle-icon">✨</span> Bantuan Tulis AI
                    </button>
                </div>
                <textarea
                    name="deskripsi_singkat"
                    id="deskripsi_singkat"
                    rows="4"
                    class="form-textarea @error('deskripsi_singkat') is-invalid @enderror"
                    required
                >{{ old('deskripsi_singkat', $dusun->deskripsi_singkat) }}</textarea>
                @error('deskripsi_singkat')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="nama_kepala_dusun" class="form-label">
                        Nama Kepala Dusun <span class="required-mark">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama_kepala_dusun"
                        id="nama_kepala_dusun"
                        class="form-input @error('nama_kepala_dusun') is-invalid @enderror"
                        value="{{ old('nama_kepala_dusun', $dusun->nama_kepala_dusun) }}"
                        required
                        maxlength="150"
                    >
                    @error('nama_kepala_dusun')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jumlah_rt" class="form-label">
                        Jumlah RT <span class="required-mark">*</span>
                    </label>
                    <input
                        type="number"
                        name="jumlah_rt"
                        id="jumlah_rt"
                        min="0"
                        max="65535"
                        class="form-input @error('jumlah_rt') is-invalid @enderror"
                        value="{{ old('jumlah_rt', $dusun->jumlah_rt) }}"
                        required
                    >
                    @error('jumlah_rt')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jumlah_rw" class="form-label">
                        Jumlah RW <span class="required-mark">*</span>
                    </label>
                    <input
                        type="number"
                        name="jumlah_rw"
                        id="jumlah_rw"
                        min="0"
                        max="65535"
                        class="form-input @error('jumlah_rw') is-invalid @enderror"
                        value="{{ old('jumlah_rw', $dusun->jumlah_rw) }}"
                        required
                    >
                    @error('jumlah_rw')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="foto_kepala_dusun" class="form-label">
                    Foto Kepala Dusun <span class="optional-tag">(Opsional, Maks. 3MB, Format JPG/PNG/WebP)</span>
                </label>
                <div class="admin-avatar-upload-preview" style="display: flex; align-items: center; gap: 16px; margin-bottom: 10px;">
                    <div style="width: 64px; height: 64px; border-radius: 50%; overflow: hidden; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #475569; font-size: 20px; border: 2px solid #cbd5e1; flex-shrink: 0;">
                        @if($dusun->foto_kepala_dusun_path)
                            <img src="{{ asset('storage/' . $dusun->foto_kepala_dusun_path) }}" alt="Foto Kepala Dusun" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            {{ strtoupper(substr($dusun->nama_kepala_dusun, 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <span class="preview-text" style="font-size: 13px; color: #64748b; display: block;">
                            @if($dusun->foto_kepala_dusun_path)
                                Foto pimpinan saat ini tersimpan. Unggah berkas baru jika ingin memperbarui.
                            @else
                                Belum ada foto khusus. Avatar pimpinan saat ini menggunakan inisial huruf nama.
                            @endif
                        </span>
                    </div>
                </div>
                <input
                    type="file"
                    name="foto_kepala_dusun"
                    id="foto_kepala_dusun"
                    accept="image/jpeg,image/png,image/webp"
                    class="form-file @error('foto_kepala_dusun') is-invalid @enderror"
                >
                @error('foto_kepala_dusun')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="banner" class="form-label">
                    Foto Banner Dusun <span class="optional-tag">(Opsional, Maks. 3MB, Format JPG/PNG/WebP)</span>
                </label>
                @if($dusun->banner_path)
                    <div class="current-media-preview">
                        <img src="{{ asset('storage/' . $dusun->banner_path) }}" alt="Banner Dusun saat ini" class="preview-img">
                        <span class="preview-text">Banner saat ini tersimpan. Unggah berkas baru jika ingin mengganti.</span>
                    </div>
                @endif
                <input
                    type="file"
                    name="banner"
                    id="banner"
                    accept="image/jpeg,image/png,image/webp"
                    class="form-file @error('banner') is-invalid @enderror"
                >
                @error('banner')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('admin-dusun.dashboard') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
