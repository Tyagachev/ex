<template>
    <div class="container">
        <CommentNote
            v-for="comment in comments"
            :key="comment.id"
            :comment="comment"
        />
    </div>
</template>

<script setup>
import {computed, defineComponent, onMounted} from "vue";
import CommentNote from "@/components/Comment/CommentNote.vue";
import {useCommentsStore} from "@/stores/comments.js";

defineOptions({
    name: "index"
})

onMounted(() => {
    c.getUserComments()
})
const c = useCommentsStore()
const comments = computed(() => useCommentsStore().comments)

</script>

<style scoped>

</style>
