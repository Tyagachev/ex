<!-- components/UI/Modal.vue -->
<template>
    <transition name="modal">
        <div v-if="show" class="fixed inset-0 z-70 flex items-center justify-center">
            <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4">
                <div class="text-center">
                    <h3 class="text-lg font-medium text-white mb-4">
                        <slot name="title">Подтверждение</slot>
                    </h3>
                    <p class="text-gray-300 mb-6">
                        <slot name="content">Вы уверены, что хотите выполнить это действие?</slot>
                    </p>
                    <div class="flex justify-center gap-4">
                        <button
                            @click="$emit('cancel')"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 cursor-pointer transition"
                        >
                            Отмена
                        </button>
                        <button
                            @click="$emit('confirm')"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-500 cursor-pointer transition"
                        >
                            Удалить
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
defineProps({
    show: {
        type: Boolean,
        default: false
    }
});

defineEmits(['confirm', 'cancel']);
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
</style>
