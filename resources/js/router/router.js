import {createRouter, createWebHistory} from "vue-router";
import NProgress from "nprogress";

const routes = [
    {
        path: '/',
        name: 'main',
        redirect: '/posts',
        component: () => import('@/layouts/MainLayout.vue'),
        children: [
            {
                path: '/posts',
                name: 'posts.page',
                component: () => import('@/pages/Posts/Index.vue')
            },
            {
                path: '/answers',
                name: 'answers.page',
                component: () => import('@/layouts/AnswersLayout.vue'),
                children: [
                    {
                        path: 'replies', // Пустой путь для /comments
                        name: 'answers.reply',
                        component: () => import('@/pages/Answers/Reply/index.vue'),
                    },
                    {
                        path: 'posts', // Пустой путь для /comments
                        name: 'answers.posts',
                        component: () => import('@/pages/Answers/Post/index.vue'),
                    }
                ]
            },
            {
                path: '/posts/create',
                name: 'posts.create',
                component: () => import('@/pages/Posts/create.vue')
            },
            {
                path: '/posts/edit/:id',
                name: 'posts.edit',
                component: () => import('@/pages/Posts/edit.vue')
            },
            {
                path: '/posts/show/:id',
                name: 'posts.show',
                component: () => import('@/pages/Posts/show.vue')
            },
            {
                path: '/visited',
                name: 'visited.page',
                component: () => import('@/pages/Visited/Index.vue')
            },
        ]
    },
    {
        path: '/comments/:id', // Перенесите сюда
        name: 'comments.show',
        component: () => import('@/pages/Comments/show.vue')
    },
    {
        path: '/login',
        name: 'login.page',
        component: () => import('../pages/Auth/Login/Login.vue')
    },
    {
        path: '/test',
        name: 'test.page',
        component: () => import('@/pages/Test/Test.vue')
    },
    {
        path: '/register',
        name: 'register.page',
        component: () => import('../pages/Auth/Registration/Registration.vue')
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router
