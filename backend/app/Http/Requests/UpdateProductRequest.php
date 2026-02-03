<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'sku' => 'required|string|max:50|unique:products,sku,' . $this->product->id,
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'provider_id' => 'required|exists:providers,id',
            'type' => 'prohibited',
            'price' => 'required_if:type,simple|numeric|min:0',
            'cost' => 'required_if:type,simple|numeric|min:0',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
            'variant_types' => 'array',
            'variant_types.*.id' => 'exists:variant_types,id',
            'variant_types.*.price' => 'required|numeric|min:0',
            'variant_types.*.cost' => 'required|numeric|min:0',
        ];
    }
}
