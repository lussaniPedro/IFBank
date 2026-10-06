<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('movimentacoes', function(Blueprint $table){
            $table->foreignId('conta_destino_id')
                ->nullable()
                ->after('conta_id')
                ->constrained('contas')
                ->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::table('movimentacoes', function(Blueprint $table){
            $table->dropForeign(['conta_destino_id']);
            $table->dropColumn('conta_destino_id');
        });
    }
};