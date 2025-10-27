import '../css/app.css'
import './bootstrap';

import { createApp } from 'vue'
import App from './pages/App.vue'
import { createPinia } from 'pinia'
import auth from "../js/mixins/auth.js";
import router from './router/router.js'
import {useUserStore} from '@/stores/users.js'

const pinia = createPinia()

const app = createApp(App);
app.mixin(auth);
app.use(pinia);
app.use(router);
// Автоматическая инициализация пользователя при старте приложения
async function initializeApp() {
    try {
        const userStore = useUserStore();
        await userStore.getUser();
    } finally {
        app.mount('#app');
    }
}
// Запускаем инициализацию
initializeApp();
