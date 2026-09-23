<?php

namespace App\Http\Requests;

use App\Models\WeightRecord;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreWeightRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', [WeightRecord::class, $this->route('pet')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'weight' => 'required|numeric|between:0,150',
            'recorded_at' => 'required|date_format:Y-m-d\TH:i|before_or_equal:now',
        ];
    }

    /**
     * Noms lisibles des champs dans les messages d'erreur, propres à ce formulaire.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'weight' => 'poids',
            'recorded_at' => 'date',
        ];
    }
}
