@extends('layouts.super-admin')

@section('title', 'Edit Pengumuman')
@section('breadcrumb')
    <a href="{{ route('super-admin.dashboard') }}">Dashboard</a> /
    <a href="{{ route('super-admin.pengumuman.index') }}">Pengumuman</a> /
    <span>Edit: {{ $pengumuman->judul }}</span>
@endsection

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-title">Edit Pengumuman</h1>
    <p class="admin-page-desc">Perbarui isi berita atau masa berlaku aktif pengumuman.</p>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Formulir Edit Pengumuman</h2>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="{{ route('super-admin.pengumuman.update', $pengumuman->id) }}" class="admin-form">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label for="scope_level" class="form-label">
                        Cakupan Tingkat Wilayah <span class="required-mark">*</span>
                    </label>
                    <select name="scope_level" id="scope_level" class="form-select @error('scope_level') is-invalid @enderror" required onchange="toggleDusunSelector(this.value)">
                        <option value="DESA" {{ old('scope_level', $pengumuman->scope_level) === 'DESA' ? 'selected' : '' }}>Tingkat Desa (Seluruh Warga Desa)</option>
                        <option value="DUSUN" {{ old('scope_level', $pengumuman->scope_level) === 'DUSUN' ? 'selected' : '' }}>Tingkat Dusun (Wilayah Tertentu)</option>
                    </select>
                    @error('scope_level')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group" id="dusunSelectorGroup" style="{{ old('scope_level', $pengumuman->scope_level) === 'DUSUN' ? '' : 'display: none;' }}">
                    <label for="dusun_id" class="form-label">
                        Pilih Wilayah Dusun <span class="required-mark">*</span>
                    </label>
                    <select name="dusun_id" id="dusun_id" class="form-select @error('dusun_id') is-invalid @enderror">
                        <option value="">-- Pilih Wilayah Dusun --</option>
                        @foreach($dusuns as $dusun)
                            <option value="{{ $dusun->id }}" {{ old('dusun_id', $pengumuman->dusun_id) == $dusun->id ? 'selected' : '' }}>
                                {{ $dusun->nama_dusun }}
                            </option>
                        @endforeach
                    </select>
                    @error('dusun_id')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <div class="form-label-row">
                    <label for="judul" class="form-label">
                        Judul Pengumuman <span class="required-mark">*</span>
                    </label>
                    <button type="button" class="btn-ai-assist" data-ai-feature="pengumuman_draft" data-target-title="judul" data-target-text="isi">
                        <span class="ai-sparkle-icon">✨</span> Bantu Tulis / Edit AI
                    </button>
                </div>
                <input
                    type="text"
                    name="judul"
                    id="judul"
                    class="form-input @error('judul') is-invalid @enderror"
                    value="{{ old('judul', $pengumuman->judul) }}"
                    required
                    maxlength="255"
                >
                @error('judul')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="tanggal_kedaluwarsa" class="form-label">
                    Masa Berlaku Aktif (Kedaluwarsa) <span class="required-mark">*</span>
                </label>
                <input
                    type="date"
                    name="tanggal_kedaluwarsa"
                    id="tanggal_kedaluwarsa"
                    class="form-input @error('tanggal_kedaluwarsa') is-invalid @enderror"
                    value="{{ old('tanggal_kedaluwarsa', $pengumuman->tanggal_kedaluwarsa?->format('Y-m-d')) }}"
                    required
                >
                <span class="form-hint">Setelah tanggal ini, pengumuman otomatis dipindahkan ke halaman Arsip Publik.</span>
                @error('tanggal_kedaluwarsa')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="isi" class="form-label">
                    Isi Lengkap Pengumuman <span class="required-mark">*</span>
                </label>
                <textarea
                    name="isi"
                    id="isi"
                    rows="8"
                    class="form-textarea @error('isi') is-invalid @enderror"
                    required
                >{{ old('isi', $pengumuman->isi) }}</textarea>
                @error('isi')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('super-admin.pengumuman.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleDusunSelector(scope) {
    const group = document.getElementById('dusunSelectorGroup');
    const select = document.getElementById('dusun_id');
    if (scope === 'DUSUN') {
        group.style.display = '';
        select.required = true;
    } else {
        group.style.display = 'none';
        select.required = false;
        select.value = '';
    }
}
</script>
@endpush
@endsection
