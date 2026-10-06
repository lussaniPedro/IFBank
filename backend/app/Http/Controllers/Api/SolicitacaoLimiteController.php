<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\SolicitacaoLimite;
use App\Services\SolicitacaoLimiteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SolicitacaoLimiteController extends Controller {
    public function __construct(
        protected SolicitacaoLimiteService $service
    ) {}

    public function index(): JsonResponse {
        Gate::authorize('viewAny', SolicitacaoLimite::class);

        return response()->json(
            SolicitacaoLimite::with([
                'conta.cliente.user',
                'gerente',
            ])->latest()->get()
        );
    }

    public function store(
        Request $request,
        Cliente $cliente
    ): JsonResponse {
        Gate::authorize('create', [$cliente]);

        $data = $request->validate([
            'limite_solicitado' => [
                'required',
                'numeric',
                'min:0.01',
            ],
        ]);

        $solicitacao = $this->service->solicitar(
            $cliente,
            (float) $data['limite_solicitado']
        );

        return response()->json([
            'message' => 'Solicitação de aumento de limite enviada.',
            'solicitacao' => $solicitacao->load([
                'conta.cliente.user',
                'gerente',
            ]),
        ], 201);
    }

    public function aprovar(
        SolicitacaoLimite $solicitacao
    ): JsonResponse {
        Gate::authorize('approve', $solicitacao);

        return response()->json([
            'message' => 'Solicitação aprovada.',
            'solicitacao' => $this->service->aprovar(
                $solicitacao
            ),
        ]);
    }

    public function reprovar(
        SolicitacaoLimite $solicitacao
    ): JsonResponse {
        Gate::authorize('reject', $solicitacao);

        return response()->json([
            'message' => 'Solicitação reprovada.',
            'solicitacao' => $this->service->reprovar(
                $solicitacao
            ),
        ]);
    }
}
