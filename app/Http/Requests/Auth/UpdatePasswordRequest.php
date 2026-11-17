<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string', 'current_password'],
            'password' => [
                'required',
                'string',
                'confirmed',
                'different:current_password',
                Password::min(8)->letters()->numbers()->symbols(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.current_password' => 'The provided current password does not match your actual password.',
            'password.different' => 'The new password must be different from your current password.',
            'password.confirmed' => 'The new password confirmation does not match.',
        ];
    }
}
