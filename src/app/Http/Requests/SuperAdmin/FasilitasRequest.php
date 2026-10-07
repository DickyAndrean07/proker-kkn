<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class FasilitasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'dusun_id' => ['required', 'integer', 'exists:dusuns,id'],
            'kategori_fasilitas_id' => ['required', 'integer', 'exists:kategori_fasilitas,id'],
            'nama' => ['required', 'string', 'max:200'],
            'deskripsi' => ['required', 'string'],
            'alamat' => ['required', 'string'],
            'nomor_whatsapp' => ['nullable', 'string', 'max:32'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ];
    }

    public function messages(): array
    {
        return [
            'dusun_id.required' => 'Wilayah dusun wajib dipilih.',
            'dusun_id.exists' => 'Wilayah dusun yang dipilih tidak valid.',
            'kategori_fasilitas_id.required' => 'Kategori fasilitas wajib dipilih.',
            'kategori_fasilitas_id.exists' => 'Kategori fasilitas yang dipilih tidak valid.',
            'nama.required' => 'Nama fasilitas wajib diisi.',
            'deskripsi.required' => 'Deskripsi fasilitas wajib diisi.',
            'alamat.required' => 'Alamat fasilitas wajib diisi.',
            'foto.image' => 'Berkas foto harus berupa gambar.',
            'foto.mimes' => 'Format foto harus berupa JPG, PNG, atau WebP.',
            'foto.max' => 'Ukuran berkas foto maksimal 3MB.',
            'latitude.required' => 'Titik koordinat peta (garis lintang) wajib ditentukan.',
            'latitude.numeric' => 'Garis lintang harus berupa angka.',
            'latitude.between' => 'Garis lintang harus berada pada rentang -90 hingga 90.',
            'longitude.required' => 'Titik koordinat peta (garis bujur) wajib ditentukan.',
            'longitude.numeric' => 'Garis bujur harus berupa angka.',
            'longitude.between' => 'Garis bujur harus berada pada rentang -180 hingga 180.',
        ];
    }
}
