<template>
    <div class="container">
        <div class="wrapper">
            <button
                @click="goBack"
                class="px-2.5 py-1.5 bg-orange-600 hover:bg-gray-500 text-sm text-white rounded-full cursor-pointer"
            >
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
        <div>
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

        <div v-for="(block, index) in form.blocks" :key="index" class="my-4 relative group">
            <!-- Кнопка удаления для любого блока -->
            <button
                @click="openDeleteModal(index)"
                class="absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs group-hover:bg-red-400  z-10 cursor-pointer"
                title="Удалить блок"
            >
                ✕
            </button>

            <div v-if="block.type === 'text'">
                <QuillEditor
                    v-model="block.content"
                    @char-count-update="(count) => handleCharCountUpdate(index, count)"
                />
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
            </div>
        </div>
            <hr class="text-gray-400">
            <!-- Счетчик общего количества символов -->
            <div class="text-sl  mt-2 text-gray-400 mb-2 text-center">
                Общее количество символов (без пробелов):
                <span v-if="totalCharCount < 5000">{{ totalCharCount }}<span class="text-gray-400"> / 5000</span></span>
                <span v-else class="text-red-500">{{ totalCharCount }}<span class="text-gray-400"> / 5000</span></span>
            </div>
            <!-- Счетчик блоков изображений -->
            <div v-if="imageBlocksCount < 10" class="text-sl text-gray-400 mb-2 text-center">
                Блоков с изображениями: {{imageBlocksCount}} / 10
            </div>
            <div v-else class="text-sm text-red-400 mb-2 text-center">
                {{alertBlocks}}
            </div>

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

        <div v-if="showMenu" class="flex justify-center gap-4 mt-2">
            <button
                @click="addBlock('text')"
                class="px-4 py-2 bg-gray-800 text-white rounded-lg shadow hover:bg-gray-700 cursor-pointer transition"
            >
                Текст
            </button>
            <button
                @click="addBlock('image')"
                class="px-4 py-2 bg-gray-800 text-white rounded-lg shadow hover:bg-gray-700 cursor-pointer transition"
                :disabled="imageBlocksCount >= 10"
                :class="{ 'opacity-50 cursor-not-allowed': imageBlocksCount >= 10 }"
            >
                Изображение
            </button>
        </div>
        <BlueButton
            @click="submitUpdateContent"
            class="submit-btn my-6 cursor-pointer"
            :disabled="form.title.length === 0 || form.title.length > 255 || totalCharCount >= 5000"
        >
            Обновить
        </BlueButton>

        <!-- Модальное окно подтверждения удаления -->
        <Modal
            :show="showDeleteModal"
            @confirm="confirmDelete"
            @cancel="closeDeleteModal"
        >
            <template #title>Удаление блока</template>
            <template #content>Вы уверены, что хотите удалить этот блок? Это действие нельзя отменить.</template>
        </Modal>
        </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref, watch } from "vue";
import { usePostsStore } from "@/stores/posts.js";
import { useRoute, useRouter } from "vue-router";
import TextInput from "@/components/Inertia/TextInput.vue";
import QuillEditor from "@/components/Quill/QuillEditor.vue";
import BlueButton from "@/components/UI/Buttons/BlueButton.vue";
import Modal from "@/components/UI/Modal.vue";

defineOptions({
    name: "Edit"
})

// Добавляем ref для хранения количества символов каждого текстового блока
const blockCharCounts = ref({});

// Состояние для модального окна удаления
const showDeleteModal = ref(false);
const blockToDelete = ref(null);

onMounted(async () => {
    await postStore.getPost(route.params.id);
    initializeFormData();
    // Инициализируем счетчики символов после загрузки данных
    initializeCharCounts();
})

const post = computed(() => postStore.post);
const imageBlocksCount = computed(() => {
    return form.blocks.filter(block => block.type === 'image').length;
});

// Вычисляем общее количество символов
const totalCharCount = computed(() => {
    return Object.values(blockCharCounts.value).reduce((sum, count) => sum + count, 0);
});

// Следим за изменением общего количества символов и записываем в хранилище
watch(totalCharCount, (newCount) => {
    postStore.updateTotalCharCount(newCount);
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
 * Инициализация счетчиков символов для существующих текстовых блоков
 */
const initializeCharCounts = () => {
    form.blocks.forEach((block, index) => {
        if (block.type === 'text') {
            // Вычисляем начальное количество символов для существующих блоков
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = block.content || '';
            const text = tempDiv.textContent || tempDiv.innerText || '';
            const cleanText = text.replace(/\s/g, '');
            blockCharCounts.value[index] = cleanText.length;
        }
    });
}

/**
 * Обработчик обновления количества символов из QuillEditor
 */
const handleCharCountUpdate = (index, count) => {
    blockCharCounts.value[index] = count;
}

/**
 * Открытие модального окна удаления
 */
const openDeleteModal = (index) => {
    blockToDelete.value = index;
    showDeleteModal.value = true;
}

/**
 * Закрытие модального окна удаления
 */
const closeDeleteModal = () => {
    showDeleteModal.value = false;
    blockToDelete.value = null;
}

/**
 * Подтверждение удаления блока
 */
const confirmDelete = () => {
    if (blockToDelete.value !== null) {
        const index = blockToDelete.value;

        // Удаляем блок
        form.blocks.splice(index, 1);

        // Удаляем счетчик символов для этого блока
        if (blockCharCounts.value[index]) {
            delete blockCharCounts.value[index];
        }

        // Перенумеруем оставшиеся счетчики
        const newCounts = {};
        form.blocks.forEach((block, newIndex) => {
            if (block.type === 'text') {
                // Находим соответствующий старый индекс
                const oldValue = Object.values(blockCharCounts.value)[newIndex];
                newCounts[newIndex] = oldValue || 0;
            }
        });
        blockCharCounts.value = newCounts;
    }

    closeDeleteModal();
}

/**
 * Добавление блока текста
 * или изображения
 * @param type
 */
const addBlock = (type) => {
    if (type === 'text') {
        const newIndex = form.blocks.length;
        form.blocks.push({ type: 'text', content: '' });
        // Инициализируем счетчик для нового блока
        blockCharCounts.value[newIndex] = 0;
    } else if (type === 'image' && imageBlocksCount.value < 10) {
        form.blocks.push({ type: 'image', file: null, preview: null });
    } else {
        alertBlocks.value = 'Максимальное количество изображений - 10';
    }
    showMenu.value = false;
}

/**
 * Инициализация данных
 */
const initializeFormData = () => {
    if (!post.value) return;

    const postData = Array.isArray(post.value) ? post.value[0] : post.value;

    if (postData) {
        form.title = postData.title || '';

        if (postData.blocks && Array.isArray(postData.blocks)) {
            form.blocks = postData.blocks.map(block => {
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
/* Существующие стили без изменений */
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

/* Новые стили для кнопки удаления */
.group:hover .group-hover\:opacity-100 {
    opacity: 1;
}
</style>
