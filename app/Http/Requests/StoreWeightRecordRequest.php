<?php

namespace App\Http\Requests;

use App\Models\Pet;
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
        $pet = Pet::find($this->input('pet_id'));

        return $pet === null || $this->user()->can('create', [WeightRecord::class, $pet]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pet_id' => 'required|exists:pets,id',
            'weight' => 'required|numeric|between:0,150',
            'recorded_at' => 'required|date_format:Y-m-d\TH:i|before_or_equal:now',
        ];
    }
}
