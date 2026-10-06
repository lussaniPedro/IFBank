import { get } from 'svelte/store';
import { token, usuario } from '$lib/stores/auth.js';

const API_BASE_URL = '/api';

class ApiError extends Error {
    constructor(message, status = 0, data = null){
        super(message);
        this.name = 'ApiError';
        this.status = status;
        this.data = data;
    }
}

async function request(path, options = {}){
    const {
        method = 'GET',
        body,
        headers = {},
        ...rest
    } = options;

    const authToken = get(token);

    const config = {
        method,
        credentials: 'include',
        headers: {
            Accept: 'application/json',
            ...(authToken ? { Authorization: `Bearer ${authToken}` } : {}),
            ...headers
        },
        ...rest
    };

    if (body !== undefined){
        config.headers['Content-Type'] = 'application/json';
        config.body = JSON.stringify(body);
    }

    const response = await fetch(`${API_BASE_URL}${path}`, config);

    let data = null;
    const contentType = response.headers.get('content-type') ?? '';

    if (contentType.includes('application/json')){
        try {
            data = await response.json();
        } catch {
            data = null;
        }
    }

    if (!response.ok){
        if (response.status === 401){
            localStorage.removeItem('user');
        }

        const validation = data?.errors
            ? Object.values(data.errors).flat().join(' ')
            : null;

        const message =
            validation ||
            data?.message ||
            data?.error ||
            'Ocorreu um erro ao realizar a requisição.';

        throw new ApiError(message, response.status, data);
    }

    return data;
}

const http = {
    get(path, options = {}){
        return request(path, {
            ...options,
            method: 'GET'
        });
    },

    post(path, body, options = {}){
        return request(path, { ...options, method: 'POST', body });
    },

    put(path, body, options = {}){
        return request(path, { ...options, method: 'PUT', body });
    },

    delete(path, options = {}){
        return request(path, { ...options, method: 'DELETE' });
    },
};

export const api = {
    auth: {
        async login(email, senha){
            const data = await http.post('/login', {
                email,
                password: senha
            });

            if(data?.token){
                token.set(data.token);
            }

            if(data?.user){
                usuario.set(data.user);
            }

            return data;
        },

        async register(name, email, password, passwordConfirmation){
            return http.post('/register', {
                name,
                email,
                password,
                password_confirmation: passwordConfirmation
            });
        },

        async logout(){
            try {
                return await http.post('/logout');
            } finally {
                localStorage.removeItem('user');
                token.set(null);
            }
        },

        async me(){
            return http.get('/me');
        }
    },

    async login(email, senha){
        return this.auth.login(email, senha);
    },

    async register(name, email, password, passwordConfirmation){
        return this.auth.register(name, email, password, passwordConfirmation);
    },

    async logout(){
        return this.auth.logout();
    },

    async saldo(){
        const [conta, aplicacoes] = await Promise.all([
            http.get('/conta'),
            http.get('/investimentos')
        ]);

        return {
            ...conta,
            aplicacoes
        };
    },

    async extrato(dataInicial = null, dataFinal = null){
        const params = new URLSearchParams();

        if (dataInicial){
            params.set('data_inicial', dataInicial);
        }

        if (dataFinal){
            params.set('data_final', dataFinal);
        }

        const query = params.toString();

        return http.get(
            `/conta/extrato${query ? `?${query}` : ''}`
        );
    },

    async pix(numeroContaDestino, valor, descricao = null){
		return http.post('/conta/pix', {
			numero_conta_destino: numeroContaDestino,
			valor,
			...(descricao ? { descricao } : {})
		});
	},

    async aplicar(tipo, valor){
        return http.post('/investimentos/aplicar', {
            tipo,
            valor
        });
    },

    async resgatar(tipo, valor){
        return http.post('/investimentos/resgatar', { tipo, valor });
    },

    gerentes: {
        list: () => http.get('/gerentes'),
        show: (id) => http.get(`/gerentes/${id}`),
        create: (data) => http.post('/gerentes', data),
        update: (id, data) => http.put(`/gerentes/${id}`, data),
        remove: (id) => http.delete(`/gerentes/${id}`)
    },

    clientes: {
        list: () => http.get('/clientes'),
        show: (id) => http.get(`/clientes/${id}`),
        create: (data) => http.post('/clientes', data),
        update: (id, data) => http.put(`/clientes/${id}`, data),
        remove: (id) => http.delete(`/clientes/${id}`),
        bloquear: (id) => http.post(`/clientes/${id}/bloquear`),
        desbloquear: (id) => http.post(`/clientes/${id}/desbloquear`),
        extrato: (id) => http.get(`/clientes/${id}/extrato`),
        limite: (id) => http.get(`/clientes/${id}/limite`),
        solicitarLimite: (id, limite_solicitado) => http.post(`/clientes/${id}/limite`, { limite_solicitado })
    },

    solicitacoes: {
        list: () => http.get('/solicitacoes-limite'),
        aprovar: (id) => http.post(`/solicitacoes-limite/${id}/aprovar`),
        reprovar: (id) => http.post(`/solicitacoes-limite/${id}/reprovar`)
    },

    auditoria: {
        list: () => http.get('/auditorias')
    }
};

export { ApiError };