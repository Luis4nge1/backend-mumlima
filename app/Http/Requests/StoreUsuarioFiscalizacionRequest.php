<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUsuarioFiscalizacionRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios_fiscalizacion,email',
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()],
            'cellphone' => 'required|string|max:20',
            'status' => 'sometimes|in:active,inactive',
            'distribucion_id' => 'required|exists:distribuciones,id',
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
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.mixed' => 'La contraseña debe contener mayúsculas y minúsculas.',
            'password.numbers' => 'La contraseña debe contener al menos un número.',
            
            'cellphone.required' => 'El teléfono celular es obligatorio.',
            'status.in' => 'El estado debe ser active o inactive.',
            'distribucion_id.required' => 'La distribución es obligatoria.',
            'distribucion_id.exists' => 'La distribución seleccionada no existe.',
        ];
    }
}