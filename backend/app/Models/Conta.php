<?php

namespace App\Models;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conta extends Model implements Auditable {
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'cliente_id',
        'numero_conta',
        'saldo',
        'limite',
        'status',
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
        'limite' => 'decimal:2',
    ];

    public function cliente(): BelongsTo {
        return $this->belongsTo(Cliente::class);
    }

    public function movimentacoes(): HasMany {
        return $this->hasMany(Movimentacao::class);
    }

    public function investimentos(): HasMany {
        return $this->hasMany(Investimento::class);
    }

    public function solicitacoesLimite(): HasMany {
        return $this->hasMany(SolicitacaoLimite::class);
    }

    public function isActive(): bool {
        return $this->status === 'ativa';
    }

    public function podeMovimentar(float $valor): bool {
        return $this->isActive() && $valor > 0;
    }
}