<?php

namespace App\Repositories;

use App\Models\SolicitacaoLimite;

class SolicitacaoLimiteRepository extends BaseRepository
{
    public function __construct(SolicitacaoLimite $model)
    {
        parent::__construct($model);
    }

    public function pendentes()
    {
        return $this->model->with([
            'conta.cliente.user',
            'gerente',
        ])->where('status', 'pendente')->latest()->get();
    }

    public function getByCliente(int $clienteId)
    {
        return $this->model->with('gerente')
            ->whereHas('conta', fn ($q) => $q->where('cliente_id', $clienteId))
            ->latest()->get();
    }

    public function existePendenteParaConta(int $contaId): bool
    {
        return $this->model->where('conta_id', $contaId)->where('status', 'pendente')->exists();
    }
}
