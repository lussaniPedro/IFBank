<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Models\Cliente;
use App\Services\ClienteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class ClienteController extends Controller {
    public function __construct(
        protected ClienteService $service
    ) {
    }

    public function index(): View {
        Gate::authorize('viewAny', Cliente::class);

        $clientes = $this->service->list();

        return view('clientes.index', compact('clientes'));
    }

    public function create(): View {
        Gate::authorize('create', Cliente::class);

        return view('clientes.create');
    }

    public function store(ClienteRequest $request): RedirectResponse {
        Gate::authorize('create', Cliente::class);

        $this->service->create($request->validated());

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente criado com sucesso.');
    }

    public function edit(Cliente $cliente): View {
        Gate::authorize('update', $cliente);

        return view('clientes.edit', compact('cliente'));
    }

    public function update(
        ClienteRequest $request,
        Cliente $cliente
    ): RedirectResponse {
        Gate::authorize('update', $cliente);

        $this->service->updateCliente(
            $cliente,
            $request->validated()
        );

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente atualizado com sucesso.');
    }

    public function destroy(Cliente $cliente): RedirectResponse {
        Gate::authorize('delete', $cliente);

        $this->service->delete($cliente);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente removido com sucesso.');
    }

    public function bloquear(Cliente $cliente): RedirectResponse {
        Gate::authorize('bloquear', $cliente);

        $this->service->bloquear($cliente);

        return back()->with(
            'success',
            'Cliente bloqueado com sucesso.'
        );
    }

    public function desbloquear(Cliente $cliente): RedirectResponse {
        Gate::authorize('desbloquear', $cliente);

        $this->service->desbloquear($cliente);

        return back()->with(
            'success',
            'Cliente desbloqueado com sucesso.'
        );
    }

    public function extrato(Cliente $cliente): View {
        $cliente->load('conta.movimentacoes');

        return view('clientes.extrato', compact('cliente'));
    }
}