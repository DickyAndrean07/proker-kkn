<?php

namespace App\Http\Requests\SuperAdmin;

use App\Models\Desa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KategoriFasilitasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isSuperAdmin();
    }

    public function rules(): array
    {
        $desaId = Desa::first()?->id ?? 1;
        $kategoriId = $this->route('kategori') ?? $this->route('kategori_fasilita');

        return [
            'nama_kategori' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kategori_fasilitas', 'nama_kategori')
                    ->where(fn ($query) => $query->where('desa_id', $desaId))
                    ->ignore($kategoriId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_kategori.required' => 'Nama kategori fasilitas wajib diisi.',
            'nama_kategori.max' => 'Nama kategori maksimal 100 karakter.',
            'nama_kategori.unique' => 'Kategori dengan nama tersebut sudah ada di desa ini.',
        ];
    }
}
