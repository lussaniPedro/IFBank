<x-app-layout>
    <x-slot name="header">Logs de Auditoria</x-slot>

    <div class="topo-lista">
        <h1>Histórico de Ações dos Gerentes de Conta</h1>
    </div>

    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Usuário</th>
                <th>Evento</th>
                <th>Entidade</th>
                <th>Alterações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $log->user?->name ?? '—' }}</td>
                    <td>{{ ucfirst($log->event) }}</td>
                    <td>{{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}</td>
                    <td><pre>{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre></td>
                </tr>
            @empty
                <tr><td colspan="5" class="vazio">Nenhum log registrado ainda.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="paginacao">{{ $logs->links() }}</div>
</x-app-layout>
