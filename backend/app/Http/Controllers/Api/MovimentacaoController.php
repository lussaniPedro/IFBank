<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conta;
use App\Models\Movimentacao;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Gate;

class MovimentacaoController extends Controller {
    public function pix(Request $request): JsonResponse {
        $data = $request->validate([
            'numero_conta_destino' => [
                'required',
                'string',
                'size:8',
            ],
            'valor' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'descricao' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $origem = $request->user()->cliente?->conta;

        if(!$origem){
            return response()->json([
                'message' => 'Conta de origem não encontrada.'
            ], 404);
        }

        $destino = Conta::where(
            'numero_conta',
            $data['numero_conta_destino']
        )->first();

        if(!$destino){
            return response()->json([
                'message' => 'Conta destino não encontrada.'
            ], 404);
        }

        Gate::authorize('pix', $origem);

        if($origem->id === $destino->id){
            return response()->json([
                'message' => 'Não é possível realizar PIX para a própria conta.'
            ], 422);
        }

        if($origem->status !== 'ativa'){
            return response()->json([
                'message' => 'Conta bloqueada.'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $origem = Conta::whereKey($origem->id)
                ->lockForUpdate()
                ->first();

            $destino = Conta::whereKey($destino->id)
                ->lockForUpdate()
                ->first();

            if($destino->status !== 'ativa'){
                throw ValidationException::withMessages([
                    'numero_conta_destino' => 'A conta de destino está bloqueada.',
                ]);
            }

            $valor = (float) $data['valor'];

            if($origem->saldo < $valor){
                throw ValidationException::withMessages([
                    'valor' => 'Saldo insuficiente.',
                ]);
            }

            $origem->saldo = (float) $origem->saldo - $valor;
            $origem->save();

            $destino->saldo = (float) $destino->saldo + $valor;
            $destino->save();

            Movimentacao::create([
                'conta_id' => $origem->id,
                'conta_destino_id' => $destino->id,
                'tipo' => 'pix_envio',
                'valor' => $valor,
                'descricao' => $data['descricao'] ?? 'PIX enviado',
            ]);

            Movimentacao::create([
                'conta_id' => $destino->id,
                'conta_destino_id' => $origem->id,
                'tipo' => 'pix_recebimento',
                'valor' => $valor,
                'descricao' => $data['descricao'] ?? 'PIX recebido',
            ]);

            DB::commit();

            return response()->json([
                'message' => 'PIX realizado com sucesso.',
                'saldo' => $origem->saldo,
            ], 201);
        } catch(\Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
    }
}