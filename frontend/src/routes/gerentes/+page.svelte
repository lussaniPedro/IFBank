<script>
  import { onMount } from 'svelte';
  import { api } from '$lib/api.js';

  let gerentes = $state([]);
  let erro = $state('');
  let mensagem = $state('');
  let carregando = $state(false);
  let editando = $state(null);
  let nome = $state('');
  let email = $state('');
  let senha = $state('');

  async function carregar() {
    try {
      erro = '';
      gerentes = await api.gerentes.list();
    } catch (e) {
      erro = e.message;
    }
  }

  function limpar() {
    editando = null;
    nome = '';
    email = '';
    senha = '';
  }

  function editar(gerente) {
    editando = gerente.id;
    nome = gerente.name ?? '';
    email = gerente.email ?? '';
    senha = '';
  }

  async function salvar() {
    carregando = true;
    erro = '';
    mensagem = '';

    try {
      const data = { name: nome, email };
      if (senha) data.password = senha;

      if (editando) {
        await api.gerentes.update(editando, data);
        mensagem = 'Gerente atualizado com sucesso.';
      } else {
        data.password = senha;
        await api.gerentes.create(data);
        mensagem = 'Gerente criado com sucesso e credenciais enviadas por e-mail.';
      }

      limpar();
      await carregar();
    } catch (e) {
      erro = e.message;
    } finally {
      carregando = false;
    }
  }

  async function remover(id) {
    if (!confirm('Remover este gerente?')) return;

    try {
      erro = '';
      await api.gerentes.remove(id);
      mensagem = 'Gerente removido com sucesso.';
      await carregar();
    } catch (e) {
      erro = e.message;
    }
  }

  onMount(carregar);
</script>

<div class="pagina admin-page">
  <div class="page-head">
    <div>
      <span class="eyebrow">GERENTE GERAL</span>
      <h1>Gerentes de Conta</h1>
      <p>Cadastre, altere e remova os gerentes de conta.</p>
    </div>
  </div>

  {#if erro}<div class="alert-error">{erro}</div>{/if}
  {#if mensagem}<div class="alert-success">{mensagem}</div>{/if}

  <div class="admin-form card-dash">
    <h2>{editando ? 'Editar gerente' : 'Novo gerente'}</h2>

    <div class="form-grid">
      <input bind:value={nome} placeholder="Nome" />
      <input bind:value={email} type="email" placeholder="E-mail" />
      <input bind:value={senha} type="password" placeholder={editando ? 'Nova senha (opcional)' : 'Senha'} />

      <div class="modal-actions">
        {#if editando}
          <button class="btn-secondary" onclick={limpar}>Cancelar</button>
        {/if}
        <button class="btn-primary" onclick={salvar} disabled={carregando || !nome || !email || (!editando && !senha)}>
          {carregando ? 'Salvando...' : editando ? 'Salvar alterações' : 'Criar gerente'}
        </button>
      </div>
    </div>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>Nome</th><th>E-mail</th><th>Ações</th></tr>
      </thead>
      <tbody>
        {#each gerentes as gerente}
          <tr>
            <td>{gerente.name}</td>
            <td>{gerente.email}</td>
            <td class="actions">
              <button onclick={() => editar(gerente)}>Editar</button>
              <button class="btn-danger" onclick={() => remover(gerente.id)}>Remover</button>
            </td>
          </tr>
        {:else}
          <tr><td colspan="3">Nenhum gerente encontrado.</td></tr>
        {/each}
      </tbody>
    </table>
  </div>
</div>
