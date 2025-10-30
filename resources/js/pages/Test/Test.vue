<template>
    <div>
        <button class="bg-blue-300 p-2" @click="test">Кликай</button>
    </div>
    <div>
        <CommentNote v-if="t.user"

         :comment="t"/>
    </div>
</template>

<script setup>
import CommentNote from "@/components/Comment/CommentNote.vue";
import {onMounted, reactive, ref} from "vue";

defineOptions({
        name: "Test"
})

onMounted(async () => {
    await test()
})

let t = ref({})

const test = async () => {
    const res = await axios.get(`/api/comments/${1}`);
    console.log(res.data);
    t.value = res.data;
    console.log(t.value);
}
</script>


<style scoped>

</style>
