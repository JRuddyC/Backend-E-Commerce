<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
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
            'email' => ['required', 'unique:users', 'email'],
            'password' => ['required', 'string', 'min:7', 'confirmed'],
            'person_id' => ['required','exists:people,id']
        ];
    }
    protected function prepareForValidation(): void
    {
        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower($this->email),
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El campo email es obligatorio.',
            'email.email' => 'No corresponde a un email válido.',
            'email.unique' => 'El email ya ha sido registrado.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'La contraseña debe tener un mínimo de 7 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'person_id.exists' => 'La persona no esta registrada.',
            'person_id.required' => 'Debe asignar una persona a este usuario.'
        ];
    }
}
