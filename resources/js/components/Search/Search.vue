<template>
    <div class="flex-1 mx-4 min-w-0">
        <div class="search w-full">
            <input
                name="search"
                type="text"
                class="search-input"
                placeholder="Поиск по сайту"
                v-model="newPostTitle"
                @input="searchPost"
            >
        </div>
    </div>
</template>

<script setup>
import {ref} from "vue";

defineOptions({
    name: "Search"
})

const newPostTitle = ref('');
const searchPost = () => {};
</script>

<style scoped>

.search {
    display: flex;
    max-width: 600px;
    margin: 0 auto;
}

.search-input {
    width: 100%;
    background: #2a2a2a;
    border: 1px solid #3a3a3a;
    border-radius: 20px;
    padding: 10px 20px;
    font-size: 14px;
    color: #e0e0e0;
    outline: none;
    transition: all 0.3s;
}

.search-input:focus {
    border-color: #ff4500;
    background: #333;
    box-shadow: 0 0 0 2px rgba(255, 69, 0, 0.2);
}

.search-input::placeholder {
    color: #8a8a8a;
}

</style>
