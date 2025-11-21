<template>
    <TabbedLayout :tabs="tabs"/>
</template>

<script setup>
import {computed, defineOptions, ref, watch} from "vue";
import TabbedLayout from "@/layouts/TabbedLayout.vue";
import {useRoute} from "vue-router";

defineOptions({
    name: "index"
})
const route = useRoute();

const page = ref([])
const updatePageName = (name) => {
    page.value.push(name);
}

watch(() => route.path, () => {
    page.value = [];

    const path = route.path;

    const match = path.match(/(posts|comments)\/[^\/]+/);

    route.matched[1].children.filter(r => {
        if (r.path.includes(match[1])) {
            updatePageName(r.name)
        }
    })
}, {immediate:true})

const tabs = computed(() => {
    if (page.value.length) {
        return [
            {
                to: { name: page.value[0] },
                name: page.value[0],
                title: 'Понравилось'
            },
            {
                to: { name: page.value[1] },
                name: page.value[1],
                title: 'Не понравилось'
            }
        ];
    } else {
        return [];
    }
});
</script>

<style scoped>

</style>
