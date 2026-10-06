<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository {
    public function __construct(
        protected Model $model
    ){}

    public function list(array $with = [], array $where = [], string $orderBy = 'id'){
        $query = $this->model->with($with);

        if(isset($where['field']) && isset($where['value'])){
            $query->where(
                $where['field'],
                $where['value']
            );
        }

        return $query
            ->orderBy($orderBy)
            ->get();
    }

    public function listPaginate(array $with = [], array $where = [], string $orderBy = 'id', int $limit = 6){
        $query = $this->model->with($with);

        if(isset($where['field']) && isset($where['value'])){
            $query->where(
                $where['field'],
                $where['value']
            );
        }

        return $query
            ->orderBy($orderBy)
            ->paginate($limit);
    }

    public function find(int|string $id, array $with = []): ?Model {
        return $this->model
            ->with($with)
            ->find($id);
    }

    public function findOrFail(int|string $id, array $with = []): Model {
        return $this->model
            ->with($with)
            ->findOrFail($id);
    }

    public function store(array $data): ?Model{
        return $this->model->create($data);
    }

    public function update(array $data, int|string $id): ?Model {
        $row = $this->model->findOrFail($id);

        $row->update($data);

        return $row->fresh();
    }

    public function remove(int|string $id): ?bool{
        return $this->model
            ->findOrFail($id)
            ->delete();
    }

    public function audit(int|string $id){
        $row = $this->model->findOrFail($id);

        return $row
            ->audits()
            ->with('user')
            ->latest()
            ->get();
    }
}