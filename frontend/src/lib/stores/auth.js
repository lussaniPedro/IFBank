import { writable } from 'svelte/store';

const TOKEN_KEY = 'ifbank_token';
const USER_KEY = 'ifbank_user';

const getStoredValue = (key) => {
  if (typeof window === 'undefined') return null;
  return localStorage.getItem(key);
};

const initialToken = getStoredValue(TOKEN_KEY);
const initialUser = getStoredValue(USER_KEY);

export const token = writable(initialToken ?? null);
export const usuario = writable(initialUser ? JSON.parse(initialUser) : null);

token.subscribe((value) => {
  if (typeof window === 'undefined') return;

  if(value){
    localStorage.setItem(TOKEN_KEY, value);
  } else {
    localStorage.removeItem(TOKEN_KEY);
  }
});

usuario.subscribe((value) => {
  if (typeof window === 'undefined') return;

  if(value){
    localStorage.setItem(USER_KEY, JSON.stringify(value));
  } else {
    localStorage.removeItem(USER_KEY);
  }
});

export function limparSessao() {
  token.set(null);
  usuario.set(null);
}
