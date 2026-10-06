<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovimentacaoSeeder extends Seeder {
    public function run(): void {
        $contas = DB::table('contas')->get();

        foreach ($contas as $conta) {
            $movimentacoes = [
                [
                    'conta_id' => $conta->id,
                    'conta_destino_id' => null,
                    'tipo' => 'pix_recebimento',
                    'valor' => 1500.00,
                    'descricao' => 'Recebimento inicial',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'conta_id' => $conta->id,
                    'conta_destino_id' => null,
                    'tipo' => 'aplicacao',
                    'valor' => 1200.00,
                    'descricao' => 'Aplicação em CDB',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'conta_id' => $conta->id,
                    'conta_destino_id' => null,
                    'tipo' => 'resgate',
                    'valor' => 350.00,
                    'descricao' => 'Resgate parcial',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            DB::table('movimentacoes')->insertOrIgnore($movimentacoes);
        }
    }
}
