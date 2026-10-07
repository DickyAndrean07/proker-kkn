<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class DusunProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'nama_dusun' => ['required', 'string', 'max:150'],
            'deskripsi_singkat' => ['required', 'string'],
            'nama_kepala_dusun' => ['required', 'string', 'max:150'],
            'jumlah_rt' => ['required', 'integer', 'min:0'],
            'jumlah_rw' => ['required', 'integer', 'min:0'],
            'banner' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
            'foto_kepala_dusun' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_dusun.required' => 'Nama dusun wajib diisi.',
            'deskripsi_singkat.required' => 'Deskripsi profil dusun wajib diisi.',
            'nama_kepala_dusun.required' => 'Nama kepala dusun wajib diisi.',
            'jumlah_rt.required' => 'Jumlah RT wajib diisi.',
            'jumlah_rt.integer' => 'Jumlah RT harus berupa angka.',
            'jumlah_rw.required' => 'Jumlah RW wajib diisi.',
            'jumlah_rw.integer' => 'Jumlah RW harus berupa angka.',
            'banner.image' => 'Berkas banner harus berupa gambar.',
            'banner.mimes' => 'Format banner harus berupa JPG, PNG, atau WebP.',
            'banner.max' => 'Ukuran berkas banner maksimal 3MB.',
            'foto_kepala_dusun.image' => 'Berkas foto kepala dusun harus berupa gambar.',
            'foto_kepala_dusun.mimes' => 'Format foto kepala dusun harus berupa JPG, PNG, atau WebP.',
            'foto_kepala_dusun.max' => 'Ukuran berkas foto kepala dusun maksimal 3MB.',
        ];
    }
}
