<?php

namespace App\Http\Requests\dashboard;

use Illuminate\Foundation\Http\FormRequest;

class MenuManagementRequest extends FormRequest
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
            'price' => 'required|numeric',
            'stock_item_id.*' => 'nullable|exists:stock_items,id',
            'quantity.*' => 'nullable|integer|min:1|required_with:stock_item_id.*',
        ];

        // Validasi untuk menu pictures
        foreach (range(1, 4) as $index) {
            $rules["menu_pict_{$index}"] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024';
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['delete_image.*'] = 'nullable|exists:menu_images,id';
        }

        return $rules;
    }
}
