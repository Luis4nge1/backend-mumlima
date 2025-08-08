<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEntityRequest extends FormRequest
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
            'parent_id' => 'sometimes|nullable|exists:entities,id',
            'type_id' => 'sometimes|required|exists:entity_types,id',
            'name' => 'sometimes|required|string|max:255',
            'short_name' => 'sometimes|nullable|string|max:100',
            'description' => 'sometimes|nullable|string|max:1000',
            'contact_email' => 'sometimes|nullable|email|max:255',
            'contact_phone' => 'sometimes|nullable|string|max:20',
            'address' => 'sometimes|nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'parent_id.exists' => 'La entidad padre seleccionada no existe.',
            'type_id.required' => 'El tipo de entidad es obligatorio.',
            'type_id.exists' => 'El tipo de entidad seleccionado no existe.',
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'short_name.max' => 'El nombre corto no puede tener más de 100 caracteres.',
            'description.max' => 'La descripción no puede tener más de 1000 caracteres.',
            'contact_email.email' => 'El email de contacto debe tener un formato válido.',
            'contact_email.max' => 'El email de contacto no puede tener más de 255 caracteres.',
            'contact_phone.max' => 'El teléfono de contacto no puede tener más de 20 caracteres.',
            'address.max' => 'La dirección no puede tener más de 500 caracteres.',
        ];
    }
}