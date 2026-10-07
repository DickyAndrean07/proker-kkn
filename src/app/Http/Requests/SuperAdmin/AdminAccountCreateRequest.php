<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class AdminAccountCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:admin_accounts,username'],
            'password' => ['required', 'string', 'min:6'],
            'dusun_id' => ['required', 'integer', 'exists:dusuns,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda hubung (-), dan garis bawah (_).',
            'username.unique' => 'Username tersebut sudah terdaftar / pernah digunakan.',
            'password.required' => 'Kata sandi (password) wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'dusun_id.required' => 'Penugasan wilayah dusun wajib dipilih.',
            'dusun_id.exists' => 'Wilayah dusun yang dipilih tidak valid.',
        ];
    }
}
