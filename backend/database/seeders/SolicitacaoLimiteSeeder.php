<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SolicitacaoLimiteSeeder extends Seeder {
    public function run(): void {
        $gerenteId = DB::table('users')
            ->where('email', 'gerente@ifbank.test')
            ->value('id');

        $contas = DB::table('contas')->get();

        foreach ($contas as $index => $conta) {
            DB::table('solicitacoes_limite')->updateOrInsert(
                [
                    'conta_id' => $conta->id,
                    'gerente_id' => $gerenteId,
                ],
                [
                    'limite_atual' => $conta->limite,
                    'limite_solicitado' => $conta->limite + 2000 + ($index * 500),
                    'status' => $index % 2 === 0 ? 'pendente' : 'aprovada',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
