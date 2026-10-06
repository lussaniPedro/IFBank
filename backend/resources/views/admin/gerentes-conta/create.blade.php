<x-app-layout>
    <x-slot name="header">Novo Gerente de Conta</x-slot>

    <div class="form-card">
        <h1>Novo Gerente de Conta</h1>

        <form method="POST" action="{{ route('admin.gerentes-conta.store') }}">
            @csrf

            <div class="campo">
                <label for="name">Nome</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus />
                @error('name')<p class="erro">{{ $message }}</p>@enderror
            </div>

            <div class="campo">
                <label for="email">E-mail</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required />
                @error('email')<p class="erro">{{ $message }}</p>@enderror
                <p class="ajuda">A senha de acesso é gerada automaticamente e enviada por e-mail (Mailtrap).</p>
            </div>

            <button type="submit" class="btn-dourado">Salvar</button>
        </form>

        <a href="{{ route('admin.gerentes-conta.index') }}" class="voltar-link">← Voltar</a>
    </div>
</x-app-layout>
