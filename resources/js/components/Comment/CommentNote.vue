<template>
    <div v-bind="$attrs">
        <transition name="fade">
            <div v-if="showNotification" class="notification">
                Ссылка скопирована
            </div>
        </transition>
        <div class="pt-5" v-if="noCommentPage && !comment.parent && comment.postId">
            <router-link :to="{name: 'posts.show', params: {id: props.comment.postId}}">
                <h3 class="post-title">{{props.comment.postTitle}}</h3>
            </router-link>
        </div>
        <div v-if="props.comment?.title"><h1>{{props.comment?.title}}</h1></div>
        <div :class="['relative', depth > 0 ? 'ml-5 border-l border-slate-600' : '']">
            <div class="pt-4"></div>
        <!--<div class="relative pt-3">-->
            <div :id="props.comment.id" class="flex items-start gap-2 pl-3 pt-1">
                <!--<div v-if="depth > 0" class="absolute rounded-full -left-1 top-8 w-2 h-2 bg-slate-500 border border-slate-700"></div>-->

                <!--Закорючка к комментарию-->
                <div v-if="depth > 0" class="absolute rounded-l-lg -left-0 top-7 w-2 h-2 pl-3 border-b border-slate-600"></div>
                <div
                    class="w-8 h-8 rounded-full top-20 flex-shrink-0 grid place-items-center text-slate-900 font-bold"
                    :style="{ background: avatarStore.avatarColor(props.comment.user?.name) }"
                    :title="props.comment.user?.name"
                >
                    {{ props.comment.user?.name[0].toUpperCase() }}
                    <div v-show="props.comment.replies?.length && showReplies" class="absolute border-l left-7 top-13 border-slate-600 w-2 h-full"></div>
                </div>
                <div class="flex-1 mr-1 mt-0 min-w-0">
                    <div class="flex items-center gap-2 text-sm text-slate-300">
                    <span :class="[user?.id === props.comment.user?.id ? 'text-sm text-black pr-1 pl-1 rounded-md bg-green-300 hover:underline cursor-pointer' : 'font-semibold text-sm truncate  hover:underline cursor-pointer' ]">
                        {{ props.comment.user?.name }}
                    </span>
                        <div class="circle"></div>
                        <span class="time_comment">{{ props.comment.createdAtHuman }}</span>
                        <!-- Кнопка троеточия для меню -->
                        <div class="ml-auto relative">
                            <button class="rotate-90 p-2 rounded-full hover:bg-slate-700 cursor-pointer" @click.stop="toggleCommentMenu(props.comment.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="16" height="16" viewBox="0 0 100 20">
                                <g>
                                    <circle cx="10" cy="10" r="10" fill="white"/>
                                    <circle cx="50" cy="10" r="10" fill="white"/>
                                    <circle cx="90" cy="10" r="10" fill="white"/>
                                </g>
                            </svg>
                            </button>
                            <!-- Выпадающее меню -->
                            <div v-if="activeCommentMenu.activeMenu === props.comment.id"
                                 class="absolute right-0 top-full bg-slate-800 border border-slate-700 rounded shadow-lg z-10"
                                 ref="commentMenu">
                                <div v-if="user?.id === props.comment.user?.id">
                                    <div v-if="noCommentPage">
                                        <div class="flex text-center">
                                            <router-link :to="{name: 'comments.show', params: {id: props.comment.id}}">
                                            <button
                                                class="flex space-between block w-full text-left px-2 py-2 text-sm hover:bg-slate-700 cursor-pointer">
                                                <div class="mr-1" ><i class="fa fa-pencil" aria-hidden="true"></i></div>
                                                <span>Перейти</span>
                                            </button>
                                            </router-link>
                                        </div>
                                    </div>
                                    <div v-else >
                                        <div class="flex text-center">
                                            <button
                                                class="flex space-between block w-full text-left px-2 py-2 text-sm hover:bg-slate-700 cursor-pointer"
                                                @click="showEdit(props.comment)"
                                            >
                                                <div class="mr-1" ><i class="fa fa-pencil" aria-hidden="true"></i></div>
                                                <span>Редактировать</span>
                                            </button>
                                        </div>
                                        <button
                                            class="flex block w-full text-left px-2 py-2 text-sm hover:bg-slate-700 text-red-400 cursor-pointer"
                                            @click="commentStore.deleteComment(props.comment)"
                                        >
                                            <div class="mr-1 " ><i class="fa fa-trash" aria-hidden="true"></i></div>
                                            <span>Удалить</span>
                                        </button>
                                    </div>

                                </div>
                                <div v-else>
                                    <div v-if="noCommentPage">
                                        <div>
                                            <router-link :to="{name: 'comments.show', params: {id: props.comment.id}}">
                                                <button
                                                    class="flex space-between w-full text-left px-2 py-2 text-sm hover:bg-slate-700 cursor-pointer">
                                                    <div class="mr-1" ><i class="fa fa-pencil" aria-hidden="true"></i></div>
                                                    <span>Перейти</span>
                                                </button>
                                            </router-link>
                                        </div>
                                    </div>
                                    <button
                                        class="flex space-between block w-full text-left px-2 py-2 text-sm hover:bg-slate-700 cursor-pointer"
                                    >
                                        <div class="mr-1" ><i class="fa fa-bell" aria-hidden="true"></i></div>
                                        <span>Подписаться</span>
                                    </button>
                                    <button
                                        class="flex space-between block w-full text-left px-2 py-2 text-sm hover:bg-slate-700 cursor-pointer"
                                    >
                                        <div class="mr-1"><i class="fa fa-flag" aria-hidden="true"></i></div>
                                        <span>Пожаловаться</span>
                                    </button>
                                    <button v-if="user" @click="savePost(props.comment, componentType)"
                                        class="flex space-between block w-full text-left px-2 py-2 text-sm hover:bg-slate-700 cursor-pointer">
                                        <div class="mr-1" >
                                            <span class="footer-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="16px" height="16px" version="1.1"
                                                     viewBox="0 0 200 252.391">
                                                    <g id="Objects">
                                                        <path :class="[!userSaves ? 'save str0' : 'save_red']" d="M10 0l180 0c5.508,0 10,4.493 10,10l0 232.354c0,3.7 -1.851,6.876 -5.07,8.7 -3.219,1.824 -6.894,1.78 -10.069,-0.121l-79.88 -47.849c-3.251,-1.948 -7.042,-1.945 -10.29,0.007l-79.54 47.804c-3.173,1.907 -6.852,1.956 -10.075,0.133 -3.223,-1.823 -5.076,-5.001 -5.076,-8.704l0 -232.324c0,-5.507 4.492,-10 10,-10z"/>
                                                    </g>
                                                </svg>
                                            </span>
                                        </div>
                                        <span>Сохранить</span>
                                    </button>
                                    <button v-else
                                            class="flex space-between block w-full text-left px-2 py-2 text-sm hover:bg-slate-700">
                                        <div class="mr-1" >
                                            <span class="footer-icon">
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
                            <div v-if="props.comment.reply_user != null || props.comment.parent != null">
                                <button  @click.prevent="getCommentText(props.comment)">
                                    <div class="flex">
                                        <div>
                                            <a href="#" style="font-size: 0.8rem; color: #dfba8b;">{{!showCommentText ? 'Показать' : 'Скрыть' }} комментарий <i :class="[showCommentText ? 'fa rotate-180 fa-caret-down' : 'fa fa-caret-down']"></i></a>
                                        </div>
                                    </div>
                                </button>
                            </div>
                            <div v-if="showCommentText">
                                <div class="quote-block border border-gray-100">
                                    <p class="comment_text pl-2">{{responseCommentText}}</p>
                                </div>
                            </div>
                        </div>
                        <div v-if="!showEditArea" >
                            <div class="pr-5">
                                <p class="comment_text inline-block">
                                    <span class="text-sm hover:underline cursor-pointer text-blue-400">
                                        <router-link :to="{name:'posts.create'}">
                                            {{props.comment.reply_user?.name ? `@${props.comment.reply_user.name}` : null}}
                                        </router-link></span>
                                    <span :class="props.comment.reply_user?.name ? 'pl-2' : ''" style="color:#B7CAD4;">{{props.comment.text }}</span>
                                </p>
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
                                        @click="updateComment(props.comment)"
                                        :disabled="!editText || editText.length > 3000"
                                    >
                                        Обновить
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Кнопки голосования и ответа -->

                        <div class="flex mt-2 items-center">
                            <div v-if="user?.id !== props.comment.user?.id" class="vote-panel vote-panel_bckg">
                                <div class="flex">
                                    <button @click.prevent="upVote(props.comment)" class="vote-btn">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 127 124.848">
                                            <path :class="[userVote === 1 ? 'v-up' : 'vote-icon up']" stroke="currentColor" stroke-width="6" d="M4.408 73.59l31.855 0c1.105,0 2.006,0.901 2.006,2.006l0 29.189c0,11.05 9.018,20.063 20.064,20.063l10.333 0c11.047,0 20.064,-9.017 20.064,-20.063l0 -29.189c0,-1.105 0.901,-2.006 2.006,-2.006l31.909 0c0.801,0 1.487,-0.439 1.822,-1.167 0.336,-0.728 0.224,-1.535 -0.297,-2.144l-58.565 -68.511c-0.393,-0.459 -0.912,-0.7 -1.516,-0.703 -0.604,-0.003 -1.125,0.233 -1.522,0.689l-59.672 68.511c-0.528,0.607 -0.647,1.417 -0.313,2.149 0.333,0.733 1.022,1.176 1.826,1.176z"/>
                                        </svg>
                                    </span>
                                    </button>
                                    <div class="vote-count px-2">
                                        <p :class="[props.comment.totalVotes === 0 ? 'total_white' : props.comment.totalVotes > 0 ? 'total_green' : 'total_red']">{{ countStore.formatCount(props.comment.totalVotes) }}</p>
                                    </div>
                                    <button @click.prevent="downVote(props.comment)" class="vote-btn">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 127 124.848">
                                            <path :class="[userVote === -1 ? 'v-down' : 'vote-icon down']" transform="translate(0,124.848) scale(1,-1)" stroke="currentColor" stroke-width="6" d="M4.408 73.59l31.855 0c1.105,0 2.006,0.901 2.006,2.006l0 29.189c0,11.05 9.018,20.063 20.064,20.063l10.333 0c11.047,0 20.064,-9.017 20.064,-20.063l0 -29.189c0,-1.105 0.901,-2.006 2.006,-2.006l31.909 0c0.801,0 1.487,-0.439 1.822,-1.167 0.336,-0.728 0.224,-1.535 -0.297,-2.144l-58.565 -68.511c-0.393,-0.459 -0.912,-0.7 -1.516,-0.703 -0.604,-0.003 -1.125,0.233 -1.522,0.689l-59.672 68.511c-0.528,0.607 -0.647,1.417 -0.313,2.149 0.333,0.733 1.022,1.176 1.826,1.176z"/>
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
                                        <p :class="[props.comment.totalVotes === 0 ? 'total_white' : props.comment.totalVotes > 0 ? 'total_green' : 'total_red']">{{ countStore.formatCount(props.comment.totalVotes) }}</p>
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
                            <div>
                                <button class="footer-btn" @click="copyLink(comment)">
                                <span class="footer-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" version="1.1"
                                         viewBox="0 0 63 63">
                                     <g id="Objects">
                                      <metadata id="CorelCorpID_0Corel-Layer"/>
                                      <circle class="dot_color str0" cx="51.582" cy="11.418" r="11.418"/>
                                      <circle class="dot_color str0" cx="51.582" cy="51.583" r="11.418"/>
                                      <circle class="dot_color str0" cx="11.417" cy="31.5" r="11.418"/>
                                      <path class="dot_color str1" d="M41.368 16.525l-19.737 9.868m0 10.214l19.737 9.869"/>
                                     </g>
                                    </svg>
                                </span>
                                    <p style="font-size: 12px">{{ countStore.formatCount(comment.shareCount) }}</p>
                                </button>
                            </div>
                            <div >
                                <button v-if="user?.id !== props.comment.user?.id
                                && user.auth
                                && depth < 15
                                && !props.noCommentPage"
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
                        :placeholder="setPlaceholder(props.comment)"
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
                                    @click="submitReply(props.comment)"
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
            <div class="pl-2">
                <span v-if="depth >= 15 && props.comment.replies.length" class="mt-2 pl-9 cursor-pointer text-orange-300 text-sm hover:text-slate-200 flex items-center gap-1">
                    <router-link :to="{name: 'comments.show', params: {id: props.comment.id}}">Еще ответов ({{props.comment.replies.length}})</router-link>
                </span>
                <!-- Кнопка раскрытия ветки -->
                <div v-else>
                    <div v-if="!noCommentPage">
                        <div v-if="props.comment.replies && props.comment.replies.length" class="mt-2 pl-9">
                            <button
                                class="cursor-pointer text-blue-300 text-sm hover:text-slate-200 flex items-center gap-1"
                                @click="toggleReplies"
                            >
                                <span>{{ showReplies ? 'Скрыть ответы' : `Ещё (${props.comment.replies.length})` }}</span>
                                <i :class="[showReplies ? 'fa rotate-180 fa-caret-down' : 'fa fa-caret-down']"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pt-2"></div>
                <!-- Ветка дочерних комментариев -->
                    <div v-if="showReplies" class=" mt-1 mb-10">
                        <CommentNote
                            v-for="child in props.comment.replies"
                            :key="child.id"
                            :comment="child"
                            :depth="depth + 1"
                            :active-comment-menu="activeCommentMenu"
                            @update:active-comment-menu="activeCommentMenu = $event"
                        />
                    </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import {useUserStore} from "@/stores/users.js";
