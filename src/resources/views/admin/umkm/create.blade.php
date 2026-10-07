@extends('layouts.admin')

@section('title', 'Tambah UMKM Baru')
@section('breadcrumb')
    <a href="{{ route('admin-dusun.dashboard') }}">Dashboard</a> /
    <a href="{{ route('admin-dusun.umkm.index') }}">Kelola UMKM</a> /
    <span>Tambah Baru</span>
@endsection

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Tambah UMKM Baru</h1>
        <p class="admin-page-desc">Daftarkan data usaha warga di Dusun {{ $dusun->nama_dusun }}.</p>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Formulir Pendaftaran Usaha (UMKM)</h2>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="{{ route('admin-dusun.umkm.store') }}" enctype="multipart/form-data" class="admin-form">
            @csrf

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
                        value="{{ old('nama_umkm') }}"
                        required
                        maxlength="200"
                        placeholder="Contoh: Keripik Singkong Barokah"
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
                        value="{{ old('nama_pemilik') }}"
                        required
                        maxlength="150"
                        placeholder="Contoh: Ibu Siti Aminah"
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
                        value="{{ old('jenis_usaha') }}"
                        required
                        maxlength="150"
                        placeholder="Contoh: Makanan Ringan / Kerajinan / Pertanian"
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
                        value="{{ old('jam_operasional') }}"
                        required
                        maxlength="255"
                        placeholder="Contoh: Setiap hari, 08.00 - 17.00 WIB"
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
                        value="{{ old('nomor_whatsapp') }}"
                        required
                        maxlength="32"
                        placeholder="Contoh: 081234567890"
                    >
                    @error('nomor_whatsapp')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="foto_utama" class="form-label">
                        Foto Utama Usaha <span class="optional-tag">(Opsional, Maks. 3MB, Format JPG/PNG/WebP)</span>
                    </label>
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
                        <span class="ai-sparkle-icon">✨</span> Bantu Tulis Deskripsi UMKM
                    </button>
                </div>
                <textarea
                    name="deskripsi"
                    id="deskripsi"
                    rows="3"
                    class="form-textarea @error('deskripsi') is-invalid @enderror"
                    required
                    placeholder="Jelaskan mengenai keunggulan usaha, bahan baku, atau keunikan produk..."
                >{{ old('deskripsi') }}</textarea>
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
                    placeholder="Contoh: RT 03 / RW 01, Dusun Krajan"
                >{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Produk Unggulan (Repeatable Child Rows) -->
            <div class="form-section-box">
                <div class="flex-between" style="margin-bottom: 0.75rem;">
                    <div>
                        <h3 class="section-box-title">Daftar Produk Unggulan</h3>
                        <p class="section-box-desc">Tambahkan nama-nama produk yang ditawarkan (informasi katalog saja).</p>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary" id="addProductBtn">+ Tambah Baris Produk</button>
                </div>

                <div id="productRowsContainer" class="product-rows-wrapper">
                    @if(old('produk'))
                        @foreach(old('produk') as $idx => $prod)
                            <div class="product-input-row">
                                <input
                                    type="text"
                                    name="produk[{{ $idx }}][nama_produk]"
                                    class="form-input"
                                    value="{{ $prod['nama_produk'] ?? '' }}"
                                    placeholder="Nama Produk (misal: Keripik Balado)"
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
                                placeholder="Nama Produk (misal: Keripik Singkong Original)"
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
                    :latitude="old('latitude')"
                    :longitude="old('longitude')"
                    mapId="umkmCreateMap"
                />
            </div>

            <div class="form-actions">
                <a href="{{ route('admin-dusun.umkm.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan UMKM</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('productRowsContainer');
    const addBtn = document.getElementById('addProductBtn');
    let nextIdx = container.querySelectorAll('.product-input-row').length;

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
                e.target.closest('.product-input-row').querySelector('input').value = '';
            }
        }
    });
});
</script>
@endpush
@endsection
