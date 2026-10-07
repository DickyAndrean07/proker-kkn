@extends('layouts.super-admin')

@section('title', 'Identitas Desa')
@section('breadcrumb')
    <a href="{{ route('super-admin.dashboard') }}">Dashboard</a> / <span>Identitas Desa</span>
@endsection

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-title">Kelola Identitas Desa</h1>
    <p class="admin-page-desc">Informasi utama profil dan kontak pemerintahan {{ $desa->nama_desa }}.</p>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Formulir Identitas Desa</h2>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="{{ route('super-admin.desa.update') }}" enctype="multipart/form-data" class="admin-form">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label for="nama_desa" class="form-label">
                        Nama Desa <span class="required-mark">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama_desa"
                        id="nama_desa"
                        class="form-input @error('nama_desa') is-invalid @enderror"
                        value="{{ old('nama_desa', $desa->nama_desa) }}"
                        required
                        maxlength="150"
                    >
                    @error('nama_desa')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nama_kepala_desa" class="form-label">
                        Nama Kepala Desa (Lurah) <span class="required-mark">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama_kepala_desa"
                        id="nama_kepala_desa"
                        class="form-input @error('nama_kepala_desa') is-invalid @enderror"
                        value="{{ old('nama_kepala_desa', $desa->nama_kepala_desa) }}"
                        required
                        maxlength="150"
                    >
                    @error('nama_kepala_desa')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="nomor_kontak" class="form-label">
                        Nomor Kontak Resmi Desa <span class="required-mark">*</span>
                    </label>
                    <input
                        type="text"
                        name="nomor_kontak"
                        id="nomor_kontak"
                        class="form-input @error('nomor_kontak') is-invalid @enderror"
                        value="{{ old('nomor_kontak', $desa->nomor_kontak) }}"
                        required
                        maxlength="32"
                    >
                    @error('nomor_kontak')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jam_pelayanan" class="form-label">
                        Jam Pelayanan Kantor Desa <span class="required-mark">*</span>
                    </label>
                    <input
                        type="text"
                        name="jam_pelayanan"
                        id="jam_pelayanan"
                        class="form-input @error('jam_pelayanan') is-invalid @enderror"
                        value="{{ old('jam_pelayanan', $desa->jam_pelayanan) }}"
                        required
                        maxlength="255"
                        placeholder="Contoh: Senin - Jumat, 08.00 - 15.00 WIB"
                    >
                    @error('jam_pelayanan')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="alamat_kantor" class="form-label">
                    Alamat Kantor Balai Desa <span class="required-mark">*</span>
                </label>
                <textarea
                    name="alamat_kantor"
                    id="alamat_kantor"
                    rows="2"
                    class="form-textarea @error('alamat_kantor') is-invalid @enderror"
                    required
                >{{ old('alamat_kantor', $desa->alamat_kantor) }}</textarea>
                @error('alamat_kantor')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <div class="form-label-row">
                    <label for="deskripsi_singkat" class="form-label">
                        Deskripsi / Selayang Pandang Desa <span class="required-mark">*</span>
                    </label>
                    <button type="button" class="btn-ai-assist" data-ai-feature="desa_draft" data-target-text="deskripsi_singkat" data-entity-name="{{ $desa->nama_desa }}">
                        <span class="ai-sparkle-icon">✨</span> Bantuan Tulis AI
                    </button>
                </div>
                <textarea
                    name="deskripsi_singkat"
                    id="deskripsi_singkat"
                    rows="4"
                    class="form-textarea @error('deskripsi_singkat') is-invalid @enderror"
                    required
                >{{ old('deskripsi_singkat', $desa->deskripsi_singkat) }}</textarea>
                @error('deskripsi_singkat')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="banner" class="form-label">
                    Foto Banner Utama Desa <span class="optional-tag">(Opsional, Maks. 3MB, Format JPG/PNG/WebP)</span>
                </label>
                @if($desa->banner_path)
                    <div class="current-media-preview">
                        <img src="{{ asset('storage/' . $desa->banner_path) }}" alt="Banner {{ $desa->nama_desa }}" class="preview-img">
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
                <button type="submit" class="btn btn-primary">Simpan Identitas Desa</button>
            </div>
        </form>
    </div>
</div>
@endsection
