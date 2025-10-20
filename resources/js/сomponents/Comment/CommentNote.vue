<template>
    <transition name="fade">
        <div v-if="showNotification" class="notification">
            Ссылка скопирована
        </div>
    </transition>
    <!--<div :class="['relative', depth > 0 ? 'ml-2 pl-2 border-l border-slate-700' : '']">-->
    <div class="relative pt-3 ">
        <div :id="comment.id" class="flex items-start gap-2">
            <div v-if="depth > 0"></div>
            <div
                class="w-8 h-8 rounded-full flex-shrink-0 grid place-items-center text-slate-900 font-bold"
                :style="{ background: avatarStore.avatarColor(comment.user?.name) }"
                :title="comment.user?.name"
            >
                {{ comment.user?.name[0] }}
            </div>
            <div class="flex-1 mt-1 mr-1 min-w-0">
                <div class="flex items-center gap-2 text-sm text-slate-300">
                    <span :class="[user?.id === comment.user?.id ? 'text-sm text-black pr-1 pl-1 bg-green-300 hover:underline cursor-pointer' : 'font-semibold text-sm truncate  hover:underline cursor-pointer' ]">
                        {{ comment.user?.name }}
                    </span>
                    <div class="circle"></div>
                    <span class="time_comment">{{ comment.createdAtHuman }}</span>
                    <!-- Кнопка троеточия для меню -->
                    <div class="ml-auto relative">
                        <button class="rotate-90 p-2 rounded-full hover:bg-slate-700 cursor-pointer" @click.stop="toggleCommentMenu(comment.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="16" height="16" viewBox="0 0 100 20">
                                <g>
                                    <circle cx="10" cy="10" r="10" fill="white"/>
                                    <circle cx="50" cy="10" r="10" fill="white"/>
                                    <circle cx="90" cy="10" r="10" fill="white"/>
                                </g>
                            </svg>
                        </button>
                        <!-- Выпадающее меню -->
                        <div v-if="activeMenuComment.activeMenu === comment.id"
                             class="absolute right-0 top-full bg-slate-800 border border-slate-700 rounded shadow-lg z-10"
                             ref="commentMenu">
                            <div v-if="user?.id === comment.user.id">
                                <div class="flex text-center">
                                    <button
                                        class="flex space-between block w-full text-left px-2 py-2 text-sm hover:bg-slate-700 cursor-pointer"
                                        @click="showEdit(comment)"
                                    >
                                        <div class="mr-1" ><i class="fa fa-pencil" aria-hidden="true"></i></div>
                                        <span>Редактировать</span>
                                    </button>

                                </div>

                                <button
                                    class="flex block w-full text-left px-2 py-2 text-sm hover:bg-slate-700 text-red-400 cursor-pointer"
                                    @click="commentStore.deleteComment(comment)"
                                >
                                    <div class="mr-1 " ><i class="fa fa-trash" aria-hidden="true"></i></div>
                                    <span>Удалить</span>
                                </button>
                            </div>
                            <div v-else>
                                <button
                                    class="flex space-between block w-full text-left px-2 py-2 text-sm hover:bg-slate-700"
                                >
                                    <div class="mr-1" ><i class="fa fa-bell" aria-hidden="true"></i></div>
                                    <span>Подписаться</span>
                                </button>
                                <button
                                    class="flex space-between block w-full text-left px-2 py-2 text-sm hover:bg-slate-700"
                                >
                                    <div class="mr-1"><i class="fa fa-flag" aria-hidden="true"></i></div>
                                    <span>Пожаловаться</span>
                                </button>
                                <button
                                    class="flex space-between block w-full text-left px-2 py-2 text-sm hover:bg-slate-700"
                                >
                                    <div class="mr-1" >                                    <span class="footer-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="16px" height="16px" version="1.1"
                                             viewBox="0 0 200 252.391">
                                         <g id="Objects">
                                          <path class="save str0" d="M10 0l180 0c5.508,0 10,4.493 10,10l0 232.354c0,3.7 -1.851,6.876 -5.07,8.7 -3.219,1.824 -6.894,1.78 -10.069,-0.121l-79.88 -47.849c-3.251,-1.948 -7.042,-1.945 -10.29,0.007l-79.54 47.804c-3.173,1.907 -6.852,1.956 -10.075,0.133 -3.223,-1.823 -5.076,-5.001 -5.076,-8.704l0 -232.324c0,-5.507 4.492,-10 10,-10z"/>
                                         </g>
                                        </svg>
                                    </span>
                                    </div>
                                    <span>Сохранить</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-1 whitespace-pre-line text-slate-200 leading-6">
                    <div class="mt-1 mb-1">
                        <div v-if="comment.reply_user != null || comment.parent != null">
                            <button  @click.prevent="getCommentText(comment.user, comment.reply_user, comment.parent)">
                                <div class="flex">
                                    <div>
                                        <a href="#" style="font-size: 0.8rem; color: #dfba8b;">{{!showCommentText ? 'Показать' : 'Скрыть' }} комментарий <i :class="[showCommentText ? 'fa rotate-180 fa-caret-down' : 'fa fa-caret-down']"></i></a>
                                    </div>
                                </div>
                            </button>
                        </div>
                        <!--<div v-else>
                            <p  style="font-size: 0.8rem; color: #dfba8b;">Ответ на пост</p>
                        </div>-->
                        <div v-if="showCommentText">
                            <div class="quote-block border border-gray-100">
                                <p class="comment_text pl-2">{{responseCommentText}}</p>
                            </div>
                        </div>
                    </div>
                    <div v-if="!showEditArea" >
                        <div>
                            <p class="text-sm hover:underline cursor-pointer text-blue-400">{{comment.reply_user?.name ? `@${comment.reply_user.name}` : null}}</p>
                            <p class="comment_text inline-block">{{comment.text }}</p>
                        </div>

                    </div>
                    <!-- Текстовое поле для редактирования -->
                    <div v-show="showEditArea" class="mt-2">
                        <textarea
                            ref="textarea"
                            @input="handleEditInput"
                            :value="editText"
                            class="comment-textarea w-full"
                            rows="1"
                        ></textarea>
                        <!-- Счетчик символов для редактирования -->
                        <div class="flex justify-between items-center mt-2">
                            <div class="text-xs text-slate-400" :class="{ 'text-red-500': editText.length > 3000 }">
                                {{ editText.length }}/3000
                            </div>
                            <div class="flex justify-end gap-2">
                                <button
                                    class="text-sm cancel-button"
                                    @click="cancelEdit"
                                >
                                    Отмена
                                </button>
                                <button
                                    class="text-sm submit-button"
                                    @click="updateComment(comment)"
                                    :disabled="!editText || editText.length > 3000"
                                >
                                    Обновить
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Кнопки голосования и ответа -->

                    <div class="flex">
                        <div v-if="user?.id !== comment.user?.id" class="vote-panel vote-panel_bckg">
                            <div class="flex">
                                <button @click.prevent="voteStore.upVote(comment)" class="vote-btn">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 127 124.848">
                                            <path :class="[voteStore.getUserVote(comment) === 1 ? 'v-up' : 'vote-icon up']" stroke="currentColor" stroke-width="6" d="M4.408 73.59l31.855 0c1.105,0 2.006,0.901 2.006,2.006l0 29.189c0,11.05 9.018,20.063 20.064,20.063l10.333 0c11.047,0 20.064,-9.017 20.064,-20.063l0 -29.189c0,-1.105 0.901,-2.006 2.006,-2.006l31.909 0c0.801,0 1.487,-0.439 1.822,-1.167 0.336,-0.728 0.224,-1.535 -0.297,-2.144l-58.565 -68.511c-0.393,-0.459 -0.912,-0.7 -1.516,-0.703 -0.604,-0.003 -1.125,0.233 -1.522,0.689l-59.672 68.511c-0.528,0.607 -0.647,1.417 -0.313,2.149 0.333,0.733 1.022,1.176 1.826,1.176z"/>
                                        </svg>
                                    </span>
                                </button>
                                <div class="vote-count px-2">
                                    <p :class="[comment.totalVotes === 0 ? 'total_white' : comment.totalVotes > 0 ? 'total_green' : 'total_red']">{{ countStore.formatCount(comment.totalVotes) }}</p>
                                </div>
                                <button @click.prevent="voteStore.downVote(comment)" class="vote-btn">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 127 124.848">
                                            <path :class="[voteStore.getUserVote(comment) === -1 ? 'v-down' : 'vote-icon down']" transform="translate(0,124.848) scale(1,-1)" stroke="currentColor" stroke-width="6" d="M4.408 73.59l31.855 0c1.105,0 2.006,0.901 2.006,2.006l0 29.189c0,11.05 9.018,20.063 20.064,20.063l10.333 0c11.047,0 20.064,-9.017 20.064,-20.063l0 -29.189c0,-1.105 0.901,-2.006 2.006,-2.006l31.909 0c0.801,0 1.487,-0.439 1.822,-1.167 0.336,-0.728 0.224,-1.535 -0.297,-2.144l-58.565 -68.511c-0.393,-0.459 -0.912,-0.7 -1.516,-0.703 -0.604,-0.003 -1.125,0.233 -1.522,0.689l-59.672 68.511c-0.528,0.607 -0.647,1.417 -0.313,2.149 0.333,0.733 1.022,1.176 1.826,1.176z"/>
                                        </svg>
                                    </span>
                                </button>
                            </div>

                        </div>
                        <div v-else class="vote-panel vote-panel_bckg">
                            <div class="flex">
                                <button class="no_vote-btn">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 127 124.848">
                                            <path class="vote-icon up" stroke="currentColor" stroke-width="6" d="M4.408 73.59l31.855 0c1.105,0 2.006,0.901 2.006,2.006l0 29.189c0,11.05 9.018,20.063 20.064,20.063l10.333 0c11.047,0 20.064,-9.017 20.064,-20.063l0 -29.189c0,-1.105 0.901,-2.006 2.006,-2.006l31.909 0c0.801,0 1.487,-0.439 1.822,-1.167 0.336,-0.728 0.224,-1.535 -0.297,-2.144l-58.565 -68.511c-0.393,-0.459 -0.912,-0.7 -1.516,-0.703 -0.604,-0.003 -1.125,0.233 -1.522,0.689l-59.672 68.511c-0.528,0.607 -0.647,1.417 -0.313,2.149 0.333,0.733 1.022,1.176 1.826,1.176z"/>
                                        </svg>
                                    </span>
                                </button>
                                <div class="vote-count px-2">
                                    <p :class="[comment.totalVotes === 0 ? 'total_white' : comment.totalVotes > 0 ? 'total_green' : 'total_red']">{{ countStore.formatCount(comment.totalVotes) }}</p>
                                </div>
                                <button class="no_vote-btn">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 127 124.848">
                                            <path class="vote-icon down" transform="translate(0,124.848) scale(1,-1)" stroke="currentColor" stroke-width="6" d="M4.408 73.59l31.855 0c1.105,0 2.006,0.901 2.006,2.006l0 29.189c0,11.05 9.018,20.063 20.064,20.063l10.333 0c11.047,0 20.064,-9.017 20.064,-20.063l0 -29.189c0,-1.105 0.901,-2.006 2.006,-2.006l31.909 0c0.801,0 1.487,-0.439 1.822,-1.167 0.336,-0.728 0.224,-1.535 -0.297,-2.144l-58.565 -68.511c-0.393,-0.459 -0.912,-0.7 -1.516,-0.703 -0.604,-0.003 -1.125,0.233 -1.522,0.689l-59.672 68.511c-0.528,0.607 -0.647,1.417 -0.313,2.149 0.333,0.733 1.022,1.176 1.826,1.176z"/>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <!--<div>
                            <button class="footer-btn" @click="copyLink(comment.id)">
                            <span class="footer-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" version="1.1"
                                     viewBox="0 0 63 63">
                                 <g id="Objects">
                                  <metadata id="CorelCorpID_0Corel-Layer"/>
                                  <circle class="share str0" cx="51.582" cy="11.418" r="11.418"/>
                                  <circle class="share str0" cx="51.582" cy="51.583" r="11.418"/>
                                  <circle class="share str0" cx="11.417" cy="31.5" r="11.418"/>
                                  <path class="share str1" d="M41.368 16.525l-19.737 9.868m0 10.214l19.737 9.869"/>
                                 </g>
                                </svg>
                            </span>
                                <p style="font-size: 12px">{{ countStore.formatCount(comment.shareCount) }}</p>
                            </button>
                        </div>-->
                        <div class="">
                            <button v-if="user?.id !== comment.user.id && user"
                                    class="footer-btn hover:text-slate-200"
                                    @click.prevent="toggleReply">
                                <p class="text-blue-400 text-sm font-semibold">
                                    Ответить
                                </p>

                            </button>
                        </div>
                    </div>
                </div>
                <!-- Поле ответа на комментарий -->
                <div v-if="showReplyArea" class="mt-2">
                    <textarea
                        ref="textarea"
                        :placeholder="setPlaceholder(comment)"
                        @input="autoResize"
                        v-model="commentStore.replyText"
                        class="comment-textarea w-full"
                        rows="1"
                    >
                    </textarea>
                    <div class="flex justify-between items-center mt-1">
                        <div class="text-xs text-slate-400" :class="{ 'text-red-500': commentStore.replyText.length > 3000 }">
                            {{ commentStore.replyText.length }}/3000
                        </div>
                        <div class="flex justify-end gap-2">
                            <button
                                class="text-sm cancel-button"
                                @click="clearText"
                            >
                                Отмена
                            </button>
                            <button
                                class="submit-button text-sm"
                                @click="submitReply(comment)"
                                :disabled="!commentStore.replyText || commentStore.replyText.length > 3000"
                            >
                                Отправить
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- рекурсивный вывод дочерних комментариев -->
        <div class="space-y-2 pl-3">
            <!-- Кнопка раскрытия ветки -->
            <div v-if="comment.replies && comment.replies.length" class="mt-2 pl-9">
                <button
                    class="cursor-pointer text-blue-400 text-sm hover:text-slate-200 flex items-center gap-1"
                    @click="toggleReplies"
                >
                    <span>{{ showReplies ? 'Скрыть ответы' : `Показать ответы (${comment.replies.length})` }}</span>
                    <i :class="[showReplies ? 'fa rotate-180 fa-caret-down' : 'fa fa-caret-down']"></i>

                </button>
            </div>

            <!-- Ветка дочерних комментариев -->
            <transition name="fade">
                <div
                    v-if="showReplies"
                    class="space-y-2 pl-3 ml-2 mt-2"
                >
                    <CommentNote
                        v-for="child in comment.replies"
                        :key="child.id"
                        :comment="child"
                        :depth="depth + 1"
                    />
                </div>
            </transition>
        </div>
    </div>

