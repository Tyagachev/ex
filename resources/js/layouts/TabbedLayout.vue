<template>
    <div class="container">
            <nav class="mb-6 flex space-x-4 border-b border-gray-600 pb-2">
                <router-link
                    v-for="tab in tabs"
                    :key="tab.name"
                    :to="tab.to"
                    class="px-4 py-2 transition-colors rounded-t"
                    :class="{
                    'bg-gray-600 text-white': $route.name === tab.name,
                    'text-gray-600 hover:bg-gray-100': $route.name !== tab.name
                }"
                >
                    {{ tab.title }}
                </router-link>
        </nav>
        <div class="comments-content">
            <router-view></router-view>
        </div>
    </div>
</template>

<script setup>

defineOptions({
    name: "TabbedLayout"
})

defineProps({
    tabs: {
        type: Array,
        required: true
    }
})
</script>

<style scoped>

</style>
