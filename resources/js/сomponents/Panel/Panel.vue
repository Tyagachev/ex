<template>
    <div v-if="user?.id !== item.user?.id" class="vote-panel flex items-center">
        <button @click.prevent="vote.upVote(item)" class="vote-btn">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 127 124.848">
                    <path
                        :class="[vote.getUserVote(item, user) === 1 ? 'v-up' : 'vote-icon up']"
                        stroke="currentColor" stroke-width="6"
                        d="M4.408 73.59l31.855 0c1.105,0 2.006,0.901 2.006,2.006l0 29.189c0,11.05 9.018,20.063 20.064,20.063l10.333 0c11.047,0 20.064,-9.017 20.064,-20.063l0 -29.189c0,-1.105 0.901,-2.006 2.006,-2.006l31.909 0c0.801,0 1.487,-0.439 1.822,-1.167 0.336,-0.728 0.224,-1.535 -0.297,-2.144l-58.565 -68.511c-0.393,-0.459 -0.912,-0.7 -1.516,-0.703 -0.604,-0.003 -1.125,0.233 -1.522,0.689l-59.672 68.511c-0.528,0.607 -0.647,1.417 -0.313,2.149 0.333,0.733 1.022,1.176 1.826,1.176z"/>
                </svg>
            </span>
        </button>
        <div class="vote-count px-2">
            <p :class="[item.totalVotes === 0 ? 'total_white' : item.totalVotes > 0 ? 'total_green' : 'total_red']">
                {{ count.formatCount(item.totalVotes) }}</p>
        </div>
        <button @click.prevent="vote.downVote(item)" class="vote-btn">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 127 124.848">
                    <path
                        :class="[vote.getUserVote(item, user) === -1 ? 'v-down' : 'vote-icon down']"
                        transform="translate(0,124.848) scale(1,-1)"
                        stroke="currentColor" stroke-width="6"
                        d="M4.408 73.59l31.855 0c1.105,0 2.006,0.901 2.006,2.006l0 29.189c0,11.05 9.018,20.063 20.064,20.063l10.333 0c11.047,0 20.064,-9.017 20.064,-20.063l0 -29.189c0,-1.105 0.901,-2.006 2.006,-2.006l31.909 0c0.801,0 1.487,-0.439 1.822,-1.167 0.336,-0.728 0.224,-1.535 -0.297,-2.144l-58.565 -68.511c-0.393,-0.459 -0.912,-0.7 -1.516,-0.703 -0.604,-0.003 -1.125,0.233 -1.522,0.689l-59.672 68.511c-0.528,0.607 -0.647,1.417 -0.313,2.149 0.333,0.733 1.022,1.176 1.826,1.176z"/>
                </svg>
            </span>
        </button>
    </div>
    <div v-else class="vote-panel flex items-center">
        <button class="no_vote-btn">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 127 124.848">
                    <path :class="['vote-icon']" stroke="currentColor" stroke-width="6"
                          d="M4.408 73.59l31.855 0c1.105,0 2.006,0.901 2.006,2.006l0 29.189c0,11.05 9.018,20.063 20.064,20.063l10.333 0c11.047,0 20.064,-9.017 20.064,-20.063l0 -29.189c0,-1.105 0.901,-2.006 2.006,-2.006l31.909 0c0.801,0 1.487,-0.439 1.822,-1.167 0.336,-0.728 0.224,-1.535 -0.297,-2.144l-58.565 -68.511c-0.393,-0.459 -0.912,-0.7 -1.516,-0.703 -0.604,-0.003 -1.125,0.233 -1.522,0.689l-59.672 68.511c-0.528,0.607 -0.647,1.417 -0.313,2.149 0.333,0.733 1.022,1.176 1.826,1.176z"/>
                </svg>
            </span>
        </button>
        <div class="vote-count px-2">
            <p :class="[item.totalVotes === 0 ? 'total_white' : item.totalVotes > 0 ? 'total_green' : 'total_red']">
                {{ count.formatCount(item.totalVotes) }}</p>
        </div>
        <button class="no_vote-btn">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 127 124.848">
                    <path :class="['vote-icon']"
                          transform="translate(0,124.848) scale(1,-1)"
                          stroke="currentColor" stroke-width="6"
                          d="M4.408 73.59l31.855 0c1.105,0 2.006,0.901 2.006,2.006l0 29.189c0,11.05 9.018,20.063 20.064,20.063l10.333 0c11.047,0 20.064,-9.017 20.064,-20.063l0 -29.189c0,-1.105 0.901,-2.006 2.006,-2.006l31.909 0c0.801,0 1.487,-0.439 1.822,-1.167 0.336,-0.728 0.224,-1.535 -0.297,-2.144l-58.565 -68.511c-0.393,-0.459 -0.912,-0.7 -1.516,-0.703 -0.604,-0.003 -1.125,0.233 -1.522,0.689l-59.672 68.511c-0.528,0.607 -0.647,1.417 -0.313,2.149 0.333,0.733 1.022,1.176 1.826,1.176z"/>
                </svg>
            </span>
        </button>
    </div>
    <button @click="postShow(item)" class="footer-btn ">
        <span class="footer-icon">
            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="16px" height="16px" version="1.1" viewBox="0 0 60 52.008">
                <g id="Objects">
                    <path class="comment_img" d="M30.909 0c14.409,0 26.091,11.682 26.091,26.091 0,13.271 -9.91,24.229 -22.733,25.876 -2.323,0 -4.428,0.093 -6.713,0.001 -9.185,0 -18.369,0 -27.554,0 3.372,-3.373 6.744,-6.745 10.116,-10.117 -3.324,-4.379 -5.298,-9.838 -5.298,-15.76 0,-14.409 11.682,-26.091 26.091,-26.091z"/>
                </g>
            </svg>
        </span>

        <div class="comments_count">
            {{ count.formatCount(item.commetsCount) }}
        </div>
    </button>
    <div class="footer-btn">
        <span class="footer-icon">
            <i class="fa fa-eye" aria-hidden="true"></i>
        </span>
        {{ count.formatCount(item.viewCount) }}
    </div>
    <button class="footer-btn" @click="copyLink(item, bodyUrl, componentType)">
        <span class="footer-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" version="1.1" viewBox="0 0 63 63">
                <g id="Objects">
                    <metadata id="CorelCorpID_0Corel-Layer"/>
                    <circle class="dot_color str0" cx="51.582" cy="11.418" r="11.418"/>
                    <circle class="dot_color str0" cx="51.582" cy="51.583" r="11.418"/>
                    <circle class="dot_color str0" cx="11.417" cy="31.5" r="11.418"/>
                    <path class="dot_color str1" d="M41.368 16.525l-19.737 9.868m0 10.214l19.737 9.869"/>
                </g>
            </svg>
        </span>
        {{ count.formatCount(item.shareCount) }}
    </button>
    <button class="footer-btn">
        <span class="footer-icon">
            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="16px" height="16px" version="1.1" viewBox="0 0 200 252.391"><g id="Objects">
                <path class="dot_color str0" d="M10 0l180 0c5.508,0 10,4.493 10,10l0 232.354c0,3.7 -1.851,6.876 -5.07,8.7 -3.219,1.824 -6.894,1.78 -10.069,-0.121l-79.88 -47.849c-3.251,-1.948 -7.042,-1.945 -10.29,0.007l-79.54 47.804c-3.173,1.907 -6.852,1.956 -10.075,0.133 -3.223,-1.823 -5.076,-5.001 -5.076,-8.704l0 -232.324c0,-5.507 4.492,-10 10,-10z"/>
            </g>
            </svg>
        </span>
        Сохранить
    </button>
</template>

<script setup>

import {useRouter} from "vue-router";
import axios from "axios";
import {useVotesStore} from "@/stores/votes.js";
import {useCountsStore} from "@/stores/counts.js";
import {useUserStore} from "@/stores/users.js";
import {computed} from "vue";

defineOptions({
    name: "Panel"
})

defineProps({
    item: {
        type: Object,
        default: () => {}
    },
    componentType: {
        type: String
    },
    bodyUrl: {
        type: String
    }
})

const user = computed(() => userStore.u)
const userStore = useUserStore();
const vote = useVotesStore();
const count = useCountsStore();
const router = useRouter();

const emits = defineEmits(['shownotificationmessage'])
const postShow = (post) => {
    router.push({name: 'posts.show', params: { id: post.id }});
}

const copyLink = async (item, bodyUrl, componentType) => {

    const url = window.location.origin;
    const link = `${url}/${bodyUrl}/${item.id}`;

    navigator.clipboard.writeText(link);
    item.shareCount++;

    emits('shownotificationmessage');

    await axios.post('/api/share', {
        id: item.id,
        type: componentType
    })
}
</script>

<style scoped>
.dot_color {
    fill: #bebbbb;
    stroke: #bebbbb;
}
</style>
