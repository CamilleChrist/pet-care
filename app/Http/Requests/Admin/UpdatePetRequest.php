<?php

namespace App\Http\Requests\Admin;

use App\Enums\PetGender;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePetRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'breed_id' => ['required', 'exists:breeds,id'],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', Rule::enum(PetGender::class)],
            'birth_date' => ['required', 'date'],
            'health_notes' => ['nullable', 'string'],
            'last_vet_visit_at' => ['nullable', 'date'],
            'remove_photo' => ['nullable', 'boolean'],
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
            'user_id' => 'propriétaire',
            'breed_id' => 'race',
            'name' => 'nom',
            'gender' => 'sexe',
            'birth_date' => 'date de naissance',
            'health_notes' => 'notes de santé',
            'last_vet_visit_at' => 'dernière visite vétérinaire',
        ];
    }
}
