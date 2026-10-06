<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService {
    public function __construct(UserRepository $repository){
        parent::__construct($repository);
    }

    public function createGerente(array $data){
        $data['password'] = Hash::make($data['password']);

        return $this->repository->create($data);
    }

    public function updateGerente(int $id, array $data){
        if(isset($data['password'])){
            $data['password'] = Hash::make($data['password']);
        }

        return $this->repository->update($id, $data);
    }

    public function getGerentes(){
        return $this->repository->getGerentes();
    }
}