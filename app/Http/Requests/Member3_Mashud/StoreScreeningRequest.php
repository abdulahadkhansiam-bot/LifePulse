<?php

namespace App\Http\Requests\Member3_Mashud;

use Illuminate\Foundation\Http\FormRequest;

class StoreScreeningRequest extends FormRequest
{
    public function authorize(): bool
    {
    public function authorize(): bool
    {
        $user = $this->user();

        return $user && ($user->isHospital() || $user->isAdmin());
    }
    }

    public function rules(): array
    {
        $b = config('screening.input_bounds');

        return [
            'donor_id'        => ['required', 'exists:users,id'],
            'appointment_id'  => ['nullable', 'integer'],
            'weight_kg'       => ['required', 'numeric', 'between:' . $b['weight_kg'][0] . ',' . $b['weight_kg'][1]],
            'systolic'        => ['required', 'integer', 'between:' . $b['systolic'][0] . ',' . $b['systolic'][1]],
            'diastolic'       => ['required', 'integer', 'between:' . $b['diastolic'][0] . ',' . $b['diastolic'][1]],
            'pulse'           => ['required', 'integer', 'between:' . $b['pulse'][0] . ',' . $b['pulse'][1]],
            'temperature_c'   => ['required', 'numeric', 'between:' . $b['temperature_c'][0] . ',' . $b['temperature_c'][1]],
            'hemoglobin_g_dl' => ['required', 'numeric', 'between:' . $b['hemoglobin_g_dl'][0] . ',' . $b['hemoglobin_g_dl'][1]],
            'notes'           => ['nullable', 'string', 'max:1000'],
        ];
    }
}