import {computed, nextTick, ref} from "vue";
import {useAvatarStore} from "@/stores/avatars.js";
import {useCommentsStore} from "@/stores/comments.js";
import {useCountsStore} from "@/stores/counts.js";
import CommentNote from "@/components/Comment/CommentNote.vue";
import axios from "axios";


defineOptions({
    name: "CommentNote",
})

const props = defineProps({
    comment: {
        type: Object,
        required: true
    },
    noCommentPage: {
        type: Boolean,
        default: false
    },
    depth: {
        type: Number,
        default: 0
    }
})

const avatarStore = useAvatarStore();
const countStore = useCountsStore();
const commentStore = useCommentsStore();
const userStore = useUserStore();
let activeCommentMenu = useCommentsStore();

let showCommentText = ref(false);
let responseCommentText = ref('');

const showEditArea = ref(false);
const editText = ref('');
const showNotification = ref(false);
const showReplyArea = ref(false);
const textarea = ref(null);
const commentMenu = ref(null);
const showReplies = ref(false);
const componentType = 'comment';

const emits = defineEmits(['shownotificationmessage']);

const user = computed(() => userStore.user);

/**
 * Красим кноку голосования
 * @type {ComputedRef<unknown>}
 */
const userVote = computed(() => {
    if (!user || !props.comment || !Array.isArray(props.comment.votes)) return 0;
    const voteObject = props.comment.votes.find(vote => vote.user_id === user.value.id);
    return voteObject ? voteObject.vote : 0;
});

