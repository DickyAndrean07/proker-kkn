@extends('layouts.admin')

@section('title', 'Edit UMKM')
@section('breadcrumb')
    <a href="{{ route('admin-dusun.dashboard') }}">Dashboard</a> /
    <a href="{{ route('admin-dusun.umkm.index') }}">Kelola UMKM</a> /
    <span>Edit: {{ $umkm->nama_umkm }}</span>
@endsection

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Edit Usaha (UMKM)</h1>
        <p class="admin-page-desc">Perbarui data usaha warga di Dusun {{ $dusun->nama_dusun }}.</p>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Formulir Edit UMKM</h2>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="{{ route('admin-dusun.umkm.update', $umkm->id) }}" enctype="multipart/form-data" class="admin-form">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label for="nama_umkm" class="form-label">
                        Nama Usaha (UMKM) <span class="required-mark">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama_umkm"
                        id="nama_umkm"
                        class="form-input @error('nama_umkm') is-invalid @enderror"
                        value="{{ old('nama_umkm', $umkm->nama_umkm) }}"
                        required
                        maxlength="200"
                    >
                    @error('nama_umkm')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nama_pemilik" class="form-label">
                        Nama Pemilik Usaha <span class="required-mark">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama_pemilik"
                        id="nama_pemilik"
                        class="form-input @error('nama_pemilik') is-invalid @enderror"
                        value="{{ old('nama_pemilik', $umkm->nama_pemilik) }}"
                        required
                        maxlength="150"
                    >
                    @error('nama_pemilik')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="jenis_usaha" class="form-label">
                        Kategori / Jenis Usaha <span class="required-mark">*</span>
                    </label>
                    <input
                        type="text"
                        name="jenis_usaha"
                        id="jenis_usaha"
                        class="form-input @error('jenis_usaha') is-invalid @enderror"
                        value="{{ old('jenis_usaha', $umkm->jenis_usaha) }}"
                        required
                        maxlength="150"
                    >
                    @error('jenis_usaha')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jam_operasional" class="form-label">
                        Jam Operasional <span class="required-mark">*</span>
                    </label>
                    <input
                        type="text"
                        name="jam_operasional"
                        id="jam_operasional"
                        class="form-input @error('jam_operasional') is-invalid @enderror"
                        value="{{ old('jam_operasional', $umkm->jam_operasional) }}"
                        required
                        maxlength="255"
                    >
                    @error('jam_operasional')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="nomor_whatsapp" class="form-label">
                        Nomor WhatsApp Pemesanan / Kontak <span class="required-mark">*</span>
                    </label>
                    <input
                        type="text"
                        name="nomor_whatsapp"
                        id="nomor_whatsapp"
                        class="form-input @error('nomor_whatsapp') is-invalid @enderror"
                        value="{{ old('nomor_whatsapp', $umkm->nomor_whatsapp) }}"
                        required
                        maxlength="32"
                    >
                    @error('nomor_whatsapp')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="foto_utama" class="form-label">
                        Foto Utama Usaha <span class="optional-tag">(Opsional, Maks. 3MB, Format JPG/PNG/WebP)</span>
                    </label>
                    @if($umkm->foto_utama_path)
                        <div class="current-media-preview">
                            <img src="{{ asset('storage/' . $umkm->foto_utama_path) }}" alt="{{ $umkm->nama_umkm }}" class="preview-thumb">
                            <span class="preview-text">Foto saat ini tersimpan. Unggah berkas baru jika ingin mengganti.</span>
                        </div>
                    @endif
                    <input
                        type="file"
                        name="foto_utama"
                        id="foto_utama"
                        accept="image/jpeg,image/png,image/webp"
                        class="form-file @error('foto_utama') is-invalid @enderror"
                    >
                    @error('foto_utama')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <div class="form-label-row">
                    <label for="deskripsi" class="form-label">
                        Deskripsi Usaha <span class="required-mark">*</span>
                    </label>
                    <button type="button" class="btn-ai-assist" data-ai-feature="umkm_draft" data-target-text="deskripsi">
                        <span class="ai-sparkle-icon">✨</span> Bantu Tulis / Edit AI
                    </button>
                </div>
                <textarea
                    name="deskripsi"
                    id="deskripsi"
                    rows="3"
                    class="form-textarea @error('deskripsi') is-invalid @enderror"
                    required
                >{{ old('deskripsi', $umkm->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="alamat" class="form-label">
                    Alamat Lengkap Usaha <span class="required-mark">*</span>
                </label>
                <textarea
                    name="alamat"
                    id="alamat"
                    rows="2"
                    class="form-textarea @error('alamat') is-invalid @enderror"
                    required
                >{{ old('alamat', $umkm->alamat) }}</textarea>
                @error('alamat')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Produk Unggulan (Repeatable Child Rows) -->
            <div class="form-section-box">
                <div class="flex-between" style="margin-bottom: 0.75rem;">
                    <div>
                        <h3 class="section-box-title">Daftar Produk Unggulan</h3>
                        <p class="section-box-desc">Kelola daftar nama produk (informasi katalog).</p>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary" id="addProductBtn">+ Tambah Baris Produk</button>
                </div>

                <div id="productRowsContainer" class="product-rows-wrapper">
                    @php
                        $products = old('produk', $umkm->produkUmkms->toArray());
                    @endphp
                    @if(!empty($products))
                        @foreach($products as $idx => $prod)
                            <div class="product-input-row">
                                @if(!empty($prod['id']))
                                    <input type="hidden" name="produk[{{ $idx }}][id]" value="{{ $prod['id'] }}">
                                @endif
                                <input
                                    type="text"
                                    name="produk[{{ $idx }}][nama_produk]"
                                    class="form-input"
                                    value="{{ $prod['nama_produk'] ?? '' }}"
                                    placeholder="Nama Produk"
                                    required
                                >
                                <button type="button" class="btn btn-sm btn-outline-danger remove-product-btn">Hapus</button>
                            </div>
                        @endforeach
                    @else
                        <div class="product-input-row">
                            <input
                                type="text"
                                name="produk[0][nama_produk]"
                                class="form-input"
                                placeholder="Nama Produk"
                            >
                            <button type="button" class="btn btn-sm btn-outline-danger remove-product-btn">Hapus</button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Coordinate Picker -->
            <div class="form-group">
                <label class="form-label">Titik Koordinat Lokasi UMKM <span class="optional-tag">(Opsional)</span></label>
                <x-admin.coordinate-picker
                    :latitude="old('latitude', $umkm->latitude)"
                    :longitude="old('longitude', $umkm->longitude)"
                    mapId="umkmEditMap"
                />
            </div>

            <div class="form-actions">
                <a href="{{ route('admin-dusun.umkm.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('productRowsContainer');
    const addBtn = document.getElementById('addProductBtn');
    let nextIdx = container.querySelectorAll('.product-input-row').length + 100;

    addBtn.addEventListener('click', () => {
        const row = document.createElement('div');
        row.className = 'product-input-row';
        row.innerHTML = `
            <input
                type="text"
                name="produk[${nextIdx}][nama_produk]"
                class="form-input"
                placeholder="Nama Produk Baru"
                required
            >
            <button type="button" class="btn btn-sm btn-outline-danger remove-product-btn">Hapus</button>
        `;
        container.appendChild(row);
        nextIdx++;
    });

    container.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-product-btn')) {
            const rows = container.querySelectorAll('.product-input-row');
            if (rows.length > 1) {
                e.target.closest('.product-input-row').remove();
            } else {
                e.target.closest('.product-input-row').querySelector('input[type="text"]').value = '';
            }
        }
    });
});
</script>
@endpush
@endsection
