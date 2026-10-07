<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class DesaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'nama_desa' => ['required', 'string', 'max:150'],
            'deskripsi_singkat' => ['required', 'string'],
            'alamat_kantor' => ['required', 'string', 'max:255'],
            'nomor_kontak' => ['required', 'string', 'max:32'],
            'nama_kepala_desa' => ['required', 'string', 'max:150'],
            'jam_pelayanan' => ['required', 'string', 'max:255'],
            'banner' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_desa.required' => 'Nama desa wajib diisi.',
            'deskripsi_singkat.required' => 'Deskripsi profil desa wajib diisi.',
            'alamat_kantor.required' => 'Alamat kantor desa wajib diisi.',
            'nomor_kontak.required' => 'Nomor kontak desa wajib diisi.',
            'nama_kepala_desa.required' => 'Nama kepala desa wajib diisi.',
            'jam_pelayanan.required' => 'Jam pelayanan wajib diisi.',
            'banner.image' => 'Berkas banner harus berupa gambar.',
            'banner.mimes' => 'Format banner harus berupa JPG, PNG, atau WebP.',
            'banner.max' => 'Ukuran berkas banner maksimal 3MB.',
        ];
    }
}
