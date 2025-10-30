import {createRouter, createWebHistory} from "vue-router";
import NProgress from "nprogress";

const routes = [
    {
        path: '/',
        name: 'main',
        redirect: '/posts',
        component: () => import('../pages/App.vue')
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
    },
    {
        path: '/posts',
        name: 'posts.page',
        component: () => import('@/pages/Posts/Index.vue')
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
        path: '/comments/show/:id',
        name: 'comments.show',
        component: () => import('@/pages/Comments/show.vue')
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
});
/*router.beforeResolve((to, from) => {
    NProgress.start()
})
router.afterEach((to, from) => {
    NProgress.done()
})*/

export default router

