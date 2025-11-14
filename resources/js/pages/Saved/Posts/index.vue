<template>
    <Posts
        :isTabbedPage = tabbedStore.isTabbedPage
    />
</template>

<script setup>
import {computed, onMounted} from "vue";
import {useTabbedStore} from "@/stores/tabbed.js";

import Posts from  '@/pages/Posts/index.vue'

defineOptions({
    name: "index"
})

const tabbedStore = useTabbedStore();

onMounted(() => {
    if (tabbedStore.tabbedData.length) {
        tabbedStore.resetLoadedStatusAndRefresh()
    } else {
        tabbedStore.getTabbedData();
    }
})


</script>

<style scoped>

</style>
