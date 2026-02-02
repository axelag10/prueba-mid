<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sku' => 'required|string|unique:products,sku',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'type' => 'required|in:individual,variant',
            'price' => 'required_if:type,individual|numeric',
            'cost' => 'required_if:type,individual|numeric',
            'provider_id' => 'required|exists:providers,id',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'variant_types' => 'nullable|array',
            'variant_types.*.id' => 'exists:variant_types,id',
            'variant_types.*.price' => 'required_if:type,variant|numeric',
            'variant_types.*.cost' => 'required_if:type,variant|numeric',
        ];
    }
}
