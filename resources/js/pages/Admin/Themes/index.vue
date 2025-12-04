<template>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-white text-center text-3xl font-bold mb-8">Темы для сообществ</h1>

        <div class="flex flex-col md:flex-row items-center justify-center space-y-4 md:space-y-0 md:space-x-4 w-full mb-8">
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
                <label class="block text-white mb-2 md:mb-0 md:inline-block md:mr-2" for="topic-select"></label>
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
        <!-- Таблица -->
        <div class="bg-gray-800 rounded-xl overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-700">
                <tr>
                    <th class="py-3 px-6 text-left text-sm font-medium text-gray-300">#</th>
                    <th class="py-3 px-6 text-left text-sm font-medium text-gray-300">Название</th>
                    <th class="py-3 px-6 text-left text-sm font-medium text-gray-300">Топик</th>
                    <th class="py-3 px-6 text-left text-sm font-medium text-gray-300">Создан</th>
                    <th class="py-3 px-6 text-left text-sm font-medium text-gray-300">Действия</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                <tr
                    v-for="theme in themes"
                    :key="theme.id"
                    class="hover:bg-gray-750 transition-colors duration-150"
                >
                    <td class="py-4 px-6 text-gray-300">{{ theme.id }}</td>
                    <td class="py-4 px-6 text-white font-medium">{{ theme.name }}</td>
                    <td class="py-4 px-6 text-white font-medium">{{ theme.topics[0]?.title }}</td>
                    <td class="py-4 px-6 text-gray-300">{{ theme.createdAtHuman }}</td>
                    <td class="py-4 px-6">
                        <button
                            @click="destroy(theme)"
                            class="text-red-400 hover:text-red-300 font-medium transition-colors duration-200 cursor-pointer"
                        >
                            Удалить
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
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
const themes = ref([]);

onMounted(() => {
    getTopics();
    getThemes();
});

const getTopics = async () => {
    const { data } = await axios.get('/api/topics');
    topics.value = data;
};

const getThemes = async () => {
    const { data } = await axios.get('/api/themes');
    themes.value = data;
};

const submit = async () => {

    const {data} = await axios.post('/api/themes', {
        name: name.value,
        topicId: selectedTopic.value
    })
    if (data) {
        name.value = '';
        selectedTopic.value = '';
        await getThemes();
    }
};

const destroy = async (theme) => {
    const {data} = await axios.delete(`/api/themes/${theme.id}`)
    if (data) {
        await getThemes();
    }
}
</script>

<style scoped>
/* Дополнительные стили, если нужны */
</style>
