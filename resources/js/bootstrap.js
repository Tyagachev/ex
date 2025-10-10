import axios from 'axios';
import NProgress from 'nprogress';
import 'nprogress/nprogress.css';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;

axios.interceptors.request.use(config => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, error => {
    return Promise.reject(error);
});

NProgress.configure({
    easing: 'ease',
    speed: 200, // уменьшить с 500 до 300
    minimum: 0.1,
    trickleSpeed: 200,
    showSpinner: false
});

axios.interceptors.request.use(config => { NProgress.start(); return config; });
axios.interceptors.response.use(response => { NProgress.done(); return response; },
    error => { NProgress.done(); return Promise.reject(error); });

