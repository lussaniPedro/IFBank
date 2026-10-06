<?php

namespace App\Services;

use App\Repositories\ContaRepository;
use App\Repositories\InvestimentoRepository;
use App\Repositories\MovimentacaoRepository;
use Illuminate\Support\Facades\DB;

class InvestimentoService extends BaseService
{
    public function __construct(
        InvestimentoRepository $repository,
        protected ContaRepository $contaRepository,
        protected MovimentacaoRepository $movimentacaoRepository
    ){
        parent::__construct($repository);
    }

    public function aplicar(int $contaId, string $tipo, float $valor){
        if($valor <= 0){
            throw new \InvalidArgumentException('O valor da aplicação deve ser maior que zero.');
        }

        return DB::transaction(function () use ($contaId, $tipo, $valor){
            $conta = $this->contaRepository->find($contaId);

            if(!$conta->isActive()){
                throw new \DomainException(
                    'A conta está bloqueada.'
                );
            }

            if($conta->saldo < $valor){
                throw new \DomainException(
                    'Saldo insuficiente.'
                );
            }

            $conta->decrement('saldo', $valor);

            $investimento = $this->repository->firstOrCreate(
                [
                    'conta_id' => $contaId,
                    'tipo' => $tipo,
                ],
                [
                    'valor' => 0,
                ]
            );

            $investimento->increment('valor', $valor);

            $this->movimentacaoRepository->create([
                'conta_id' => $contaId,
                'tipo' => 'aplicacao',
                'valor' => -$valor,
            ]);

            return $investimento->fresh();
        });
    }

    public function resgatar(int $investimentoId, float $valor){
        return DB::transaction(function () use (
            $investimentoId,
            $valor
        ){
            $investimento = $this->repository->find($investimentoId);

            if($valor <= 0 || $investimento->valor < $valor){
                throw new \DomainException(
                    'Valor de resgate inválido.'
                );
            }

            $conta = $this->contaRepository->find(
                $investimento->conta_id
            );

            $investimento->decrement('valor', $valor);
            $conta->increment('saldo', $valor);

            $this->movimentacaoRepository->create([
                'conta_id' => $conta->id,
                'tipo' => 'resgate',
                'valor' => $valor,
            ]);

            return $investimento->fresh();
        });
    }

    public function getInvestimentos(int $contaId){
        return $this->repository->getByConta($contaId);
    }
}