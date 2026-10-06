<x-app-layout>
    <x-slot name="header">Contas de Clientes</x-slot>

    <div class="topo-lista">
        <h1>Minhas Contas de Clientes</h1>
        <a href="{{ route('admin.contas.create') }}" class="btn-novo" title="Nova conta">+</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Conta</th><th>Cliente</th><th>Saldo</th><th>Limite</th><th>Status</th><th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($contas as $conta)
                <tr>
                    <td>{{ $conta->numero_conta }}</td>
                    <td>{{ $conta->cliente->name }}</td>
                    <td>R$ {{ number_format($conta->saldo, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($conta->limite, 2, ',', '.') }}</td>
                    <td>
                        @if ($conta->bloqueada)
                            <span class="badge-status badge-bloqueada">Bloqueada</span>
                        @else
                            <span class="badge-status badge-ativa">Ativa</span>
                        @endif
                    </td>
                    <td class="acoes-col">
                        <a href="{{ route('admin.contas.show', $conta) }}" class="icone-btn btn-editar" title="Ver extrato">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <a href="{{ route('admin.contas.edit', $conta) }}" class="icone-btn btn-editar" title="Editar">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                        <form action="{{ route('admin.contas.destroy', $conta) }}" method="POST" onsubmit="return confirm('Remover esta conta e todo o seu histórico?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="icone-btn btn-remover" title="Remover conta">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </form>
                        <form action="{{ route($conta->bloqueada ? 'admin.contas.desbloquear' : 'admin.contas.bloquear', $conta) }}" method="POST">
                            @csrf
                            <button type="submit" class="icone-btn btn-remover" title="{{ $conta->bloqueada ? 'Desbloquear' : 'Bloquear (fraude)' }}">
                                @if ($conta->bloqueada)
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V8a5 5 0 0 1 9.5-2.2" stroke-linecap="round"/></svg>
                                @else
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4" stroke-linecap="round"/></svg>
                                @endif
                            </button>
                        </form>
                        <form action="{{ route('admin.contas.solicitar-limite', $conta) }}" method="POST" class="campo-inline">
                            @csrf
                            <input type="number" name="limite_solicitado" step="0.01" min="0.01" placeholder="Novo limite" required>
                            <button type="submit" class="icone-btn btn-aprovar" title="Solicitar aumento de limite">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M12 19V5M5 12l7-7 7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="vazio">Nenhuma conta cadastrada ainda.</td></tr>
            @endforelse
        </tbody>
    </table>
</x-app-layout>