</template>

<script setup>

import {useUserStore} from "@/stores/users.js";
import {computed, nextTick, ref} from "vue";
import {useAvatarStore} from "@/stores/avatars.js";
import {useCommentsStore} from "@/stores/comments.js";
import {useVotesStore} from "@/stores/votes.js";
import {useCountsStore} from "@/stores/counts.js";
import CommentNote from "@/сomponents/Comment/CommentNote.vue";
import axios from "axios";

defineOptions({
    name: "CommentNote",
})

defineProps({
    comment: {
        type: Object,
        required: true
    },
    depth: {
        type: Number,
        default: 0
    }
})

const avatarStore = useAvatarStore();
const countStore = useCountsStore();
const voteStore = useVotesStore();
const commentStore = useCommentsStore();
const userStore = useUserStore();
let activeMenuComment = useCommentsStore();

const user = computed(() => userStore.u);

let showCommentText = ref(false);
let responseCommentText = ref('');

const showEditArea = ref(false);
const editText = ref('');
const showNotification = ref(false);
const showReplyArea = ref(false);
const textarea = ref(null);
const commentMenu = ref(null);
const showReplies = ref(false);

const emits = defineEmits(['shownotificationmessage']);

/**
 * Открытие или закрытие дочерних комментов
 */
const toggleReplies = () => {
    showReplies.value = !showReplies.value;
};

