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
        Schema::create('movimentacoes', function(Blueprint $table){
            $table->id();
            $table->foreignId('conta_id')->constrained()->cascadeOnDelete();
            $table->enum('tipo', [
                'pix_envio',
                'pix_recebimento',
                'aplicacao',
                'resgate'
            ]);
            $table->decimal('valor', 15, 2);
            $table->text('descricao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentacoes');
    }
};
