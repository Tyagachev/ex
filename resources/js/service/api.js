import axios from 'axios';

// Создаем экземпляр axios
const api = axios.create({
    baseURL: 'http://localhost:8000/api',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
});

// Interceptor для requests
api.interceptors.request.use((config) => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, (error) => {
    return Promise.reject(error);
});

// Interceptor для responses
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Автоматически очищаем аутентификацию при 401 ошибке
            localStorage.removeItem('token');
            delete api.defaults.headers.common['Authorization'];

            // Можно добавить редирект на логин
            // window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

export default api;
