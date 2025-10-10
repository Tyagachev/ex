<template>
    <div class="container flex-1 align-center overflow-y-auto">
        <transition name="fade">
            <div v-if="showNotification" class="notification">
                Ссылка скопирована
            </div>
        </transition>

        <!-- Модальное окно для изображения -->
        <transition name="modal">
            <div v-if="showImageModal" class="image-modal" @click="closeImageModal">
                <div class="modal-content" @click.stop>
                    <button class="modal-close" @click="closeImageModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                    <img :src="currentImage" :alt="currentImageAlt" class="modal-image">
                </div>
            </div>
        </transition>

        <!-- Лента постов -->
        <div class="posts">
            <div>
                <div v-if="!posts" class="text-center">
                    <h3 class="text-white">Нет постов для отображения</h3>
                    <p class="text-white">Создайте первый пост, чтобы начать обсуждение</p>
                </div>


                <div v-for="(post, index) in posts" :key="post.id">
                    <div class="post">
                        <!-- Основное содержимое поста -->
                        <div class="post-content">
                            <div class="flex justify-between">
                                <div class="post-header">
                                    <div>
                                        <div class="flex items-center">
                                            <div
                                                class=" mr-2 w-8 h-8 rounded-full flex-shrink-0 grid place-items-center text-slate-900 font-bold"
                                                :style="{ background: avatarColor(post.user?.name[0]) }"
                                                :title="post.user.name"
                                            >
                                                {{post.user?.name[0]}}
                                            </div>
                                            <div v-if="post.user.id === authUser?.id">
                                                <span :class="['text-sm text-black pr-1 pl-1 bg-green-300 hover:underline cursor-pointer']">{{ post.user.name }}</span>
                                            </div>
                                            <div v-else>
                                                <span class="author">
                                                    <router-link :to="{name: 'posts.create'}" class="hover:underline cursor-pointer">{{ post.user.name }}</router-link>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="circle"></div>
                                        <span class="time">{{ post.createdAtHuman }}</span>
                                    </div>
                                </div>
                                <div class="post-menu-container" @click.stop>
                                    <button class="vote-btn" @click="toggleMenu(post.id)">
                                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="16px" height="16px" viewBox="0 0 100 20">
                                            <g id="Objects">
                                                <circle class="fil0 str0" cx="10" cy="10" r="10"/>
                                                <circle class="fil0 str0" cx="50" cy="10" r="10"/>
                                                <circle class="fil0 str0" cx="90" cy="10" r="10"/>
                                            </g>
                                        </svg>
                                    </button>
                                    <div v-if="activeMenu === post.id" class="post-menu">
                                        <div v-if="post.user.id === authUser?.id">
                                            <button class="menu-item">
                                                <div class="mr-1 text-white"><i class="fa fa-pencil" aria-hidden="true"></i></div>
                                                <router-link :to="{name: 'posts.edit', params: {id: post.id}}">
                                                    <span class="text-white">Редактировать</span>
                                                </router-link>
                                            </button>
                                            <button class="menu-item">
                                                <div class="mr-1 text-white" ><i class="fa fa-eye-slash" aria-hidden="true"></i></div>
                                                <div class="text-white">Скрыть</div>
                                            </button>
                                            <button @click.prevent="destroyPost.destroyPost(post)" class="menu-item">
                                                <div class="mr-1 text-red-500"><i class="fa fa-trash" aria-hidden="true"></i></div>
                                                <div class="text-red-500">Удалить</div>
                                            </button>
                                        </div>
                                        <div v-else-if="post.user.id !== authUser?.id || !authUser" class="post-menu">
                                            <button class="menu-item">
                                                <span class="text-white">Пожаловаться</span>
                                            </button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            <h3 class="post-title">{{ post.title }}</h3>
                            <div>
                                <div v-for="(block, index) in post.blocks" :key="index">
                                    <div v-if="block.type === 'text'">
                                        <div v-html="block.content" class="post-body"></div>
                                    </div>
                                    <div class="mt-2 mb-2" v-else-if="block.type === 'image'">
                                        <img class="m-auto cursor-pointer" :src="block.path" @click="openImageModal(block.path, post.title)">
                                    </div>
                                </div>
                            </div>
                            <div class="post-footer">
                                <!--Кнопки голосования-->
                                <div v-if="user?.id !== post.user.id" class="vote-panel flex items-center">
                                    <button @click.prevent="upVote(post)" class="vote-btn">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 127 124.848">
                                                    <path :class="[getUserVote(post) === 1 ? 'v-up' : 'vote-icon up']" stroke="currentColor" stroke-width="6" d="M4.408 73.59l31.855 0c1.105,0 2.006,0.901 2.006,2.006l0 29.189c0,11.05 9.018,20.063 20.064,20.063l10.333 0c11.047,0 20.064,-9.017 20.064,-20.063l0 -29.189c0,-1.105 0.901,-2.006 2.006,-2.006l31.909 0c0.801,0 1.487,-0.439 1.822,-1.167 0.336,-0.728 0.224,-1.535 -0.297,-2.144l-58.565 -68.511c-0.393,-0.459 -0.912,-0.7 -1.516,-0.703 -0.604,-0.003 -1.125,0.233 -1.522,0.689l-59.672 68.511c-0.528,0.607 -0.647,1.417 -0.313,2.149 0.333,0.733 1.022,1.176 1.826,1.176z"/>
                                                </svg>
                                            </span>
                                    </button>
                                    <div class="vote-count px-2">
                                        <p :class="[post.totalVotes === 0 ? 'total_white' : post.totalVotes > 0 ? 'total_green' : 'total_red']">{{ formatCount(post.totalVotes) }}</p>
                                    </div>
                                    <button @click.prevent="downVote(post)" class="vote-btn">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 127 124.848">
                                                    <path :class="[getUserVote(post) === -1 ? 'v-down' : 'vote-icon down']" transform="translate(0,124.848) scale(1,-1)" stroke="currentColor" stroke-width="6" d="M4.408 73.59l31.855 0c1.105,0 2.006,0.901 2.006,2.006l0 29.189c0,11.05 9.018,20.063 20.064,20.063l10.333 0c11.047,0 20.064,-9.017 20.064,-20.063l0 -29.189c0,-1.105 0.901,-2.006 2.006,-2.006l31.909 0c0.801,0 1.487,-0.439 1.822,-1.167 0.336,-0.728 0.224,-1.535 -0.297,-2.144l-58.565 -68.511c-0.393,-0.459 -0.912,-0.7 -1.516,-0.703 -0.604,-0.003 -1.125,0.233 -1.522,0.689l-59.672 68.511c-0.528,0.607 -0.647,1.417 -0.313,2.149 0.333,0.733 1.022,1.176 1.826,1.176z"/>
                                                </svg>
                                            </span>
                                    </button>
                                </div>
                                <div v-else class="vote-panel flex items-center">
                                    <button  class="no_vote-btn">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 127 124.848">
                                                    <path :class="['vote-icon']" stroke="currentColor" stroke-width="6" d="M4.408 73.59l31.855 0c1.105,0 2.006,0.901 2.006,2.006l0 29.189c0,11.05 9.018,20.063 20.064,20.063l10.333 0c11.047,0 20.064,-9.017 20.064,-20.063l0 -29.189c0,-1.105 0.901,-2.006 2.006,-2.006l31.909 0c0.801,0 1.487,-0.439 1.822,-1.167 0.336,-0.728 0.224,-1.535 -0.297,-2.144l-58.565 -68.511c-0.393,-0.459 -0.912,-0.7 -1.516,-0.703 -0.604,-0.003 -1.125,0.233 -1.522,0.689l-59.672 68.511c-0.528,0.607 -0.647,1.417 -0.313,2.149 0.333,0.733 1.022,1.176 1.826,1.176z"/>
                                                </svg>
                                            </span>
                                    </button>
                                    <div class="vote-count px-2">
                                        <p :class="[post.totalVotes === 0 ? 'total_white' : post.totalVotes > 0 ? 'total_green' : 'total_red']">{{ formatCount(post.totalVotes) }}</p>
                                    </div>
                                    <button class="no_vote-btn">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 127 124.848">
                                                    <path :class="['vote-icon']" transform="translate(0,124.848) scale(1,-1)" stroke="currentColor" stroke-width="6" d="M4.408 73.59l31.855 0c1.105,0 2.006,0.901 2.006,2.006l0 29.189c0,11.05 9.018,20.063 20.064,20.063l10.333 0c11.047,0 20.064,-9.017 20.064,-20.063l0 -29.189c0,-1.105 0.901,-2.006 2.006,-2.006l31.909 0c0.801,0 1.487,-0.439 1.822,-1.167 0.336,-0.728 0.224,-1.535 -0.297,-2.144l-58.565 -68.511c-0.393,-0.459 -0.912,-0.7 -1.516,-0.703 -0.604,-0.003 -1.125,0.233 -1.522,0.689l-59.672 68.511c-0.528,0.607 -0.647,1.417 -0.313,2.149 0.333,0.733 1.022,1.176 1.826,1.176z"/>
                                                </svg>
                                            </span>
                                    </button>
                                </div>
                                <button @click="postShow(post)" class="footer-btn ">
                                    <span class="footer-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="16px" height="16px" version="1.1" viewBox="0 0 60 52.008">
                                            <g id="Objects">
                                                <path class="comment_img" d="M30.909 0c14.409,0 26.091,11.682 26.091,26.091 0,13.271 -9.91,24.229 -22.733,25.876 -2.323,0 -4.428,0.093 -6.713,0.001 -9.185,0 -18.369,0 -27.554,0 3.372,-3.373 6.744,-6.745 10.116,-10.117 -3.324,-4.379 -5.298,-9.838 -5.298,-15.76 0,-14.409 11.682,-26.091 26.091,-26.091z"/>
                                            </g>
                                        </svg>
                                    </span>

                                    <div class="comments_count">
                                        {{ formatCount(post.commetsCount) }}
                                    </div>
                                </button>
                                <div class="footer-btn">
                                    <span class="footer-icon">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </span>
                                    {{ formatCount(post.viewCount) }}
                                </div>
                                <button class="footer-btn" @click="copyLink(post.id)">
                                    <span class="footer-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" version="1.1"
                                             viewBox="0 0 63 63">
                                         <g id="Objects">
                                          <metadata id="CorelCorpID_0Corel-Layer"/>
                                          <circle class="fil0 str0" cx="51.582" cy="11.418" r="11.418"/>
                                          <circle class="fil0 str0" cx="51.582" cy="51.583" r="11.418"/>
                                          <circle class="fil0 str0" cx="11.417" cy="31.5" r="11.418"/>
                                          <path class="fil0 str1" d="M41.368 16.525l-19.737 9.868m0 10.214l19.737 9.869"/>
                                         </g>
                                        </svg>
                                    </span>
                                    {{ formatCount(post.shareCount) }}
                                </button>
                                <button class="footer-btn">
                                    <span class="footer-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="16px" height="16px" version="1.1"
                                             viewBox="0 0 200 252.391">
                                         <g id="Objects">
                                          <path class="fil0 str0" d="M10 0l180 0c5.508,0 10,4.493 10,10l0 232.354c0,3.7 -1.851,6.876 -5.07,8.7 -3.219,1.824 -6.894,1.78 -10.069,-0.121l-79.88 -47.849c-3.251,-1.948 -7.042,-1.945 -10.29,0.007l-79.54 47.804c-3.173,1.907 -6.852,1.956 -10.075,0.133 -3.223,-1.823 -5.076,-5.001 -5.076,-8.704l0 -232.324c0,-5.507 4.492,-10 10,-10z"/>
                                         </g>
                                        </svg>
                                    </span>
                                    Сохранить
                                </button>
                            </div>
                            </div>
                        </div>
                    <!-- Горизонтальная линия между постами -->
                    <div v-if="index < posts.length - 1" class="post-divider"></div>
                    </div>
            </div>
        </div>
        </div>
