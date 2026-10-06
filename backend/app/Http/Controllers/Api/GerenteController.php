<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GerenteRequest;
use App\Models\User;
use App\Services\GerenteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class GerenteController extends Controller {
    public function __construct(
        protected GerenteService $service
    ) {}

    public function index(): JsonResponse {
        Gate::authorize('viewAny', User::class);

        return response()->json(['success' => true, 'data' => $this->service->listGerentes()]);
    }

    public function store(GerenteRequest $request): JsonResponse {
        Gate::authorize('create', User::class);

        $gerente = $this->service->create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Gerente de conta criado com sucesso.',
            'gerente' => $gerente->load('role'),
        ], 201);
    }

    public function show(User $gerente): JsonResponse {
        Gate::authorize('view', $gerente);

        return response()->json(['success' => true, 'data' => $gerente->load('role')]);
    }

    public function update(
        GerenteRequest $request,
        User $gerente
    ): JsonResponse {
        Gate::authorize('update', $gerente);

        return response()->json([
            'message' => 'Gerente atualizado com sucesso.',
            'gerente' => $this->service->updateGerente(
                $gerente,
                $request->validated()
            ),
        ]);
    }

    public function destroy(User $gerente): JsonResponse {
        Gate::authorize('delete', $gerente);

        $this->service->deleteGerente($gerente);

        return response()->json([
            'message' => 'Gerente removido com sucesso.',
        ]);
    }
}
