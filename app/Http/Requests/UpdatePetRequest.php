<?php

namespace App\Http\Requests;

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
        if (auth()->check()) {
            return true;
        }

        return false;
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
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'health_notes' => ['nullable', 'string'],
            'last_vet_visit_at' => ['nullable', 'date'],
            'remove_photo' => ['nullable', 'boolean'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => auth()->user()->id,
        ]);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'photo.uploaded' => 'La photo est trop volumineuse pour être envoyée (2 Mo maximum).',
            'photo.max' => 'La photo ne doit pas dépasser 2 Mo.',
        ];
    }
}
