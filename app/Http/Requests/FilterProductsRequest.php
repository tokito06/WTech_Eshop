<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class FilterProductsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $sizes = $this->query('sizes');

        if (is_string($sizes)) {
            $sizes = [$sizes];
        }

        if (!is_array($sizes)) {
            $sizes = [];
        }

        $normalizedSizes = array_values(array_filter(array_map(
            static fn ($size) => strtoupper(trim((string) $size)),
            $sizes
        )));

        $this->merge([
            'q' => trim((string) $this->query('q', '')),
            'sizes' => $normalizedSizes,
        ]);
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:255'],
            'price_min' => ['nullable', 'numeric', 'min:0'],
            'price_max' => ['nullable', 'numeric', 'min:0'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'brand_id' => ['nullable', 'integer', 'exists:brands,id'],
            'sex' => ['nullable', 'in:men,women,kids,unisex'],
            'sizes' => ['nullable', 'array'],
            'sizes.*' => ['string', 'max:4'],
            'sort' => ['nullable', 'in:price_asc,price_desc,newest'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $min = $this->input('price_min');
            $max = $this->input('price_max');

            if ($min !== null && $max !== null && (float) $max < (float) $min) {
                $validator->errors()->add('price_max', 'The maximum price must be greater than or equal to the minimum price.');
            }
        });
    }
}
