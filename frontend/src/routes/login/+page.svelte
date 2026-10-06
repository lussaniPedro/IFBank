<script>
  import { goto } from '$app/navigation';
  import { token, usuario } from '$lib/stores/auth.js';
  import { api } from '$lib/api.js';

  let modo = $state('login');
  let nome = $state('');
  let email = $state('');
  let password = $state('');
  let confirmacao = $state('');
  let erro = $state('');
  let mensagem = $state('');
  let carregando = $state(false);

  function trocarModo(next) {
    modo = next;
    erro = '';
    mensagem = '';
  }

  async function entrar(e) {
    e.preventDefault();
    erro = '';
    mensagem = '';
    carregando = true;

    try {
      const dados = await api.login(email, password);
      token.set(dados.token);
      usuario.set(dados.user);
      goto('/');
    } catch (e) {
      erro = e.message;
    } finally {
      carregando = false;
    }
  }

  async function cadastrar(e) {
    e.preventDefault();
    erro = '';
    mensagem = '';

    if (password !== confirmacao) {
      erro = 'As senhas não conferem.';
      return;
    }

    carregando = true;

    try {
      const dados = await api.register(nome, email, password, confirmacao);
      mensagem = dados?.message || 'Conta criada com sucesso.';
      modo = 'login';
      nome = '';
      password = '';
      confirmacao = '';
    } catch (e) {
      erro = e.message;
    } finally {
      carregando = false;
    }
  }
</script>

