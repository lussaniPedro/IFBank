<x-app-layout>
    <x-slot name="header">Nova Conta de Cliente</x-slot>

    <div class="form-card">
        <h1>Nova Conta de Cliente</h1>

        <form method="POST" action="{{ route('admin.contas.store') }}">
            @csrf

            <div class="campo">
                <label for="name">Nome do Cliente</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus />
                @error('name')<p class="erro">{{ $message }}</p>@enderror
            </div>

            <div class="campo">
                <label for="email">E-mail</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required />
                @error('email')<p class="erro">{{ $message }}</p>@enderror
                <p class="ajuda">A senha de acesso é gerada automaticamente e enviada por e-mail (Mailtrap).</p>
            </div>

            <div class="campo">
                <label for="saldo">Saldo Inicial</label>
                <input id="saldo" name="saldo" type="number" step="0.01" min="0" value="{{ old('saldo', 0) }}" />
                @error('saldo')<p class="erro">{{ $message }}</p>@enderror
            </div>

            <div class="campo">
                <label for="limite">Limite Inicial</label>
                <input id="limite" name="limite" type="number" step="0.01" min="0" value="{{ old('limite', 0) }}" />
                @error('limite')<p class="erro">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="btn-dourado">Salvar</button>
        </form>

        <a href="{{ route('admin.contas.index') }}" class="voltar-link">← Voltar</a>
    </div>
</x-app-layout>
