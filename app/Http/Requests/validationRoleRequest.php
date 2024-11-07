<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Permission;

class validationRoleRequest extends FormRequest
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
        $id = $this->route("id");
        return [
            'name' => ['required', 'string', 'unique:roles,name,' . ($id ?? 'NULL')],
            'permissions' =>  ["array"],
        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => strtolower($this->name),
            'permissions' => array_map('strtolower', $this->permissions),
        ]);
    }
    public function messages()
    {
        return [
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.unique' => 'El rol ya ha sido registrado.',
            'permissions.array' => 'Los permisos deben ser un array.',
        ];
    }
    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $permissions = $this->permissions;
            $invalidPermissions = [];

            foreach ($permissions as $permission) {
                if (!Permission::where('name', $permission)->exists()) {
                    $invalidPermissions[] = $permission;
                }
            }

            if (!empty($invalidPermissions)) {
                $validator->errors()->add('permissions', 'No existen los siguientes permisos: ' . implode(', ', $invalidPermissions));
            }
        });
    }
}
