<template>
    <TabbedLayout :tabs="tabs"/>
</template>

<script setup>
import TabbedLayout from "@/layouts/TabbedLayout.vue";
defineOptions({
    name: "index"
})

const tabs = [
    {
        to: { name: 'answers.posts' },
        name: 'answers.posts',
        title: 'Ответы на посты'
    },
    {
        to: { name: 'answers.replies' },
        name: 'answers.replies',
        title: 'Ответы на комментарии'
    }
]
</script>

<style scoped>

</style>
