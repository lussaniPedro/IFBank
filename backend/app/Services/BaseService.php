<?php

namespace App\Services;

use App\Repositories\BaseRepository;

abstract class BaseService {
    public function __construct(
        protected BaseRepository $repository
    ){}

    public function all(
        array $with = [],
        array $where = [],
        string $orderBy = 'id'
    ){
        return $this->repository->list(
            $with,
            $where,
            $orderBy
        );
    }

    public function allPaginate(
        array $with = [],
        array $where = [],
        string $orderBy = 'id',
        int $limit = 6
    ){
        return $this->repository->listPaginate(
            $with,
            $where,
            $orderBy,
            $limit
        );
    }

    public function find(int|string $id, array $with = []){
        return $this->repository->find($id, $with);
    }

    public function findOrFail(
        int|string $id,
        array $with = []
    ) {
        return $this->repository->findOrFail($id, $with);
    }

    public function store(array $data){
        return $this->repository->store($data);
    }

    public function update(array $data, int|string $id){
        return $this->repository->update($data, $id);
    }

    public function remove(int|string $id){
        return $this->repository->remove($id);
    }

    public function audit(int|string $id){
        return $this->repository->audit($id);
    }
}