</template>

<script setup>
import {computed, defineOptions, onMounted, reactive, ref} from "vue";
import axios from "axios";
import {useStore} from "vuex";
import {useRouter} from "vue-router";
import useAuth from "@/mixins/auth.js";
import {usePostsStore} from "@/stores/posts.js";
import {useUserStore} from "@/stores/users.js";

defineOptions({
    name: "index"
})

onMounted(async () => {
    await postStore.getPosts();
});

const posts = computed(() => postStore.allPosts);
const user = computed(() => userStore.u)

const store = useStore();
const router = useRouter();

const postStore = usePostsStore();
const userStore = useUserStore();
const destroyPost = usePostsStore();

const authUser = reactive(user);
const activeMenu = ref(null);
const url = window.location.origin;
const showNotification = ref(false);
const notificationTimeout = ref(null);
const showImageModal = ref(false);
const currentImage = ref("")
const currentImageAlt = ref("")

const avatarColor = (author) => {
    const str = (author || "u").toLowerCase();
    let hash = 0;
    for (let i = 0; i < str.length; i++)
        hash = str.charCodeAt(i) + ((hash << 5) - hash);
    const hue = Math.abs(hash) % 360;
    return `hsl(${hue} 70% 60%)`;
};

const closeImageModal = () => {
    showImageModal.value = false;
    currentImage.value = '';
    currentImageAlt.value = '';
    document.body.style.overflow = '';
};

