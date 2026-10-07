<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProfilDusunRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdminDusun();
    }

    public function rules(): array
    {
        return [
            'nama_dusun' => ['required', 'string', 'max:150'],
            'deskripsi_singkat' => ['required', 'string'],
            'nama_kepala_dusun' => ['required', 'string', 'max:150'],
            'jumlah_rt' => ['required', 'integer', 'min:0', 'max:65535'],
            'jumlah_rw' => ['required', 'integer', 'min:0', 'max:65535'],
            'banner' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
            'foto_kepala_dusun' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_dusun.required' => 'Nama Dusun wajib diisi.',
            'deskripsi_singkat.required' => 'Deskripsi singkat Dusun wajib diisi.',
            'nama_kepala_dusun.required' => 'Nama Kepala Dusun wajib diisi.',
            'jumlah_rt.required' => 'Jumlah RT wajib diisi.',
            'jumlah_rw.required' => 'Jumlah RW wajib diisi.',
            'banner.image' => 'Banner harus berupa berkas gambar.',
            'banner.mimes' => 'Format banner harus berupa JPG, PNG, atau WebP.',
            'banner.max' => 'Ukuran banner maksimal 3MB.',
            'foto_kepala_dusun.image' => 'Foto Kepala Dusun harus berupa berkas gambar.',
            'foto_kepala_dusun.mimes' => 'Format foto Kepala Dusun harus berupa JPG, PNG, atau WebP.',
            'foto_kepala_dusun.max' => 'Ukuran foto Kepala Dusun maksimal 3MB.',
        ];
    }
}
