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
        to: { name: 'comments.posts' },
        name: 'comments.posts',
        title: 'Комментарии к постам'
    },
    {
        to: { name: 'comments.replies' },
        name: 'comments.replies',
        title: 'Ответы на комментарии'
    }
]
</script>

<style scoped>

</style>
