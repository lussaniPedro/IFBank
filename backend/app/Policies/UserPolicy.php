<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy {
    public function viewAny(User $user): bool {
        return $user->hasPermission('gerente.index');
    }

    public function view(User $user, User $target): bool {
        return $user->hasPermission('gerente.show');
    }

    public function create(User $user): bool {
        return $user->hasPermission('gerente.create');
    }

    public function update(User $user, User $target): bool {
        return $user->hasPermission('gerente.edit');
    }

    public function delete(User $user, User $target): bool {
        return $user->hasPermission('gerente.delete');
    }
}