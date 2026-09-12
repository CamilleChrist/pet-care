<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVaccinationRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $pet = $this->route('vaccinationRecord')->pet;

        return [
            'vaccine_id' => [
                'nullable',
                Rule::exists('vaccines', 'id')->when(
                    $pet->breed,
                    fn ($rule, $breed) => $rule->where('species', $breed->species)
                ),
                'required_without:custom_name',
            ],
            'custom_name' => ['nullable', 'string', 'required_without:vaccine_id'],
            'administered_at' => ['required', 'date', 'before_or_equal:today'],
            'next_due_at' => ['nullable', 'date', 'after_or_equal:administered_at'],
            'veterinarian_name' => ['nullable', 'string'],
            'clinic_name' => ['nullable', 'string'],
            'lot_number' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
