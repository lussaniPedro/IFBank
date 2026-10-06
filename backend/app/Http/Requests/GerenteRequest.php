<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GerenteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isGerenteGeral();
    }

    public function rules(): array
    {
        $gerente = $this->route('gerente');
        $gerenteId = $gerente instanceof \App\Models\User ? $gerente->id : $gerente;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($gerenteId),
            ],
            'password' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'string',
                'min:8',
            ],
        ];
    }
}
