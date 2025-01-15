<?php

namespace App\Http\Requests\dashboard;

use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Event\Test\PreparationFailed;

class OutletManagementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'tax' => $this->tax ? (float) $this->tax : 0,
            'discount' => $this->discount ? (float) $this->discount : 0,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone_number' => 'nullable|numeric|digits_between:1,20',
            'tax' => 'nullable|numeric|between:0,100.00',
            'discount' => 'nullable|numeric|between:0,100.00',
        ];

        if ($this->isMethod('post')) {
            // Aturan validasi untuk create
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // Aturan validasi untuk update
            $rules['delete_image'] = 'nullable|exists:outlets,id';
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        return $rules;
    }
}
