<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->foreignId('gerente_id')
                ->nullable()
                ->after('user_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->index('gerente_id');
        });

        // Compatibilidade com clientes criados antes da associação ao gerente.
        $gerentes = DB::table('users')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->where('roles.name', 'GERENTE_CONTA')
            ->orderBy('users.id')
            ->pluck('users.id');

        if ($gerentes->count() === 1) {
            DB::table('clientes')
                ->whereNull('gerente_id')
                ->update(['gerente_id' => $gerentes->first()]);
        }
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign(['gerente_id']);
            $table->dropIndex(['gerente_id']);
            $table->dropColumn('gerente_id');
        });
    }
};
