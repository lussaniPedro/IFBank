<?php

namespace App\Http\Controllers;

use App\Http\Requests\GerenteRequest;
use App\Models\User;
use App\Services\GerenteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class GerenteController extends Controller {
    public function __construct(protected GerenteService $service){}

    public function index(): View {
        Gate::authorize('viewAny', User::class);

        $gerentes = $this->service->listGerentes();

        return view('admin.gerentes-conta.index', compact('gerentes'));
    }

    public function create(): View {
        Gate::authorize('create', User::class);

        return view('admin.gerentes-conta.create');
    }

    public function store(GerenteRequest $request): RedirectResponse {
        Gate::authorize('create', User::class);

        $this->service->create($request->validated());

        return redirect()
            ->route('gerentes.index')
            ->with('success', 'Gerente de conta criado e credenciais enviadas por e-mail.');
    }

    public function edit(User $gerente): View {
        Gate::authorize('update', $gerente);

        return view('admin.gerentes-conta.edit', compact('gerente'));
    }

    public function update(GerenteRequest $request, User $gerente): RedirectResponse {
        Gate::authorize('update', $gerente);

        $this->service->updateGerente(
            $gerente,
            $request->validated()
        );

        return redirect()
            ->route('gerentes.index')
            ->with('success', 'Gerente atualizado com sucesso.');
    }

    public function destroy(User $gerente): RedirectResponse {
        Gate::authorize('delete', $gerente);

        $this->service->deleteGerente($gerente);

        return redirect()
            ->route('gerentes.index')
            ->with('success', 'Gerente removido com sucesso.');
    }
}