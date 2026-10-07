@extends('layouts.super-admin')

@section('title', 'Edit Kategori Fasilitas')
@section('breadcrumb')
    <a href="{{ route('super-admin.dashboard') }}">Dashboard</a> /
    <a href="{{ route('super-admin.kategori-fasilitas.index') }}">Kategori Fasilitas</a> /
    <span>Edit: {{ $kategori->nama_kategori }}</span>
@endsection

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-title">Edit Kategori Fasilitas</h1>
    <p class="admin-page-desc">Perbarui nama kategori klasifikasi fasilitas.</p>
</div>

<div class="admin-card" style="max-width: 600px;">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Formulir Edit Kategori</h2>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="{{ route('super-admin.kategori-fasilitas.update', $kategori->id) }}" class="admin-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nama_kategori" class="form-label">
                    Nama Kategori Fasilitas <span class="required-mark">*</span>
                </label>
                <input
                    type="text"
                    name="nama_kategori"
                    id="nama_kategori"
                    class="form-input @error('nama_kategori') is-invalid @enderror"
                    value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                    required
                    maxlength="100"
                >
                @error('nama_kategori')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('super-admin.kategori-fasilitas.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
