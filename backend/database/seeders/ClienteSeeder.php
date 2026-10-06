<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClienteSeeder extends Seeder {
    public function run(): void {
        $clienteRoleId = DB::table('roles')
            ->where('name', 'CLIENTE')
            ->value('id');

        $clientes = [
            [
                'name' => 'Cliente Demo 1',
                'email' => 'cliente1@ifbank.test',
                'password' => '@1234@5678',
                'numero_conta' => '10000001',
                'saldo' => 15500.00,
                'limite' => 5000.00,
                'status' => 'ativa',
            ],
            [
                'name' => 'Cliente Demo 2',
                'email' => 'cliente2@ifbank.test',
                'password' => '@1234@5678',
                'numero_conta' => '10000002',
                'saldo' => 8200.50,
                'limite' => 3500.00,
                'status' => 'ativa',
            ],
            [
                'name' => 'Cliente Demo 3',
                'email' => 'cliente3@ifbank.test',
                'password' => '@1234@5678',
                'numero_conta' => '10000003',
                'saldo' => 4200.75,
                'limite' => 2000.00,
                'status' => 'ativa',
            ],
        ];

        foreach ($clientes as $cliente) {
            $user = DB::table('users')->where('email', $cliente['email'])->first();

            if (!$user) {
                $userId = DB::table('users')->insertGetId([
                    'name' => $cliente['name'],
                    'email' => $cliente['email'],
                    'password' => Hash::make($cliente['password']),
                    'role_id' => $clienteRoleId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $userId = $user->id;

                DB::table('users')->where('id', $userId)->update([
                    'name' => $cliente['name'],
                    'password' => Hash::make($cliente['password']),
                    'role_id' => $clienteRoleId,
                    'updated_at' => now(),
                ]);
            }

            $clienteId = DB::table('clientes')->where('user_id', $userId)->value('id');

            if (!$clienteId) {
                $clienteId = DB::table('clientes')->insertGetId([
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $conta = DB::table('contas')->where('cliente_id', $clienteId)->first();

            $contaData = [
                'cliente_id' => $clienteId,
                'numero_conta' => $cliente['numero_conta'],
                'saldo' => $cliente['saldo'],
                'limite' => $cliente['limite'],
                'status' => $cliente['status'],
                'updated_at' => now(),
            ];

            if ($conta) {
                DB::table('contas')->where('id', $conta->id)->update(array_merge($contaData, [
                    'created_at' => $conta->created_at,
                ]));
            } else {
                DB::table('contas')->insert(array_merge($contaData, [
                    'created_at' => now(),
                ]));
            }
        }
    }
}
