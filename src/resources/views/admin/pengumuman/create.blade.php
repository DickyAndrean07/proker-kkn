@extends('layouts.admin')

@section('title', 'Tambah Pengumuman Dusun')
@section('breadcrumb')
    <a href="{{ route('admin-dusun.dashboard') }}">Dashboard</a> /
    <a href="{{ route('admin-dusun.pengumuman.index') }}">Kelola Pengumuman</a> /
    <span>Tambah Baru</span>
@endsection

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Tambah Pengumuman Dusun</h1>
        <p class="admin-page-desc">Terbitkan pemberitahuan atau informasi resmi untuk warga Dusun {{ $dusun->nama_dusun }}.</p>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Formulir Pengumuman Baru</h2>
        <span class="badge badge-info">Cakupan Wilayah: Dusun {{ $dusun->nama_dusun }}</span>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="{{ route('admin-dusun.pengumuman.store') }}" class="admin-form">
            @csrf

            <div class="form-group">
                <div class="form-label-row">
                    <label for="judul" class="form-label">
                        Judul Pengumuman <span class="required-mark">*</span>
                    </label>
                    <button type="button" class="btn-ai-assist" data-ai-feature="pengumuman_draft" data-target-title="judul" data-target-text="isi">
                        <span class="ai-sparkle-icon">✨</span> Bantu Tulis Pengumuman
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
                    placeholder="Contoh: Pemberitahuan Penyaluran Bantuan Sosial Dusun"
                >
                @error('judul')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="tanggal_kedaluwarsa" class="form-label">
                    Tanggal Kedaluwarsa (Batas Masa Aktif) <span class="required-mark">*</span>
                </label>
                <input
                    type="date"
                    name="tanggal_kedaluwarsa"
                    id="tanggal_kedaluwarsa"
                    class="form-input @error('tanggal_kedaluwarsa') is-invalid @enderror"
                    value="{{ old('tanggal_kedaluwarsa') }}"
                    required
                >
                <span class="field-hint">Setelah tanggal ini berlalu (WIB), pengumuman akan secara otomatis berpindah ke arsip publik.</span>
                @error('tanggal_kedaluwarsa')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="isi" class="form-label">
                    Isi Pengumuman Lengkap <span class="required-mark">*</span>
                </label>
                <textarea
                    name="isi"
                    id="isi"
                    rows="6"
                    class="form-textarea @error('isi') is-invalid @enderror"
                    required
                    placeholder="Tuliskan isi pengumuman lengkap secara jelas..."
                >{{ old('isi') }}</textarea>
                @error('isi')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('admin-dusun.pengumuman.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Terbitkan Pengumuman</button>
            </div>
        </form>
    </div>
</div>
@endsection
