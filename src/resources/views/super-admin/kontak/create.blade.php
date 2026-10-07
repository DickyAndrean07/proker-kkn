@extends('layouts.super-admin')

@section('title', 'Tambah Kontak Pelayanan')
@section('breadcrumb')
    <a href="{{ route('super-admin.dashboard') }}">Dashboard</a> /
    <a href="{{ route('super-admin.kontak.index') }}">Kontak Pelayanan</a> /
    <span>Tambah Baru</span>
@endsection

@section('content')
<div class="admin-page-header">
    <div>
        <p class="admin-page-kicker">Kontak Pelayanan</p>
        <h1 class="admin-page-title">Tambah Kontak</h1>
        <p class="admin-page-desc">Daftarkan kontak petugas atau pelayan masyarakat untuk wilayah dusun tertentu.</p>
    </div>
</div>

<div class="admin-form-shell">
    <form method="POST" action="{{ route('super-admin.kontak.store') }}" enctype="multipart/form-data" class="admin-form">
        @csrf

        <section class="form-section" aria-labelledby="basic-info-title">
            <div class="form-section-header">
                <div>
                    <h2 class="form-section-title" id="basic-info-title">Informasi dasar</h2>
                    <p class="form-section-desc">Pilih cakupan dusun, lalu isi nama dan peran kontak pelayanan.</p>
                </div>
            </div>
            <div class="form-section-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="dusun_id" class="form-label">Wilayah Dusun <span class="required-mark">*</span></label>
                        <select name="dusun_id" id="dusun_id" class="form-select @error('dusun_id') is-invalid @enderror" required>
                            <option value="">Pilih Wilayah Dusun</option>
                            @foreach($dusuns as $dusun)
                                <option value="{{ $dusun->id }}" {{ old('dusun_id') == $dusun->id ? 'selected' : '' }}>{{ $dusun->nama_dusun }}</option>
                            @endforeach
                        </select>
                        @error('dusun_id')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label for="nama" class="form-label">Nama Lengkap <span class="required-mark">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-input @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required maxlength="150" placeholder="Contoh: Budi Santoso">
                        @error('nama')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="jabatan" class="form-label">Jabatan / Posisi <span class="required-mark">*</span></label>
                        <input type="text" name="jabatan" id="jabatan" class="form-input @error('jabatan') is-invalid @enderror" value="{{ old('jabatan') }}" required maxlength="150" placeholder="Contoh: Kepala Dusun / Kasi Pelayanan">
                        @error('jabatan')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label for="nomor_whatsapp" class="form-label">Nomor WhatsApp <span class="required-mark">*</span></label>
                        <input type="text" name="nomor_whatsapp" id="nomor_whatsapp" class="form-input @error('nomor_whatsapp') is-invalid @enderror" value="{{ old('nomor_whatsapp') }}" required maxlength="32" placeholder="Contoh: 081234567890">
                        @error('nomor_whatsapp')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </section>

        <section class="form-section" aria-labelledby="contact-location-title">
            <div class="form-section-header">
                <div>
                    <h2 class="form-section-title" id="contact-location-title">Kontak dan alamat</h2>
                    <p class="form-section-desc">Alamat pelayanan bersifat opsional dan mengikuti data existing.</p>
                </div>
            </div>
            <div class="form-section-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="alamat_pelayanan" class="form-label">Alamat / Lokasi Pelayanan <span class="optional-tag">(Opsional)</span></label>
                        <input type="text" name="alamat_pelayanan" id="alamat_pelayanan" class="form-input @error('alamat_pelayanan') is-invalid @enderror" value="{{ old('alamat_pelayanan') }}" placeholder="Contoh: Balai Pertemuan RT 01">
                        @error('alamat_pelayanan')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label for="foto" class="form-label">Foto Kontak <span class="optional-tag">(Opsional, Maks. 3MB, Format JPG/PNG/WebP)</span></label>
                        <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/webp" class="form-file @error('foto') is-invalid @enderror">
                        @error('foto')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
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
                <x-admin.coordinate-picker :latitude="old('latitude')" :longitude="old('longitude')" mapId="saKontakCreateMap" />
            </div>
        </section>

        <div class="form-actions">
            <a href="{{ route('super-admin.kontak.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Kontak</button>
        </div>
    </form>
</div>
@endsection
