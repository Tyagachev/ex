<template>
    <Posts
        :isTabbedPage = tabbedStore.isTabbedPage
    />
</template>

<script setup>
import {onMounted, watch} from "vue";
import {useTabbedStore} from "@/stores/tabbed.js";

import Posts from '@/pages/Posts/index.vue'

defineOptions({
    name: "index"
})

const tabbedStore = useTabbedStore();
const updateData = () => {
    if (tabbedStore.tabbedData.length) {
        tabbedStore.resetLoadedStatusAndRefresh()
    } else {
        tabbedStore.getTabbedData();
    }
}
onMounted(() => {
    updateData()
})

watch(() => tabbedStore.path, () => {
    updateData();
})



</script>

<style scoped>

</style>
