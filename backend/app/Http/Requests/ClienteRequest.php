<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest {
    public function authorize(): bool {
        return auth()->check() && auth()->user()->isGerenteConta();
    }

    public function rules(): array {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . optional($this->route('cliente')?->user)->id,
            ],
            'password' => ['nullable', 'string', 'min:8'],
            'saldo' => ['nullable', 'numeric', 'min:0'],
            'limite' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}