const toggleMenu = (postId) => {
    activeMenu.value = activeMenu.value === postId ? null : postId;
    if (activeMenu.value === postId) {
        document.addEventListener('click', closeMenuOnClickOutside);
    } else {
        document.removeEventListener('click', closeMenuOnClickOutside);
    }
}

const copyLink = async (id) => {
    const link = `${url}/posts/${id}`;

    navigator.clipboard.writeText(link);

    showNotificationMessage();

    await axios.post('/api/share/store', {
        id: id,
        type: 'post'
    })
    await fetchPosts();
}

const showNotificationMessage = () => {
    if (notificationTimeout) {
        clearTimeout(notificationTimeout);
    }

    showNotification.value = true;

    notificationTimeout.value = setTimeout(() => {
        showNotification.value = false;
    }, 2000);
}

const closeMenuOnClickOutside = () => {
    activeMenu.value = null;
    document.removeEventListener('click', closeMenuOnClickOutside);
}

const upVote = async (post) => {
    await axios.post('/api/vote/store', {
        id: post.id,
        vote: -1,
        type: 'post'
    });
    await fetchPosts();
}

const formatCount = (votes) => {
    if (votes >= 1000) {
        return (votes / 1000).toFixed(1) + 'k';
    }
    return votes;
}

const downVote = async (post) => {
    await axios.post(route('/api/vote/store'), {
        id: post.id,
        vote: -1,
        type: 'post'
    })
    await fetchPosts();
}

