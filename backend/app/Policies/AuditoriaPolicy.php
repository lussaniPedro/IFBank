<?php

namespace App\Policies;

use App\Models\User;
use OwenIt\Auditing\Models\Audit;

class AuditoriaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isGerenteGeral()
            && $user->hasPermission('auditoria.index');
    }
}
