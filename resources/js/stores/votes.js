import axios from "axios";
import {defineStore} from "pinia";
import {usePostsStore} from "@/stores/posts.js";
import NProgress from "nprogress";

export const useVotesStore = defineStore('votes',{
    state: () => ({
        obj: null
    }),
    actions: {
        async upVote(post) {
            const res = await axios.post('/api/votes', {
                id: post.id,
                vote: 1,
                type: 'post'
            });
            if (res.status === 200) {
                await this.refreshPosts(post);
            }
        },

        async downVote(post) {
            const res = await axios.post('/api/votes', {
                id: post.id,
                vote: -1,
                type: 'post'
            })
            if (res.status === 200) {
                await this.refreshPosts(post);
            }
        },

        getUserVote(post, user) {
            if (!user || !post || !Array.isArray(post.votes)) return 0;
            const voteObj = post.votes.find(vote => vote.user_id === user.id);
            return voteObj ? voteObj.vote : 0;
        },

        async refreshPosts(post) {
            const postsStore  = usePostsStore();
            if (postsStore.posts.length) {
                await postsStore.getPosts();
            } else if (!postsStore.posts.length && postsStore.postShow) {
                await postsStore.getPost(post.id);
            }
        },
    }
});
