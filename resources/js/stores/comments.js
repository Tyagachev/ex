import {defineStore} from "pinia";

export const useCommentsStore = defineStore('comment', {
    state: () => ({
        commentsList: {},
        editArea: false,
        editText: '',
        responseCommentText: '',
        showCommentText: false,
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
            this.commentsList = {};
            this.commentsList = comments;
        },

        /**
         *
         * @param post
         * @returns {Promise<void>}
         */
        async submitComment(post) {
            console.log(post)
            const res = await axios.post('/api/comments', {
                text: this.commentText,
                postId: post.id
            })
            this.commentText = '';
        },

        /**
         *
         * @param comment
         * @returns {Promise<void>}
         */
        async sendReplyComment(comment) {
            if (!this.replyText.trim()) return;
            const res = axios.post('/api/comments', {
                postId: comment.postId,
                parentId: comment.id,
                text: this.replyText,
                replyId: comment.user?.id || null,
            })

        },

        /**
         * Просмотр комментария
         * @param comment
         * @returns {Promise<void>}
         */
        async showEdit(comment) {
            if (this.editArea) {
                const res = await axios.get(`/api/comments/${comment.id}/edit`);
                this.editText = res.data.text || res.data;
            }
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
            }
        },

        /**
         *
         * @param userId
         * @param replyUserId
         * @param parent
         * @returns {Promise<void>}
         */
        async getCommentText(userId, replyUserId, parent) {
            const res = await axios.post('/api/comments/text', {
                userId: userId.id,
                replyUserId: replyUserId?.id || null,
                parentId: parent || null
            })
            this.responseCommentText = res.data.text;
            this.showCommentText = !this.showCommentText;
        },
    }
});
