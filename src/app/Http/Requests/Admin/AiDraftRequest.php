<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AiDraftRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user && ($user->isSuperAdmin() || $user->isAdminDusun());
    }

    public function rules(): array
    {
        return [
            'feature' => [
                'required',
                'string',
                Rule::in(['pengumuman_draft', 'agenda_draft', 'umkm_draft', 'desa_draft', 'dusun_draft', 'fasilitas_draft', 'improve_text']),
            ],
            'mode' => [
                'required',
                'string',
                Rule::in(['draft', 'rapikan', 'persingkat', 'formal']),
            ],
            'notes' => ['nullable', 'string', 'max:2500'],
            'existing_text' => ['nullable', 'string', 'max:4500'],
            'structured_input' => ['nullable', 'array'],
            'structured_input.*' => ['nullable', 'string', 'max:1000'],
            'draft_length' => ['nullable', 'string', Rule::in(['ringkas', 'standar', 'lengkap'])],
        ];
    }

    public function messages(): array
    {
        return [
            'feature.required' => 'Fitur asisten AI harus ditentukan.',
            'feature.in' => 'Fitur asisten AI yang dipilih tidak valid.',
            'mode.required' => 'Mode penulisan harus ditentukan.',
            'mode.in' => 'Mode penulisan tidak valid.',
            'notes.max' => 'Catatan admin tidak boleh lebih dari 2500 karakter.',
            'existing_text.max' => 'Teks saat ini tidak boleh lebih dari 4500 karakter.',
        ];
    }
}
