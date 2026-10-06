<x-app-layout>
    <x-slot name="header">Gerentes de Conta</x-slot>

    <div class="topo-lista">
        <h1>Lista de Gerentes de Conta</h1>
        <a href="{{ route('admin.gerentes-conta.create') }}" class="btn-novo" title="Novo gerente">+</a>
    </div>

    <table>
        <thead>
            <tr><th>Nome</th><th>E-mail</th><th>Ações</th></tr>
        </thead>
        <tbody>
            @forelse ($gerentes as $gerente)
                <tr>
                    <td>{{ $gerente->name }}</td>
                    <td>{{ $gerente->email }}</td>
                    <td class="acoes">
                        <a href="{{ route('admin.gerentes-conta.edit', $gerente) }}" class="icone-btn btn-editar" title="Editar">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                        <form action="{{ route('admin.gerentes-conta.destroy', $gerente) }}" method="POST" onsubmit="return confirm('Remover este gerente?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="icone-btn btn-remover" title="Remover">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="vazio">Nenhum gerente de conta cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
</x-app-layout>
