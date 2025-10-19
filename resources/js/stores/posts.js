import axios from "axios";
import {defineStore} from "pinia";
import router from "../router/router.js";
import NProgress from "nprogress";
import {useCommentsStore} from "@/stores/comments.js";

export const usePostsStore = defineStore('posts', {
        state: () => ({
            posts: [],
            page: 1,
            currentPage: null,
            hasMore: true,
            postShow: {},
            loading: false,
            scrollPosition: 0
        }),
        getters: {
            allPosts: (state) => state.posts,
            post: (state) => state.postShow
        },
        actions: {

            /**
             * Получение постов
             * @returns {Promise<void>}
             */
            async getPosts() {
                NProgress.start()
                if (this.loading || !this.hasMore) return

                this.loading = true;

                try {
                    const {data} = await axios.get(`/api/posts?page=${this.page}`);
                    NProgress.done()
                    this.posts.push(...data.data);

                    if (data.meta.current_page < data.meta.last_page) {
                        this.page++
                    } else {
                        this.hasMore = false;
                    }
                } finally {
                    this.loading = false;
                }
            },
            /**
             * Получение одного поста
             * @param post
             * @returns {Promise<void>}
             */
            async getPost(post) {
                this.loading = true;
                this.postShow = {};

                try {
                    NProgress.start()
                    const res = await axios.get(`/api/posts/show/${post}`);
                    this.postShow = res.data;
                    const commentStore = useCommentsStore();
                    await commentStore.getComments(this.postShow.comments);
                    NProgress.done()
                } finally {
                    this.loading = false;
                }
            },

            /**
             * Отправка в БД
             * @param title
             * @param from
             * @param blocks
             * @returns {Promise<void>}
             */
            async storePosts(title, from, blocks) {
                NProgress.start()
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
                    await this.clearPostsField();
                    NProgress.done()
                    await router.push({
                        name: 'main',
                    });
                }
            },

            /**
             * Очистка данных хранилища
             * @returns {Promise<void>}
             */
            async clearPostsField() {
                this.page = 1;
                this.posts = [];
                this.loading = false;
                this.scrollPosition = 0;
            },

            /**
             * Удаление поста
             * @param post
             * @returns {Promise<void>}
             */
            async destroyPost(post) {
                const res = await axios.delete(`/api/posts/${post.id}`);
                if (res.status === 200) {
                    const index = this.posts.findIndex(p => p.id === post.id);
                    if (index !== -1) {
                        this.posts.splice(index, 1);
                    }
                    await router.push({
                        name: 'main',
                    });
                    if (this.posts.length < 10 && this.hasMore && !this.loading) {
                        await this.getPosts();
                    }
                }
            },
            async destroyPostFromShowPost(post) {
                const res = await axios.delete(`/api/posts/${post.id}`);
                if (res.status === 200) {
                    const index = this.posts.findIndex(p => p.id === post.id);
                    if (index !== -1) {
                        this.posts.splice(index, 1);
                    }
                    await router.push({
                        name: 'main',
                    });
                }
            }
        },
})
