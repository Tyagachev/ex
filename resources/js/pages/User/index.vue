<template>
    <div class="container">
        <TabbedLayout :tabs="tabs"/>
    </div>
</template>

<script setup>
import TabbedLayout from "@/layouts/TabbedLayout.vue";

defineOptions({
    name: "Index"
})

const tabs = [
    {
        to: { name: 'posts.me' },
        name: 'posts.me',
        title: 'Мои посты',
        subtitle: true
    }
]

</script>

<style scoped>

</style>
