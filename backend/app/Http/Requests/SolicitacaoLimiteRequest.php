<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SolicitacaoLimiteRequest extends FormRequest {
    public function authorize(): bool {
        return auth()->check() && auth()->user()->isGerenteConta();
    }

    public function rules(): array {
        return [
            'limite_solicitado' => [
                'required',
                'numeric',
                'min:0.01',
            ],
        ];
    }
}