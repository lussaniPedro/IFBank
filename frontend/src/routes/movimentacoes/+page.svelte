<script>
  import { api } from '$lib/api.js';

  // Pix e aplicações/resgates (CDB, CDI, Poupança) — item 9 do documento.
  let valorPix = $state('');
  let contaDestino = $state('');
  let descricaoPix = $state('');
  let tipoAplicacao = $state('CDB');
  let valorAplicacao = $state('');
  let valorResgate = $state('');
  let mensagem = $state('');
  let erro = $state('');

  async function enviarPix(e) {
    e.preventDefault();
    await executar(() => api.pix(contaDestino, Number(valorPix), descricaoPix), 'Pix enviado.');
    valorPix = '';
    contaDestino = '';
    descricaoPix = '';
  }

  async function aplicar(e) {
    e.preventDefault();
    await executar(() => api.aplicar(tipoAplicacao, Number(valorAplicacao)), 'Aplicação realizada.');
    valorAplicacao = '';
  }

  async function resgatar(e) {
    e.preventDefault();
    await executar(() => api.resgatar(tipoAplicacao, Number(valorResgate)), 'Resgate realizado.');
    valorResgate = '';
  }

  async function executar(acao, textoSucesso) {
    erro = '';
    mensagem = '';
    try {
      await acao();
      mensagem = textoSucesso;
    } catch (e) {
      erro = e.message;
    }
  }
</script>

<div class="pagina">
  <h1>Movimentações</h1>

  {#if mensagem}<p class="sucesso">{mensagem}</p>{/if}
  {#if erro}<p class="erro">{erro}</p>{/if}

  <div class="form-card">
    <h2>Pix</h2>
    <form onsubmit={enviarPix}>
      <div class="campo">
        <label for="contaDestino">Conta de destino</label>
        <input id="contaDestino" type="text" inputmode="numeric" maxlength="8" bind:value={contaDestino} placeholder="Digite os 8 dígitos" required />
      </div>
      <div class="campo">
        <label for="valorPix">Valor</label>
        <input id="valorPix" type="number" min="0.01" step="0.01" bind:value={valorPix} required />
      </div>
      <div class="campo">
        <label for="descricaoPix">Descrição</label>
        <input id="descricaoPix" type="text" bind:value={descricaoPix} placeholder="Opcional" />
      </div>
      <button type="submit" class="btn-dourado">Enviar Pix</button>
    </form>
  </div>

  <div class="form-card">
    <h2>Aplicações e Resgates</h2>

    <div class="campo">
      <label for="tipo">Tipo de investimento</label>
      <select id="tipo" bind:value={tipoAplicacao}>
        <option value="CDB">CDB</option>
        <option value="CDI">CDI</option>
        <option value="POUPANCA">Poupança</option>
      </select>
    </div>

    <form onsubmit={aplicar} style="margin-bottom: 0.8rem;">
      <div class="campo">
        <label for="valorAplicacao">Valor a aplicar</label>
        <input id="valorAplicacao" type="number" min="0.01" step="0.01" bind:value={valorAplicacao} required />
      </div>
      <button type="submit" class="btn-dourado">Aplicar</button>
    </form>

    <form onsubmit={resgatar}>
      <div class="campo">
        <label for="valorResgate">Valor a resgatar</label>
        <input id="valorResgate" type="number" min="0.01" step="0.01" bind:value={valorResgate} required />
      </div>
      <button type="submit" class="btn-dourado">Resgatar</button>
    </form>
  </div>
</div>
