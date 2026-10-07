@extends('layouts.super-admin')

@section('title', 'Kategori Fasilitas')
@section('breadcrumb')
    <a href="{{ route('super-admin.dashboard') }}">Dashboard</a> / <span>Kategori Fasilitas</span>
@endsection

@section('content')
<div class="admin-page-header flex-between">
    <div>
        <h1 class="admin-page-title">Kategori Fasilitas Umum</h1>
        <p class="admin-page-desc">Master klasifikasi fasilitas (contoh: Posyandu/Kesehatan, Pendidikan, Keagamaan, Sarana Olahraga).</p>
    </div>
    <div>
        <a href="{{ route('super-admin.kategori-fasilitas.create') }}" class="btn btn-primary">
            + Tambah Kategori
        </a>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-body p-0">
        @if($kategoriList->isEmpty())
            <div class="admin-empty-state">
                <div class="empty-icon">🏷️</div>
                <h3 class="empty-title">Belum ada kategori fasilitas</h3>
                <p class="empty-desc">Buat kategori baru untuk mengelompokkan fasilitas umum di desa.</p>
                <a href="{{ route('super-admin.kategori-fasilitas.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    + Tambah Kategori Pertama
                </a>
            </div>
        @else
            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nama Kategori</th>
                            <th>Jumlah Fasilitas Terdaftar</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kategoriList as $kategori)
                            <tr>
                                <td>
                                    <strong class="item-title">{{ $kategori->nama_kategori }}</strong>
                                </td>
                                <td>
                                    <span class="badge {{ $kategori->fasilitas_count > 0 ? 'badge-primary' : 'badge-neutral' }}">
                                        {{ $kategori->fasilitas_count }} Fasilitas
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="action-buttons">
                                        <a href="{{ route('super-admin.kategori-fasilitas.edit', $kategori->id) }}" class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('super-admin.kategori-fasilitas.destroy', $kategori->id) }}" class="inline-form" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ addslashes($kategori->nama_kategori) }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
