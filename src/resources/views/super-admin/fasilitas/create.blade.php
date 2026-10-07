@extends('layouts.super-admin')

@section('title', 'Tambah Fasilitas Umum')
@section('breadcrumb')
    <a href="{{ route('super-admin.dashboard') }}">Dashboard</a> /
    <a href="{{ route('super-admin.fasilitas.index') }}">Kelola Fasilitas</a> /
    <span>Tambah Baru</span>
@endsection

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-title">Tambah Fasilitas Umum</h1>
    <p class="admin-page-desc">Daftarkan sarana dan prasarana umum baru di wilayah desa/dusun.</p>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Formulir Fasilitas</h2>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="{{ route('super-admin.fasilitas.store') }}" enctype="multipart/form-data" class="admin-form">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="dusun_id" class="form-label">
                        Wilayah Dusun <span class="required-mark">*</span>
                    </label>
                    <select name="dusun_id" id="dusun_id" class="form-select @error('dusun_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Wilayah Dusun --</option>
                        @foreach($dusuns as $dusun)
                            <option value="{{ $dusun->id }}" {{ old('dusun_id') == $dusun->id ? 'selected' : '' }}>
                                {{ $dusun->nama_dusun }}
                            </option>
                        @endforeach
                    </select>
                    @error('dusun_id')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="kategori_fasilitas_id" class="form-label">
                        Kategori Fasilitas <span class="required-mark">*</span>
                    </label>
                    <select name="kategori_fasilitas_id" id="kategori_fasilitas_id" class="form-select @error('kategori_fasilitas_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoriList as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_fasilitas_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_fasilitas_id')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="nama" class="form-label">
                        Nama Fasilitas <span class="required-mark">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama"
                        id="nama"
                        class="form-input @error('nama') is-invalid @enderror"
                        value="{{ old('nama') }}"
                        required
                        maxlength="200"
                        placeholder="Contoh: Balai Pertemuan Dusun Krajan"
                    >
                    @error('nama')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nomor_whatsapp" class="form-label">
                        Nomor WhatsApp Pengelola <span class="optional-tag">(Opsional)</span>
                    </label>
                    <input
                        type="text"
                        name="nomor_whatsapp"
                        id="nomor_whatsapp"
                        class="form-input @error('nomor_whatsapp') is-invalid @enderror"
                        value="{{ old('nomor_whatsapp') }}"
                        maxlength="32"
                        placeholder="Contoh: 081234567890"
                    >
                    @error('nomor_whatsapp')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <div class="form-label-row">
                    <label for="deskripsi" class="form-label">
                        Deskripsi Fasilitas <span class="required-mark">*</span>
                    </label>
                    <button type="button" class="btn-ai-assist" data-ai-feature="fasilitas_draft" data-target-text="deskripsi" data-target-title="nama">
                        <span class="ai-sparkle-icon">✨</span> Bantu Tulis Deskripsi Fasilitas
                    </button>
                </div>
                <textarea
                    name="deskripsi"
                    id="deskripsi"
                    rows="3"
                    class="form-textarea @error('deskripsi') is-invalid @enderror"
                    required
                    placeholder="Jelaskan mengenai fungsi fasilitas, kapasitas, sarana pendukung yang tersedia..."
                >{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="alamat" class="form-label">
                    Alamat / Lokasi Fasilitas <span class="required-mark">*</span>
                </label>
                <textarea
                    name="alamat"
                    id="alamat"
                    rows="2"
                    class="form-textarea @error('alamat') is-invalid @enderror"
                    required
                    placeholder="Contoh: Jl. Dusun Krajan No. 12"
                >{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="foto" class="form-label">
                    Foto Fasilitas <span class="optional-tag">(Opsional, Maks. 3MB, Format JPG/PNG/WebP)</span>
                </label>
                <input
                    type="file"
                    name="foto"
                    id="foto"
                    accept="image/jpeg,image/png,image/webp"
                    class="form-file @error('foto') is-invalid @enderror"
                >
                @error('foto')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Coordinate Picker (REQUIRED for Fasilitas) -->
            <div class="form-group">
                <label class="form-label">Titik Koordinat Peta <span class="required-mark">*</span></label>
                <x-admin.coordinate-picker
                    :latitude="old('latitude')"
                    :longitude="old('longitude')"
                    :required="true"
                    mapId="saFasilitasCreateMap"
                />
            </div>

            <div class="form-actions">
                <a href="{{ route('super-admin.fasilitas.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Fasilitas</button>
            </div>
        </form>
    </div>
</div>
@endsection
