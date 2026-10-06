<?php

namespace App\Policies;

use App\Models\Conta;
use App\Models\User;

class ContaPolicy
{
    public function view(User $user, Conta $conta): bool
    {
        return $user->isCliente()
            && $user->hasPermission('conta.saldo')
            && $user->cliente?->conta?->id === $conta->id;
    }

    public function statement(User $user, Conta $conta): bool
    {
        return $user->isCliente()
            && $user->hasPermission('conta.extrato')
            && $user->cliente?->conta?->id === $conta->id;
    }

    public function pix(User $user, Conta $conta): bool
    {
        return $user->isCliente()
            && $user->hasPermission('conta.pix')
            && $user->cliente?->conta?->id === $conta->id
            && $conta->isActive();
    }

    public function application(User $user, Conta $conta): bool
    {
        return $user->isCliente()
            && $user->hasPermission('conta.aplicacao')
            && $user->cliente?->conta?->id === $conta->id
            && $conta->isActive();
    }

    public function redeem(User $user, Conta $conta): bool
    {
        return $user->isCliente()
            && $user->hasPermission('conta.resgate')
            && $user->cliente?->conta?->id === $conta->id
            && $conta->isActive();
    }
}
