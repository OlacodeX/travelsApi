<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ToursListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'priceFrom' => 'numeric',
            'priceTo' => 'numeric',
            'dateTo' => 'date',
            'dateFrom' => 'date',
            'sortBy' => Rule::in(['price']),
            'sortOrder' => Rule::in(['asc','desc']),
        ];
    }

    /**
     * Validation messages to override default laravel messages
     *
     * @return array<string, 
     */
    public function messages(): array
    {
        return [
            'sortBy' => "The 'sortBy' parameter accepts only 'price' value",
            'sortOrder' => "The 'sortOrder' parameter accepts only 'asc' or 'desc' values",
        ];
    }
}