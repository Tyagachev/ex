<template>
    <div class="container flex-1 align-center">
        <div class="wrapper">
            <!-- Остальной код без изменений -->
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
                        <div class="post pb-3">
                            <!-- Основное содержимое поста -->
                            <div class="post-content">
                                <div class="flex justify-between">
                                    <div class="post-header">
                                        <div>
                                            <div class="flex items-center">
                                                <div
                                                    class="mr-2 w-8 h-8 rounded-full flex-shrink-0 grid place-items-center text-slate-900 font-bold"
                                                    :style="{ background: avatar.avatarColor(post.user?.name) }"
                                                    :title="post.user.name"
                                                >
                                                    {{post.user?.name[0].toUpperCase()}}
                                                </div>
                                                <div v-if="post.user.id === user?.id">
                                                    <span :class="['text-sm text-black pr-1 pl-1 mr-1 bg-green-300 rounded-md hover:underline cursor-pointer']">{{ post.user.name }}</span>
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
                                                        <span class="text-white text-sm">Редактировать</span>
                                                    </router-link>
                                                </button>
                                                <button class="menu-item">
                                                    <div class="mr-1 text-white" ><i class="fa fa-eye-slash" aria-hidden="true"></i></div>
                                                    <div class="text-white text-sm">Скрыть</div>
                                                </button>
                                                <button @click.prevent="destroyPost.destroyPost(post)" class="menu-item">
                                                    <div class="mr-1 text-red-500"><i class="fa fa-trash" aria-hidden="true"></i></div>
                                                    <div class="text-red-500 text-sm">Удалить</div>
                                                </button>
                                            </div>
                                            <div v-else-if="post.user.id !== user?.id || !user" class="post-menu">
                                                <button class="menu-item">
                                                    <span class="text-white text-sm">Пожаловаться</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="post-title">{{ post.title }}</h3>
                                    <div>
                                        <!-- Обертка для ограничения высоты контента - не применяется если только одно изображение -->
                                        <div
                                            v-if="shouldApplyWrapper(post)"
                                            class="content-wrapper"
                                            :class="{ 'expanded': postStates[post.id]?.expanded }"
                                            :ref="el => setContentRef(el, post.id)"
                                        >
                                            <div v-for="(block, index) in post.blocks" :key="index">
                                                <div class="content">
                                                    <div v-if="block.type === 'text'">
                                                        <div v-html="block.content" class="post-body"></div>
                                                    </div>
                                                    <div class="mt-2 mb-2" v-else-if="block.type === 'image'">
                                                        <div class="image-container">
                                                            <img
                                                                class="post-image cursor-pointer"
                                                                :src="block.path"
                                                                @click="openImageModal(block.path, post.title)"
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Контент без ограничения высоты для постов с одним изображением -->
                                        <div v-else>
                                            <div v-for="(block, index) in post.blocks" :key="index">
                                                <div class="content">
                                                    <div v-if="block.type === 'text'">
                                                        <div v-html="block.content" class="post-body"></div>
                                                    </div>
                                                    <div class="mt-2 mb-2" v-else-if="block.type === 'image'">
                                                        <div class="image-container">
                                                            <img
                                                                class="post-image cursor-pointer"
                                                                :src="block.path"
                                                                @click="openImageModal(block.path, post.title)"
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Кнопка показать больше - не показываем для постов с одним изображением -->
                                        <div
                                            v-if="shouldApplyWrapper(post) && postStates[post.id]?.needsExpand && !postStates[post.id]?.expanded"
                                            class="expand-section"
                                        >
                                            <div class="gradient-overlay"></div>

                                            <button @click.stop="expandPost(post.id)" class="expand-btn">
                                                <div v-if="post.imgCount > 1">
                                                    <span class="pr-1">Показать полностью</span>
                                                    <span>{{post.imgCount}}</span>
                                                    <i class="ml-1 fa fa-camera" aria-hidden="true"></i>
                                                </div>
                                                <div v-else>
                                                    <span>Показать полностью</span>
                                                </div>
                                            </button>
                                        </div>
                                        <div v-else-if="shouldApplyWrapper(post) && postStates[post.id]?.needsExpand && postStates[post.id]?.expanded" class="expand-section">
                                            <button
                                                @click.stop="expandPost(post.id)"
                                                class="expand-btn"
                                            >
                                                <span>Свернуть</span>
                                                <svg class="expand-icon rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
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
                        <div v-if="post" class="post-divider"></div>
                    </div>

                    <div v-if="!posts" class="text-center">
                        <h3 class="text-white">Нет постов для отображения</h3>
                        <p class="text-white">Создайте первый пост, чтобы начать обсуждение</p>
                    </div>
                </div>

                <div v-show="postsStore.hasMore" ref="loadTrigger" class="h-50"></div>

                <!-- Конец ленты -->
                <div v-if="!postsStore.hasMore && !postsStore.loading" class="end-of-feed">
                    <div class="end-line"></div>
                    <div class="end-text">Вы просмотрели все посты</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, defineOptions, ref, onUpdated, onMounted, nextTick } from "vue";
import { useRouter } from "vue-router";
import { usePostsStore } from "@/stores/posts.js";
import { useUserStore } from "@/stores/users.js";
import { useAvatarStore } from "@/stores/avatars.js";
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

const user = computed(() => userStore.user)
const posts = computed(() => postsStore.allPosts);

