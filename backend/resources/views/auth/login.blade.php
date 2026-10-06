<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>IFBANK - Entrar</title>
    <style>
        :root {
            --preto: #0d0d0d;
            --preto-2: #1a1a1a;
            --dourado: #FFC000;
            --texto: #f5f5f5;
            --texto-fraco: #9a9a9a;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: var(--preto);
            color: var(--texto);
        }
        .wrap { display: flex; min-height: 100vh; }
        .marca {
            flex: 1;
            background: linear-gradient(160deg, var(--preto) 0%, #1f1a00 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
            border-right: 1px solid #2a2a2a;
        }
        .marca h1 { color: var(--dourado); font-size: 2.4rem; margin: 0 0 0.5rem; letter-spacing: 1px; }
        .marca p { color: var(--texto-fraco); max-width: 320px; }
        .form-col { flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .card { width: 100%; max-width: 360px; background: var(--preto-2); border: 1px solid #2a2a2a; border-radius: 14px; padding: 2rem; }
        .card h2 { margin-top: 0; }
        .card label { display: block; font-size: 0.8rem; color: var(--texto-fraco); margin: 1rem 0 0.3rem; }
        .card input[type="email"], .card input[type="password"] {
            width: 100%;
            padding: 0.65rem 0.8rem;
            border-radius: 8px;
            border: 1px solid #333;
            background: #111;
            color: var(--texto);
        }
        .card input:focus { outline: none; border-color: var(--dourado); }
        .lembrar { display: flex; align-items: center; gap: 0.5rem; margin-top: 1rem; font-size: 0.8rem; color: var(--texto-fraco); }
        .btn-dourado {
            margin-top: 1.5rem;
            width: 100%;
            padding: 0.75rem;
            border: none;
            border-radius: 8px;
            background: var(--dourado);
            color: var(--preto);
            font-weight: 700;
            cursor: pointer;
        }
        .rodape-link { display: block; text-align: center; margin-top: 1rem; font-size: 0.8rem; }
        .rodape-link a { color: var(--texto-fraco); text-decoration: none; }
        .rodape-link a:hover { color: var(--dourado); }
        .erro { color: #ff6b6b; font-size: 0.8rem; margin-top: 0.3rem; }
        .status { background: #1f2a1a; color: #8fd18f; padding: 0.6rem 0.9rem; border-radius: 8px; font-size: 0.85rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="marca">
            <h1>IFBANK</h1>
            <p>Painel administrativo da agência. Acesso restrito a gerentes gerais e gerentes de conta.</p>
        </div>
        <div class="form-col">
            <div class="card">
                <h2>Entrar</h2>

                @session('status')
                    <div class="status">{{ $value }}</div>
                @endsession

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <label for="email">E-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                    @error('email')<p class="erro">{{ $message }}</p>@enderror

                    <label for="password">Senha</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" />
                    @error('password')<p class="erro">{{ $message }}</p>@enderror

                    <label class="lembrar">
                        <input type="checkbox" name="remember" style="width:auto;">
                        Lembrar de mim
                    </label>

                    <button type="submit" class="btn-dourado">Entrar</button>
                </form>

                @if (Route::has('password.request'))
                    <span class="rodape-link"><a href="{{ route('password.request') }}">Esqueceu a senha?</a></span>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
