<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('solicitacoes_limite', function(Blueprint $table){
            $table->id();
            $table->foreignId('conta_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gerente_id')->constrained('users');
            $table->decimal('limite_atual', 15, 2);
            $table->decimal('limite_solicitado', 15, 2);
            $table->enum('status', ['pendente', 'aprovada', 'reprovada'])->default('pendente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacoes_limite');
    }
};