const activeMenu = ref(null);
const showNotification = ref(false);
const notificationTimeout = ref(null);
const showImageModal = ref(false);
const currentImage = ref("")
const currentImageAlt = ref("")

// Состояния для каждого поста
const postStates = ref({});

const componentType = 'post'
const bodyUrl = 'posts'

// Максимальная высота контента до обрезки
const MAX_CONTENT_HEIGHT = 350;

/**
 * Проверяет, нужно ли применять обертку для ограничения высоты
 */
const shouldApplyWrapper = (post) => {
    // Если только одно изображение - не применяем обертку
    if (post.imgCount === 1 && post.blocks.length === 1) {
        return false;
    }
    // Во всех остальных случаях применяем обертку
    return true;
}

/**
 * Установка ссылки на элемент контента и проверка высоты
 */
const setContentRef = (el, postId) => {
    if (!el) return;

    // Пропускаем проверку для постов, к которым не применяется обертка
    const post = posts.value.find(p => p.id === postId);
    if (post && !shouldApplyWrapper(post)) return;

    if (!postStates.value[postId]) {
        postStates.value[postId] = {
            expanded: false,
            needsExpand: false
        };
    }

    // Используем несколько попыток для проверки высоты
    checkContentHeightWithRetry(el, postId, 3);
}

/**
 * Проверка высоты контента с повторными попытками
 */
const checkContentHeightWithRetry = (el, postId, retries) => {
    if (!el || retries <= 0) return;

    setTimeout(() => {
        const contentHeight = el.scrollHeight;
        const state = postStates.value[postId];

        if (state) {
            const newNeedsExpand = contentHeight > MAX_CONTENT_HEIGHT;

            // Если состояние изменилось, обновляем и проверяем снова
            if (state.needsExpand !== newNeedsExpand) {
                state.needsExpand = newNeedsExpand;

                // Если все еще не совпадает, пробуем еще раз
                if (state.needsExpand !== (el.scrollHeight > MAX_CONTENT_HEIGHT)) {
                    checkContentHeightWithRetry(el, postId, retries - 1);
                }
            }
        }
    }, 100); // Задержка для полной отрисовки контента
}

/**
 * Развертывание поста
 */
const expandPost = (postId) => {
    if (postStates.value[postId]) {
        postStates.value[postId].expanded = !postStates.value[postId].expanded;
    }
}

/**
 * Открытие модального окна с изображением
 */
const openImageModal = (imagePath, altText) => {
    currentImage.value = imagePath;
    currentImageAlt.value = altText;
    showImageModal.value = true;
    document.body.style.overflow = 'hidden';
}

/**
 * Закрытие модального окна
 */
const closeImageModal = () => {
    showImageModal.value = false;
    currentImage.value = '';
    currentImageAlt.value = '';
    document.body.style.overflow = '';
};

/**
 * Принудительная проверка высоты для всех постов
 */
const checkAllPostsHeight = () => {
    nextTick(() => {
        posts.value?.forEach(post => {
            if (!shouldApplyWrapper(post)) return;

            const el = document.querySelector(`[data-post-id="${post.id}"]`);
            if (el) {
                checkContentHeightWithRetry(el, post.id, 3);
            }
        });
    });
}

/**
 * Перепроверка высоты контента при обновлении компонента
 */
onUpdated(() => {
    checkAllPostsHeight();
});

/**
 * Проверка высоты при монтировании компонента
 */
onMounted(() => {
    checkAllPostsHeight();
});

/**
 * Слушаем изменения хранилища постов
 */
postsStore.$subscribe(() => {
    // Даем время на обновление DOM перед проверкой высоты
    setTimeout(() => {
        checkAllPostsHeight();
    }, 300);
});

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

</script>

<style scoped>

.image-container {
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    max-width: 100%;
    margin: 0 auto;
}

.image-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 20px;
}

.modal-close {
    position: absolute;
    top: 0;
    right: -35px;
    background: #E01E1EE2;
    border: none;
    color: white;
    padding: 10px;
    border-radius: 100%;
    cursor: pointer;
    backdrop-filter: blur(10px);
    transition: background 0.2s ease;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.2);
}

@keyframes zoomIn {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
.modal-content {
    position: relative;
    max-width: 90vw;
    max-height: 90vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.2);
}

.modal-image {
    max-width: 100%;
    max-height: 80vh;
    border-radius: 12px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
    animation: zoomIn 0.3s ease;
}

.expand-section {
    display: flex;
    padding-top: 20px;
    justify-content: center;
}

.gradient-overlay {
    position: absolute;
    bottom: 100%;
    left: 0;
    right: 0;
    height: 80px;
    pointer-events: none;
}

.expand-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #d1d5db;
    padding: 10px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    backdrop-filter: blur(10px);
}

.expand-btn:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
    transform: translateY(-1px);
}

.expand-icon {
    width: 16px;
    height: 16px;
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

.content-wrapper {
    max-height: 400px;
    overflow: hidden;
    position: relative;
    transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 8px;
    padding: 8px;
}

.content-wrapper.expanded {
    max-height: 5000px;
}

.content-wrapper:not(.expanded)::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 60px;
    pointer-events: none;
}

@keyframes zoomIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.end-line {
    height: 1px;
    background: #374151;
    margin-bottom: 20px;
}

.end-text {
    color: #6b7280;
    font-size: 14px;
}
</style>