<div class="auth-shell">
  <div class="auth-card">
    <div class="brand-panel">
      <div class="brand-mark">IF</div>
      <div class="brand-copy">
        <span class="eyebrow">Banco digital</span>
        <h1>IFBANK</h1>
      </div>
      <p>Seu banco com segurança, controle e praticidade em um só lugar.</p>
      <ul>
        <li>Conta digital com saldo em tempo real</li>
        <li>PIX e movimentações sem complicação</li>
        <li>Acesso rápido e moderno</li>
      </ul>
    </div>

    <div class="form-panel">
      <div class="tab-toggle">
        <button type="button" class:active={modo === 'login'} onclick={() => trocarModo('login')}>Entrar</button>
        <button type="button" class:active={modo === 'register'} onclick={() => trocarModo('register')}>Criar conta</button>
      </div>

      {#if modo === 'login'}
        <div class="form-header">
          <h2>Bem-vindo de volta</h2>
          <p>Digite seus dados para continuar.</p>
        </div>

        <form onsubmit={entrar}>
          <label>
            E-mail
            <input type="email" bind:value={email} required autocomplete="username" placeholder="nome@exemplo.com" />
          </label>

          <label>
            Senha
            <input type="password" bind:value={password} required autocomplete="current-password" placeholder="••••••••" />
          </label>

          {#if erro}<p class="erro">{erro}</p>{/if}

          <button type="submit" class="primary" disabled={carregando}>
            {carregando ? 'Entrando...' : 'Entrar'}
          </button>
        </form>
      {:else}
        <div class="form-header">
          <h2>Abra sua conta</h2>
          <p>Cadastre-se e comece hoje mesmo.</p>
        </div>

        <form onsubmit={cadastrar}>
          <label>
            Nome
            <input type="text" bind:value={nome} required autocomplete="name" placeholder="Seu nome completo" />
          </label>

          <label>
            E-mail
            <input type="email" bind:value={email} required autocomplete="username" placeholder="nome@exemplo.com" />
          </label>

          <label>
            Senha
            <input type="password" bind:value={password} required autocomplete="new-password" placeholder="Mínimo 8 caracteres" />
          </label>

          <label>
            Confirmar senha
            <input type="password" bind:value={confirmacao} required autocomplete="new-password" placeholder="Repita sua senha" />
          </label>

          {#if erro}<p class="erro">{erro}</p>{/if}
          {#if mensagem}<p class="sucesso">{mensagem}</p>{/if}

          <button type="submit" class="primary" disabled={carregando}>
            {carregando ? 'Cadastrando...' : 'Cadastrar'}
          </button>
        </form>
      {/if}
    </div>
  </div>
</div>

<style>
  :global(body) {
    margin: 0;
    background: #06131d;
  }

  .auth-shell {
    min-height: 100vh;
    display: grid;
    place-items: center;
    padding: 2rem;
    background:
      radial-gradient(circle at top, rgba(248, 200, 76, 0.18), transparent 30%),
      linear-gradient(135deg, #071722 0%, #0d1d2c 100%);
  }

  .auth-card {
    width: min(980px, 100%);
    display: grid;
    grid-template-columns: 1.1fr 1fr;
    border-radius: 28px;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.08);
    background: rgba(12, 23, 32, 0.9);
    box-shadow: 0 24px 60px rgba(7, 12, 18, 0.45);
  }

  .brand-panel {
    padding: 2.5rem 2rem;
    background: linear-gradient(180deg, rgba(248, 200, 76, 0.12), rgba(12, 23, 32, 0.92));
    border-right: 1px solid rgba(255, 255, 255, 0.06);
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .brand-mark {
    width: 68px;
    height: 68px;
    border-radius: 20px;
    display: grid;
    place-items: center;
    font-size: 1.8rem;
    font-weight: 900;
    background: linear-gradient(135deg, #f8c84d, #f0a913);
    color: #0b1722;
    box-shadow: 0 18px 28px rgba(248, 200, 76, 0.22);
  }

  .brand-copy {
    margin-top: 1.25rem;
  }

  .eyebrow {
    display: inline-block;
    font-size: 0.7rem;
    letter-spacing: 0.14em;
    color: rgba(234, 243, 255, 0.8);
    text-transform: uppercase;
    margin-bottom: 0.5rem;
  }

  h1 {
    margin: 0;
    font-size: clamp(2.1rem, 4vw, 3rem);
    color: #f5f7fb;
  }

  .brand-panel p {
    margin: 1.2rem 0 1rem;
    line-height: 1.6;
    color: rgba(234, 243, 255, 0.8);
  }

  .brand-panel ul {
    margin: 0;
    padding-left: 1.2rem;
    color: rgba(234, 243, 255, 0.78);
    line-height: 1.8;
  }

  .form-panel {
    padding: 2.2rem 1.8rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .tab-toggle {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 14px;
    padding: 0.35rem;
    margin-bottom: 1.5rem;
  }

  .tab-toggle button {
    border: none;
    border-radius: 10px;
    padding: 0.75rem 0.9rem;
    background: transparent;
    color: rgba(234, 243, 255, 0.7);
    cursor: pointer;
    font-weight: 700;
  }

  .tab-toggle button.active {
    background: linear-gradient(135deg, #f8c84d, #f0a913);
    color: #0a1723;
    box-shadow: 0 8px 20px rgba(248, 200, 76, 0.18);
  }

  .form-header h2 {
    margin: 0;
    color: #f5f7fb;
    font-size: 1.9rem;
  }

  .form-header p {
    margin: 0.5rem 0 1.25rem;
    color: rgba(234, 243, 255, 0.7);
  }

  form {
    display: flex;
    flex-direction: column;
    gap: 0.9rem;
  }

  label {
    display: flex;
    flex-direction: column;
    gap: 0.45rem;
    color: rgba(234, 243, 255, 0.8);
    font-size: 0.82rem;
  }

  input {
    width: 100%;
    border: 1px solid rgba(255, 255, 255, 0.08);
    background: rgba(8, 18, 26, 0.9);
    color: #edf5ff;
    border-radius: 12px;
    padding: 0.8rem 0.9rem;
    font-size: 0.98rem;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }

  input::placeholder {
    color: rgba(234, 243, 255, 0.35);
  }

  input:focus {
    outline: none;
    border-color: rgba(248, 200, 76, 0.85);
    box-shadow: 0 0 0 4px rgba(248, 200, 76, 0.08);
  }

  .primary {
    margin-top: 0.4rem;
    border: none;
    border-radius: 12px;
    padding: 0.9rem 1rem;
    background: linear-gradient(135deg, #f8c84d, #f0a913);
    color: #0a1723;
    font-weight: 800;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    box-shadow: 0 12px 22px rgba(248, 200, 76, 0.2);
  }

  .primary:hover {
    transform: translateY(-1px);
  }

  .primary:disabled {
    opacity: 0.7;
    cursor: default;
    transform: none;
  }

  .erro {
    color: #fca5a5;
    margin: 0;
    font-size: 0.82rem;
  }

  .sucesso {
    color: #86efac;
    margin: 0;
    font-size: 0.82rem;
  }

  @media (max-width: 760px) {
    .auth-card {
      grid-template-columns: 1fr;
    }

    .brand-panel {
      border-right: none;
      border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }
  }
</style>
