<?php

namespace App\Models;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitacaoLimite extends Model implements Auditable {
    use \OwenIt\Auditing\Auditable;

    protected $table = 'solicitacoes_limite';

    protected $fillable = [
        'conta_id',
        'gerente_id',
        'limite_atual',
        'limite_solicitado',
        'status',
    ];

    protected $casts = [
        'limite_atual' => 'decimal:2',
        'limite_solicitado' => 'decimal:2',
    ];

    public function conta(): BelongsTo {
        return $this->belongsTo(Conta::class);
    }

    public function gerente(): BelongsTo {
        return $this->belongsTo(User::class, 'gerente_id');
    }
}