<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use NotificationChannels\WebPush\PushSubscription;

class StorePushSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * The server sends the reminders to `endpoint`: only an https URL is accepted.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'endpoint' => ['required', 'url:https', 'max:'.PushSubscription::ENDPOINT_MAX_LENGTH],
            'key' => ['required', 'string', 'max:255'],
            'token' => ['required', 'string', 'max:255'],
            'encoding' => ['nullable', Rule::in(['aesgcm', 'aes128gcm'])],
        ];
    }
}
