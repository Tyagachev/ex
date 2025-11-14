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
        to: { name: 'saves.posts' },
        name: 'saves.posts',
        title: 'Посты'
    },
    {
        to: { name: 'saves.comments' },
        name: 'saves.comments',
        title: 'Комментарии'
    }
]
</script>

<style scoped>

</style>
