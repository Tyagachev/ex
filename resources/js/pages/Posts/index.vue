<template>
    <div class="container flex-1 align-center ">
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
                                                :style="{ background: avatar.avatarColor(post.user?.name) }"
                                                :title="post.user.name"
                                            >
                                                {{post.user?.name[0]}}
                                            </div>
                                            <div v-if="post.user.id === user?.id">
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
                                    <button class="rotate-90 vote-btn" @click="toggleMenu(post.id)">
                                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="16px" height="16px" viewBox="0 0 100 20">
                                            <g id="Objects">
                                                <circle class="fil0 str0" cx="10" cy="10" r="10"/>
                                                <circle class="fil0 str0" cx="50" cy="10" r="10"/>
                                                <circle class="fil0 str0" cx="90" cy="10" r="10"/>
                                            </g>
                                        </svg>
                                    </button>
                                    <div v-if="activeMenu === post.id" class="post-menu">
                                        <div v-if="post.user.id === user?.id">
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
                                        <div v-else-if="post.user.id !== user?.id || !user" class="post-menu">
                                            <button class="menu-item">
                                                <span class="text-white">Пожаловаться</span>
                                            </button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            <router-link :to="{name: 'posts.show', params: {id: post.id}}">
                            <div>
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
                            </div>
                            </router-link>

                            <div class="post-footer border-b-white">
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

                <div v-if="posts.index < posts.length - 1" class="post-divider"></div>
                <div v-if="!posts" class="text-center">
                    <h3 class="text-white">Нет постов для отображения</h3>
                    <p class="text-white">Создайте первый пост, чтобы начать обсуждение</p>
                </div>
            </div>

            <div v-show="postsStore.hasMore" ref="loadTrigger" class="h-50"></div>

            <div v-if="!postsStore.hasMore && !postsStore.loading" class="text-center py-4 text-gray-400">
                Больше постов нет
            </div>
            </div>
        </div>
</template>

<script setup>
import {computed, defineOptions, ref} from "vue";
import {useRouter} from "vue-router";
import {usePostsStore} from "@/stores/posts.js";
import {useUserStore} from "@/stores/users.js";
import {useAvatarStore} from "@/stores/avatars.js";
import Panel from "@/components/Panel/Panel.vue";
import { useInfiniteScroll } from '@/composables/useInfiniteScroll'

defineOptions({
    name: "Index"
})

const destroyPost = usePostsStore();
const userStore = useUserStore();
const avatar = useAvatarStore();
const router = useRouter();
const postsStore = usePostsStore();

const { loadTrigger } = useInfiniteScroll(postsStore.getPosts,
    {
        rootMargin: '200px',
        hasMore: () => postsStore.hasMore,
        isLoading: () => postsStore.loading
    }
)

const user = computed(() => userStore.u)
const posts = computed(() => postsStore.allPosts);

const activeMenu = ref(null);
const showNotification = ref(false);
const notificationTimeout = ref(null);
const showImageModal = ref(false);
const currentImage = ref("")
const currentImageAlt = ref("")

const componentType = 'post'
const bodyUrl = 'posts'

/**
 * Открытие модального окна изображения
 */
const closeImageModal = () => {
    showImageModal.value = false;
    currentImage.value = '';
    currentImageAlt.value = '';
    document.body.style.overflow = '';
};

/**
 * Окно редактирования, удаление поста
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
 * Закрытие окна toggleMenu
 */
const closeMenuOnClickOutside = () => {
    activeMenu.value = null;
    document.removeEventListener('click', closeMenuOnClickOutside);
}

</script>

<style scoped>

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

.fil0 {
    fill: #bebbbb;
    stroke: #bebbbb;
}

.container {
    width: 1000px;
    margin: 0 auto;
    max-height: 100vh;
    scrollbar-width: thin;
    scrollbar-track-color: #2bf10d;
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
