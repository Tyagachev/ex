import { createStore } from 'vuex';
import axios from '../service/api.js';
import posts from '@/vuex/modules/posts';
import votes from "@/vuex/modules/votes.js";

export default createStore({
    state: {
        user: null,
        token: localStorage.getItem('token') || null, // сохраняем токен между сессиями
    },
    mutations: {
        setUser(state, user) {
            state.user = user;
        },
        setToken(state, token) {
            state.token = token;
            if (token) {
                localStorage.setItem('token', token);
            } else {
                localStorage.removeItem('token');
            }
        },
    },
    actions: {
        async initialize({ commit, state }) {

            if (state.token) {
                try {
                    const res = await axios.get('/user');
                    commit('setUser', res.data);
                } catch (err) {
                    console.warn('Failed to fetch user on init', err);
                }
            }
        },
        async login({ commit }, { email, password }) {
            try {
                const res = await axios.post('/login', { email, password });
                commit('setToken', res.data.token);
                commit('setUser', res.data.user);
            } catch (err) {
                console.error('Login error:', err.response?.data || err);
                throw err;
            }
        },

        async getUser({ commit }) {
            try {
                const res = await axios.get('/user');
                commit('setUser', res.data);
            } catch (err) {
                console.error('GetUser error:', err.response?.data || err);
                throw err;
            }
        },

        async logout({ commit }) {
            try {
                await axios.post('/logout');
            } catch (err) {
                console.warn('Logout failed:', err.response?.data || err);
            }
            commit('setToken', null);
            commit('setUser', null);
        },
    },
    getters: {
        isAuthenticated: state => !!state.token,
    },
    modules: {
        posts,
        votes
    }
});
