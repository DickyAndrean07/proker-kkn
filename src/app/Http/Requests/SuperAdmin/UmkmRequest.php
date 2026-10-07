<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class UmkmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'dusun_id' => ['required', 'integer', 'exists:dusuns,id'],
            'nama_umkm' => ['required', 'string', 'max:200'],
            'nama_pemilik' => ['required', 'string', 'max:150'],
            'jenis_usaha' => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string'],
            'alamat' => ['required', 'string'],
            'nomor_whatsapp' => ['required', 'string', 'max:32'],
            'jam_operasional' => ['required', 'string', 'max:255'],
            'foto_utama' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
            'latitude' => ['nullable', 'required_with:longitude', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'required_with:latitude', 'numeric', 'between:-180,180'],
            'produk' => ['nullable', 'array'],
            'produk.*.id' => ['nullable', 'integer'],
            'produk.*.nama_produk' => ['nullable', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'dusun_id.required' => 'Wilayah dusun wajib dipilih.',
            'dusun_id.exists' => 'Wilayah dusun yang dipilih tidak valid.',
            'nama_umkm.required' => 'Nama usaha (UMKM) wajib diisi.',
            'nama_pemilik.required' => 'Nama pemilik usaha wajib diisi.',
            'jenis_usaha.required' => 'Kategori / jenis usaha wajib diisi.',
            'deskripsi.required' => 'Deskripsi usaha wajib diisi.',
            'alamat.required' => 'Alamat usaha wajib diisi.',
            'nomor_whatsapp.required' => 'Nomor WhatsApp pemesanan wajib diisi.',
            'jam_operasional.required' => 'Jam operasional wajib diisi.',
            'foto_utama.image' => 'Berkas foto utama harus berupa gambar.',
            'foto_utama.mimes' => 'Format foto utama harus berupa JPG, PNG, atau WebP.',
            'foto_utama.max' => 'Ukuran berkas foto utama maksimal 3MB.',
            'latitude.required_with' => 'Garis lintang (latitude) wajib diisi jika garis bujur (longitude) diisi.',
            'latitude.numeric' => 'Garis lintang harus berupa angka.',
            'latitude.between' => 'Garis lintang harus berada pada rentang -90 hingga 90.',
            'longitude.required_with' => 'Garis bujur (longitude) wajib diisi jika garis lintang (latitude) diisi.',
            'longitude.numeric' => 'Garis bujur harus berupa angka.',
            'longitude.between' => 'Garis bujur harus berada pada rentang -180 hingga 180.',
        ];
    }
}
