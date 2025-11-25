<template>
    <div class="container">
        <div>
            <div>
                <button
                    @click="goBack">

                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    <span class="text-red-500">Назад к основной ветке</span>
                </button>
            </div>
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

const goBack = () => {
    router.back();
}

</script>

<style scoped>

</style>
