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
                v-model="limitedTitle"
                type="text"
                class="my-2 w-full"
                placeholder="Заголовок поста"
                maxlength="255"
            />
            <!-- Счетчик символов для заголовка -->
            <div class="absolute right-2 bottom-2 text-xs text-gray-400"
                 :class="{ 'text-red-500': limitedTitle.length > 250 }">
                {{ limitedTitle.length }}/255
            </div>
        </div>

        <!-- Блоки -->
        <div v-for="(block, index) in blocks" :key="index" class="my-4">
            <!-- Текстовый блок -->
            <div v-if="block.type === 'text'">
                <QuillEditor v-model="block.content" />
            </div>

            <!-- Блок изображения -->
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
                <div v-if="!block.preview" class="border border-gray-400 p-5 text-gray-400">
                    Перетащите изображение или нажмите для выбора
                </div>
                <img
                    v-if="block.preview"
                    :src="block.preview"
                    class="max-h-70 mx-auto rounded shadow"
                />
            </div>
        </div>

        <!-- Счетчик блоков изображений -->
        <div v-if="imageBlocksCount < 10" class="text-sm text-gray-400 mb-2 text-center">
            Блоков с изображениями: {{imageBlocksCount}}/10
        </div>
        <div v-else class="text-sm text-red-400 mb-2 text-center">
            {{alertBlocks}}
        </div>

        <!-- Кнопка + -->
        <div class="text-center">
            <button
                type="button"
                @click="showMenu = !showMenu"
                class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center text-2xl
         shadow-md transition transform hover:scale-110 hover:bg-blue-500 focus:outline-none cursor-pointer"
                :disabled="imageBlocksCount >= 10"
                :class="{ 'opacity-50 cursor-not-allowed': imageBlocksCount >= 10 }"
            >
                +
            </button>
        </div>

        <!-- Меню выбора блока -->
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
            @click="submitContent"
            class="submit-btn my-6 cursor-pointer"
            :disabled="title.length === 0 || title.length > 255"
        >
            Отправить
        </BlueButton>
    </div>
</template>

<script setup>
import TextInput from "@/components/Inertia/TextInput.vue";
import {computed, ref} from "vue";
import QuillEditor from "@/components/Quill/QuillEditor.vue";
import BlueButton from "@/UI/Buttons/BlueButton.vue";
import {usePostsStore} from "@/stores/posts.js";
import {useRouter} from "vue-router";


defineOptions({
    name: "Create"
})
const router = useRouter();
const postStore = usePostsStore();

const title = ref("");
const alertBlocks = ref("");
const blocks = ref([]);
const showMenu = ref(false);
const from = "post";

const goBack = () => {
    router.back();
}

const limitedTitle = computed({
    get: () => title.value,
    set: (newValue) => {
        if (newValue.length > 255) {
            title.value = newValue.substring(0, 255)
        } else {
            title.value = newValue
        }
    }
});

const imageBlocksCount = computed(() => {
    const imageBlocks = blocks.value.filter(block => {
        return block.type === 'image';
    });
    return imageBlocks.length;
});

const handleFileUpload = (e, index) => {
    const file = e.target.files[0];

    if (!file) {
        return;
    }

    blocks.value[index].file = file;
    blocks.value[index].preview = URL.createObjectURL(file);
}

const addBlock = (type) => {
    if (type === "text") {
        blocks.value.push({ type: "text", content: "" });
    } else if (type === "image") {
        // Проверяем, не превышен ли лимит изображений
        if (imageBlocksCount.value < 10) {
            blocks.value.push({ type: "image", file: null, preview: null });
        } else {
            alertBlocks.value = 'Максимальное количество изображений - 10';
        }
    }
    showMenu.value = false;
}

const submitContent = () => {
    postStore.storePosts(title, from, blocks);
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
