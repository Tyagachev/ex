<template>
    <div class="container comments-layout">
        <!-- Навигация внутри комментариев -->
        <nav class="mb-6 flex space-x-4 border-b border-gray-600 pb-2">
            <router-link
                :to="{ name: 'answers.reply' }"
                class="px-4 py-2 transition-colors"
                :class="{
                    'bg-gray-600 text-white': $route.name === 'answers.reply',
                    'text-gray-600 hover:bg-gray-100': $route.name !== 'answers.reply'
                }"
            >
                Ответы на комментарии
            </router-link>
            <router-link
                :to="{ name: 'answers.posts' }"
                class="px-4 py-2 transition-colors"
                :class="{
                    'bg-gray-600 text-white': $route.name === 'answers.posts',
                    'text-gray-600 hover:bg-gray-100': $route.name !== 'answers.posts'
                }"
            >
                Ответы на посты
            </router-link>
        </nav>

        <div class="comments-content">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
export default {
    name: "AnswersLayout"
}
</script>

<style scoped>

</style>
