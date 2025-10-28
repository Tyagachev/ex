<template>
    <button
            type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-normal py-2 px-3 rounded transition-colors duration-200">
        <slot></slot>
    </button>
</template>

<script>
export default {
    name: "BlueButton"
}
</script>

<style scoped>

</style>
