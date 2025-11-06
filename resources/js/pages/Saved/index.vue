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
        to: { name: 'saved.posts' },
        name: 'saved.posts',
        title: 'Посты'
    },
    {
        to: { name: 'saved.comments' },
        name: 'saved.comments',
        title: 'Комментарии'
    }
]
</script>

<style scoped>

</style>
