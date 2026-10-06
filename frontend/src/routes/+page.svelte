<script>
  import { onMount } from 'svelte';
  import { goto } from '$app/navigation';
  import { get } from 'svelte/store';
  import { api } from '$lib/api.js';
  import { usuario } from '$lib/stores/auth.js';

  let conta = $state(null);
  let clientes = $state([]);
  let gerentes = $state([]);
  let erro = $state('');
  let carregandoClientes = $state(false);

  const gerenteConta = () => get(usuario)?.role?.name === 'GERENTE_CONTA';
  const gerenteGeral = () => get(usuario)?.role?.name === 'GERENTE_GERAL';
  const cliente = () => get(usuario)?.role?.name === 'CLIENTE';

  onMount(async () => {
    try {
      if (gerenteConta()) {
        carregandoClientes = true;
        clientes = await api.clientes.list();
      } else if (gerenteGeral()) {
        carregandoClientes = true;
        gerentes = await api.gerentes.list();
      } else if (cliente()) {
        conta = await api.saldo();
      }
    } catch (e) {
      erro = e.message;
    } finally {
      carregandoClientes = false;
    }
  });
</script>

{#if gerenteConta()}
  <div class="pagina admin-page">
    <div class="page-head">
      <div>
        <span class="eyebrow">GERENTE DE CONTA</span>
        <h1>Clientes</h1>
        <p>Clientes e contas sob gerenciamento da agência.</p>
      </div>
      <button class="btn-primary" onclick={() => goto('/clientes')}>Gerenciar clientes</button>
    </div>

    {#if erro}<div class="alert-error">{erro}</div>{/if}

    {#if carregandoClientes}
      <div class="card-dash">Carregando clientes...</div>
    {:else}
      <div class="table-wrap">
        <table>
          <thead><tr><th>Cliente</th><th>Conta</th><th>Saldo</th><th>Limite</th><th>Status</th><th></th></tr></thead>
          <tbody>
            {#each clientes as c}
              <tr>
                <td><strong>{c.user?.name ?? '—'}</strong><small>{c.user?.email ?? ''}</small></td>
                <td>{c.conta?.numero_conta ?? '—'}</td>
                <td>R$ {Number(c.conta?.saldo ?? 0).toFixed(2)}</td>
                <td>R$ {Number(c.conta?.limite ?? 0).toFixed(2)}</td>
                <td>{c.conta?.status ?? '—'}</td>
                <td class="actions"><button onclick={() => goto(`/clientes/${c.id}`)}>Detalhes</button></td>
              </tr>
            {:else}
              <tr><td colspan="6">Nenhum cliente cadastrado.</td></tr>
            {/each}
          </tbody>
        </table>
      </div>
    {/if}
  </div>
{:else if gerenteGeral()}
  <div class="pagina admin-page">
    <div class="page-head">
      <div>
        <span class="eyebrow">GERENTE GERAL</span>
        <h1>Gerentes de Conta</h1>
        <p>Lista dos gerentes de conta cadastrados na agência.</p>
      </div>
      <button class="btn-primary" onclick={() => goto('/gerentes')}>Gerenciar gerentes</button>
    </div>

    {#if erro}<div class="alert-error">{erro}</div>{/if}

    {#if carregandoClientes}
      <div class="card-dash">Carregando gerentes...</div>
    {:else}
      <div class="table-wrap">
        <table>
          <thead><tr><th>Gerente</th><th>E-mail</th></tr></thead>
          <tbody>
            {#each gerentes as gerente}
              <tr><td><strong>{gerente.name}</strong></td><td>{gerente.email}</td></tr>
            {:else}
              <tr><td colspan="2">Nenhum gerente de conta cadastrado.</td></tr>
            {/each}
          </tbody>
        </table>
      </div>
    {/if}
  </div>
{:else if cliente()}
  <div class="pagina">
    <h1>Meu Saldo</h1>
    {#if erro}<p class="erro">{erro}</p>
    {:else if !conta}<p>Carregando...</p>
    {:else}
      <div class="cards-grid">
        <div class="card-dash"><div class="label">Conta</div><div class="num" style="font-size:1.1rem">{conta.numero_conta}</div></div>
        <div class="card-dash"><div class="label">Saldo</div><div class="num">R$ {conta.saldo}</div></div>
        <div class="card-dash"><div class="label">Limite</div><div class="num" style="font-size:1.1rem">R$ {conta.limite}</div></div>
      </div>
      {#if conta.status === 'bloqueada'}<p class="erro">Conta bloqueada por suspeita de fraude — apenas consulta de saldo disponível.</p>{/if}
      <div class="form-card">
        <h2>Aplicações</h2>
        <table><thead><tr><th>Tipo</th><th>Valor Aplicado</th></tr></thead><tbody>
          {#each conta.aplicacoes ?? [] as aplicacao}<tr><td>{aplicacao.tipo}</td><td>R$ {aplicacao.valor}</td></tr>{/each}
        </tbody></table>
      </div>
    {/if}
  </div>
{:else}
  <div class="pagina"><h1>Painel</h1><p>Selecione uma opção no menu.</p></div>
{/if}
