import {defineStore} from "pinia";
import axios from "axios";

export const useCommentsStore = defineStore('comment', {
    state: () => ({
        commentsList: [],
        activeMenu: null,
        responseCommentText: '',
        showReply: false,
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
                const res = await axios.post('/api/comments', {
                    postId: comment.postId,
                    parentId: comment.id,
                    text: this.replyText,
                    replyId: comment.user?.id || null,
                });
                const id = res.data.post.id
                await this.refresh(id)
                /*не уверен что это будет удобно
                const newCommentId = res.data.commentId;
                this.getIdComment(newCommentId);*/
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
            console.log(res.data);
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
                element.scrollIntoView({ behavior: 'auto', block: 'end' });
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
         * Удалить коммент
         * @param comment
         * @returns {Promise<void>}
         */
        async deleteComment(comment) {
            const res = await axios.delete(`/api/posts/${comment.postId}/comments/${comment.id}`);
            if (res.status === 200) {
                const index = this.commentsList.findIndex(c => c.id === comment.id);
                if (index !== -1) {
                    this.commentsList.splice(index, 1);
                }
                await this.refresh(comment.postId)
            }
        },
    }
});
