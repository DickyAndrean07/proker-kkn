@extends('layouts.admin')

@section('title', 'Edit Kontak Pelayanan')
@section('breadcrumb')
    <a href="{{ route('admin-dusun.dashboard') }}">Dashboard</a> /
    <a href="{{ route('admin-dusun.kontak.index') }}">Kontak Pelayanan</a> /
    <span>Edit: {{ $kontak->nama }}</span>
@endsection

@section('content')
<div class="admin-page-header">
    <div>
        <p class="admin-page-kicker">Kontak Pelayanan</p>
        <h1 class="admin-page-title">Edit Kontak</h1>
        <p class="admin-page-desc">Perbarui data kontak pelayanan di Dusun {{ $dusun->nama_dusun }}.</p>
    </div>
</div>

<div class="admin-form-shell">
    <form method="POST" action="{{ route('admin-dusun.kontak.update', $kontak->id) }}" enctype="multipart/form-data" class="admin-form">
        @csrf
        @method('PUT')

        <section class="form-section" aria-labelledby="basic-info-title">
            <div class="form-section-header">
                <div>
                    <h2 class="form-section-title" id="basic-info-title">Informasi dasar</h2>
                    <p class="form-section-desc">Nama dan peran yang akan tampil di halaman publik dusun.</p>
                </div>
            </div>
            <div class="form-section-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nama" class="form-label">Nama Petugas / Pengurus <span class="required-mark">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-input @error('nama') is-invalid @enderror" value="{{ old('nama', $kontak->nama) }}" required maxlength="150">
                        @error('nama')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label for="jabatan" class="form-label">Jabatan / Peran Pelayanan <span class="required-mark">*</span></label>
                        <input type="text" name="jabatan" id="jabatan" class="form-input @error('jabatan') is-invalid @enderror" value="{{ old('jabatan', $kontak->jabatan) }}" required maxlength="150">
                        @error('jabatan')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </section>

        <section class="form-section" aria-labelledby="contact-info-title">
            <div class="form-section-header">
                <div>
                    <h2 class="form-section-title" id="contact-info-title">Informasi kontak</h2>
                    <p class="form-section-desc">Nomor WhatsApp dan alamat pelayanan jika tersedia.</p>
                </div>
            </div>
            <div class="form-section-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nomor_whatsapp" class="form-label">Nomor WhatsApp <span class="required-mark">*</span></label>
                        <input type="text" name="nomor_whatsapp" id="nomor_whatsapp" class="form-input @error('nomor_whatsapp') is-invalid @enderror" value="{{ old('nomor_whatsapp', $kontak->nomor_whatsapp) }}" required maxlength="32">
                        @error('nomor_whatsapp')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label for="alamat_pelayanan" class="form-label">Alamat Pelayanan <span class="optional-tag">(Opsional)</span></label>
                        <textarea name="alamat_pelayanan" id="alamat_pelayanan" rows="2" class="form-textarea @error('alamat_pelayanan') is-invalid @enderror">{{ old('alamat_pelayanan', $kontak->alamat_pelayanan) }}</textarea>
                        <span class="field-hint">Pastikan izin publikasi kontak dan lokasi telah disetujui oleh yang bersangkutan.</span>
                        @error('alamat_pelayanan')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </section>

        <section class="form-section" aria-labelledby="media-info-title">
            <div class="form-section-header">
                <div>
                    <h2 class="form-section-title" id="media-info-title">Foto / media</h2>
                    <p class="form-section-desc">Unggah foto baru hanya jika ingin mengganti foto saat ini.</p>
                </div>
            </div>
            <div class="form-section-body">
                <div class="form-group">
                    <label for="foto" class="form-label">Foto Petugas <span class="optional-tag">(Opsional, Maks. 3MB, Format JPG/PNG/WebP)</span></label>
                    @if($kontak->foto_path)
                        <div class="current-media-preview">
                            <img src="{{ asset('storage/' . $kontak->foto_path) }}" alt="{{ $kontak->nama }}" class="preview-thumb">
                            <span class="preview-text">Foto saat ini tersimpan. Unggah berkas baru jika ingin mengganti.</span>
                        </div>
                    @endif
                    <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/webp" class="form-file @error('foto') is-invalid @enderror">
                    @error('foto')<p class="field-error">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        <section class="form-section" aria-labelledby="location-info-title">
            <div class="form-section-header">
                <div>
                    <h2 class="form-section-title" id="location-info-title">Lokasi pelayanan</h2>
                    <p class="form-section-desc">Tempel koordinat atau pilih titik pelayanan pada peta.</p>
                </div>
            </div>
            <div class="form-section-body">
                <x-admin.coordinate-picker :latitude="old('latitude', $kontak->latitude)" :longitude="old('longitude', $kontak->longitude)" mapId="kontakEditMap" />
            </div>
        </section>

        <div class="form-actions">
            <a href="{{ route('admin-dusun.kontak.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
