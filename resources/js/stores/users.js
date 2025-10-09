import axios from '../service/api.js';
import { defineStore } from "pinia";

export const useUserStore = defineStore('user', {
    state: () => ({
        user: null,
        token: localStorage.getItem('token') || null,
        isLoading: false,
        isInitialized: false // Флаг инициализации
    }),

    getters: {
        u: (state) => state.user,
        isAuthenticated: (state) => !!state.user && !!state.token,
    },

    actions: {
        async login(email, password) {
            try {
                const res = await axios.post('/login', { email, password });
                console.log('Login response:', res.data);

                this.user = res.data.user;
                this.token = res.data.token;
                localStorage.setItem('token', res.data.token);

                // Устанавливаем заголовок авторизации для будущих запросов
                axios.defaults.headers.common['Authorization'] = `Bearer ${res.data.token}`;

                return res.data;
            } catch (error) {
                console.error('Login error:', error);
                throw error;
            }
        },

        async getUser() {
            // Если нет токена, пропускаем запрос
            if (!this.token) {
                this.isInitialized = true;
                return;
            }

            // Если уже загружаем, не делаем повторный запрос
            if (this.isLoading) return;

            this.isLoading = true;

            try {
                // Устанавливаем токен перед запросом
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;

                const response = await axios.get('/user');
                console.log('User data loaded:', response.data);

                this.user = response.data;
                this.isInitialized = true;
            } catch (error) {
                console.error('Failed to fetch user:', error);

                // Если ошибка 401 (неавторизован), очищаем токен
                if (error.response?.status === 401) {
                    this.clearAuth();
                }

                this.isInitialized = true;
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async logout() {
            try {
                if (this.token) {
                    await axios.post('/logout');
                }
            } catch (error) {
                console.error('Logout error:', error);
            } finally {
                this.clearAuth();
            }
        },

        clearAuth() {
            this.user = null;
            this.token = null;
            this.isInitialized = true;
            localStorage.removeItem('token');
            delete axios.defaults.headers.common['Authorization'];
        },

        // Принудительная повторная инициализация
        async reinitialize() {
            this.isInitialized = false;
            await this.getUser();
        }
    },

    // Persist state (опционально, для сохранения состояния)
    persist: {
        key: 'user-store',
        paths: ['user', 'token']
    }
});
