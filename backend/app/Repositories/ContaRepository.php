<?php

namespace App\Repositories;

use App\Models\Conta;

class ContaRepository extends BaseRepository {
    public function __construct(Conta $model){
        parent::__construct($model);
    }

    public function findByCliente(int $clienteId){
        return $this->model
            ->where('cliente_id', $clienteId)
            ->firstOrFail();
    }

    public function findWithMovimentacoes(int $id){
        return $this->model
            ->with('movimentacoes')
            ->findOrFail($id);
    }

    public function findWithInvestimentos(int $id){
        return $this->model
            ->with('investimentos')
            ->findOrFail($id);
    }
}