import {defineStore} from "pinia";
import axios from "axios";
import router from "@/router/router.js";

export const useCommentsStore = defineStore('comment', {
    state: () => ({
        commentsList: [],
        commentObject: {},
        activeMenu: null,
        responseCommentText: '',
        showReply: false,
        isCommentPage: false,
        commentText: '',
        replyText: ''
    }),

    getters: {
        comments:(state) => state.commentsList
    },

    actions: {

        /**
         * Получение комментариев
         * @param comments
         */
        getComments(comments) {
            this.commentsList = [];
            this.commentsList = comments;
        },

        /**
         * Получение одного коммента
         * с ответами
         * @param comment
         * @returns {Promise<void>}
         */
        async getComment(comment) {
            this.isCommentPage = true
            const res = await axios.get(`/api/comments/${comment}`);
            this.commentObject = res.data;
        },

        /**
         * Отправка коммнета на пост
         * @param post
         * @returns {Promise<void>}
         */
        async submitComment(post) {
            try {
                const res = await axios.post('/api/comments', {
                    text: this.commentText,
                    postId: post.id
                })

                await this.refresh(post.id)
                const newCommentId = res.data.commentId;
                this.getIdComment(newCommentId);
            } finally {
                this.commentText = '';
            }
        },

        /**
         * Меню редактирования комментария
         * @param commentId
         */
        setActiveMenu(commentId) {
            this.activeMenu = this.activeMenu === commentId ? null : commentId;
        },

        /**
         * Отправка ответа на коммент
         * @param comment
         * @returns {Promise<void>}
         */
        async sendReplyComment(comment) {
            if (!this.replyText.trim()) return;

            try {
                if (this.isCommentPage) {
                    const res = await axios.post('/api/comments', {
                        postId: comment.postId,
                        parentId: comment.id,
                        text: this.replyText,
                        replyId: comment.user?.id || null,
                    });
                    await this.getComment(this.commentObject.id);
                    this.getIdComment(res.data.commentId);
                } else {
                    const res = await axios.post('/api/comments', {
                        postId: comment.postId,
                        parentId: comment.id,
                        text: this.replyText,
                        replyId: comment.user?.id || null,
                    });
                    const id = res.data.post.id
                    await this.refresh(id)
                    this.getIdComment(res.data.commentId);
                }
            } finally {
                this.replyText = '';
            }
        },

        /**
         * Обновление списка комментариев
         * @param id
         * @returns {Promise<void>}
         */
        async refresh(id) {
            const res = await axios.get(`/api/posts/show/${id}`);
            this.commentsList = res.data.comments;
        },

        /**
         * Получение id созданного
         * коммента
         * @param newCommentId
         */
        getIdComment(newCommentId) {
            const element = document.getElementById(newCommentId);
            if (element) {
                element.scrollIntoView({ behavior: 'auto', block: 'center' });
                //Вспышка нового комментария
                this.fastHighlight(element);
            }
        },

        /**
         * Вспышка комментария
         * @param element
         */
        fastHighlight(element) {
            element.classList.add('fast-highlight');
            setTimeout(() => element.classList.remove('fast-highlight'), 800);
        },

        /**
         * Обновление комментария
         * @param comment
         * @param text
         * @returns {Promise<*>}
         */
        async updateComment(comment, text) {
            const res = await axios.patch(`/api/comments/${comment.id}`, {
                text: text
            })
            if (res.data.status === 200) {
                if (this.isCommentPage) {
                    await this.getComment(this.commentObject.id);
                    return res.data.status;
                } else {
                    await this.refresh(comment.postId);
                }
            }
        },

        /**
         * Удалить коммент
         * @param comment
         * @returns {Promise<void>}
         */
        async deleteComment(comment) {
            await axios.delete(`/api/comments/${comment.id}`);

            if (comment.id === this.commentObject.id && this.isCommentPage) {
                await this.refresh(comment.postId);
                router.back()
            } else if (comment.id !== this.commentObject.id && this.isCommentPage) {
                await this.getComment(this.commentObject.id);
            } else {
                const index = this.commentsList.findIndex(c => c.id === comment.id);
                if (index !== -1) {
                    this.commentsList.splice(index, 1);
                }
                await this.refresh(comment.postId);
            }
        },
    }
});
