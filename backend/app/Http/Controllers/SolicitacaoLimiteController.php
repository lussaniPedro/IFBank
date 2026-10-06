<?php

namespace App\Http\Controllers;

use App\Http\Requests\SolicitacaoLimiteRequest;
use App\Models\Cliente;
use App\Models\SolicitacaoLimite;
use App\Services\SolicitacaoLimiteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class SolicitacaoLimiteController extends Controller {
    public function __construct(
        protected SolicitacaoLimiteService $service
    ){}

    public function index(): View {
        Gate::authorize('viewAny', SolicitacaoLimite::class);

        $solicitacoes = $this->service->pendentes();

        return view(
            'admin.solicitacoes.index',
            compact('solicitacoes')
        );
    }

    public function create(Cliente $cliente): View {
        Gate::authorize('create', SolicitacaoLimite::class);

        return view('solicitacoes.create', compact('cliente'));
    }

    public function store(
        SolicitacaoLimiteRequest $request,
        Cliente $cliente
    ): RedirectResponse {
        Gate::authorize('create', SolicitacaoLimite::class);

        $this->service->solicitar(
            $cliente,
            (float) $request->validated()['limite_solicitado']
        );

        return redirect()
            ->route('clientes.index')
            ->with(
                'success',
                'Solicitação de aumento de limite enviada.'
            );
    }

    public function aprovar(
        SolicitacaoLimite $solicitacao
    ): RedirectResponse {
        Gate::authorize('approve', $solicitacao);

        $this->service->aprovar($solicitacao);

        return back()->with(
            'success',
            'Solicitação aprovada.'
        );
    }

    public function reprovar(
        SolicitacaoLimite $solicitacao
    ): RedirectResponse {
        Gate::authorize('reject', $solicitacao);

        $this->service->reprovar($solicitacao);

        return back()->with(
            'success',
            'Solicitação reprovada.'
        );
    }
}