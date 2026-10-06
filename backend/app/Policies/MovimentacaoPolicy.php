<?php

namespace App\Policies;

use App\Models\Conta;
use App\Models\Movimentacao;
use App\Models\User;

class MovimentacaoPolicy {
    public function view(User $user, Movimentacao $movimentacao): bool {
        return $user->hasPermission('movimentacao.view');
    }

    public function create(User $user, Conta $conta): bool {
        return $user->isCliente() && $user->hasPermission('movimentacao.create') && $conta->isActive();
    }

    public function pix(User $user, Conta $conta): bool {
        return $user->hasPermission('conta.pix') && $conta->isActive();
    }
}