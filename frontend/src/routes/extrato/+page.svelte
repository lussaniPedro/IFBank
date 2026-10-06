<script>
  import { api } from '$lib/api.js';

  let inicio = $state('');
  let fim = $state('');
  let movimentacoes = $state([]);
  let erro = $state('');
  let carregando = $state(false);
  let buscou = $state(false);

  async function carregarExtrato() {
    erro = '';
    carregando = true;

    try {
      movimentacoes = await api.extrato(inicio || null, fim || null);
      buscou = true;
    } catch (e) {
      erro = e.message;
      movimentacoes = [];
    } finally {
      carregando = false;
    }
  }

  async function buscar(e) {
    e.preventDefault();
    await carregarExtrato();
  }

  $effect(() => {
    if (!buscou && !carregando) {
      carregarExtrato();
    }
  });
</script>

<div class="pagina">
  <h1>Extrato Bancário</h1>

  <div class="form-card">
    <form onsubmit={buscar} style="display:flex; gap:0.8rem; align-items:end; flex-wrap:wrap;">
      <div class="campo" style="margin-bottom:0; min-width: 180px; flex: 1;">
        <label for="inicio">De</label>
        <input id="inicio" type="date" bind:value={inicio} />
      </div>
      <div class="campo" style="margin-bottom:0; min-width: 180px; flex: 1;">
        <label for="fim">Até</label>
        <input id="fim" type="date" bind:value={fim} />
      </div>
      <button type="submit" class="btn-dourado" style="width:auto; padding:0.6rem 1.2rem; min-width:120px;" disabled={carregando}>
        {carregando ? 'Buscando...' : 'Buscar'}
      </button>
    </form>
  </div>

  {#if erro}<p class="erro">{erro}</p>{/if}

  {#if buscou}
    <table>
      <thead>
        <tr><th>Data</th><th>Tipo</th><th>Natureza</th><th>Descrição</th><th>Valor</th></tr>
      </thead>
      <tbody>
        {#each movimentacoes as m}
          <tr>
            <td>{new Date(m.data).toLocaleString('pt-BR')}</td>
            <td>{m.tipo}</td>
            <td>{m.natureza === 'entrada' ? 'Entrada' : 'Saída'}</td>
            <td>{m.descricao || 'Sem descrição'}</td>
            <td>R$ {Number(m.valor).toFixed(2)}</td>
          </tr>
        {:else}
          <tr><td colspan="5">Nenhuma movimentação disponível.</td></tr>
        {/each}
      </tbody>
    </table>
  {/if}
</div>