/**
 * Открытия меню действия с комментом
 * @param commentId
 */
const toggleCommentMenu = (commentId) => {
    activeMenuComment.setActiveMenu(commentId)
    if (activeMenuComment.activeMenu === commentId) {
        document.addEventListener('click', closeMenuOnClickOutside);
    } else {
        document.removeEventListener('click', closeMenuOnClickOutside);
    }
}

/**
 * Закрытия меню действия с комментом
 * (клик в любом месте экрана)
 */
const closeMenuOnClickOutside = () => {
    activeMenuComment.activeMenu = null;
    document.removeEventListener('click', closeMenuOnClickOutside);
}

/**
 * Получения текста комментария для цитаты
 * @param userId
 * @param replyUserId
 * @param parent
 * @returns {Promise<void>}
 */
const getCommentText = async (userId, replyUserId, parent) => {
    const res = await axios.post('/api/comments/text', {
        userId: userId.id,
        replyUserId: replyUserId?.id || null,
        parentId: parent || null
    })
    responseCommentText.value = res.data.text;
    showCommentText.value = !showCommentText.value;
}

/**
 * Просмотр комментария
 * @param comment
 * @returns {Promise<void>}
 */
const showEdit = async (comment) => {
    if (!showEditArea.value) {
        const res = await axios.get(`/api/comments/${comment.id}/edit`);
        showEditArea.value = true;
        editText.value = res.data.text || res.data;
        await nextTick();
        autoResize()
    }
}

