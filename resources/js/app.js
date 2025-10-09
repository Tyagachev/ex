import '../css/app.css'
import './bootstrap';

import { createApp } from 'vue'
import App from './pages/App.vue'
//import store from '../js/vuex/store.js'
import { createPinia } from 'pinia'
import router from './router/router.js'
import auth from "../js/mixins/auth.js";
import {useUserStore} from '@/stores/users.js'

const pinia = createPinia()

const app = createApp(App);
app.mixin(auth);
app.use(pinia);
app.use(router);

// Автоматическая инициализация пользователя при старте приложения
async function initializeApp() {
    const userStore = useUserStore();
    await userStore.getUser();
    // Монтируем приложение после инициализации
    app.mount('#app');
}

// Запускаем инициализацию
initializeApp();
