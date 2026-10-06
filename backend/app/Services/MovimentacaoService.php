<?php

namespace App\Services;

use App\Repositories\ContaRepository;
use App\Repositories\MovimentacaoRepository;
use DomainException;
use Illuminate\Support\Facades\DB;

class MovimentacaoService extends BaseService {
    public function __construct(
        MovimentacaoRepository $repository,
        protected ContaRepository $contaRepository
    ){
        parent::__construct($repository);
    }

    public function pix(
        int $contaOrigem,
        int $contaDestino,
        float $valor
    ){
        if($valor <= 0){
            throw new DomainException(
                'O valor do PIX deve ser maior que zero.'
            );
        }

        return DB::transaction(function () use (
            $contaOrigem,
            $contaDestino,
            $valor
        ){
            $origem = $this->contaRepository
                ->findOrFail($contaOrigem);

            $destino = $this->contaRepository
                ->findOrFail($contaDestino);

            if($origem->status !== 'ativa'){
                throw new DomainException(
                    'A conta de origem está bloqueada.'
                );
            }

            if($destino->status !== 'ativa'){
                throw new DomainException(
                    'A conta de destino está bloqueada.'
                );
            }

            if($origem->saldo < $valor){
                throw new DomainException(
                    'Saldo insuficiente.'
                );
            }

            $origem->decrement(
                'saldo',
                $valor
            );

            $destino->increment(
                'saldo',
                $valor
            );

            $this->repository->store([
                'conta_id' => $origem->id,
                'tipo' => 'pix_envio',
                'valor' => -$valor,
                'descricao' =>
                    "PIX enviado para conta {$destino->id}",
            ]);

            $this->repository->store([
                'conta_id' => $destino->id,
                'tipo' => 'pix_recebimento',
                'valor' => $valor,
                'descricao' =>
                    "PIX recebido da conta {$origem->id}",
            ]);

            return true;
        });
    }

    public function extrato(
        int $contaId,
        ?string $dataInicio = null,
        ?string $dataFim = null
    ){
        return $this->repository->getByConta(
            $contaId,
            $dataInicio,
            $dataFim
        );
    }
}