/**
 * Обновление комментария
 * @param comment
 * @returns {Promise<void>}
 */
const updateComment = async (comment) => {
    const res = await axios.patch(`/api/comments/${comment.id}`, {
        text: editText.value
    })

    if (res.data.status === 200) {
        editText.value = "";
        showEditArea.value = false;
    }
    await commentStore.refresh(comment.postId);
}

/**
 * Отслеживание ввода текста
 * для изменения размера поля ввода
 * @param event
 */
const handleEditInput = (event) => {
    editText.value = event.target.value;
    autoResize();
}

/**
 * Изменение высоты текстового поля комментария
 */
const autoResize = () => {
    if (!textarea.value) return;
    textarea.value.style.height = 'auto';
    const newHeight = Math.min(textarea.value.scrollHeight, 200);
    textarea.value.style.height = newHeight + 'px';
    textarea.value.style.overflowY = textarea.value.scrollHeight > 200 ? 'auto' : 'hidden';
}

/**
 * Placeholder ответа на коммент
 * @param comment
 * @returns {string}
 */
const setPlaceholder = (comment) => {
    return `Ответить пользователю ${comment.user.name}`
}

/**
 * Закрытие текстового поля
 */
const cancelEdit = () => {
    showEditArea.value = false;
    closeCommentMenu();
}

