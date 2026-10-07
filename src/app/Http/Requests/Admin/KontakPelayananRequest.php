<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class KontakPelayananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdminDusun();
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'jabatan' => ['required', 'string', 'max:150'],
            'nomor_whatsapp' => ['required', 'string', 'max:32'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
            'alamat_pelayanan' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90', 'required_with:longitude'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180', 'required_with:latitude'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama kontak wajib diisi.',
            'jabatan.required' => 'Jabatan atau peran wajib diisi.',
            'nomor_whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'foto.image' => 'Foto harus berupa berkas gambar.',
            'foto.mimes' => 'Format foto harus berupa JPG, PNG, atau WebP.',
            'foto.max' => 'Ukuran foto maksimal 3MB.',
            'latitude.between' => 'Latitude harus berada dalam rentang -90 hingga 90.',
            'longitude.between' => 'Longitude harus berada dalam rentang -180 hingga 180.',
            'latitude.required_with' => 'Latitude harus diisi jika longitude diisi.',
            'longitude.required_with' => 'Longitude harus diisi jika latitude diisi.',
        ];
    }
}
