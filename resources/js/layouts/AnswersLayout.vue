<template>
    <TabbedLayout :tabs="tabs"/>
</template>

<script setup>
import TabbedLayout from "@/layouts/TabbedLayout.vue";
defineOptions({
    name: "AnswersLayout"
})

const tabs = [
    {
        to: { name: 'answers.reply' },
        name: 'answers.reply',
        title: 'Ответы на комментарии'
    },
    {
        to: { name: 'answers.posts' },
        name: 'answers.posts',
        title: 'Ответы на посты'
    }
]
</script>

<style scoped>

</style>