/**
 * Очистка поля ответа на комментарий
 */
const clearText = () => {
    commentStore.replyText = '';
    autoResize();
    showReplyArea.value = false;
}

/**
 * Закрытие меню управления комментарием
 */
const closeCommentMenu = () => {
    document.removeEventListener('click', handleDocumentClick);
}

/**
 * Открытие или закрытие меню коммента
 * @param event
 */
const handleDocumentClick = (event) => {
    const menu = commentMenu;
    const menuButton = event.target.closest('button');

    if (menu && !menu.contains(event.target) && menuButton !== event.target) {
        closeCommentMenu();
    }
}

/**
 * Отправка ответа на комментарий
 * @param comment
 */
const submitReply = async (comment) => {
    await commentStore.sendReplyComment(comment);
    showReplyArea.value = false;
    showReplies.value = true;
}

/**
 * Открытие или скрытие поля ввода комментария
 */
const toggleReply = () => {
    showReplyArea.value = !showReplyArea.value;
    commentStore.replyText.length ? commentStore.replyText = '' : commentStore.replyText
}


</script>

<style scoped>

.footer-btn {
    display: flex;
    align-items: center;
    padding: 7px;
    background: none;
    border: none;
    border-radius: 18px;
    cursor: pointer;
    margin-right: 6px;
    color: #b0b0b0;
}

.footer-btn:hover {
    background: #383737;
    padding: 7px;
}

.footer-icon {
    margin-right: 6px;
    font-size: 16px;
}

.fade-enter-active,
.fade-leave-active {
    transition: all 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-4px);
}
</style>
