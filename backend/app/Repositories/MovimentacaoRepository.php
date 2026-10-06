<?php

namespace App\Repositories;

use App\Models\Movimentacao;

class MovimentacaoRepository extends BaseRepository {
    public function __construct(Movimentacao $model){
        parent::__construct($model);
    }

    public function getByConta(int $contaId, ?string $dataInicio = null, ?string $dataFim = null){
        $query = $this->model
            ->where('conta_id', $contaId)
            ->orderByDesc('created_at');

        if($dataInicio){
            $query->whereDate('created_at', '>=', $dataInicio);
        }

        if($dataFim){
            $query->whereDate('created_at', '<=', $dataFim);
        }

        return $query->get();
    }
}