<?php

namespace App\Models;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Investimento extends Model implements Auditable {
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'conta_id',
        'tipo',
        'valor',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
    ];

    public function conta(): BelongsTo {
        return $this->belongsTo(Conta::class);
    }
}