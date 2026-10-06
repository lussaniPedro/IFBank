<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Models\Conta;
use App\Models\Cliente;
use App\Services\ClienteService;
use App\Services\SolicitacaoLimiteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AdminContaController extends Controller
{
    public function __construct(
        protected ClienteService $clienteService,
        protected SolicitacaoLimiteService $limiteService,
    ) {}

    public function index(): View
    {
        Gate::authorize('viewAny', Cliente::class);
        $contas = Conta::with('cliente.user')->latest()->get();
        return view('admin.contas.index', compact('contas'));
    }

    public function create(): View
    {
        Gate::authorize('create', Cliente::class);
        return view('admin.contas.create');
    }

    public function store(ClienteRequest $request): RedirectResponse
    {
        Gate::authorize('create', Cliente::class);
        $this->clienteService->create($request->validated());
        return redirect()->route('admin.contas.index')->with('success', 'Cliente criado com sucesso.');
    }

    public function show(Conta $conta): View
    {
        Gate::authorize('view', $conta->cliente);
        $conta->load('cliente.user');
        $extrato = $conta->movimentacoes()->latest()->get()->map(function ($mov) {
            $mov->natureza = in_array($mov->tipo, ['pix_recebimento', 'resgate'], true) ? 'entrada' : 'saida';
            return $mov;
        });
        return view('admin.contas.show', compact('conta', 'extrato'));
    }

    public function edit(Conta $conta): View
    {
        Gate::authorize('update', $conta->cliente);
        $conta->load('cliente.user');
        return view('admin.contas.edit', compact('conta'));
    }

    public function update(ClienteRequest $request, Conta $conta): RedirectResponse
    {
        Gate::authorize('update', $conta->cliente);
        $this->clienteService->updateCliente($conta->cliente, $request->validated());
        return redirect()->route('admin.contas.index')->with('success', 'Cliente atualizado com sucesso.');
    }

    public function destroy(Conta $conta): RedirectResponse
    {
        Gate::authorize('delete', $conta->cliente);
        $this->clienteService->delete($conta->cliente);
        return redirect()->route('admin.contas.index')->with('success', 'Cliente removido com sucesso.');
    }

    public function bloquear(Conta $conta): RedirectResponse
    {
        Gate::authorize('bloquear', $conta->cliente);
        $this->clienteService->bloquear($conta->cliente);
        return back()->with('success', 'Conta bloqueada com sucesso.');
    }

    public function desbloquear(Conta $conta): RedirectResponse
    {
        Gate::authorize('desbloquear', $conta->cliente);
        $this->clienteService->desbloquear($conta->cliente);
        return back()->with('success', 'Conta desbloqueada com sucesso.');
    }

    public function solicitarLimite(\Illuminate\Http\Request $request, Conta $conta): RedirectResponse
    {
        Gate::authorize('create', \App\Models\SolicitacaoLimite::class);
        $data = $request->validate(['limite_solicitado' => ['required', 'numeric', 'min:0.01']]);
        $this->limiteService->solicitar($conta->cliente, (float) $data['limite_solicitado']);
        return back()->with('success', 'Solicitação de aumento de limite enviada.');
    }
}
