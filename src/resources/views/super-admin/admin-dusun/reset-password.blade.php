@extends('layouts.super-admin')

@section('title', 'Reset Kata Sandi Admin Dusun')
@section('breadcrumb')
    <a href="{{ route('super-admin.dashboard') }}">Dashboard</a> /
    <a href="{{ route('super-admin.admin-dusun.index') }}">Admin Dusun</a> /
    <span>Reset Password: {{ $account->username }}</span>
@endsection

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-title">Reset Kata Sandi Akun</h1>
    <p class="admin-page-desc">Atur ulang kata sandi (password) untuk akun pengelola dusun {{ $account->username }}.</p>
</div>

<div class="admin-card" style="max-width: 600px;">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Formulir Reset Kata Sandi</h2>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="{{ route('super-admin.admin-dusun.reset-password', $account->id) }}" class="admin-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Akun Target</label>
                <div class="p-3 bg-neutral-light rounded font-medium">
                    {{ $account->username }} (Wilayah: {{ $account->dusun->nama_dusun ?? 'Dusun' }})
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">
                    Kata Sandi Baru <span class="required-mark">*</span>
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
                <label for="password_confirmation" class="form-label">
                    Konfirmasi Kata Sandi Baru <span class="required-mark">*</span>
                </label>
                <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    class="form-input"
                    required
                    minlength="6"
                    placeholder="Ketik ulang kata sandi baru"
                    autocomplete="new-password"
                >
            </div>

            <div class="form-actions">
                <a href="{{ route('super-admin.admin-dusun.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Kata Sandi Baru</button>
            </div>
        </form>
    </div>
</div>
@endsection