/**
 * Красим флажок сохранения
 * @type {ComputedRef<unknown>}
 */
const userSaves = computed(() => {
    if (!user || !props.comment || !Array.isArray(props.comment.saves)) return false;
    const save = props.comment.saves.find(save => save.user_id === user.value.id);
    return !!save;
})

/**
 * Кнопка сохранения поста
 * @param post
 * @param componentType
 * @returns {Promise<void>}
 */
const savePost = async (post, componentType) => {
    const res = await axios.post('/api/saves', {
        id: post.id,
        type: componentType,
    })
    post.saves = res.data.saved;
}

/**
 * Кнопка голосования вверх
 * @param comment
 * @returns {Promise<void>}
 */
const upVote = async (comment) => {
    const res = await axios.post('/api/votes', {
        id: comment.id,
        vote: 1,
        type: 'comment'
    });
    comment.totalVotes = res.data.totalVotes;
    comment.votes = res.data.votes;
}

/**
 * Кнопка голосования вниз
 * @param comment
 * @returns {Promise<void>}
 */
const downVote = async (comment) => {

    const res = await axios.post('/api/votes', {
        id: comment.id,
        vote: -1,
        type: 'comment'
    })
    comment.totalVotes = res.data.totalVotes;
    comment.votes = res.data.votes;
}

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
    activeCommentMenu.setActiveMenu(commentId)
    if (activeCommentMenu.activeMenu === commentId) {
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
    activeCommentMenu.activeMenu = null;
    document.removeEventListener('click', closeMenuOnClickOutside);
}

