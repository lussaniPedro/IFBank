<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
    public function run(): void {
        $clienteRole = DB::table('roles')
            ->where('name', 'CLIENTE')
            ->value('id');

        $gerenteContaRole = DB::table('roles')
            ->where('name', 'GERENTE_CONTA')
            ->value('id');

        $gerenteGeralRole = DB::table('roles')
            ->where('name', 'GERENTE_GERAL')
            ->value('id');

        DB::table('users')->upsert(
            [
                [
                    'name' => 'CLIENTE TESTE',
                    'email' => 'cliente@ifbank.test',
                    'password' => Hash::make('@1234@5678'),
                    'role_id' => $clienteRole,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'GERENTE DE CONTA',
                    'email' => 'gerente@ifbank.test',
                    'password' => Hash::make('@1234@5678'),
                    'role_id' => $gerenteContaRole,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'GERENTE GERAL',
                    'email' => 'geral@ifbank.test',
                    'password' => Hash::make('@1234@5678'),
                    'role_id' => $gerenteGeralRole,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ],
            ['email'],
            ['name', 'password', 'role_id', 'updated_at']
        );
    }
}