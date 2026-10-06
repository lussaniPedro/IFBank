<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClienteRequest;
use App\Models\Cliente;
use App\Services\ClienteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ClienteController extends Controller {
    public function __construct(
        protected ClienteService $service
    ) {}

    public function index(): JsonResponse {
        Gate::authorize('viewAny', Cliente::class);

        return response()->json(
            $this->service->list(auth()->id())
        );
    }

    public function show(Cliente $cliente): JsonResponse {
        Gate::authorize('view', $cliente);

        return response()->json(
            $cliente->load(['user.role', 'conta'])
        );
    }

    public function store(ClienteRequest $request): JsonResponse {
        Gate::authorize('create', Cliente::class);

        $cliente = $this->service->create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Cliente criado com sucesso.',
            'cliente' => $cliente,
        ], 201);
    }

    public function update(
        ClienteRequest $request,
        Cliente $cliente
    ): JsonResponse {
        Gate::authorize('update', $cliente);

        return response()->json([
            'message' => 'Cliente atualizado com sucesso.',
            'cliente' => $this->service->updateCliente(
                $cliente,
                $request->validated()
            ),
        ]);
    }

    public function destroy(Cliente $cliente): JsonResponse {
        Gate::authorize('delete', $cliente);

        $this->service->delete($cliente);

        return response()->json([
            'message' => 'Cliente removido com sucesso.',
        ]);
    }

    public function bloquear(Cliente $cliente): JsonResponse {
        Gate::authorize('bloquear', $cliente);

        return response()->json([
            'message' => 'Cliente bloqueado com sucesso.',
            'cliente' => $this->service->bloquear($cliente),
        ]);
    }

    public function desbloquear(Cliente $cliente): JsonResponse {
        Gate::authorize('desbloquear', $cliente);

        return response()->json([
            'message' => 'Cliente desbloqueado com sucesso.',
            'cliente' => $this->service->desbloquear($cliente),
        ]);
    }

    public function extrato(Cliente $cliente): JsonResponse {
        Gate::authorize('view', $cliente);

        $cliente->load('conta.movimentacoes');

        return response()->json(
            $cliente->conta->movimentacoes
        );
    }

    public function limite(Cliente $cliente): JsonResponse {
        Gate::authorize('view', $cliente);

        return response()->json([
            'cliente' => $cliente->load('user'),
            'conta' => $cliente->conta,
        ]);
    }
}
