<template>
    <div class="container">
        <div>
            <Post />
        </div>
    </div>
</template>

<script setup>

import { useRoute } from "vue-router";
import router from "@/router/router.js";
import Post from "@/pages/Posts/show.vue";
defineOptions({
    name: "show"
})

const route = useRoute();

</script>

<style scoped>

</style>
