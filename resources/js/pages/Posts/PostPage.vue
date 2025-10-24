<template>
    <div class="container flex-1 align-center">
        <PostsList  v-for="post in posts" :post/>
        <div ref="loadTrigger" class="h-8"></div>
    </div>
</template>

<script setup>
import {computed, defineOptions, onMounted, onUnmounted, nextTick} from "vue";
import {usePostsStore} from "@/stores/posts.js";
import PostsList from "@/pages/Posts/PostsList.vue";
import { useInfiniteScroll } from '@/composables/useInfiniteScroll'


defineOptions({
    name: "PostPage",
})

const postsStore = usePostsStore()

const { target: loadTrigger } = useInfiniteScroll(postsStore.getPosts())

const posts = computed(() => postsStore.allPosts);
let observer = null

onMounted(async () => {
    await initPosts()
    window.addEventListener('scroll', saveScrollPosition)
})

onUnmounted(() => {
    window.removeEventListener('scroll', saveScrollPosition)
    postsStore.saveScrollPosition(window.scrollY)
})

const initPosts = async () => {
    if (!posts.length) {
        await postsStore.getPosts()
    }
    await nextTick()
    window.scrollTo(0, postsStore.scrollPosition)
}


const  saveScrollPosition = () => {
    postsStore.saveScrollPosition(window.scrollY)
}

</script>

<style scoped>

</style>
