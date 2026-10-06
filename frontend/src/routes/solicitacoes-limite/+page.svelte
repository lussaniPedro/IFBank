<script>
  import { onMount } from 'svelte';
  import { api } from '$lib/api.js';

  let solicitacoes = $state([]);
  let erro = $state('');
  let carregando = $state(true);
  let processando = $state(null);

  async function carregar() {
    carregando = true;
    erro = '';
    try { solicitacoes = await api.solicitacoes.list(); }
    catch (e) { erro = e.message; }
    finally { carregando = false; }
  }

  async function processar(id, acao) {
    processando = id;
    erro = '';
    try {
      if (acao === 'aprovar') await api.solicitacoes.aprovar(id);
      else await api.solicitacoes.reprovar(id);
      await carregar();
    } catch (e) { erro = e.message; }
    finally { processando = null; }
  }

  onMount(carregar);
</script>

<div class="pagina admin-page">
  <div class="page-head">
    <div>
      <span class="eyebrow">GERENTE GERAL</span>
      <h1>Solicitações de limite</h1>
      <p>Aprove ou reprove os pedidos de aumento de limite feitos pelos gerentes de conta.</p>
    </div>
  </div>

  {#if erro}<div class="alert-error">{erro}</div>{/if}

  {#if carregando}
    <div class="card-dash">Carregando solicitações...</div>
  {:else}
    <div class="table-wrap">
      <table>
        <thead><tr><th>Cliente</th><th>Conta</th><th>Gerente</th><th>Limite atual</th><th>Solicitado</th><th>Status</th><th>Ações</th></tr></thead>
        <tbody>
          {#each solicitacoes as s}
            <tr>
              <td><strong>{s.conta?.cliente?.user?.name ?? '—'}</strong><small>{s.conta?.cliente?.user?.email ?? ''}</small></td>
              <td>{s.conta?.numero_conta ?? '—'}</td>
              <td>{s.gerente?.name ?? '—'}</td>
              <td>R$ {Number(s.limite_atual ?? 0).toFixed(2)}</td>
              <td>R$ {Number(s.limite_solicitado ?? 0).toFixed(2)}</td>
              <td>{s.status}</td>
              <td class="actions">
                {#if s.status === 'pendente'}
                  <button class="btn-primary" onclick={() => processar(s.id, 'aprovar')} disabled={processando === s.id}>Aprovar</button>
                  <button class="btn-danger" onclick={() => processar(s.id, 'reprovar')} disabled={processando === s.id}>Reprovar</button>
                {:else}<span>Processada</span>{/if}
              </td>
            </tr>
          {:else}
            <tr><td colspan="7">Nenhuma solicitação encontrada.</td></tr>
          {/each}
        </tbody>
      </table>
    </div>
  {/if}
</div>
