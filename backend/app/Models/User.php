<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'role_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable {
    use HasFactory, Notifiable, HasApiTokens;

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function cliente(): HasOne {
        return $this->hasOne(Cliente::class);
    }

    public function solicitacoesLimite(){
        return $this->hasMany(SolicitacaoLimite::class, 'gerente_id');
    }

    public function isGerenteGeral(): bool {
        return $this->role?->name === 'GERENTE_GERAL';
    }

    public function isGerenteConta(): bool {
        return $this->role?->name === 'GERENTE_CONTA';
    }

    public function isCliente(): bool {
        return $this->role?->name === 'CLIENTE';
    }

    public function hasPermission(string $resource): bool {
        if(!$this->role){
            return false;
        }

        return $this->role
            ->resource()
            ->where('name', $resource)
            ->exists();
    }
}