import adapter from '@sveltejs/adapter-static';

// Modo SPA: sem SSR, um único index.html com fallback para as rotas do client-side router.
// Isso é o que separa este projeto do backend Laravel (SSR) — aqui tudo roda no navegador
// e conversa com a API só via fetch/token Sanctum.
export default {
  kit: {
    adapter: adapter({
      fallback: 'index.html',
    }),
  },
};
