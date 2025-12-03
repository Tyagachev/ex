<template>
    <div class="min-h-screen bg-gray-900 p-6">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-3xl font-bold text-white text-center mb-8">
                Топики для тем
            </h1>

            <!-- Форма добавления -->
            <div class="bg-gray-800 rounded-xl p-6 mb-8">
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Поле ввода -->
                    <div class="flex-1">
                        <input
                            type="text"
                            maxlength="255"
                            v-model="title"
                            placeholder="Название топика"
                            class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white
                                   placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                    </div>

                    <!-- Кнопка -->
                    <div>
                        <button
                            @click="submit"
                            class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg
                                   hover:bg-indigo-700 transition-colors duration-200 cursor-pointer"
                        >
                            Добавить
                        </button>
                    </div>
                </div>
            </div>

            <!-- Таблица -->
            <div class="bg-gray-800 rounded-xl overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-700">
                    <tr>
                        <th class="py-3 px-6 text-left text-sm font-medium text-gray-300">#</th>
                        <th class="py-3 px-6 text-left text-sm font-medium text-gray-300">Название</th>
                        <th class="py-3 px-6 text-left text-sm font-medium text-gray-300">Создан</th>
                        <th class="py-3 px-6 text-left text-sm font-medium text-gray-300">Действия</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                    <tr
                        v-for="topic in topics"
                        :key="topic.id"
                        class="hover:bg-gray-750 transition-colors duration-150"
                    >
                        <td class="py-4 px-6 text-gray-300">{{ topic.id }}</td>
                        <td class="py-4 px-6 text-white font-medium">{{ topic.title }}</td>
                        <td class="py-4 px-6 text-gray-300">{{ topic.createdAtHuman }}</td>
                        <td class="py-4 px-6">
                            <button
                                @click="destroy(topic)"
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
    </div>
</template>

<script setup>

import {onMounted, ref} from "vue";

defineOptions({
    name: "index"
})

onMounted(() => {
    getTopics();
})

const title = ref('');
const topics = ref([]);

const getTopics = async () => {
    const {data} = await axios.get('/api/topics')
    topics.value = data
}
const submit = async () => {
   const {data} = await axios.post('/api/topics', {
       title: title.value
   })
    if (data) {
        title.value = '';
        await getTopics();
    }
}
const destroy = async (topic) => {
    const {data} = await axios.delete(`/api/topics/${topic.id}`)
    if (data) {
        await getTopics();
    }
}

</script>

<style scoped>

</style>
