<template>

</template>

<script setup>

import {computed, onMounted} from "vue";
import {useTabbedStore} from "@/stores/tabbed.js";

defineOptions({
    name: "index"
})

const tabbedStore = useTabbedStore()
const comments = computed(() => tabbedStore.tabbedData)

onMounted(() => {
    if (tabbedStore.tabbedData.length) {
        tabbedStore.resetLoadedStatusAndRefresh()
    }
    tabbedStore.getTabbedData();
})
</script>

<style scoped>

</style>
