<?php

namespace App\Policies;

use App\Models\Cliente;
use App\Models\User;

class ClientePolicy
{
    private function owns(User $user, Cliente $cliente): bool
    {
        return $cliente->gerente_id === $user->id;
    }

    public function viewAny(User $user): bool
    {
        return $user->isGerenteConta() && $user->hasPermission('cliente.index');
    }

    public function view(User $user, Cliente $cliente): bool
    {
        return $user->isGerenteConta()
            && $user->hasPermission('cliente.show')
            && $this->owns($user, $cliente);
    }

    public function create(User $user): bool
    {
        return $user->isGerenteConta() && $user->hasPermission('cliente.create');
    }

    public function update(User $user, Cliente $cliente): bool
    {
        return $user->isGerenteConta()
            && $user->hasPermission('cliente.edit')
            && $this->owns($user, $cliente);
    }

    public function delete(User $user, Cliente $cliente): bool
    {
        return $user->isGerenteConta()
            && $user->hasPermission('cliente.delete')
            && $this->owns($user, $cliente);
    }

    public function bloquear(User $user, Cliente $cliente): bool
    {
        return $user->isGerenteConta()
            && $user->hasPermission('cliente.bloquear')
            && $this->owns($user, $cliente)
            && $cliente->conta?->status === 'ativa';
    }

    public function desbloquear(User $user, Cliente $cliente): bool
    {
        return $user->isGerenteConta()
            && $user->hasPermission('cliente.desbloquear')
            && $this->owns($user, $cliente)
            && $cliente->conta?->status === 'bloqueada';
    }
}