const getUserVote = (post) => {
    if (!authUser) return 0;
    const voteObj = post.votes.find(vote => vote.user_id === authUser.id);
    return voteObj ? voteObj.vote : 0;
}

const postShow = (post) => {
    router.push({
        name: 'posts.show',
        params: {id: post.id}
    });
}

</script>

<style scoped>

.notification {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: rgba(30, 29, 29, 0.85);
    color: white;
    padding: 16px 24px;
    border-radius: 30px;
    z-index: 1000;
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.time {
    font-weight: 100;
    font-size: 12px;
    color: #94a1a5;
}

.circle {
    width: 4px;
    border: 1px solid gray;
    height: 4px;
    border-radius: 50px;
    background-color: white;
}

.up:hover {
    fill: #2bf10d;
}

.down:hover {
    fill: #f80707;
}

.v-up {
    fill: #2bf10d!important;
    stroke: #2bf10d!important;
}

.v-down {
    fill: #f80707!important;
    stroke: #f80707!important;
}

.fil0 {
    fill: #bebbbb;
    stroke: #bebbbb;
}

.container {
    width: 1000px;
    margin: 0 auto;
}

.post {
    border-radius: 8px;
    margin-bottom: 12px;
    display: flex;
    overflow: hidden;
    background-color: #8080800d;
}

.post-title {
    font-size: 18px;
    font-weight: 500;
    margin-bottom: 10px;
    color: #ffffff;
    word-wrap: break-word;
}

.post-image {
    max-width: 100%;
    max-height: 500px;
    border-radius: 6px;
    margin-bottom: 12px;
    border: 1px solid #333;
}

.post-footer {
    display: flex;
    color: #9a9a9a;
    font-size: 12px;
    font-weight: 700;
}

.post:hover {
    background-color: #1f1f1f;
    cursor: pointer;
}

.post-content {
    width: 500px;
    padding: 12px;
    flex: 1;
}

.post-header {
    display: flex;
    font-size: 12px;
    color: #b0b0b0;
    margin-bottom: 10px;
}
.post-body {
    font-size: 15px;
    line-height: 1.6;
    color: #d0d0d0;
    margin-bottom: 12px;
    word-wrap: break-word;
}

.vote-panel {
    padding: 5px;
    background: #2a2a2a;
    border-radius: 18px;
    margin-right: 6px;
}

.vote-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 20px;
}

