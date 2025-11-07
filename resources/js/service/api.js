import axios from 'axios';

// Создаем экземпляр axios
const api = axios.create({
    baseURL: 'http://localhost/api',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
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
