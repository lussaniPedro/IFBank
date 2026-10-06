<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class GerenteRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function listGerentes(): Collection
    {
        return $this->model
            ->whereHas('role', function ($query) {
                $query->where(
                    'name',
                    'GERENTE_CONTA'
                );
            })
            ->with('role')
            ->orderBy('name')
            ->get();
    }
}