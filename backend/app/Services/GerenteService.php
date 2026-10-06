<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\GerenteRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GerenteService extends BaseService
{
    public function __construct(GerenteRepository $repository)
    {
        parent::__construct($repository);
    }

    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $roleId = DB::table('roles')
                ->where('name', 'GERENTE_CONTA')
                ->value('id');

            abort_unless($roleId, 500, 'Role GERENTE_CONTA não encontrada. Execute os seeders.');

            $password = $data['password'] ?? Str::password(12);

            $user = $this->repository->store([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($password),
                'role_id' => $roleId,
            ]);


            return $user->load('role');
        });
    }

    public function updateGerente(User $gerente, array $data): User
    {
        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $gerente->update($payload);

        return $gerente->fresh('role');
    }

    public function deleteGerente(User $gerente): bool
    {
        return $gerente->delete();
    }

    public function listGerentes()
    {
        return $this->repository->listGerentes();
    }
}
