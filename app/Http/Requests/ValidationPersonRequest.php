<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationPersonRequest extends FormRequest
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
        $id = $this->route('id');
        $ci = $this->get("ci");
        return [
            'name' => ['required', 'regex:/^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]*$/'],
            'first_surname' => ['nullable', 'regex:/^[A-Za-zñÑáéíóúÁÉÍÓÚ]+$/'],
            'last_surname' => ['nullable', 'regex:/^[A-Za-zÑñ\s]+$/'],
            'ci' => ['required', 'string', 'unique:people,ci,' . ($id ?? 'NULL')],
            'birthdate' => ['required', 'date_format:d-m-Y', 'before:today']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'ci' => strtoupper($this->ci)
        ]);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $first_surname = $this->first_surname;
            $last_surname = $this->last_surname;

            if (empty($first_surname) && empty($last_surname)) {
                $validator->errors()->add('first_surname', 'Al menos uno de los apellidos debe tener un valor.');
                $validator->errors()->add('last_surname', 'Al menos uno de los apellidos debe tener un valor.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo es obligatorio',
            'name.regex' => 'El campo solo debe tener letras',
            'first_surname.regex' => 'El campo solo debe tener letras',
            'last_surname.regex' => 'El campo solo debe tener letras',
            'ci.required' => 'El campo es obligatorio',
            'ci.unique' => 'El documento de identidad ya esta en uso.',
            'birthdate.required' => 'El campo es obligatorio',
            'birthdate.date_format' => 'El formato debe ser d-m-Y',
        ];
    }
}
