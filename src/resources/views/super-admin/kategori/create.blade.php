@extends('layouts.super-admin')

@section('title', 'Tambah Kategori Fasilitas')
@section('breadcrumb')
    <a href="{{ route('super-admin.dashboard') }}">Dashboard</a> /
    <a href="{{ route('super-admin.kategori-fasilitas.index') }}">Kategori Fasilitas</a> /
    <span>Tambah Baru</span>
@endsection

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-title">Tambah Kategori Fasilitas</h1>
    <p class="admin-page-desc">Buat kategori klasifikasi fasilitas baru untuk Desa Bendung.</p>
</div>

<div class="admin-card" style="max-width: 600px;">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Formulir Kategori Baru</h2>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="{{ route('super-admin.kategori-fasilitas.store') }}" class="admin-form">
            @csrf

            <div class="form-group">
                <label for="nama_kategori" class="form-label">
                    Nama Kategori Fasilitas <span class="required-mark">*</span>
                </label>
                <input
                    type="text"
                    name="nama_kategori"
                    id="nama_kategori"
                    class="form-input @error('nama_kategori') is-invalid @enderror"
                    value="{{ old('nama_kategori') }}"
                    required
                    maxlength="100"
                    placeholder="Contoh: Sarana Olahraga"
                >
                @error('nama_kategori')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('super-admin.kategori-fasilitas.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>
@endsection
