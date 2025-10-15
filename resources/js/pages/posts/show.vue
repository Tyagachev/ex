<template>
    <div class="container overflow-y-auto">
        <transition name="fade">
            <div v-if="showNotification" class="notification">
                Ссылка скопирована
            </div>
        </transition>
        <button
            @click="goBack"
            class="mb-2 py-2.5 px-4 bg-orange-600 hover:bg-gray-500 text-sm text-white rounded-full cursor-pointer"
        >
            Назад
        </button>
        <div v-show="!postStore.loading">
            <div class="post">
                <div class="post-content">
                    <div class="flex justify-between">
                        <div class="post-header">
                            <div v-if="post.user?.id === user?.id">
                                <div class="flex items-center gap-2 ">
                                    <div
                                        class="w-8 h-8 rounded-full flex-shrink-0 grid place-items-center text-slate-900 font-bold"
                                        :style="{ background: avatar.avatarColor(post.user?.name) }"
                                        :title="post.user?.name"
                                    >
                                        {{ post.user?.name[0] }}
                                    </div>
                                    <span class="author">
                                    <p class="text-black pr-1 pl-1 bg-green-300">{{post.user.name}}</p>
                                </span>
                                </div>
                            </div>
                            <div v-else>
                                <div class="flex items-center gap-2 ">
                                    <div
                                        class="w-8 h-8 rounded-full flex-shrink-0 grid place-items-center text-slate-900 font-bold"
                                        :style="{ background: avatar.avatarColor(post.user?.name) }"
                                        :title="post.user?.name"
                                    >
                                        {{ post.user?.name[0] }}
                                    </div>
                                    <span class="author">
                                    <router-link :to="({name: 'posts.show', params: {id: post.user?.id}})" class="hover:underline cursor-pointer">
                                        {{ post.user?.name }}
                                    </router-link>
                                </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="circle"></div>
                                <span class="time">{{ post.createdAtHuman }}</span>
                            </div>
                            <div class="post-menu-container" @click.stop>
                                <button class="vote-btn" @click="toggleMenu(post.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="16px" height="16px" viewBox="0 0 100 20">
                                        <g id="Objects">
                                            <circle class="share str0" cx="10" cy="10" r="10"/>
                                            <circle class="share str0" cx="50" cy="10" r="10"/>
                                            <circle class="share str0" cx="90" cy="10" r="10"/>
                                        </g>
                                    </svg>
                                </button>
                                <div v-if="activeMenu === post.id" class="post-menu">
                                    <div v-if="post.user.id === user?.id">
                                        <button class="menu-item">
                                            <router-link :to="({name: 'posts.edit', params: {id:post.id}})">
                                                <span class="text-white">Редактировать</span>
                                            </router-link>
                                        </button>
                                        <button class="menu-item">
                                            <span class="text-white">Скрыть</span>
                                        </button>
                                        <button @click="postStore.destroyPost(post)" class="menu-item">
                                            <span class="text-white">Удалить</span>
                                        </button>
                                    </div>
                                    <div v-else-if="post.user.id !== user?.id || !user" class="post-menu">
                                        <button class="menu-item">
                                            <span class="text-white">Пожаловаться</span>
                                        </button>
                                    </div>
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
                                <img class="m-auto" :src="block.path">
                            </div>
                        </div>
                    </div>
                    <div class="post-footer">

                        <Panel
                            :item = post
                            :componentType = componentType
                            :bodyUrl = bodyUrl
                            @shownotificationmessage="showNotificationMessage"
                        />
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import {useRoute} from "vue-router";
import {useRouter} from "vue-router";
import {computed, onMounted, ref} from "vue";
import {usePostsStore} from "@/stores/posts.js";
import {useUserStore} from "@/stores/users.js";
import {useAvatarStore} from "@/stores/avatars.js";
import Panel from "@/сomponents/Panel/Panel.vue";

defineOptions({
    name: "Show"
})

onMounted( async ()=>{
     await postStore.getPost(route.params.id)
})

const postStore = usePostsStore();
const userStore = useUserStore();
const avatar = useAvatarStore();
const router = useRouter();
const route = useRoute();

const user = computed(() => userStore.u);
const post = computed(() => postStore.post);

const activeMenu = ref(null);

const componentType = 'post'
const bodyUrl = 'posts'
const showNotification = ref(false);
const notificationTimeout = ref(null);

const goBack = () => {
    router.back();
}

const toggleMenu = (postId) => {
    activeMenu.value = activeMenu.value === postId ? null : postId;
    if (activeMenu.value === postId) {
        document.addEventListener('click', closeMenuOnClickOutside);
    } else {
        document.removeEventListener('click', closeMenuOnClickOutside);
    }
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
//
</script>

<style scoped>

.comment-area {
    margin-top: 16px;
    overflow: hidden;
}

.textarea-container {
    position: relative;
    background: #2a2a2a;
    border-radius: 8px;
    border: 1px solid #3a3a3a;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.textarea-container:focus-within {
    border-color: #ff4500;
    box-shadow: 0 0 0 2px rgba(255, 69, 0, 0.2);
    outline: none;
}

.comment-textarea {
    width: 100%;
    background: transparent;
    border: none;
    color: #e0e0e0;
    font-size: 14px;
    padding: 12px 16px;
    resize: none;
    min-height: 44px;
    max-height: 200px;
    line-height: 1.5;
    overflow-y: hidden;
    scrollbar-width: none;
    font-family: inherit;

    outline: none;
    box-shadow: none;
    box-sizing: border-box;
}

.comment-textarea::-webkit-scrollbar {
    display: none;
}

.comment-textarea:focus {
    outline: none;
    border: none;
}

.comment-textarea::placeholder {
    color: #8a8a8a;
}

.comment-textarea::-webkit-input-placeholder {
    color: #8a8a8a;
}

.comment-textarea:-moz-placeholder {
    color: #8a8a8a;
    opacity: 1;
}

.comment-textarea::-moz-placeholder {
    color: #8a8a8a;
    opacity: 1;
}

.comment-textarea:-ms-input-placeholder {
    color: #8a8a8a;
}

.comment-textarea::-ms-input-placeholder {
    color: #8a8a8a;
}

.comment-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 12px;
    gap: 12px;
}

.time {
    font-weight: 100;
    font-size: 12px;
    color: #94a1a5;

}

.post {
//background: #1a1a1a;
    border-radius: 8px;
//border: 1px solid #2d2d2d;
    margin-bottom: 12px;
    display: flex;
    overflow: hidden;
//max-width: 800px;
///min-width: 425px;
}

.post-content {
    padding: 12px;
    flex: 1;
    word-wrap: break-word;
}

.post-header {
    display: flex;
    font-size: 12px;
    color: #b0b0b0;
    margin-bottom: 10px;
}

.post-body {
    font-size: 14px;
    line-height: 1.6;
    color: #d0d0d0;
    margin-bottom: 12px;
    overflow-wrap: break-word;
    word-break: break-word;
    hyphens: auto;
}

.share_img {
    fill: none;
    stroke-width: 5;
    stroke: #9ca3af;
}

.comments_count {
    padding: 0 0 0 5px;
}

.subreddit {
    font-weight: 700;
    color: #d7dadc;
}

.post-title {
    font-size: 18px;
    font-weight: 500;
    margin-bottom: 10px;
    color: #ffffff;
    overflow-wrap: break-word;
    word-break: break-word;
    hyphens: auto;
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

</style>
