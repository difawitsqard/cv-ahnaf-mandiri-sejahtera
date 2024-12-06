<?php

namespace App\Http\Requests\dashboard\superadmin;

use Illuminate\Foundation\Http\FormRequest;

class StockItemManagementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'price' => $this->price ? str_replace('.', '', $this->price) : 0,
            'min_stock' =>  $this->min_stock ?? 0,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules =  [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'unit_id' => 'required',
            'category_id' => 'required',
            'min_stock' => 'nullable|numeric',
            'price' => 'required|numeric',
        ];

        if ($this->isMethod('post')) {
            // Aturan validasi untuk create
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024';
            $rules['stock'] = 'required|numeric';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // Aturan validasi untuk update
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024';
            $rules['delete_image.*'] = 'nullable|exists:stock_items,id';
        }

        return $rules;
    }
}
