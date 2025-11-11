<template>
    <div class="container">
            <nav class="mb-6 flex space-x-4 border-b border-gray-600 pb-2">
                <router-link
                    v-for="tab in tabs"
                    :key="tab.name"
                    :to="tab.to"
                    class="px-4 py-2 transition-colors rounded-t"
                    :class="{
                    'bg-gray-600 text-white': route.name === tab.name,
                    'text-gray-600 hover:bg-gray-100': route.name !== tab.name
                }">
                    {{ tab.title }}
                </router-link>
        </nav>
        <div class="comments-content">
            <router-view></router-view>
            <div v-show="tabbedStore.hasMore" ref="loadTrigger" class="h-50"></div>
            <div v-if="!tabbedStore.hasMore && !tabbedStore.loading" class="end-of-feed">
                <div class="end-line"></div>
                <div class="end-text">Вы просмотрели все ответы</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {useTabbedStore} from "@/stores/tabbed.js";
import {useRoute} from "vue-router";
import {onBeforeMount, onBeforeUnmount, onUpdated, ref} from "vue";
import {useInfiniteScroll} from "@/composables/useInfiniteScroll";

defineOptions({
    name: "TabbedLayout"
})

defineProps({
    tabs: {
        type: Array,
        required: true
    }
})

const route = useRoute()
const tabbedStore = useTabbedStore()

onUpdated(() => {
    tabbedStore.setRoutePath(route.path);
})

onBeforeMount(() => {
    tabbedStore.setRoutePath(route.path);
})

onBeforeUnmount(() => {
    tabbedStore.path = ''
})

const { loadTrigger } = useInfiniteScroll(tabbedStore.getTabbedData,
    {
        hasMore: () => tabbedStore.hasMore,
        isLoading: () => tabbedStore.loading,
        immediate: false // автоматический запуск при монтировании
    }
)

</script>

<style scoped>
.end-text {
    color: #b9c3d2;
    font-size: 18px;
    text-align: center;
}
</style>
