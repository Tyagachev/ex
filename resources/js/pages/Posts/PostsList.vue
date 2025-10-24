<template>
    <div>{{post}}</div>
</template>

<script setup>
import {defineOptions} from "vue";

defineOptions({
    name: "PostsList"
})

defineProps({
    post: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped>

</style>
