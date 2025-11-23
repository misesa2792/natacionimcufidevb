<?php

namespace App\Http\Requests\Usuarios;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUsuarioRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return canEditRecord("usuarios");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'idnivel'       => 'required|integer',
            'idestatus'     => 'required|integer',
            'idinstitucion' => 'required|integer',
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $this->route('id'),
            'password'      => 'nullable|string|min:6'
        ];
    }
}
