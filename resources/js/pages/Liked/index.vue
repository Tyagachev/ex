<template>
    <TabbedLayout :tabs="tabs"/>
</template>

<script setup>
import {defineOptions} from "vue";
import TabbedLayout from "@/layouts/TabbedLayout.vue";

defineOptions({
    name: "index"
})

const tabs = [
    {
        to: { name: 'up.page' },
        name: 'up.page',
        title: 'Понравилось'
    },
    {
        to: { name: 'down.page' },
        name: 'down.page',
        title: 'Не понравилось'
    }
]
</script>

<style scoped>

</style>
