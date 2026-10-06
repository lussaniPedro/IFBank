<?php

namespace App\Services;

use App\Repositories\ContaRepository;
use Illuminate\Support\Facades\DB;
use DomainException;

class ContaService extends BaseService {
    public function __construct(ContaRepository $repository){
        parent::__construct($repository);
    }

    public function bloquear(int $id){
        return $this->repository->update($id, [
            'status' => 'bloqueada',
        ]);
    }

    public function desbloquear(int $id){
        return $this->repository->update($id, [
            'status' => 'ativa',
        ]);
    }

    public function atualizarSaldo(int $id, float $valor){
        return DB::transaction(function () use ($id, $valor){

            $conta = $this->repository->findOrFail($id);

            $novoSaldo = $conta->saldo + $valor;

            if($novoSaldo < 0){
                throw new DomainException('Saldo insuficiente.');
            }

            return $this->repository->update(
                ['saldo' => $novoSaldo],
                $id
            );
        });
    }
}