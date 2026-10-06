<script>
  import { onMount } from 'svelte';
  import { goto } from '$app/navigation';
  import { api } from '$lib/api.js';

  let clientes = $state([]);
  let erro = $state('');
  let filtro = $state('');
  let carregando = $state(true);

  async function carregar() {
    try {
      erro = '';
      carregando = true;
      clientes = await api.clientes.list();
    } catch (e) {
      erro = e.message;
    } finally {
      carregando = false;
    }
  }

  async function alternar(c) {
    try {
      erro = '';
      if (c.conta?.status === 'bloqueada') await api.clientes.desbloquear(c.id);
      else await api.clientes.bloquear(c.id);
      await carregar();
    } catch (e) {
      erro = e.message;
    }
  }

  async function remover(id) {
    if (!confirm('Remover este cliente?')) return;
    try {
      erro = '';
      await api.clientes.remove(id);
      await carregar();
    } catch (e) {
      erro = e.message;
    }
  }

  let filtrados = $derived(
    clientes.filter((c) =>
      `${c.user?.name ?? ''} ${c.user?.email ?? ''} ${c.conta?.numero_conta ?? ''}`
        .toLowerCase()
        .includes(filtro.toLowerCase())
    )
  );

  onMount(carregar);
</script>

<div class="pagina admin-page">
  <div class="page-head">
    <div><span class="eyebrow">GERENTE DE CONTA</span><h1>Clientes</h1><p>Gerencie contas, bloqueios, extratos e limites.</p></div>
    <button class="btn-primary" onclick={() => goto('/clientes/novo')}>+ Novo cliente</button>
  </div>

  {#if erro}<div class="alert-error">{erro}</div>{/if}
  <input class="search" bind:value={filtro} placeholder="Buscar por nome, e-mail ou conta..." />

  {#if carregando}
    <div class="card-dash">Carregando clientes...</div>
  {:else}
    <div class="table-wrap">
      <table>
        <thead><tr><th>Cliente</th><th>Conta</th><th>Saldo</th><th>Status</th><th>Ações</th></tr></thead>
        <tbody>
          {#each filtrados as c}
            <tr>
              <td><strong>{c.user?.name}</strong><small>{c.user?.email}</small></td>
              <td>{c.conta?.numero_conta ?? '-'}</td>
              <td>R$ {Number(c.conta?.saldo ?? 0).toFixed(2)}</td>
              <td>{c.conta?.status ?? 'sem conta'}</td>
              <td class="actions">
                <button onclick={() => goto(`/clientes/${c.id}`)}>Detalhes</button>
                <button onclick={() => alternar(c)}>{c.conta?.status === 'bloqueada' ? 'Desbloquear' : 'Bloquear'}</button>
                <button class="btn-danger" onclick={() => remover(c.id)}>Excluir</button>
              </td>
            </tr>
          {:else}
            <tr><td colspan="5">Nenhum cliente encontrado.</td></tr>
          {/each}
        </tbody>
      </table>
    </div>
  {/if}
</div>
