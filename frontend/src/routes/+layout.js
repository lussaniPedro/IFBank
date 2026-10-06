// Desliga SSR/prerender em todo o app: isto é uma SPA pura que só fala com
// a API do Laravel via fetch. Sem isso, o adapter-static tentaria renderizar
// no build e falharia por não haver "servidor" de fato.
export const ssr = false;
export const prerender = false;
