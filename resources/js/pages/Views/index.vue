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
        to: { name: 'views.page' },
        name: 'views.page',
        title: 'Посмотренное',
        subtitle: 'Посмотренное'
    }
]

</script>

<style scoped>

</style>