/**
 * Получения текста комментария для цитаты
 * @returns {Promise<void>}
 */
const getCommentText = async (comment) => {
    const res = await axios.get(`/api/comments/text/${comment.id}`)
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
    await commentStore.updateComment(comment, editText.value)
    editText.value = "";
    showEditArea.value = false;
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
    showReplies.value = true
    await commentStore.sendReplyComment(comment);
    toggleReply()
}

/**
 * Открытие или скрытие поля ввода комментария
 */
const toggleReply = () => {
    showReplyArea.value = !showReplyArea.value;
    commentStore.replyText.length ? commentStore.replyText = '' : commentStore.replyText
}

/**
 * Получение ссылки на комментарий
 * @param item
 * @param bodyUrl
 * @param componentType
 * @returns {Promise<void>}
 */
const copyLink = async (item, bodyUrl = 'comments', componentType = 'comment') => {
    const url = window.location.origin;
    const link = `${url}/${bodyUrl}/${item.id}`;

    navigator.clipboard.writeText(link);
    item.shareCount++;

    emits('shownotificationmessage');

    await axios.post('/api/shares', {
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

.save {
    fill: transparent;
    stroke: #bebbbb;
    stroke-width: 21px;
}
.save_red {
    fill: red;
    stroke: red;
    stroke-width: 21px;
}


</style>
