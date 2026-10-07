<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class AdminAccountResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ];
    }
}
