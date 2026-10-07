<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UmkmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdminDusun();
    }

    public function rules(): array
    {
        return [
            'nama_umkm' => ['required', 'string', 'max:200'],
            'nama_pemilik' => ['required', 'string', 'max:150'],
            'jenis_usaha' => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string'],
            'alamat' => ['required', 'string'],
            'nomor_whatsapp' => ['required', 'string', 'max:32'],
            'jam_operasional' => ['required', 'string', 'max:255'],
            'foto_utama' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90', 'required_with:longitude'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180', 'required_with:latitude'],
            'produk' => ['nullable', 'array'],
            'produk.*.id' => ['nullable', 'integer'],
            'produk.*.nama_produk' => ['required_with:produk', 'string', 'max:200'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_umkm.required' => 'Nama UMKM wajib diisi.',
            'nama_pemilik.required' => 'Nama pemilik UMKM wajib diisi.',
            'jenis_usaha.required' => 'Jenis usaha wajib diisi.',
            'deskripsi.required' => 'Deskripsi UMKM wajib diisi.',
            'alamat.required' => 'Alamat UMKM wajib diisi.',
            'nomor_whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'jam_operasional.required' => 'Jam operasional wajib diisi.',
            'foto_utama.image' => 'Foto utama harus berupa berkas gambar.',
            'foto_utama.mimes' => 'Format foto utama harus berupa JPG, PNG, atau WebP.',
            'foto_utama.max' => 'Ukuran foto utama maksimal 3MB.',
            'latitude.between' => 'Latitude harus berada dalam rentang -90 hingga 90.',
            'longitude.between' => 'Longitude harus berada dalam rentang -180 hingga 180.',
            'latitude.required_with' => 'Latitude harus diisi jika longitude diisi.',
            'longitude.required_with' => 'Longitude harus diisi jika latitude diisi.',
            'produk.*.nama_produk.required_with' => 'Nama produk tidak boleh kosong.',
        ];
    }
}
