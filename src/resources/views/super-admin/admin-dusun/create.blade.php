@extends('layouts.super-admin')

@section('title', 'Tambah Akun Admin Dusun')
@section('breadcrumb')
    <a href="{{ route('super-admin.dashboard') }}">Dashboard</a> /
    <a href="{{ route('super-admin.admin-dusun.index') }}">Admin Dusun</a> /
    <span>Tambah Baru</span>
@endsection

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-title">Tambah Akun Admin Dusun</h1>
    <p class="admin-page-desc">Buat kredensial login untuk pengelola wilayah dusun (Role: ADMIN_DUSUN).</p>
</div>

<div class="admin-card" style="max-width: 650px;">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Formulir Pendaftaran Akun</h2>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="{{ route('super-admin.admin-dusun.store') }}" class="admin-form">
            @csrf

            <div class="form-group">
                <label for="username" class="form-label">
                    Username <span class="required-mark">*</span>
                </label>
                <input
                    type="text"
                    name="username"
                    id="username"
                    class="form-input @error('username') is-invalid @enderror"
                    value="{{ old('username') }}"
                    required
                    maxlength="50"
                    placeholder="Contoh: admin_krajan"
                    autocomplete="off"
                >
                <span class="form-hint">Hanya boleh berisi huruf, angka, tanda hubung (-), dan garis bawah (_).</span>
                @error('username')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">
                    Kata Sandi (Password) <span class="required-mark">*</span>
                </label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-input @error('password') is-invalid @enderror"
                    required
                    minlength="6"
                    placeholder="Minimal 6 karakter"
                    autocomplete="new-password"
                >
                @error('password')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="dusun_id" class="form-label">
                    Penugasan Wilayah Dusun <span class="required-mark">*</span>
                </label>
                <select name="dusun_id" id="dusun_id" class="form-select @error('dusun_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Wilayah Dusun --</option>
                    @foreach($dusuns as $dusun)
                        <option value="{{ $dusun->id }}" {{ old('dusun_id') == $dusun->id ? 'selected' : '' }}>
                            {{ $dusun->nama_dusun }}
                        </option>
                    @endforeach
                </select>
                <span class="form-hint">Akun ini hanya akan memiliki akses kelola konten pada dusun yang dipilih.</span>
                @error('dusun_id')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('super-admin.admin-dusun.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Buat Akun Admin Dusun</button>
            </div>
        </form>
    </div>
</div>
@endsection
