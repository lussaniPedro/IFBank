<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvestimentoSeeder extends Seeder {
    public function run(): void {
        $contas = DB::table('contas')->get();

        foreach ($contas as $conta) {
            $itens = [
                [
                    'conta_id' => $conta->id,
                    'tipo' => 'cdb',
                    'valor' => 2500.00,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'conta_id' => $conta->id,
                    'tipo' => 'poupanca',
                    'valor' => 1800.00,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            DB::table('investimentos')->insertOrIgnore($itens);
        }
    }
}
