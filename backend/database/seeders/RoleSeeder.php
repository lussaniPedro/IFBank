<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['CLIENTE', 'GERENTE_CONTA', 'GERENTE_GERAL'] as $name) {
            DB::table('roles')->updateOrInsert(
                ['name' => $name],
                ['name' => $name, 'updated_at' => now()]
            );
        }
    }
}
