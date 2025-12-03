<template>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-white text-center text-3xl font-bold mb-8">Темы для сообществ</h1>

        <div class="flex flex-col md:flex-row items-center justify-center space-y-4 md:space-y-0 md:space-x-4 w-full">
            <div class="w-full md:w-auto">
                <input
                    class="w-full md:w-64 px-4 py-2 text-white bg-gray-800 rounded-lg border border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-200"
                    type="text"
                    maxlength="255"
                    placeholder="Название темы"
                    v-model="name"
                >
            </div>

            <div class="w-full md:w-auto">
                <label class="block text-white mb-2 md:mb-0 md:inline-block md:mr-2" for="topic-select">Выберите тему:</label>
                <select
                    id="topic-select"
                    class="w-full md:w-64 px-4 py-2 text-white bg-gray-800 rounded-lg border border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-200"
                    v-model="selectedTopic"
                >
                    <option value="" disabled>--Выберите тему --</option>
                    <option v-for="topic in topics" :key="topic.id" :value="topic.id" class="text-white bg-gray-800">
                        {{ topic.title }}
                    </option>
                </select>
            </div>

            <div class="w-full md:w-auto">
                <button
                    class="w-full md:w-auto px-6 py-2 bg-indigo-600 rounded-lg text-white font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition duration-200 cursor-pointer"
                    @click="submit"
                >
                    Добавить
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";

defineOptions({
    name: "index"
});

const name = ref('');
const selectedTopic = ref('');
const topics = ref([]);

onMounted(() => {
    getTopics();
});

const getTopics = async () => {
    const { data } = await axios.get('/api/topics');
    topics.value = data;
};

const submit = async () => {
    // Логика отправки
    console.log('Название:', name.value);
    console.log('Выбранная тема:', selectedTopic.value);
};
</script>

<style scoped>
/* Дополнительные стили, если нужны */
</style>
