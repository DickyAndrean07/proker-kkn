<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class AdminAccountUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'dusun_id' => ['required', 'integer', 'exists:dusuns,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'dusun_id.required' => 'Penugasan wilayah dusun wajib dipilih.',
            'dusun_id.exists' => 'Wilayah dusun yang dipilih tidak valid.',
        ];
    }
}
