<x-app-layout>
    <x-slot name="header">Extrato — Conta {{ $conta->numero_conta }}</x-slot>

    <div class="form-card" style="max-width: 500px; margin-bottom: 1.5rem;">
        <p><strong style="color: var(--dourado)">Cliente:</strong> {{ $conta->cliente->name }}</p>
        <p><strong style="color: var(--dourado)">Saldo:</strong> R$ {{ number_format($conta->saldo, 2, ',', '.') }}</p>
        <p><strong style="color: var(--dourado)">Limite:</strong> R$ {{ number_format($conta->limite, 2, ',', '.') }}</p>
        <p>
            <strong style="color: var(--dourado)">Status:</strong>
            @if ($conta->bloqueada)
                <span class="badge-status badge-bloqueada">Bloqueada</span>
            @else
                <span class="badge-status badge-ativa">Ativa</span>
            @endif
        </p>
    </div>

    <table>
        <thead>
            <tr><th>Data</th><th>Tipo</th><th>Natureza</th><th>Valor</th></tr>
        </thead>
        <tbody>
            @forelse ($extrato as $mov)
                <tr>
                    <td>{{ $mov->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ ucfirst($mov->tipo) }}</td>
                    <td>{{ $mov->natureza === 'entrada' ? 'Entrada' : 'Saída' }}</td>
                    <td>R$ {{ number_format($mov->valor, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="vazio">Nenhuma movimentação registrada.</td></tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('admin.contas.index') }}" class="voltar-link">← Voltar</a>
</x-app-layout>
