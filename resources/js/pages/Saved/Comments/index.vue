<template>
    <div class="container">
        <CommentNote
            v-for = "comment in comments"
            :key = "comment.id"
            :comment = "comment"
            :noCommentPage = tabbedStore.isTabbedPage
        />
        <div v-show="tabbedStore.hasMore" ref="loadTrigger" class="h-50"></div>
    </div>
</template>

<script setup>
import {computed, defineOptions, onMounted} from "vue";
import {useTabbedStore} from "@/stores/tabbed.js";
import CommentNote from "@/components/Comment/CommentNote.vue";
import {useInfiniteScroll} from "@/composables/useInfiniteScroll.js";

defineOptions({
    name: "index"
})

const tabbedStore = useTabbedStore();
const comments = computed(() => tabbedStore.tabbedData)
onMounted(() => {
    if (tabbedStore.tabbedData.length) {
        tabbedStore.resetLoadedStatusAndRefresh()
    } else {
        tabbedStore.getTabbedData();
    }
})

const { loadTrigger } = useInfiniteScroll(tabbedStore.getTabbedData,
    {
        rootMargin: '200px',
        hasMore: () => tabbedStore.hasMore,
        isLoading: () => tabbedStore.loading,
        immediate: false
    }
)

</script>

<style scoped>

</style>
