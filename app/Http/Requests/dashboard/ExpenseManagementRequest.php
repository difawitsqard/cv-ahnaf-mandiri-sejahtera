<?php

namespace App\Http\Requests\dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExpenseManagementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $items = $this->input('items', []);
        foreach ($items as $index => $item) {
            if (isset($item['quantity'])) {
                $items[$index]['quantity'] = (int) $item['quantity'];
            }
            if (isset($item['stock_item_id'])) {
                $items[$index]['stock_item_id'] = is_numeric($item['stock_item_id']) ? (int) $item['stock_item_id'] : null;
            }
        }
        $this->merge(['items' => $items]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'date_out' => [
                'required',
                'date_format:d M Y H:i',
                'after_or_equal:' . now()->subDays(3)->format('d M Y H:i'), // Bisa mundur hingga 3 hari ke belakang
                'before_or_equal:' . now()->addDays(7)->format('d M Y H:i'), // Tidak bisa lebih dari 7 hari ke depan
            ],
            'items' => 'required|array',
            'items.*.stock_item_id' => 'nullable|exists:stock_items,id',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer',
            'items.*.subtotal' => 'required|numeric',
            'items.*.description' => 'nullable|string',
            'items.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'status' => false,
            'code' => 422,
            'errors' => $validator->errors(),
        ], 422);

        throw new HttpResponseException($response);
    }
}
