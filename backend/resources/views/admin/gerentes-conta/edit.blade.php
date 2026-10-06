<x-app-layout>
    <x-slot name="header">Editar Gerente de Conta</x-slot>

    <div class="form-card">
        <h1>Editar Gerente de Conta</h1>

        <form method="POST" action="{{ route('admin.gerentes-conta.update', $gerente) }}">
            @csrf
            @method('PUT')

            <div class="campo">
                <label for="name">Nome</label>
                <input id="name" name="name" type="text" value="{{ old('name', $gerente->name) }}" required autofocus />
                @error('name')<p class="erro">{{ $message }}</p>@enderror
            </div>

            <div class="campo">
                <label for="email">E-mail</label>
                <input id="email" name="email" type="email" value="{{ old('email', $gerente->email) }}" required />
                @error('email')<p class="erro">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="btn-dourado">Atualizar</button>
        </form>

        <a href="{{ route('admin.gerentes-conta.index') }}" class="voltar-link">← Voltar</a>
    </div>
</x-app-layout>
