<template>
    <div class="container">
            <nav class="mb-6 flex space-x-4 border-b border-gray-600 pb-2">
                <div v-for="tab in tabs">
                    <div v-if="!tab.subtitle">
                        <router-link
                            :key="tab.name"
                            :to="tab.to"
                            class="px-4 py-2 transition-colors rounded-t"
                            :class="{
                            'bg-gray-600 text-white': route.name === tab.name,
                            'text-gray-600 hover:bg-gray-100': route.name !== tab.name
                            }">
                            {{ tab.title }}
                        </router-link>
                    </div>
                    <div v-else>
                        <div class="text-white">
                            {{tab.title}}
                        </div>
                    </div>
                </div>
        </nav>
        <div class="comments-content">
            <router-view></router-view>
        </div>
    </div>
</template>

<script setup>
import {useTabbedStore} from "@/stores/tabbed.js";
import {useRoute} from "vue-router";
import {onBeforeMount, onBeforeUnmount, onUpdated} from "vue";

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

</script>

<style scoped>
.end-text {
    color: #b9c3d2;
    font-size: 18px;
    text-align: center;
}
</style>
