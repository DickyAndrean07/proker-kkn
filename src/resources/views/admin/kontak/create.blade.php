@extends('layouts.admin')

@section('title', 'Tambah Kontak Pelayanan')
@section('breadcrumb')
    <a href="{{ route('admin-dusun.dashboard') }}">Dashboard</a> /
    <a href="{{ route('admin-dusun.kontak.index') }}">Kontak Pelayanan</a> /
    <span>Tambah Baru</span>
@endsection

@section('content')
<div class="admin-page-header">
    <div>
        <p class="admin-page-kicker">Kontak Pelayanan</p>
        <h1 class="admin-page-title">Tambah Kontak</h1>
        <p class="admin-page-desc">Tambahkan kontak petugas atau pengurus pelayanan warga di Dusun {{ $dusun->nama_dusun }}.</p>
    </div>
</div>

<div class="admin-form-shell">
    <form method="POST" action="{{ route('admin-dusun.kontak.store') }}" enctype="multipart/form-data" class="admin-form">
        @csrf

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
                        <input type="text" name="nama" id="nama" class="form-input @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required maxlength="150" placeholder="Contoh: Budi Santoso">
                        @error('nama')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label for="jabatan" class="form-label">Jabatan / Peran Pelayanan <span class="required-mark">*</span></label>
                        <input type="text" name="jabatan" id="jabatan" class="form-input @error('jabatan') is-invalid @enderror" value="{{ old('jabatan') }}" required maxlength="150" placeholder="Contoh: Ketua RT 01 / Pelayanan Administrasi">
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
                        <input type="text" name="nomor_whatsapp" id="nomor_whatsapp" class="form-input @error('nomor_whatsapp') is-invalid @enderror" value="{{ old('nomor_whatsapp') }}" required maxlength="32" placeholder="Contoh: 081234567890">
                        @error('nomor_whatsapp')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label for="alamat_pelayanan" class="form-label">Alamat Pelayanan <span class="optional-tag">(Opsional)</span></label>
                        <textarea name="alamat_pelayanan" id="alamat_pelayanan" rows="2" class="form-textarea @error('alamat_pelayanan') is-invalid @enderror" placeholder="Contoh: RT 01 / RW 02, Rumah Ketua RT">{{ old('alamat_pelayanan') }}</textarea>
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
                    <p class="form-section-desc">Opsional. Gunakan foto yang memang sudah disetujui untuk publikasi.</p>
                </div>
            </div>
            <div class="form-section-body">
                <div class="form-group">
                    <label for="foto" class="form-label">Foto Petugas <span class="optional-tag">(Opsional, Maks. 3MB, Format JPG/PNG/WebP)</span></label>
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
                <x-admin.coordinate-picker :latitude="old('latitude')" :longitude="old('longitude')" mapId="kontakCreateMap" />
            </div>
        </section>

        <div class="form-actions">
            <a href="{{ route('admin-dusun.kontak.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Kontak</button>
        </div>
    </form>
</div>
@endsection
