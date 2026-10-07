<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PengumumanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdminDusun();
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'isi' => ['required', 'string'],
            'tanggal_kedaluwarsa' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul pengumuman wajib diisi.',
            'isi.required' => 'Isi pengumuman wajib diisi.',
            'tanggal_kedaluwarsa.required' => 'Tanggal kedaluwarsa pengumuman wajib diisi.',
            'tanggal_kedaluwarsa.date' => 'Format tanggal kedaluwarsa tidak valid.',
        ];
    }
}
