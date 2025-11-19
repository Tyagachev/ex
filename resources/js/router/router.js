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
                path: 'posts-me',
                name: 'posts-me.page',
                component: () => import('@/pages/User/index.vue'),
                children: [
                    {
                        path: '',
                        name: 'posts.me',
                        component: () => import('@/pages/User/Posts/index.vue'),
                    },
                ]
            },
            {
                path: 'comments',
                name: 'comments.page',
                component: () => import('@/pages/Comments/index.vue'),
                children: [
                    {
                        path: 'posts',
                        name: 'comments.posts',
                        component: () => import('@/pages/Comments/Post/index.vue'),
                    },
                    {
                        path: 'replies',
                        name: 'comments.replies',
                        component: () => import('@/pages/Comments/Reply/index.vue'),
                    }
                ]
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
                path: 'liked',
                name: 'liked.page',
                component: () => import('@/pages/Liked/index.vue'),
                children: [
                    {
                        path: 'up',
                        name: 'up.page',
                        component: () => import('@/pages/Liked/Upvote/index.vue'),
                    },
                    {
                        path: 'down',
                        name: 'down.page',
                        component: () => import('@/pages/Liked/Downvote/index.vue'),
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
                path: 'views-stories',
                name: 'views.page',
                component: () => import('@/pages/Views/index.vue'),
                children: [
                    {
                        path: '',
                        name: 'views.posts',
                        component: () => import('@/pages/Views/Posts/index.vue'),
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
