<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Investimento;
use App\Models\Movimentacao;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Gate;

class InvestimentoController extends Controller {
    public function index(Request $request): JsonResponse {
        $conta = $request->user()->cliente?->conta;

        if(!$conta){
            return response()->json([
                'message' => 'Conta não encontrada.'
            ], 404);
        }

        Gate::authorize('view', $conta->investimentos()->first() ?? new Investimento(['conta_id' => $conta->id]));

        if($conta->status !== 'ativa'){
            return response()->json([
                'message' => 'Conta bloqueada.'
            ], 422);
        }

        return response()->json(
            $conta->investimentos()->latest()->get()
        );
    }

    public function aplicar(Request $request): JsonResponse {
        $data = $request->validate([
            'tipo' => [
                'required',
                'in:CDB,CDI,POUPANCA',
            ],
            'valor' => [
                'required',
                'numeric',
                'min:0.01',
            ],
        ]);

        $data['tipo'] = strtolower($data['tipo']);

        $conta = $request->user()->cliente?->conta;

        Gate::authorize('create', Investimento::class);

        if(!$conta){
            return response()->json([
                'message' => 'Conta não encontrada.'
            ], 404);
        }

        if($conta->status !== 'ativa'){
            return response()->json([
                'message' => 'Conta bloqueada.'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $conta = $conta
                ->newQuery()
                ->lockForUpdate()
                ->find($conta->id);

            $valor = (float) $data['valor'];

            if((float) $conta->saldo < $valor){
                throw ValidationException::withMessages([
                    'valor' => 'Saldo insuficiente.',
                ]);
            }

            $conta->saldo = (float) $conta->saldo - $valor;
            $conta->save();

            $investimento = Investimento::create([
                'conta_id' => $conta->id,
                'tipo' => $data['tipo'],
                'valor' => $valor,
            ]);

            Movimentacao::create([
                'conta_id' => $conta->id,
                'tipo' => 'aplicacao',
                'valor' => $valor,
                'descricao' => 'Aplicação em '.strtoupper($data['tipo']),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Aplicação realizada com sucesso.',
                'investimento' => $investimento,
                'saldo' => $conta->saldo,
            ], 201);
        } catch(\Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    public function resgatar(Request $request): JsonResponse {
        $data = $request->validate([
            'tipo' => [
                'required',
                'string',
                'in:CDB,CDI,POUPANCA',
            ],
            'valor' => [
                'required',
                'numeric',
                'min:0.01',
            ],
        ]);

        $data['tipo'] = strtolower($data['tipo']);

        $conta = $request->user()->cliente?->conta;

        if(!$conta){
            return response()->json([
                'message' => 'Conta não encontrada.'
            ], 404);
        }

        DB::beginTransaction();

        try {
            $investimento = $conta->investimentos()
                ->where('tipo', $data['tipo'])
                ->first();

            if(!$investimento){
                return response()->json([
                    'message' => 'Investimento não encontrado.'
                ], 404);
            }

            if((float) $data['valor'] > (float) $investimento->valor){
                return response()->json([
                    'message' => 'Valor de resgate superior ao valor investido.'
                ], 422);
            }

            Gate::authorize('redeem', $investimento);

            $conta = $conta
                ->newQuery()
                ->lockForUpdate()
                ->find($conta->id);

            $valor = (float) $investimento->valor;

            $conta->saldo = (float) $conta->saldo + $valor;
            $conta->save();

            $tipo = $investimento->tipo;

            $investimento->delete();

            Movimentacao::create([
                'conta_id' => $conta->id,
                'tipo' => 'resgate',
                'valor' => $valor,
                'descricao' => 'Resgate de '.strtoupper($tipo),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Resgate realizado com sucesso.',
                'valor' => $valor,
                'saldo' => $conta->saldo,
            ]);
        } catch(\Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
    }
}