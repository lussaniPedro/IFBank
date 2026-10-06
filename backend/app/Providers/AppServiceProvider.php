<?php

namespace App\Providers;

use App\Models\Cliente;
use App\Models\Conta;
use App\Models\SolicitacaoLimite;
use App\Models\User;
use App\Policies\AuditoriaPolicy;
use App\Policies\ClientePolicy;
use App\Policies\ContaPolicy;
use App\Policies\GerentePolicy;
use App\Policies\SolicitacaoLimitePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use OwenIt\Auditing\Models\Audit;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Gate::policy(User::class, GerentePolicy::class);
        Gate::policy(Cliente::class, ClientePolicy::class);
        Gate::policy(Conta::class, ContaPolicy::class);
        Gate::policy(SolicitacaoLimite::class, SolicitacaoLimitePolicy::class);
        Gate::policy(Audit::class, AuditoriaPolicy::class);
    }
}
