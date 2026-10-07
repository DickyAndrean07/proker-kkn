@extends('layouts.super-admin')

@section('title', 'Ubah Penugasan Dusun')
@section('breadcrumb')
    <a href="{{ route('super-admin.dashboard') }}">Dashboard</a> /
    <a href="{{ route('super-admin.admin-dusun.index') }}">Admin Dusun</a> /
    <span>Ubah Penugasan: {{ $account->username }}</span>
@endsection

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-title">Ubah Penugasan Wilayah Dusun</h1>
    <p class="admin-page-desc">Pindahkan hak kelola wilayah akun {{ $account->username }} ke dusun lain.</p>
</div>

<div class="admin-card" style="max-width: 650px;">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Penugasan Akun</h2>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="{{ route('super-admin.admin-dusun.update', $account->id) }}" class="admin-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Username</label>
                <input
                    type="text"
                    class="form-input"
                    value="{{ $account->username }}"
                    disabled
                    readonly
                >
                <span class="form-hint">Username bersifat permanen dan tidak dapat diubah.</span>
            </div>

            <div class="form-group">
                <label for="dusun_id" class="form-label">
                    Penugasan Wilayah Dusun Baru <span class="required-mark">*</span>
                </label>
                <select name="dusun_id" id="dusun_id" class="form-select @error('dusun_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Wilayah Dusun --</option>
                    @foreach($dusuns as $dusun)
                        <option value="{{ $dusun->id }}" {{ old('dusun_id', $account->dusun_id) == $dusun->id ? 'selected' : '' }}>
                            {{ $dusun->nama_dusun }}
                        </option>
                    @endforeach
                </select>
                @error('dusun_id')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('super-admin.admin-dusun.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan Penugasan</button>
            </div>
        </form>
    </div>
</div>
@endsection
