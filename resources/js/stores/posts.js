import axios from "axios";
import {defineStore} from "pinia";
import router from "../router/router.js";
import NProgress from "nprogress";



export const usePostsStore = defineStore('posts', {
    state: () => ({
        posts: [],
        postShow: {},
        loading: false,
    }),
    getters: {
        allPosts: (state) => state.posts,
        post: (state) => state.postShow
    },
    actions: {

        /**
         * Получение постов
         *
         * @returns {Promise<void>}
         */
        async getPosts() {
            const res = await axios.get('/api/posts');
            this.posts = res.data;
        },
        /**
         *
         * @param post
         * @returns {Promise<void>}
         */
        async getPost(post) {

            this.postShow = {};
            this.loading = true;
            NProgress.start()
            const res = await axios.get(`/api/posts/show/${post}`);
            NProgress.done()
            this.postShow = res.data;

        },

        /**
         * Отправка в БД
         *
         * @param title
         * @param from
         * @param blocks
         * @returns {Promise<void>}
         */
        async storePosts(title, from, blocks) {
            const titleText = String(title.value || ''); // Гарантируем, что это строка
            if (!titleText.trim()) return;

            if (title.value.length === 0 || title.value.length > 255) {
                return;
            }

            const formData = new FormData();
            formData.append("title", title.value);
            formData.append("from", from);

            blocks.value.forEach((block, i) => {
                if (block.content || block.file) {
                    formData.append(`blocks[${i}][type]`, block.type);
                    if (block.type === "text") {
                        formData.append(`blocks[${i}][content]`, block.content);
                    } else if (block.type === "image" && block.file) {
                        formData.append(`blocks[${i}][file]`, block.file);
                    }
                }
            });
            const res = await axios.post('/api/posts', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            if (res.status === 200) {
                await router.push({
                    name: 'main',
                });
            }
        },

        /**
         * Удаление поста
         * @param post
         * @returns {Promise<void>}
         */
    async destroyPost(post) {
            const res = await axios.delete(`/api/posts/${post.id}`);
            if (res.status === 200) {
                await this.getPosts()
            }
    }
    },
})
