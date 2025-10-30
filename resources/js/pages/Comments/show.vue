<template>
    <div class="container">
        <div>
            <button
                @click="goBack">

                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                <span>Назад к основной ветке</span>
            </button>
        </div>
        <CommentNote
            v-if="commentStore.commentObject.user"
            :comment="commentStore.commentObject"
        />
    </div>
</template>

<script setup>
import { useRoute } from 'vue-router';
import {useCommentsStore} from "@/stores/comments.js";
import CommentNote from "@/components/Comment/CommentNote.vue";
import {onMounted} from "vue";
import router from "@/router/router.js";

defineOptions({
    name: "show"
})

onMounted( async () => {
    await commentStore.getComment(route.params.id)
})
const commentStore = useCommentsStore();

const route = useRoute();

const goBack = () => {
    router.back();
}

</script>

<style scoped>

</style>
