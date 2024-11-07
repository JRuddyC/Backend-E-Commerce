<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationLogin extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required|min:7'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El campo email es obligatorio.',
            'email.email' => 'No corresponde a un email válido.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'La contraseña debe tener un mínimo de 7 caracteres.',
        ];
    }
}
