<?php

namespace App\Policies;

use App\Models\User;

class GerentePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isGerenteGeral() && $user->hasPermission('gerente.index');
    }

    public function view(User $user, User $gerente): bool
    {
        return $user->isGerenteGeral()
            && $user->hasPermission('gerente.show')
            && $gerente->isGerenteConta();
    }

    public function create(User $user): bool
    {
        return $user->isGerenteGeral() && $user->hasPermission('gerente.create');
    }

    public function update(User $user, User $gerente): bool
    {
        return $user->isGerenteGeral()
            && $user->hasPermission('gerente.edit')
            && $gerente->isGerenteConta();
    }

    public function delete(User $user, User $gerente): bool
    {
        return $user->isGerenteGeral()
            && $user->hasPermission('gerente.delete')
            && $gerente->isGerenteConta();
    }
}
