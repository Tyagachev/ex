<template>
    <div v-if="!postStore.loading" class="container">

        <transition name="fade">
            <div v-if="showNotification" class="notification">
                Ссылка скопирована
            </div>
        </transition>
        <div>
            <div class="wrapper">
                <button
                    @click="goBack"
                    class="px-2.5 ml-3 py-1.5 mb-2 bg-orange-600  hover:bg-gray-500 text-sm text-white rounded-full cursor-pointer">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </button>
            <div class="post-show">
                <div class="post-content">
                    <div>
                        <div class="post-header">
                            <div v-if="post.user?.id === user?.id">
                                <div class="flex items-center gap-2 ">
                                    <div
                                        class="w-8 h-8 rounded-full  flex-shrink-0 grid place-items-center text-slate-900 font-bold"
                                        :style="{ background: avatar.avatarColor(post.user?.name) }"
                                        :title="post.user?.name"
                                    >
                                        {{ post.user?.name[0].toUpperCase() }}
                                    </div>
                                    <span class="author">
                                    <p class="text-black pr-1 pl-1 rounded-md bg-green-300">{{post.user?.name}}</p>
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
                                    <span  class="author">
                                    <router-link  :to="({name: 'posts.show', params: {id: post.user?.id}})" class="hover:underline cursor-pointer">
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
                                <button class="vote-btn rotate-90" @click="toggleMenu(post.id)">
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
                                        <button v-if="post.id" class="menu-item">
                                            <router-link :to="({name: 'posts.edit', params: {id:post.id}})">
                                                <span class="text-white text-sm">Редактировать</span>
                                            </router-link>
                                        </button>
                                        <button class="menu-item">
                                            <span class="text-white text-sm">Скрыть</span>
                                        </button>
                                        <button @click="postStore.destroyPostFromShowPost(post)" class="menu-item">
                                            <span class="text-red-500 text-sm">Удалить</span>
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
                    {{post.commetsCount}}
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
                <div class="post-divider"></div>
            <div class="post-comments">
                <!--Textarea-->
                <div v-if="user" class="comment-area">
                    <div class="textarea-container">
                    <textarea
                        ref="textarea"
                        class="comment-textarea"
                        :placeholder="placeholder"
                        v-model="commentStore.commentText"
                        @input="autoResize"
                        rows="1"
                    ></textarea>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <div class="text-xs text-slate-400 ml-2" :class="{ 'text-red-500': commentStore.commentText.length > 3000 }">
                            {{ commentStore.commentText.length }}/3000
                        </div>
                        <div class="comment-actions">
                            <button class="cancel-button text-sm" @click="clearText">Отмена</button>
                            <button
                                class="submit-button text-sm"
                                @click.prevent="commentStore.submitComment(post)"
                                :disabled="!commentStore.commentText || commentStore.commentText.length > 3000"
                            >
                                Отправить
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <router-link :to="{name: 'login.page'}"><p class="text-blue-400">Авторизируйтесь чтобы оставлять комментарии :)</p></router-link>
                </div>
                <div class="my-5">
                    <CommentNote
                        v-for="comment in commentStore.comments"
                        :key="comment.id"
                        :comment="comment"
                        :depth="0"
                        :active-comment-menu="activeCommentMenu"
                        @update:active-comment-menu="activeCommentMenu = $event"
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
import Panel from "@/components/Panel/Panel.vue";
import CommentNote from "@/components/Comment/CommentNote.vue";
import {useCommentsStore} from "@/stores/comments.js";

defineOptions({
    name: "Show"
})

const props = defineProps({
    post: {
        type: Object
    }
})
onMounted( async () => {
    if (props.post) {
        postStore.loading = true
        await postStore.getPost(props.post.postId)
    } else {
        await postStore.getPost(route.params.id)
    }
})

const postStore = usePostsStore();
const commentStore = useCommentsStore();
const userStore = useUserStore();
const avatar = useAvatarStore();
const router = useRouter();
const route = useRoute();

const user = computed(() => userStore.user);
const post = computed(() => postStore.post);
const activeMenu = ref(null);

const componentType = 'post'
const bodyUrl = 'posts'
const placeholder = 'Напишите комментарий...'

const activeCommentMenu = ref(null)
const showNotification = ref(false);
const notificationTimeout = ref(null);

const textarea = ref(null);

const goBack = () => {
    router.back();
}
/**
 * Открытия меню действия с постом
 * @param postId
 */
const toggleMenu = (postId) => {
    activeMenu.value = activeMenu.value === postId ? null : postId;
    if (activeMenu.value === postId) {
        document.addEventListener('click', closeMenuOnClickOutside);
    } else {
        document.removeEventListener('click', closeMenuOnClickOutside);
    }
}

/**
 * Сообщение о копировании ссылки
 */
const showNotificationMessage = () => {
    if (notificationTimeout) {
        clearTimeout(notificationTimeout);
    }

    showNotification.value = true;

    notificationTimeout.value = setTimeout(() => {
        showNotification.value = false;
    }, 2000);
}

/**
 * Закрытия меню действия с постом
 * (клик в любом месте экрана)
 */
const closeMenuOnClickOutside = () => {
    activeMenu.value = null;
    document.removeEventListener('click', closeMenuOnClickOutside);
}

/**
 * Очистка поля текста комментария
 */
const clearText = () => {
    textarea.value.style.height = 'auto';
    commentStore.commentText = '';
}

/**
 * Изменения размера textarea
 */
const autoResize = () => {
    if (!textarea.value) return;

    textarea.value.style.height = 'auto';
    const newHeight = Math.min(textarea.value.scrollHeight, 200);
    textarea.value.style.height = newHeight + 'px';
    textarea.value.style.overflowY = textarea.value.scrollHeight > 200 ? 'auto' : 'hidden';
}

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

.post-content {
    padding: 12px;
    flex: 1;
    word-wrap: break-word;
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

.post-title {
    font-size: 18px;
    font-weight: 500;
    margin-bottom: 10px;
    color: #ffffff;
    overflow-wrap: break-word;
    word-break: break-word;
    hyphens: auto;
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
