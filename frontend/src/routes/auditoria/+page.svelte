<script>
 import { onMount } from 'svelte'; import { api } from '$lib/api.js';
 let dados=$state({data:[]}),erro=$state(''); onMount(async()=>{try{dados=await api.auditoria.list();}catch(e){erro=e.message;}});
</script>
<div class="pagina admin-page"><div class="page-head"><div><span class="eyebrow">GERENTE GERAL</span><h1>Logs de auditoria</h1><p>Atividades registradas pelo sistema.</p></div></div>{#if erro}<div class="alert-error">{erro}</div>{/if}<div class="table-wrap"><table><thead><tr><th>Data</th><th>Usuário</th><th>Ação</th><th>Modelo</th><th>ID</th></tr></thead><tbody>{#each (dados.data ?? []) as a}<tr><td>{a.created_at ? new Date(a.created_at).toLocaleString('pt-BR') : '-'}</td><td>{a.user?.name ?? '-'}</td><td>{a.event ?? '-'}</td><td>{a.auditable_type?.split('\\').pop() ?? '-'}</td><td>{a.auditable_id ?? '-'}</td></tr>{/each}{#if !(dados.data?.length)}<tr><td colspan="5">Nenhum log encontrado.</td></tr>{/if}</tbody></table></div></div>
