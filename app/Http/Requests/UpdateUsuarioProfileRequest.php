<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsuarioProfileRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('usuarios_fiscalizacion', 'email')->ignore($this->route('usuarios_fiscalizacion'))
            ],
            'cellphone' => 'sometimes|required|string|max:20',
            'status' => 'sometimes|in:active,inactive',
            'distribucion_id' => 'sometimes|required|exists:distribuciones,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.unique' => 'Ya existe un usuario con este email.',
            'cellphone.required' => 'El teléfono celular es obligatorio.',
            'status.in' => 'El estado debe ser active o inactive.',
            'distribucion_id.required' => 'La distribución es obligatoria.',
            'distribucion_id.exists' => 'La distribución seleccionada no existe.',
        ];
    }
}