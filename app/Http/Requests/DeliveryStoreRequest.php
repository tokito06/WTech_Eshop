<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeliveryStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'delivery_method_id' => ['required', 'uuid', 'exists:delivery_methods,id'],
            'email' => ['nullable', 'email', 'max:255', Rule::requiredIf(fn () => !$this->user())],
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'phone_number' => ['required', 'string', 'max:20'],
            'street' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'post_code' => ['required', 'string', 'max:50'],
            'country' => ['required', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'house' => ['nullable', 'string', 'max:50'],
        ];
    }
}
