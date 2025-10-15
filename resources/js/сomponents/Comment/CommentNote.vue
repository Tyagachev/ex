<template>
    <div>
        <div>
            {{comment.user.name}}
        </div>
        <!-- рекурсивный вывод дочерних комментариев -->
        <div class="mt-2 space-y-2">
            <!--<CommentNode
                v-for="child in comment.replies"
                :key="child.id"
                :comment="child"
                :depth="depth + 1"
                :active-comment-menu="activeCommentMenu"
                @update:active-comment-menu="updateActiveCommentMenu"
            />-->
            <CommentNote
                v-for="child in comment.replies"
                :key="child.id"
                :comment="child"
                :depth="depth + 1"
            />
        </div>
    </div>
</template>

<script setup>
import CommentNote from "@/сomponents/Comment/CommentNote.vue";
import {useUserStore} from "@/stores/users.js";
import {computed} from "vue";

defineOptions({
    name: "CommentNote"
})

defineProps({
    comment: {
        type: Object,
        required: true
    },
    depth: {
        type: Number,
        default: 0
    },
    activeCommentMenu: {
        type: Number,
        default: null
    }
})

const userStore = useUserStore();
const user = computed(() => userStore.u);

</script>

<style scoped>

</style>