.no_vote-btn {
    background: none;
    border: none;
    padding: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 20px;
}

.vote-btn:hover {
    background-color: #383737;
}

.vote-icon {
    stroke: #9ca3af;
    stroke-width: 8;
    fill: none;
    transition: all 0.2s ease;
}

.comment_img {
    fill: none;
    stroke-width: 5;
    stroke: #9ca3af;
}

.save_img {
    fill: none;
}

.share_img {
    fill: none;
    stroke-width: 5;
    stroke: #9ca3af;
}

.comments_count {
    padding: 0 0 0 5px;
}

.vote-btn:hover .up {
    fill: #2bf10d;
    stroke: #2bf10d;
}

.vote-btn:hover .down {
    fill: #f80707;
    stroke: #f80707;
}

.vote-btn.active .up {
    stroke: #2bf10d;
    fill: rgba(43, 241, 13, 0.1);
}

.vote-btn.active .down {
    stroke: #f80707;
    fill: rgba(248, 7, 7, 0.1);
}

.vote-count {
    font-weight: 700;
    font-size: 12px;
    margin: 4px 0;
    color: #d7dadc;
    text-align: center;
    width: 37px;
}

.positive { color: #ff4500; }
.negative { color: #7193ff; }

.subreddit {
    font-weight: 700;
    color: #d7dadc;
}
.footer-btn {
    display: flex;
    align-items: center;
    padding: 10px 10px;
    background: none;
    border: none;
    border-radius: 18px;
    cursor: pointer;
    margin-right: 6px;
    color: #b0b0b0;
    background: #2a2a2a;
}

.footer-btn:hover {
    background: #383737;
}

.footer-icon {
    margin-right: 6px;
    font-size: 16px;
}

.post-menu-container {
    position: relative;
    margin-left: auto;
}

.post-menu {
    position: absolute;
    right: 0;
    top: 100%;
    background: #2a2a2a;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 10;
    width: auto;
}

.menu-item {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 8px 12px;
    text-align: left;
    background: none;
    border: none;
    cursor: pointer;
    transition: background-color 0.2s;
}

.menu-item:hover {
    background-color: #f3f4f6;
}

.menu-item i {
    margin-right: 8px;
    width: 16px;
}

.post-header {
    position: relative;
    display: flex;
    align-items: center;
}

.post-divider {
    height: 2px;
    background-color: #2d2d2d;
    margin: 16px 0;
    width: 100%;
}
</style>
