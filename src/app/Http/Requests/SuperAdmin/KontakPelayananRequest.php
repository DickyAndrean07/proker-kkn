<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class KontakPelayananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'dusun_id' => ['required', 'integer', 'exists:dusuns,id'],
            'nama' => ['required', 'string', 'max:150'],
            'jabatan' => ['required', 'string', 'max:150'],
            'nomor_whatsapp' => ['required', 'string', 'max:32'],
            'alamat_pelayanan' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
            'latitude' => ['nullable', 'required_with:longitude', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'required_with:latitude', 'numeric', 'between:-180,180'],
        ];
    }

    public function messages(): array
    {
        return [
            'dusun_id.required' => 'Wilayah dusun wajib dipilih.',
            'dusun_id.exists' => 'Wilayah dusun yang dipilih tidak valid.',
            'nama.required' => 'Nama kontak pelayanan wajib diisi.',
            'jabatan.required' => 'Jabatan / unit kerja wajib diisi.',
            'nomor_whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'foto.image' => 'Berkas foto harus berupa gambar.',
            'foto.mimes' => 'Format foto harus berupa JPG, PNG, atau WebP.',
            'foto.max' => 'Ukuran berkas foto maksimal 3MB.',
            'latitude.required_with' => 'Garis lintang (latitude) wajib diisi jika garis bujur (longitude) diisi.',
            'latitude.numeric' => 'Garis lintang harus berupa angka.',
            'latitude.between' => 'Garis lintang harus berada pada rentang -90 hingga 90.',
            'longitude.required_with' => 'Garis bujur (longitude) wajib diisi jika garis lintang (latitude) diisi.',
            'longitude.numeric' => 'Garis bujur harus berupa angka.',
            'longitude.between' => 'Garis bujur harus berada pada rentang -180 hingga 180.',
        ];
    }
}
