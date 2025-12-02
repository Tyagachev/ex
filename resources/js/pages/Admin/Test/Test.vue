<template>
    <div>
        <div v-if="loading">Загрузка...</div>
        <Post />
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRoute } from "vue-router";
import {usePostsStore} from "@/stores/posts.js";
import {useCommentsStore} from "@/stores/comments.js";
import Post from '@/pages/Posts/show.vue'

const route = useRoute();
const post = ref(null); // <-- изменили на null
const loading = ref(true);
const postStore = usePostsStore()

onMounted(async () => {
    await test();
    loading.value = false;
});

const test = async () => {
    const { data } = await axios.get(`api/comments/24`);
    postStore.setPost(data.post, data.replies);
}
</script>
