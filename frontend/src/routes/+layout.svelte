<script>
  import '../app.css';
  import { goto } from '$app/navigation';
  import { page } from '$app/stores';
  import { token, usuario, limparSessao } from '$lib/stores/auth.js';
  import { api } from '$lib/api.js';

  let { children } = $props();
  let mostrarConfirmacaoLogout = $state(false);

  // Protege todas as rotas exceto /login: sem token, volta para o login.
  $effect(() => {
    if (!$token && $page.url.pathname !== '/login') {
      goto('/login');
    }
  });

  function abrirConfirmacaoLogout() {
    mostrarConfirmacaoLogout = true;
  }

  async function confirmarLogout() {
    try {
      await api.logout();
    } finally {
      limparSessao();
      mostrarConfirmacaoLogout = false;
      goto('/login');
    }
  }

  function ativo(caminho) {
    return $page.url.pathname === caminho ? 'ativo' : '';
  }
</script>

{#if $token}
  <nav class="navbar">
    <a href="/" class="marca">
      <span class="badge"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M3 21h18M4 21V9l8-6 8 6v12M9 21v-6h6v6" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
      IFBANK
    </a>

    <div class="nav-links">
      {#if $usuario?.role?.name === 'GERENTE_CONTA'}
        <a href="/" class={ativo('/')}>Clientes</a>
        <a href="/clientes" class={ativo('/clientes')}>Gerenciar clientes</a>
      {:else if $usuario?.role?.name === 'GERENTE_GERAL'}
        <a href="/" class={ativo('/')}>Gerentes</a>
        <a href="/gerentes" class={ativo('/gerentes')}>Gerenciar gerentes</a>
        <a href="/solicitacoes-limite" class={ativo('/solicitacoes-limite')}>Solicitações</a>
        <a href="/auditoria" class={ativo('/auditoria')}>Auditoria</a>
      {:else}
        <a href="/" class={ativo('/')}>
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" stroke-linecap="round" stroke-linejoin="round"/></svg>
          Saldo
        </a>
        <a href="/extrato" class={ativo('/extrato')}>Extrato</a>
        <a href="/movimentacoes" class={ativo('/movimentacoes')}>Movimentações</a>
      {/if}
    </div>

    <button class="perfil-btn" type="button" onclick={abrirConfirmacaoLogout}>
      <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v1" stroke-linecap="round"/></svg>
      {$usuario?.name}
    </button>
  </nav>
{/if}

{#if mostrarConfirmacaoLogout}
  <div
    class="modal-backdrop"
    role="button"
    tabindex="0"
    aria-label="Fechar confirmação de logout"
    onclick={() => (mostrarConfirmacaoLogout = false)}
    onkeydown={(event) => {
      if (event.key === 'Escape' || event.key === 'Enter' || event.key === ' ') {
        mostrarConfirmacaoLogout = false;
      }
    }}
  >
    <div
      class="modal-confirmacao"
      role="dialog"
      aria-modal="true"
      tabindex="0"
      onclick={(event) => event.stopPropagation()}
      onkeydown={(event) => event.stopPropagation()}
    >
      <h3>Confirmar logout</h3>
      <p>Deseja realmente sair da sua conta?</p>
      <div class="modal-actions">
        <button type="button" class="btn-secondary" onclick={() => (mostrarConfirmacaoLogout = false)}>Cancelar</button>
        <button type="button" class="btn-dourado" onclick={confirmarLogout}>Sair</button>
      </div>
    </div>
  </div>
{/if}

<main>
  {@render children()}
</main>

<style>
  .modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(3, 8, 12, 0.7);
    display: grid;
    place-items: center;
    z-index: 50;
    padding: 1rem;
  }

  .modal-confirmacao {
    width: min(420px, 100%);
    background: linear-gradient(180deg, rgba(15, 31, 45, 0.98), rgba(10, 20, 29, 0.98));
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 18px;
    padding: 1.5rem;
    box-shadow: 0 24px 50px rgba(0, 0, 0, 0.35);
  }

  .modal-confirmacao h3 {
    margin: 0 0 0.6rem;
    color: #f5f7fb;
    font-size: 1.35rem;
  }

  .modal-confirmacao p {
    margin: 0 0 1.25rem;
    color: rgba(234, 243, 255, 0.8);
  }

  .modal-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
  }

  .btn-secondary {
    padding: 0.75rem 1rem;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.04);
    color: #edf5ff;
    font-weight: 700;
    cursor: pointer;
  }
</style>
