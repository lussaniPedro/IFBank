<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>IFBANK</title>
    <style>
        :root {
            --preto: #0d0d0d;
            --preto-2: #1a1a1a;
            --preto-3: #242424;
            --dourado: #FFC000;
            --dourado-escuro: #b8860b;
            --texto-fraco: #8a8a8a;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Segoe UI', system-ui, sans-serif; background: var(--preto); color: var(--dourado); min-height: 100vh; }
        a { color: inherit; }
        svg { width: 18px; height: 18px; }

        /* ---- Navbar ---- */
        .navbar { display: flex; align-items: center; gap: 2rem; padding: 0 1.5rem; height: 64px; background: var(--preto-2); border-bottom: 3px solid var(--dourado); }
        .marca { display: flex; align-items: center; gap: 0.6rem; color: var(--dourado); font-weight: 800; font-size: 1.15rem; letter-spacing: 0.5px; text-decoration: none; }
        .marca .badge { width: 34px; height: 34px; border-radius: 50%; background: var(--dourado); display: flex; align-items: center; justify-content: center; }
        .marca .badge svg { stroke: var(--preto); }

        .nav-links { display: flex; gap: 0.3rem; flex: 1; }
        .nav-links a { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: var(--texto-fraco); padding: 0.55rem 1rem; border-radius: 8px; font-size: 0.88rem; }
        .nav-links a svg { stroke: var(--texto-fraco); }
        .nav-links a.ativo { color: var(--preto); background: var(--dourado); font-weight: 600; }
        .nav-links a.ativo svg { stroke: var(--preto); }
        .nav-links a:hover:not(.ativo) { color: var(--dourado); background: var(--preto-3); }

        .perfil { position: relative; }
        .perfil-btn { display: flex; align-items: center; gap: 0.5rem; background: var(--preto-3); border: 1px solid #2a2a2a; color: var(--dourado); padding: 0.4rem 0.9rem; border-radius: 999px; font-size: 0.85rem; cursor: pointer; }
        .perfil-btn svg { stroke: var(--dourado); }
        .perfil-menu { display: none; position: absolute; right: 0; top: 120%; background: var(--preto-3); border: 1px solid #2a2a2a; border-radius: 10px; min-width: 160px; overflow: hidden; z-index: 10; }
        .perfil:hover .perfil-menu, .perfil:focus-within .perfil-menu { display: block; }
        .perfil-menu a, .perfil-menu button { display: block; width: 100%; text-align: left; padding: 0.65rem 0.9rem; background: none; border: none; color: var(--dourado); font-size: 0.85rem; cursor: pointer; text-decoration: none; }
        .perfil-menu a:hover, .perfil-menu button:hover { background: var(--preto-2); }

        /* ---- Cabeçalho / conteúdo ---- */
        .cabecalho { padding: 1.5rem 2rem 0; max-width: 1100px; margin: 0 auto; }
        .cabecalho h1, .cabecalho h2 { font-size: 1.25rem; margin: 0; color: var(--dourado); font-weight: 600; }
        .conteudo { padding: 1.5rem 2rem 3rem; max-width: 1100px; margin: 0 auto; }
        .status { background: var(--preto-2); border: 1px solid var(--dourado); color: var(--dourado); padding: 0.65rem 1rem; border-radius: 8px; font-size: 0.85rem; margin-bottom: 1.2rem; }

        /* ---- Listagens (gerentes, contas, solicitações, auditoria) ---- */
        .topo-lista { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .topo-lista h1, .topo-lista h2 { margin: 0; color: var(--dourado); font-size: 1.25rem; }
        .btn-novo { width: 38px; height: 38px; border-radius: 10px; border: none; background: var(--dourado); color: var(--preto); font-size: 1.3rem; cursor: pointer; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }

        table { width: 100%; border-collapse: collapse; background: var(--preto-2); border-radius: 10px; overflow: hidden; border: 1px solid #2a2a2a; }
        thead th { text-align: left; font-size: 0.72rem; letter-spacing: 0.04em; text-transform: uppercase; color: var(--dourado-escuro); padding: 0.9rem 1rem; border-bottom: 1px solid #2a2a2a; }
        tbody td { padding: 0.9rem 1rem; font-size: 0.9rem; border-bottom: 1px solid #262626; color: var(--dourado); vertical-align: top; }
        tbody tr:nth-child(even) { background: var(--preto-3); }
        tbody tr:hover { background: #2a2410; }

        .acoes { display: flex; gap: 0.4rem; }
        .icone-btn { width: 30px; height: 30px; border-radius: 50%; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; }
        .icone-btn svg { width: 15px; height: 15px; stroke: var(--preto); }
        .btn-editar, .btn-aprovar { background: var(--dourado); }
        .btn-remover, .btn-reprovar { background: var(--dourado-escuro); }

        .campo-inline { display: inline-flex; align-items: center; gap: 0.4rem; }
        .campo-inline input { width: 90px; padding: 0.35rem 0.5rem; border-radius: 6px; border: 1px solid #333; background: #111; color: var(--dourado); font-size: 0.8rem; }

        .vazio { padding: 1.4rem; text-align: center; color: var(--texto-fraco); }
        pre { white-space: pre-wrap; font-size: 0.75rem; color: var(--texto-fraco); margin: 0; }
        .paginacao { margin-top: 1rem; }
        .paginacao a, .paginacao span { color: var(--texto-fraco); font-size: 0.85rem; margin-right: 0.5rem; }

        /* ---- Formulários (criar/editar) ---- */
        .form-card { max-width: 420px; background: var(--preto-2); border: 1px solid #2a2a2a; border-radius: 14px; padding: 2rem; }
        .form-card h1, .form-card h2 { margin-top: 0; color: var(--dourado); font-size: 1.15rem; }
        .campo { margin-bottom: 1rem; }
        .campo label { display: block; font-size: 0.8rem; color: var(--texto-fraco); margin-bottom: 0.35rem; }
        .campo input, .campo select {
            width: 100%; padding: 0.65rem 0.8rem; border-radius: 8px;
            border: 1px solid #333; background: #111; color: var(--dourado); font-size: 0.9rem;
        }
        .campo input:focus, .campo select:focus { outline: none; border-color: var(--dourado); }
        .campo .ajuda { font-size: 0.75rem; color: var(--texto-fraco); margin-top: 0.35rem; }
        .campo .erro { font-size: 0.78rem; color: #e0a83a; margin-top: 0.3rem; }
        .campo .info-fixa { color: var(--dourado); font-size: 0.9rem; }
        .btn-dourado {
            margin-top: 0.5rem; width: 100%; padding: 0.75rem; border: none; border-radius: 8px;
            background: var(--dourado); color: var(--preto); font-weight: 700; cursor: pointer; font-size: 0.9rem;
        }
        .voltar-link { display: inline-block; margin-top: 1rem; color: var(--texto-fraco); text-decoration: none; font-size: 0.85rem; }
        .voltar-link:hover { color: var(--dourado); }

        /* ---- Cards do dashboard ---- */
        .cards-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
        .card-dash { background: var(--preto-2); border: 1px solid #2a2a2a; border-radius: 14px; padding: 1.2rem 1.4rem; text-decoration: none; display: block; }
        .card-dash .label { font-size: 0.78rem; color: var(--texto-fraco); }
        .card-dash .num { font-size: 2rem; font-weight: 800; margin: 0.2rem 0; color: var(--dourado); }
        .card-dash .link { font-size: 0.72rem; color: var(--dourado-escuro); }
        .card-dash:hover { border-color: var(--dourado); }
        .bemvindo { background: var(--preto-2); border: 1px solid #2a2a2a; border-radius: 14px; padding: 1.4rem 1.6rem; }
        .bemvindo h3 { margin: 0 0 0.4rem; color: var(--dourado); }
        .bemvindo p { color: var(--texto-fraco); font-size: 0.88rem; margin: 0; }
        @media (max-width: 720px) { .cards-grid { grid-template-columns: 1fr; } }

        /* ---- Badges de status ---- */
        .badge-status { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }
        .badge-ativa { background: rgba(255,192,0,0.15); color: var(--dourado); }
        .badge-bloqueada { background: rgba(184,134,11,0.2); color: var(--dourado-escuro); }

        .acoes-col { display: flex; gap: 0.4rem; align-items: center; flex-wrap: wrap; }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('dashboard') }}" class="marca">
            <span class="badge"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M3 21h18M4 21V9l8-6 8 6v12M9 21v-6h6v6" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
            IFBANK
        </a>

        <div class="nav-links">
            @if (auth()->user()->isGerenteGeral())
                <a href="{{ route('admin.gerentes-conta.index') }}" class="{{ request()->routeIs('admin.gerentes-conta.*') ? 'ativo' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Gerentes
                </a>
                <a href="{{ route('admin.solicitacoes.index') }}" class="{{ request()->routeIs('admin.solicitacoes.*') ? 'ativo' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6Z M14 2v6h6M9 15l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Solicitações
                </a>
                <a href="{{ route('admin.auditoria.index') }}" class="{{ request()->routeIs('admin.auditoria.*') ? 'ativo' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M9 17V9M13 17v-4M17 17V5M4 21h16" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Logs
                </a>
            @elseif (auth()->user()->isGerenteConta())
                <a href="{{ route('admin.contas.index') }}" class="{{ request()->routeIs('admin.contas.*') ? 'ativo' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M3 10h18M7 15h.01M11 15h2M5 21h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2Z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Contas
                </a>
            @endif
        </div>

        <div class="perfil" tabindex="0">
            <button class="perfil-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v1" stroke-linecap="round"/></svg>
                {{ auth()->user()->name }} ▾
            </button>
            <div class="perfil-menu">
                <a href="{{ route('profile.edit') }}">Perfil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Sair</button>
                </form>
            </div>
        </div>
    </nav>

    @isset($header)
        <div class="cabecalho"><h1>{{ $header }}</h1></div>
    @endisset

    <div class="conteudo">
        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        {{ $slot }}
    </div>
</body>
</html>
