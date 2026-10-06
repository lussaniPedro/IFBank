<?php

namespace App\Policies;

use App\Models\Cliente;
use App\Models\SolicitacaoLimite;
use App\Models\User;

class SolicitacaoLimitePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isGerenteGeral() && $user->hasPermission('solicitacao.index');
    }

    public function create(User $user, Cliente $cliente): bool
    {
        return $user->isGerenteConta()
            && $user->hasPermission('cliente.limite.request')
            && $cliente->gerente_id === $user->id;
    }

    public function approve(User $user, SolicitacaoLimite $solicitacao): bool
    {
        return $user->isGerenteGeral()
            && $user->hasPermission('solicitacao.approve')
            && $solicitacao->status === 'pendente';
    }

    public function reject(User $user, SolicitacaoLimite $solicitacao): bool
    {
        return $user->isGerenteGeral()
            && $user->hasPermission('solicitacao.reject')
            && $solicitacao->status === 'pendente';
    }
}
