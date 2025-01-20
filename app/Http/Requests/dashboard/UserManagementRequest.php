<?php

namespace App\Http\Requests\dashboard;

use Illuminate\Foundation\Http\FormRequest;

class UserManagementRequest extends FormRequest
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

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $this->route('user')],
            'mobile_phone_number' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'string', 'exists:roles,id'],
            'outlet_id' => ['nullable', 'string', 'exists:outlets,id'],
        ];

        // if ($this->isMethod('post')) {

        // }

        return $rules;
    }
}
