<?php

namespace App\Repositories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Collection;

class ClienteRepository extends BaseRepository
{
    public function __construct(Cliente $model)
    {
        parent::__construct($model);
    }

    public function listClientes(?int $gerenteId = null): Collection
    {
        $query = $this->model->with(['user', 'conta', 'gerente']);

        if ($gerenteId !== null) {
            $query->where('gerente_id', $gerenteId);
        }

        return $query->latest()->get();
    }

    public function findWithAccount(int $id): ?Cliente
    {
        return $this->model
            ->with(['user', 'conta', 'gerente'])
            ->find($id);
    }
}
