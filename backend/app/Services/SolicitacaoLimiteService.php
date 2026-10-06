<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\SolicitacaoLimite;
use Illuminate\Support\Facades\DB;

class SolicitacaoLimiteService
{
    public function solicitar(Cliente $cliente, float $limite): SolicitacaoLimite
    {
        return DB::transaction(function () use ($cliente, $limite) {
            $conta = $cliente->conta;

            if (!$conta) {
                throw new \DomainException('O cliente não possui uma conta vinculada.');
            }

            if ($cliente->gerente_id !== auth()->id()) {
                throw new \DomainException('Este cliente não pertence ao gerente de conta autenticado.');
            }

            if ($limite <= (float) $conta->limite) {
                throw new \DomainException('O novo limite deve ser maior que o limite atual.');
            }

            if (SolicitacaoLimite::where('conta_id', $conta->id)
                ->where('status', 'pendente')
                ->exists()) {
                throw new \DomainException('Este cliente já possui uma solicitação de limite pendente.');
            }

            return SolicitacaoLimite::create([
                'conta_id' => $conta->id,
                'gerente_id' => auth()->id(),
                'limite_atual' => $conta->limite,
                'limite_solicitado' => $limite,
                'status' => 'pendente',
            ]);
        });
    }

    public function aprovar(SolicitacaoLimite $solicitacao): SolicitacaoLimite
    {
        return DB::transaction(function () use ($solicitacao) {
            if ($solicitacao->status !== 'pendente') {
                throw new \DomainException('Esta solicitação já foi processada.');
            }

            $solicitacao->conta()->update([
                'limite' => $solicitacao->limite_solicitado,
            ]);

            $solicitacao->update(['status' => 'aprovada']);


            return $solicitacao->fresh();
        });
    }

    public function reprovar(SolicitacaoLimite $solicitacao): SolicitacaoLimite
    {
        if ($solicitacao->status !== 'pendente') {
            throw new \DomainException('Esta solicitação já foi processada.');
        }

        $solicitacao->update(['status' => 'reprovada']);


        return $solicitacao->fresh();
    }
}
