<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'itemName' => ['required', 'string', 'min:2', 'max:255'],
            'itemImage' => ['required', 'string', 'url', 'max:1024'],
            'cost' => ['required', 'numeric', 'min:0.01', 'max:99999.99'],
            'size' => ['required', 'numeric', 'min:1.00', 'max:50.00'],
            'unitsInStock' => ['required', 'integer', 'min:0', 'max:100000'],
            'description' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'cost.min' => 'Cost must be greater than zero in GBP.',
            'size.min' => 'Size must be at least 1 inch.',
            'unitsInStock.min' => 'Stock cannot be negative.',
            'itemImage.url' => 'The itemImage must be a valid URL (HTTP/HTTPS).',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson() || $this->is('api/*')) {
            throw new HttpResponseException(response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422));
        }

        parent::failedValidation($validator);
    }
}