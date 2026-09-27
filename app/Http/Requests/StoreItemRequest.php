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
        'itemName'     => ['required', 'string', 'max:255'],
        'itemImage'    => ['required', 'file', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
        'cost'         => ['required', 'numeric', 'min:0.01'],
        'size'         => ['required', 'numeric', 'min:1', 'max:50'],
        'unitsInStock' => ['required', 'integer', 'min:0'],
        'description'  => ['required', 'string'],
        'admin_key'    => ['nullable', 'string'],
    ];
}

    public function messages(): array
    {
        return [
            'itemImage.required' => 'Please select an image file from your computer.',
            'itemImage.image' => 'The uploaded file must be a valid image format (PNG, JPG, JPEG, WEBP, or SVG).',
            'itemImage.max' => 'The image size cannot exceed 10MB.',
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