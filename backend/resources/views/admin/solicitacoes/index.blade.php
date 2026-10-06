<x-app-layout>
    <x-slot name="header">Solicitações de Aumento de Limite</x-slot>

    <div class="topo-lista">
        <h1>Solicitações Pendentes</h1>
    </div>

    <table>
        <thead>
            <tr>
                <th>Conta</th>
                <th>Cliente</th>
                <th>Gerente Solicitante</th>
                <th>Limite Atual</th>
                <th>Limite Solicitado</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($solicitacoes as $s)
                <tr>
                    <td>{{ $s->conta->numero_conta }}</td>
                    <td>{{ $s->conta->cliente->name }}</td>
                    <td>{{ $s->gerenteConta->name }}</td>
                    <td>R$ {{ number_format($s->limite_atual, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($s->limite_solicitado, 2, ',', '.') }}</td>
                    <td class="acoes">
                        <form action="{{ route('admin.solicitacoes.aprovar', $s) }}" method="POST">
                            @csrf
                            <button type="submit" class="icone-btn btn-aprovar" title="Aprovar">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M20 6 9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </form>
                        <form action="{{ route('admin.solicitacoes.reprovar', $s) }}" method="POST">
                            @csrf
                            <button type="submit" class="icone-btn btn-reprovar" title="Reprovar">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M18 6 6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="vazio">Nenhuma solicitação pendente.</td></tr>
            @endforelse
        </tbody>
    </table>
</x-app-layout>
