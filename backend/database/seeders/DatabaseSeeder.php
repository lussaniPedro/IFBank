<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            RoleSeeder::class,
            ResourceSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            ClienteSeeder::class,
            MovimentacaoSeeder::class,
            InvestimentoSeeder::class,
            SolicitacaoLimiteSeeder::class,
        ]);
    }
}