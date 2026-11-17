<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation (normalization edge cases).
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower(trim((string) $this->input('email'))),
            'name' => trim((string) $this->input('name')),
            'phone' => trim((string) $this->input('phone')),
            'city' => trim((string) $this->input('city')),
            'address' => trim((string) $this->input('address')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[\pL\s\.\-]+$/u', // letters, spaces, dots, hyphens only
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,filter',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],
            'role' => [
                'required',
                'string',
                Rule::in([User::ROLE_DONOR, User::ROLE_REQUESTER, User::ROLE_HOSPITAL]),
                // Notice: 'admin' is intentionally blocked from public registration!
            ],
            'blood_group' => [
                Rule::requiredIf(fn () => $this->input('role') === User::ROLE_DONOR),
                'nullable',
                Rule::in(User::BLOOD_GROUPS),
            ],
            'phone' => [
                'nullable',
                'string',
                'regex:/^(\+?\d{1,4}[-.\s]?)?\(?\d{1,4}\)?[-.\s]?\d{1,9}[-.\s]?\d{1,9}$/',
                'max:20',
            ],
            'city' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Custom messages for corner cases.
     */
    public function messages(): array
    {
        return [
            'name.regex' => 'The name may only contain letters, spaces, dots, and hyphens.',
            'role.in' => 'Please select a valid user role. Administrative accounts require authorized creation.',
            'blood_group.required_if' => 'Blood group is mandatory for voluntary blood donor accounts.',
            'blood_group.in' => 'Please select a valid ABO/Rh blood group.',
            'phone.regex' => 'Please enter a valid phone number format (e.g. 017XXXXXXXX or +8801XXXXXXXXX).',
            'password.confirmed' => 'The password confirmation does not match.',
            'email.unique' => 'An account is already registered with this email address.',
        ];
    }
}
