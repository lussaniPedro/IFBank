<script>
  import { onMount } from 'svelte';
  import { page } from '$app/stores';
  import { goto } from '$app/navigation';
  import { api } from '$lib/api.js';

  let cliente = $state(null);
  let extrato = $state([]);
  let erro = $state('');
  let mensagem = $state('');
  let nome = $state('');
  let email = $state('');
  let novoLimite = $state('');
  let salvando = $state(false);
  let solicitando = $state(false);

  let id = $derived($page.params.id);

  async function carregar() {
    try {
      erro = '';
      cliente = await api.clientes.show(id);
      nome = cliente.user?.name ?? '';
      email = cliente.user?.email ?? '';
      extrato = await api.clientes.extrato(id);
    } catch (e) {
      erro = e.message;
    }
  }

  async function salvar() {
    salvando = true;
    erro = '';
    mensagem = '';
    try {
      await api.clientes.update(id, { name: nome, email });
      mensagem = 'Cliente atualizado com sucesso.';
      await carregar();
    } catch (e) {
      erro = e.message;
    } finally {
      salvando = false;
    }
  }

  async function solicitarLimite() {
    solicitando = true;
    erro = '';
    mensagem = '';
    try {
      await api.clientes.solicitarLimite(id, Number(novoLimite));
      mensagem = 'Solicitação de aumento de limite enviada.';
      novoLimite = '';
    } catch (e) {
      erro = e.message;
    } finally {
      solicitando = false;
    }
  }

  onMount(carregar);
</script>

<div class="pagina admin-page">
  {#if erro}<div class="alert-error">{erro}</div>{/if}
  {#if mensagem}<div class="alert-success">{mensagem}</div>{/if}

  {#if cliente}
    <div class="page-head">
      <div><span class="eyebrow">CLIENTE</span><h1>{cliente.user?.name}</h1><p>{cliente.user?.email} · Conta {cliente.conta?.numero_conta}</p></div>
      <button class="btn-secondary" onclick={() => goto('/clientes')}>Voltar</button>
    </div>

    <div class="cards-grid">
      <div class="card-dash"><div class="label">Saldo</div><div class="num">R$ {Number(cliente.conta?.saldo ?? 0).toFixed(2)}</div></div>
      <div class="card-dash"><div class="label">Limite</div><div class="num">R$ {Number(cliente.conta?.limite ?? 0).toFixed(2)}</div></div>
      <div class="card-dash"><div class="label">Status</div><div class="num">{cliente.conta?.status}</div></div>
    </div>

    <div class="card-dash form-stack">
      <h2>Dados do cliente</h2>
      <label>Nome<input bind:value={nome} /></label>
      <label>E-mail<input bind:value={email} type="email" /></label>
      <button class="btn-primary" onclick={salvar} disabled={salvando}>{salvando ? 'Salvando...' : 'Salvar alterações'}</button>
    </div>

    <div class="card-dash form-inline">
      <input bind:value={novoLimite} type="number" min="0.01" step="0.01" placeholder="Novo limite" />
      <button class="btn-primary" onclick={solicitarLimite} disabled={solicitando}>{solicitando ? 'Enviando...' : 'Solicitar aumento'}</button>
    </div>

    <h2>Extrato</h2>
    <div class="table-wrap">
      <table>
        <thead><tr><th>Data</th><th>Tipo</th><th>Valor</th><th>Natureza</th></tr></thead>
        <tbody>
          {#each extrato as m}
            <tr><td>{new Date(m.created_at ?? m.data).toLocaleString('pt-BR')}</td><td>{m.tipo}</td><td>R$ {Number(m.valor).toFixed(2)}</td><td>{m.natureza ?? '-'}</td></tr>
          {:else}
            <tr><td colspan="4">Nenhuma movimentação.</td></tr>
          {/each}
        </tbody>
      </table>
    </div>
  {/if}
</div>
