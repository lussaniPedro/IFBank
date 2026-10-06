<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ContaController extends Controller {
    public function show(Request $request): JsonResponse {
        $cliente = $request->user()->cliente;

        if(!$cliente){
            return response()->json([
                'message' => 'Usuário não possui conta.'
            ], 404);
        }

        $conta = $cliente->conta;

        Gate::authorize('view', $conta);

        return response()->json([
            'id' => $conta->id,
            'numero_conta' => $conta->numero_conta,
            'saldo' => $conta->saldo,
            'limite' => $conta->limite,
            'bloqueada' => $conta->status === 'bloqueada',
            'saldo_disponivel' => (float) $conta->saldo + (float) $conta->limite,
        ]);
    }

    public function extrato(Request $request): JsonResponse {
        $conta = $request->user()->cliente?->conta;

        if(!$conta){
            return response()->json([
                'message' => 'Conta não encontrada.'
            ], 404);
        }

        Gate::authorize('statement', $conta);

        if($conta->status !== 'ativa'){
            return response()->json([
                'message' => 'Conta bloqueada. Apenas o saldo pode ser consultado.'
            ], 422);
        }

        $data = $request->validate([
            'data_inicial' => ['nullable', 'date'],
            'data_final' => ['nullable', 'date', 'after_or_equal:data_inicial'],
        ]);

        $query = $conta
            ->movimentacoes()
            ->with('contaDestino.cliente.user');

        if(!empty($data['data_inicial'])){
            $query->whereDate('created_at', '>=', $data['data_inicial']);
        }

        if(!empty($data['data_final'])){
            $query->whereDate('created_at', '<=', $data['data_final']);
        }

        $movimentacoes = $query
            ->latest()
            ->get()
            ->map(function($movimentacao){
                return [
                    'id' => $movimentacao->id,
                    'tipo' => $movimentacao->tipo,
                    'valor' => $movimentacao->valor,
                    'natureza' => match ($movimentacao->tipo){
                        'pix_recebimento',
                        'resgate' => 'entrada',
                        'pix_envio',
                        'aplicacao' => 'saida',
                        default => 'saida',
                    },
                    'descricao' => $movimentacao->descricao,
                    'data' => $movimentacao->created_at,
                ];
            });

        return response()->json($movimentacoes);
    }
}