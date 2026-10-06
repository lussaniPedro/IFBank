<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\User;
use App\Repositories\ClienteRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClienteService extends BaseService
{
    public function __construct(ClienteRepository $repository)
    {
        parent::__construct($repository);
    }

    public function create(array $data): Cliente
    {
        $gerenteId = auth()->id();

        if (!auth()->user()?->isGerenteConta()) {
            throw new \DomainException('Somente gerente de conta pode cadastrar cliente.');
        }

        return DB::transaction(function () use ($data, $gerenteId) {
            $roleId = DB::table('roles')
                ->where('name', 'CLIENTE')
                ->value('id');

            $password = $data['password'] ?? strval(random_int(10000000, 99999999));

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($password),
                'role_id' => $roleId,
            ]);

            $cliente = Cliente::create([
                'user_id' => $user->id,
                'gerente_id' => $gerenteId,
            ]);

            $cliente->conta()->create([
                'numero_conta' => $this->gerarNumeroConta(),
                'saldo' => (float) ($data['saldo'] ?? 0),
                'limite' => (float) ($data['limite'] ?? 0),
                'status' => 'ativa',
            ]);

            return $cliente->load(['user', 'conta', 'gerente']);
        });
    }

    public function updateCliente(Cliente $cliente, array $data): Cliente
    {
        return DB::transaction(function () use ($cliente, $data) {
            $cliente->user()->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            return $cliente->load(['user', 'conta', 'gerente']);
        });
    }

    public function bloquear(Cliente $cliente): Cliente
    {
        $cliente->conta()->update(['status' => 'bloqueada']);
        return $cliente->load('conta');
    }

    public function desbloquear(Cliente $cliente): Cliente
    {
        $cliente->conta()->update(['status' => 'ativa']);
        return $cliente->load('conta');
    }

    public function delete(Cliente $cliente): bool
    {
        return DB::transaction(function () use ($cliente) {
            $user = $cliente->user;
            $cliente->delete();
            return $user->delete();
        });
    }

    public function list(?int $gerenteId = null)
    {
        return $this->repository->listClientes($gerenteId);
    }

    private function gerarNumeroConta(): string
    {
        do {
            $numero = str_pad(
                (string) random_int(1, 99999999),
                8,
                '0',
                STR_PAD_LEFT
            );
        } while (\App\Models\Conta::where('numero_conta', $numero)->exists());

        return $numero;
    }
}
