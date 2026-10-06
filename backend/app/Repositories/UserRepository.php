<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository {
    public function __construct(User $model){
        parent::__construct($model);
    }

    public function getGerentes(){
        return $this->model
            ->with('role')
            ->whereHas('role', function ($query) {
                $query->where('name', 'Gerente de Conta');
            })
            ->get();
    }

    public function findWithRole(int $id){
        return $this->model
            ->with('role')
            ->findOrFail($id);
    }
}