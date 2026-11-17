<?php

namespace App\Http\Requests\Member1_Rifaat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EligibilityCheckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'has_recent_tattoo' => $this->boolean('has_recent_tattoo'),
            'has_recent_surgery' => $this->boolean('has_recent_surgery'),
            'has_fever_infection' => $this->boolean('has_fever_infection'),
            'has_chronic_illness' => $this->boolean('has_chronic_illness'),
            'is_pregnant_or_breastfeeding' => $this->boolean('is_pregnant_or_breastfeeding'),
        ]);
    }

    public function rules(): array
    {
        return [
            'gender' => ['required', 'string', Rule::in(['male', 'female', 'other'])],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'weight_kg' => ['required', 'numeric', 'min:30', 'max:250'],
            'blood_group' => ['nullable', 'string', Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])],
            'last_donation_date' => ['nullable', 'date', 'before_or_equal:today'],
            'last_donation_type' => ['nullable', 'string', Rule::in(['whole_blood', 'platelets', 'plasma'])],
            'has_recent_tattoo' => ['required', 'boolean'],
            'has_recent_surgery' => ['required', 'boolean'],
            'has_fever_infection' => ['required', 'boolean'],
            'has_chronic_illness' => ['required', 'boolean'],
            'is_pregnant_or_breastfeeding' => ['required', 'boolean'],
            'hemoglobin_g_dl' => ['nullable', 'numeric', 'min:5', 'max:22'],
        ];
    }

    public function messages(): array
    {
        return [
            'date_of_birth.before' => 'Date of birth must be a valid date in the past.',
            'last_donation_date.before_or_equal' => 'Last donation date cannot be set in the future.',
            'weight_kg.min' => 'Please provide a realistic body weight in kilograms.',
            'weight_kg.max' => 'Please verify your weight in kilograms.',
        ];
    }
}
