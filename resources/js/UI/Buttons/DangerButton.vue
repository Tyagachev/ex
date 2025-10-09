<template>
    <button
        type="button" class="bg-red-500 hover:bg-red-700 text-white font-normal py-2 px-3 rounded transition-colors duration-200">
        <slot></slot>
    </button>
</template>

<script>
export default {
    name: "DangerButton"
}
</script>

<style scoped>

</style>
