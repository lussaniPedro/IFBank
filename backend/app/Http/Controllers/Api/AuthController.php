<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ClienteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller {
    public function __construct(
        protected ClienteService $clienteService
    ) {
    }

    public function register(Request $request): JsonResponse {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $cliente = $this->clienteService->create($data);

        return response()->json([
            'message' => 'Conta criada com sucesso.',
            'cliente' => $cliente,
        ], 201);
    }

    public function login(Request $request): JsonResponse {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::with('role')
            ->where('email', $data['email'])
            ->first();

        if(!$user || !Hash::check($data['password'], $user->password)){
            return response()->json([
                'message' => 'Credenciais inválidas.'
            ], 401);
        }

        $token = $user->createToken('ifbank')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso.',
            'token' => $token,
            'user' => $user,
            'role' => $user->role?->name,
        ]);
    }

    public function logout(Request $request): JsonResponse {
        $request->user()
            ->currentAccessToken()
            ?->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso.'
        ]);
    }

    public function me(Request $request): JsonResponse {
        return response()->json(
            $request->user()->load('role', 'cliente.conta')
        );
    }
}
