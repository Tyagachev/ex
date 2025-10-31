import axios from '../service/api.js';
import { defineStore } from "pinia";

export const useUserStore = defineStore('user', {
    state: () => ({
        userObject: {},
        token: localStorage.getItem('token') || null,
        isLoading: false,
        isInitialized: false,
        errors: []
    }),

    getters: {
        user: (state) => state.userObject,
        isAuthenticated: (state) => !!state.userObject && !!state.token,
    },

    actions: {
        /**
         * Вход в систему
         * @param email
         * @param password
         * @returns {Promise<any>}
         */
        async login(email, password) {
            try {
                const res = await axios.post('/login', { email, password });

                this.userObject = res.data.user;
                this.token = res.data.token;
                localStorage.setItem('token', res.data.token);
                return res.data;

            } catch (error) {
                this.errors.push(error.response.data.errors);
                console.error('Login error:', error.response.data.errors);
                throw error;
            }
        },

        /**
         * Получение данных об авторизированном
         * пользователе.
         * @returns {Promise<void>}
         */
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
                this.userObject = response.data;
                Object.assign(this.userObject,  {auth: true})
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

        /**
         * Выход из системы
         * @returns {Promise<void>}
         */
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
        /**
         * Очистка полей
         */
        clearAuth() {
            this.userObject = {};
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
