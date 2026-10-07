<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PengumumanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'scope_level' => ['required', Rule::in(['DESA', 'DUSUN'])],
            'dusun_id' => [
                'nullable',
                Rule::requiredIf($this->input('scope_level') === 'DUSUN'),
                'prohibited_if:scope_level,DESA',
                'exists:dusuns,id',
            ],
            'judul' => ['required', 'string', 'max:255'],
            'isi' => ['required', 'string'],
            'tanggal_kedaluwarsa' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'scope_level.required' => 'Cakupan wilayah pengumuman wajib dipilih.',
            'scope_level.in' => 'Cakupan wilayah harus berupa DESA atau DUSUN.',
            'dusun_id.required' => 'Wilayah dusun wajib dipilih untuk pengumuman tingkat dusun.',
            'dusun_id.required_if' => 'Wilayah dusun wajib dipilih untuk pengumuman tingkat dusun.',
            'dusun_id.prohibited_if' => 'Wilayah dusun tidak boleh dipilih untuk pengumuman tingkat desa.',
            'dusun_id.exists' => 'Wilayah dusun yang dipilih tidak valid.',
            'judul.required' => 'Judul pengumuman wajib diisi.',
            'isi.required' => 'Isi pengumuman wajib diisi.',
            'tanggal_kedaluwarsa.required' => 'Tanggal kedaluwarsa (masa aktif) wajib diisi.',
            'tanggal_kedaluwarsa.date' => 'Format tanggal kedaluwarsa tidak valid.',
        ];
    }
}
