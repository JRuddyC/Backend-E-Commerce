<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Role;

class ValidationAssignRoleRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'roles' => ['array']
        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'roles' => array_map('strtolower', $this->roles),
        ]);
    }
    public function messages()
    {
        return [
            'user_id.required'=>'El usuario es requerido.',
            'user_id.exists'=>'El usuario no existe.',
            'roles' => 'Los permisos deben ser un array.',
        ];
    }
    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $roles = $this->roles;
            $invalidRoles = [];

            foreach ($roles as $role) {
                if (!Role::where('name', $role)->exists()) {
                    $invalidRoles[] = $role;
                }
            }

            if (!empty($invalidRoles)) {
                $validator->errors()->add('roles', 'No existen los siguientes roles: ' . implode(', ', $invalidRoles));
            }
        });
    }
}
