<?php

namespace App\Policies;

use App\Models\Investimento;
use App\Models\User;

class InvestimentoPolicy {
    public function view(
        User $user,
        Investimento $investimento
    ): bool {
        return $user->hasPermission('conta.saldo');
    }

    public function create(User $user): bool {
        return $user->hasPermission('conta.aplicacao');
    }

    public function redeem(
        User $user,
        Investimento $investimento
    ): bool {
        return $user->hasPermission('conta.resgate') && $investimento->conta->status === 'ativa';
    }
}