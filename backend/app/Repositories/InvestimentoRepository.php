<?php

namespace App\Repositories;

use App\Models\Investimento;

class InvestimentoRepository extends BaseRepository {
    public function __construct(Investimento $model){
        parent::__construct($model);
    }

    public function getByConta(int $contaId){
        return $this->model
            ->where('conta_id', $contaId)
            ->get();
    }

    public function findByContaAndTipo(int $contaId, string $tipo){
        return $this->model
            ->where('conta_id', $contaId)
            ->where('tipo', $tipo)
            ->firstOrFail();
    }
}