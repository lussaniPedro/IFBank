<x-app-layout>
    <x-slot name="header">Editar Conta — {{ $conta->numero_conta }}</x-slot>

    <div class="form-card">
        <h1>Editar Conta {{ $conta->numero_conta }}</h1>

        <form method="POST" action="{{ route('admin.contas.update', $conta) }}">
            @csrf
            @method('PUT')

            <div class="campo">
                <label for="name">Nome do Cliente</label>
                <input id="name" name="name" type="text" value="{{ old('name', $conta->cliente->name) }}" required />
                @error('name')<p class="erro">{{ $message }}</p>@enderror
            </div>

            <div class="campo">
                <label for="email">E-mail</label>
                <input id="email" name="email" type="email" value="{{ old('email', $conta->cliente->email) }}" required />
                @error('email')<p class="erro">{{ $message }}</p>@enderror
            </div>

            <div class="campo">
                <label for="saldo">Saldo</label>
                <input id="saldo" name="saldo" type="number" step="0.01" min="0" value="{{ old('saldo', $conta->saldo) }}" required />
                @error('saldo')<p class="erro">{{ $message }}</p>@enderror
            </div>

            <p class="ajuda">O limite só é alterado via fluxo de aprovação do Gerente Geral. Use "Solicitar aumento" na listagem de contas.</p>

            <button type="submit" class="btn-dourado">Atualizar</button>
        </form>

        <a href="{{ route('admin.contas.index') }}" class="voltar-link">← Voltar</a>
    </div>
</x-app-layout>
