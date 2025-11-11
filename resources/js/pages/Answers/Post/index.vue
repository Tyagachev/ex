<template>
    <div class="container">
        <CommentNote
            v-for="comment in comments"
            :key="comment.id"
            :comment="comment"
            :noCommentPage = tabbedStore.isTabbedPage
        />
    </div>
</template>

<script setup>

import CommentNote from "@/components/Comment/CommentNote.vue";
import {computed, onMounted} from "vue";
import {useTabbedStore} from "@/stores/tabbed.js";

defineOptions({
    name: "index"
})

const tabbedStore = useTabbedStore()
const comments = computed(() => tabbedStore.tabbedData);

onMounted(() => {
    if (tabbedStore.tabbedData.length) {
        tabbedStore.resetLoadedStatusAndRefresh()
    }
    tabbedStore.getTabbedData();
})

</script>

<style scoped>

</style>
