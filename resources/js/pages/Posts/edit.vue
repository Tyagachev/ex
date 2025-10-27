<template>
    <div class="container">
        <button
            @click="goBack"
            class="mb-2 py-2.5 px-4 bg-orange-600 hover:bg-gray-500 text-sm text-white rounded-full cursor-pointer"
        >
            Назад
        </button>
        <div class="relative">
            <TextInput
                v-model="form.title"
                type="text"
                class="my-2 w-full"
                placeholder="Заголовок поста"
                maxlength="255"
                @input="updateTitle"
            />
            <div class="absolute right-2 bottom-2 text-xs text-gray-400"
                 :class="{ 'text-red-500': form.title.length > 250 }">
                {{ form.title.length }}/255
            </div>
        </div>
        <div v-for="(block, index) in form.blocks" :key="index" class="my-4">
            <div v-if="block.type === 'text'">
                <QuillEditor v-model="block.content" />
            </div>

            <div
                v-else-if="block.type === 'image'"
                class="border p-4 rounded relative text-center"
            >
                <input
                    type="file"
                    accept="image/*"
                    class="absolute inset-0 opacity-0 cursor-pointer"
                    @change="handleFileUpload($event, index)"
                />
                <div v-if="!block.preview && !block.url" class="text-gray-500">
                    Перетащите изображение или нажмите для выбора
                </div>
                <img
                    v-if="block.preview || block.url"
                    :src="block.preview || block.url"
                    class="max-h-70 mx-auto rounded shadow"
                />
                <button
                    v-if="block.url"
                    @click="removeImage(index)"
                    class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded"
                >
                    ✕
                </button>
            </div>
        </div>
        <div v-if="imageBlocksCount < 10" class="text-sm text-gray-400 mb-2 text-center">
            Блоков с изображениями: {{imageBlocksCount}}/10
        </div>
        <div v-else class="text-sm text-red-400 mb-2 text-center">
            {{alertBlocks}}
        </div>

        <div class="text-center">
            <button
                type="button"
                @click="showMenu = !showMenu"
                class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center text-2xl
         shadow-md transition transform hover:scale-110 hover:bg-blue-500 focus:outline-none"
                :disabled="imageBlocksCount >= 10"
                :class="{ 'opacity-50 cursor-not-allowed': imageBlocksCount >= 10 }"
            >
                +
            </button>
        </div>

        <div v-if="showMenu" class="flex justify-center gap-4 mt-2">
            <button
                @click="addBlock('text')"
                class="px-4 py-2 bg-gray-800 text-white rounded-lg shadow hover:bg-gray-700 transition"
            >
                Текст
            </button>
            <button
                @click="addBlock('image')"
                class="px-4 py-2 bg-gray-800 text-white rounded-lg shadow hover:bg-gray-700 transition"
                :disabled="imageBlocksCount >= 10"
                :class="{ 'opacity-50 cursor-not-allowed': imageBlocksCount >= 10 }"
            >
                Изображение
            </button>
        </div>
        <BlueButton
            @click="submitUpdateContent"
            class="submit-btn my-6 cursor-pointer"
            :disabled="form.title.length === 0 || form.title.length > 255"
        >
            Обновить
        </BlueButton>
    </div>
</template>

<script setup>

import {computed, onMounted, reactive, ref} from "vue";
import {usePostsStore} from "@/stores/posts.js";
import {useRoute, useRouter} from "vue-router";
import TextInput from "@/components/Inertia/TextInput.vue";
import QuillEditor from "@/components/Quill/QuillEditor.vue";
import BlueButton from "@/UI/Buttons/BlueButton.vue";

defineOptions({
    name: "Edit"
})

onMounted( async () => {
    await postStore.getPost(route.params.id);
    initializeFormData();
})

const post = computed(() => postStore.post);
const imageBlocksCount = computed(() => {
    return form.blocks.filter(block => block.type === 'image').length;
});

const postStore = usePostsStore();
const router = useRouter();
const route = useRoute();

let alertBlocks = ref('');
let showMenu = ref(false);
const form = reactive({
    title: '',
    from: 'post',
    blocks: []
});

/**
 * Добавление блока текста
 * или изображения
 * @param type
 */
const addBlock = (type) => {
    if (type === 'text') {
        form.blocks.push({ type: 'text', content: '' });
    } else if (type === 'image' && imageBlocksCount.value < 10) {
        form.blocks.push({ type: 'image', file: null, preview: null });
        console.log(form.blocks);
    } else {
        alertBlocks.value = 'Максимальное количество изображений - 10';
    }
    showMenu.value = false;
}

/**
 * Удаление изображения
 * @param index
 */
const removeImage = (index) => {
    form.blocks.splice(index, 1);
}

/**
 * Инициализация данных
 */
const initializeFormData = () => {
    if (!post) return;

    const postData = Array.isArray(post) ? post[0] : post;

    if (postData) {
        form.title = postData.value.title || '';

        if (postData.value.blocks && Array.isArray(postData.value.blocks)) {
            form.blocks = postData.value.blocks.map(block => {
                if (block.type === 'image') {
                    return {
                        ...block,
                        url: block.path // Используем path из БД
                    };
                }
                return block;
            });
        }
    }
}

/**
 * Счетчик подсчета символов
 */
const updateTitle = () => {
    if (form.title.length > 255) {
        form.title = form.title.substring(0, 255);
    }
}

/**
 * Подгрузка файлов
 * @param e
 * @param index
 */
const handleFileUpload = (e, index) => {
    const file = e.target.files[0];

    if (!file) {
        return;
    }

    form.blocks[index].file = file;
    form.blocks[index].preview = URL.createObjectURL(file);
}

/**
 * Кнопка Назад
 */
const goBack = () => {
    router.back();
}
/**
 * Отправка данных
 */
const submitUpdateContent = () => {
    postStore.storeUpdatePosts(form);
}


</script>

<style scoped>
input[type="text"] {
    background: #1f2937;
    border: 1px solid #374151;
    border-radius: 0.5rem;
    padding: 0.5rem 0.75rem;
    color: #f9fafb;
    width: 100%;
}

input[type="text"]::placeholder {
    color: #9ca3af;
}

input[type="text"]:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 1px #3b82f6;
}

button.submit-btn {
    background: #3b82f6;
    color: #fff;
    padding: 0.5rem 1.25rem;
    border-radius: 0.5rem;
    font-weight: 500;
    transition: 0.2s;
}

button.submit-btn:hover:not(:disabled) {
    background: #2563eb;
}

button.submit-btn:focus {
    outline: none;
    box-shadow: 0 0 0 2px #2563eb66;
}

button.submit-btn:disabled {
    background: #6b7280;
    cursor: not-allowed;
}

.opacity-50 {
    opacity: 0.5;
}

.cursor-not-allowed {
    cursor: not-allowed;
}
</style>
