<?php

namespace App\Models;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movimentacao extends Model implements Auditable {
    protected $table = 'movimentacoes';
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'conta_id',
        'conta_destino_id',
        'tipo',
        'valor',
        'descricao',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
    ];

    public function conta(): BelongsTo {
        return $this->belongsTo(Conta::class);
    }

    public function contaDestino(): BelongsTo {
        return $this->belongsTo(Conta::class, 'conta_destino_id');
    }
}