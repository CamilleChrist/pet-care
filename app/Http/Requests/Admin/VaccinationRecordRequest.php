<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VaccinationRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pet_id' => ['required', 'integer', 'exists:pets,id'],
            'vaccine_id' => ['nullable', 'exists:vaccines,id', 'required_without:custom_name'],
            'custom_name' => ['nullable', 'string', 'required_without:vaccine_id'],
            'administered_at' => ['required', 'date', 'before_or_equal:today'],
            'next_due_at' => ['nullable', 'date', 'after_or_equal:administered_at'],
            'veterinarian_name' => ['nullable', 'string'],
            'clinic_name' => ['nullable', 'string'],
            'lot_number' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'pet_id' => 'animal',
            'vaccine_id' => 'vaccin',
            'custom_name' => 'nom libre',
            'administered_at' => 'date d\'injection',
            'next_due_at' => 'prochain rappel',
            'veterinarian_name' => 'vétérinaire',
            'clinic_name' => 'clinique',
            'lot_number' => 'numéro de lot',
            'notes' => 'notes',
        ];
    }
}
