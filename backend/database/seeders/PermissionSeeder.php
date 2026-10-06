<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder {
    public function run(): void {
        $roles = DB::table('roles')->pluck('id', 'name');
        $resources = DB::table('resources')->pluck('id', 'name');

        $permissions = [];

        $clienteResources = [
            'conta.saldo',
            'conta.extrato',
            'conta.pix',
            'conta.aplicacao',
            'conta.resgate',
        ];

        foreach($clienteResources as $resource){
            $permissions[] = [
                'role_id' => $roles['CLIENTE'],
                'resource_id' => $resources[$resource],
            ];
        }

        $gerenteContaResources = [
            'cliente.index',
            'cliente.create',
            'cliente.show',
            'cliente.edit',
            'cliente.delete',
            'cliente.bloquear',
            'cliente.desbloquear',
            'cliente.extrato',
            'cliente.limite.request',
        ];

        foreach($gerenteContaResources as $resource){
            $permissions[] = [
                'role_id' => $roles['GERENTE_CONTA'],
                'resource_id' => $resources[$resource],
            ];
        }

        $gerenteGeralResources = [
            'gerente.index',
            'gerente.show',
            'gerente.create',
            'gerente.edit',
            'gerente.delete',
            'solicitacao.index',
            'solicitacao.approve',
            'solicitacao.reject',
            'auditoria.index',
        ];

        foreach($gerenteGeralResources as $resource){
            $permissions[] = [
                'role_id' => $roles['GERENTE_GERAL'],
                'resource_id' => $resources[$resource],
            ];
        }

        DB::table('permissions')->upsert(
            $permissions,
            ['role_id', 'resource_id'],
            ['resource_id']
        );
    }
}
