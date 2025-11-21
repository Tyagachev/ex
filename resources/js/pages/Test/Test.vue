<template>
    <div>
        <div v-if="loading">Загрузка...</div>
        <Post v-else-if="post" :post="post" />
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRoute } from "vue-router";
import Post from '@/pages/Posts/show.vue'

const route = useRoute();
const post = ref(null); // <-- изменили на null
const loading = ref(true);

onMounted(async () => {
    await test();
    loading.value = false;
});

const test = async () => {
    const { data } = await axios.get(`api/comments/1`);
    post.value = data.post;
}
</script>
