<template>
    <div class="container">
        <CommentNote
            v-for="comment in comments"
            :key="comment.id"
            :comment="comment"
            :noCommentPage = isAnswerPage
        />
        <div v-show="answersStore.hasMore" ref="loadTrigger" class="h-50"></div>
        <div v-if="!answersStore.hasMore && !answersStore.loading" class="end-of-feed">
            <div class="end-line"></div>
            <div class="end-text">Вы просмотрели все ответы</div>
        </div>
    </div>

</template>

<script setup>
import CommentNote from "@/components/Comment/CommentNote.vue";
import {computed, onMounted} from "vue";
import {useAnswersStore} from "@/stores/answers.js";
import {useInfiniteScroll} from "@/composables/useInfiniteScroll";

defineOptions({
    name: "index"
})

const answersStore = useAnswersStore()
const comments = computed(() => useAnswersStore().allAnswers)
const isAnswerPage = true;

onMounted(() => {
     if (comments.value.length) {
         answersStore.refresh()
     }
})
const { loadTrigger } = useInfiniteScroll(answersStore.getAnswers,
    {
        hasMore: () => answersStore.hasMore,
        isLoading: () => answersStore.loading,
        immediate: false // автоматический запуск при монтировании
    }
)

</script>

<style scoped>
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
