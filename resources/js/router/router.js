import {createRouter, createWebHistory} from "vue-router";

const routes = [
    {
        path: '/',
        name: 'main',
        redirect: '/posts',
        component: () => import('@/layouts/MainLayout.vue'),
        children: [
            {
                path: 'posts',
                name: 'posts.page',
                component: () => import('@/pages/Posts/index.vue')
            },
            {
                path: 'test',
                name: 'test.page',
                component: () => import('@/pages/Test/Test.vue')
            },
            {
                path: 'answers',
                name: 'answers.page',
                component: () => import('@/pages/Answers/index.vue'),
                children: [
                    {
                        path: 'posts',
                        name: 'answers.posts',
                        component: () => import('@/pages/Answers/Post/index.vue'),
                    },
                    {
                        path: 'replies',
                        name: 'answers.replies',
                        component: () => import('@/pages/Answers/Reply/index.vue'),
                    }
                ]
            },
            {
                path: 'saves',
                name: 'saves.page',
                component: () => import('@/pages/Saved/index.vue'),
                children: [
                    {
                        path: '',
                        name: 'saves.posts',
                        component: () => import('@/pages/Saved/Posts/index.vue'),
                    },
                    {
                        path: 'comments',
                        name: 'saves.comments',
                        component: () => import('@/pages/Saved/Comments/index.vue'),
                    },
                ]
            },
            {
                path: 'posts/create',
                name: 'posts.create',
                component: () => import('@/pages/Posts/create.vue')
            },
            {
                path: 'posts/edit/:id',
                name: 'posts.edit',
                component: () => import('@/pages/Posts/edit.vue')
            },
            {
                path: 'posts/:id',
                name: 'posts.show',
                component: () => import('@/pages/Posts/show.vue')
            },
            {
                path: 'visited',
                name: 'visited.page',
                component: () => import('@/pages/Visited/index.vue')
            },
            {
                path: 'comments/:id',
                name: 'comments.show',
                component: () => import('@/pages/Comments/show.vue')
            },
        ]
    },
    {
        path: '/login',
        name: 'login.page',
        component: () => import('../pages/Auth/Login/Login.vue')
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
