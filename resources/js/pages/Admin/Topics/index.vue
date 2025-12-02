<template>
    <div class="container">
        <h1 class="text-white text-center">Топики для тем</h1>
        <div class="flex w-full">
            <div>
                <input class="px-2.5 py-1.5 text-white
                rounded-md dark:bg-gray-800
                border-gray-300 border-gray-300
                shadow-sm focus:border-indigo-500
                focus:ring-indigo-500" type="text" maxlength="255"
                v-model="title">
            </div>
            <div>
                <button class="mx-2 px-2.5 py-1.5 bg-indigo-500 rounded-md text-white cursor-pointer hover:bg-indigo-400" @click="submit">Добавить</button>
            </div>
        </div>
        <div class="my-5">
            <table class="w-full table-auto border-collapse text-sm">
                <thead>
                <tr>
                    <th class="border-b border-gray-200 p-4 pt-0 pb-3 pl-8 text-left font-medium text-gray-400 dark:border-gray-600 dark:text-gray-200">#</th>
                    <th class="border-b border-gray-200 p-4 pt-0 pb-3 pl-8 text-left font-medium text-gray-400 dark:border-gray-600 dark:text-gray-200">Название</th>
                    <th class="border-b border-gray-200 p-4 pt-0 pb-3 pl-8 text-left font-medium text-gray-400 dark:border-gray-600 dark:text-gray-200">Создан</th>
                    <th class="border-b border-gray-200 p-4 pt-0 pb-3 pl-8 text-left font-medium text-gray-400 dark:border-gray-600 dark:text-gray-200">Редактировать</th>
                    <th class="border-b border-gray-200 p-4 pt-0 pb-3 pl-8 text-left font-medium text-gray-400 dark:border-gray-600 dark:text-gray-200">Удалить</th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800">
                <tr v-for="topic in topics">
                    <td class="border-b border-gray-100 p-4 pl-8 text-gray-500 dark:border-gray-700 dark:text-gray-400">{{ topic.id }}</td>
                    <td class="border-b border-gray-100 p-4 pl-8 text-gray-500 dark:border-gray-700 dark:text-gray-400">{{ topic.title }}</td>
                    <td class="border-b border-gray-100 p-4 pl-8 text-gray-500 dark:border-gray-700 dark:text-gray-400">{{ topic.createdAtHuman }}</td>
                    <td class="border-b border-gray-100 p-4 pl-8 dark:border-gray-700"><p class="text-blue-500 hover:text-blue-400 cursor-pointer" @click="edit(topic)">Редактировать</p></td>
                    <td class="border-b border-gray-100 p-4 pl-8 text-gray-500 dark:border-gray-700 dark:text-gray-400"><p class="text-red-500 hover:text-red-200 cursor-pointer" @click="destroy(topic)">Удалить</p></td>
                </tr>
                </tbody>
            </table>
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
