<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        $resources = [
            'cliente.index','cliente.create','cliente.show','cliente.edit','cliente.delete',
            'cliente.bloquear','cliente.desbloquear','cliente.extrato','cliente.limite.request',
            'conta.index','conta.show','conta.saldo','conta.extrato','conta.pix','conta.aplicacao',
            'conta.resgate','conta.bloquear','conta.desbloquear','pix.create','pix.show',
            'investimento.index','investimento.create','investimento.resgate','limite.create',
            'limite.show','limite.aprovar','limite.reprovar','solicitacao.index',
            'solicitacao.approve','solicitacao.reject','gerente.index','gerente.show',
            'gerente.create','gerente.edit','gerente.delete','auditoria.index',
        ];

        foreach ($resources as $name) {
            DB::table('resources')->updateOrInsert(
                ['name' => $name],
                ['name' => $name, 'updated_at' => now()]
            );
        }
    }
}
