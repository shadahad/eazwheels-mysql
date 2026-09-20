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
        $rules = [
            'itemName' => ['required', 'string', 'min:2', 'max:255'],
            'cost' => ['required', 'numeric', 'min:0.01', 'max:99999.99'],
            'size' => ['required', 'numeric', 'min:1.00', 'max:50.00'],
            'unitsInStock' => ['required', 'integer', 'min:0', 'max:100000'],
            'description' => ['required', 'string', 'min:10', 'max:5000'],
        ];

        // If a file is uploaded from local computer, validate as an image
        if ($this->hasFile('itemImage')) {
            $rules['itemImage'] = ['required', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:10240'];
        } else {
            // Otherwise validate as an API URL string
            $rules['itemImage'] = ['required', 'string', 'url', 'max:1024'];
        }

        return $rules;
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