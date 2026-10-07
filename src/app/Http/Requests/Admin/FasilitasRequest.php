<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FasilitasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdminDusun();
    }

    public function rules(): array
    {
        $desaId = $this->user()?->dusun?->desa_id;

        return [
            'kategori_fasilitas_id' => [
                'required',
                'integer',
                Rule::exists('kategori_fasilitas', 'id')->where(function ($query) use ($desaId) {
                    if ($desaId) {
                        $query->where('desa_id', $desaId);
                    }
                }),
            ],
            'nama' => ['required', 'string', 'max:200'],
            'deskripsi' => ['required', 'string'],
            'alamat' => ['required', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
            'nomor_whatsapp' => ['nullable', 'string', 'max:32'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ];
    }

    public function messages(): array
    {
        return [
            'kategori_fasilitas_id.required' => 'Kategori fasilitas wajib dipilih.',
            'kategori_fasilitas_id.exists' => 'Kategori fasilitas tidak valid untuk desa ini.',
            'nama.required' => 'Nama fasilitas wajib diisi.',
            'deskripsi.required' => 'Deskripsi fasilitas wajib diisi.',
            'alamat.required' => 'Alamat fasilitas wajib diisi.',
            'foto.image' => 'Foto harus berupa berkas gambar.',
            'foto.mimes' => 'Format foto harus berupa JPG, PNG, atau WebP.',
            'foto.max' => 'Ukuran foto maksimal 3MB.',
            'latitude.required' => 'Koordinat Latitude wajib diisi.',
            'longitude.required' => 'Koordinat Longitude wajib diisi.',
            'latitude.between' => 'Latitude harus berada dalam rentang -90 hingga 90.',
            'longitude.between' => 'Longitude harus berada dalam rentang -180 hingga 180.',
        ];
    }
}
