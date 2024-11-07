<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssingRoleRequest extends FormRequest
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
            'role' => ['required', 'string', 'exists:roles,name'],
            'user' => ['required', 'exists:users,id']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'role' => strtolower($this->role),
        ]);
    }

    public function messages()
    {
        return [
            'role.exists' => 'El rol no existe.',
            'user.exists' => 'El usuario no existe.'
        ];
    }
}
