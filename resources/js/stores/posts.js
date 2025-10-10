import axios from "axios";
import {defineStore} from "pinia";
import {useRouter} from "vue-router";
import router from "../router/router.js";



export const usePostsStore = defineStore('posts', {
    state: () => ({
        posts: []
    }),
    getters: {
        allPosts: (state) => state.posts,
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
    async destroyPost(post) {
            const res = await axios.delete(`/api/posts/${post.id}`);
            if (res.status === 200) {
                await this.getPosts()
            }
    }
    },
})
