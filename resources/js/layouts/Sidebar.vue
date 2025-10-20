<template>
    <div class="bg-gray-700 z-30">
        <div class="px-4 sm:px-6 lg:px-8 relative z-50 ">
            <header class="py-2 ">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <button @click="toggleSidebar" class="text-white mr-4 lg:hidden">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path v-if="isSidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <div class="avatar">U</div>
                    </div>
                    <Search/>
                    <div class="flex-shrink-0">
                        <nav v-if="u" class="flex items-center space-x-3">
                            <router-link class='py-2.5 px-4 border border-white text-sm bg-transparent text-white rounded-full cursor-pointer font-semibold text-center transition hover:bg-gray-600 flex items-center'
                                  :to="{name:'posts.create'}">
                                <span class="text-sm">+</span>
                            </router-link>
                            <span class="text-white text-sm hidden md:inline">{{ u.name }}</span>
                            <button type='button' @click="userStore.logout()"
                                    class='py-2.5 px-4 cursor-pointer text-sm bg-indigo-500 text-white rounded-full font-semibold transition hover:bg-indigo-700'>
                                Выход
                            </button>
                        </nav>
                        <nav v-else>
                            <router-link class='py-2.5 px-4 cursor-pointer text-sm bg-indigo-500 text-white rounded-full font-semibold transition hover:bg-indigo-700'
                                  :to="{name: 'login.page'}">
                                Вход
                            </router-link>
                        </nav>
                    </div>
                </div>
            </header>
        </div>
    </div>

    <div class="h-full overflow-hidden relative">
        <main>
            <div class="flex h-full">
                <section
                    :class="['lt custom-scrollbar border-r border-black overflow-y-auto overflow-x-hidden bg-gray-800 transition-transform duration-300 ease-in-out transform fixed lg:static z-30 h-full',
                    isSidebarOpen ? 'translate-x-0 w-52' : '-translate-x-full lg:translate-x-0 lg:w-48']"
                    style="min-width: 0;">
                    <div class="w-full py-3">
                        <div v-for="link in links" class="p-3 hover:bg-gray-600 rounded-md mx-2">
                            <router-link :to="{name: link.route}" class="text-white text-sm">
                                <button class="mx-2 cursor-pointer flex items-center">
                                    <div class="mr-2" v-html="link.img"></div>
                                    <p style="font-size: medium">{{link.title}}</p>
                                </button>
                            </router-link>
                        </div>
                    </div>
                </section>

                <div
                    v-if="isSidebarOpen"
                    class="fixed inset-0 bg-black bg-opacity-50 -z-1 lg:hidden"
                    @click="toggleSidebar">
                </div>

                <section class="lt pt-4 pb-10 pl-4 pr-4 custom-scrollbar flex-1 min-h-0 w-full z-10">
                    <aside>
                        <slot/>
                    </aside>
                </section>
            </div>
        </main>
    </div>
</template>


<script setup>
import {useUserStore} from "@/stores/users.js";
import {useRouter} from "vue-router";

import {computed, onMounted, ref} from "vue";
import useAuth from "@/mixins/auth.js";
import Search from "@/сomponents/Search/Search.vue";

defineOptions({
    name: "Sidebar"
})
const userStore = useUserStore();
const router = useRouter();

const isSidebarOpen = ref(false);
const {user, isAuthenticated} = useAuth();


const links = [
    {
        "title": "Welcome",
        "img": '<i class="fa fa-home" aria-hidden="true"></i>',
        route: 'main'
    }
];

const u = computed(() => userStore.u)

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
}

</script>

<style scoped>

.avatar {
    width: 36px;
    height: 36px;
    background: #ff4500;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
}

.hide-scrollbar {
    -ms-overflow-style: none;
//scrollbar-width: none;
}
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}

.lt {
    transition: all 0.3s ease-in-out;
    height: calc(100vh - 50px);
}

/* Стили для кастомного скроллбара */
.custom-scrollbar {
    scrollbar-width: none;
    scrollbar-color: #2d3748 transparent;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 5px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #2d3748;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #4a5568;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #718096;
}

/* Скрываем скроллбар по умолчанию и показываем при наведении */
.custom-scrollbar {
    overflow: auto;
    scrollbar-width: none; /* Firefox */
}

.custom-scrollbar::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Edge */
}

.custom-scrollbar:hover {
    scrollbar-width: none; /* Firefox */
}

.custom-scrollbar:hover::-webkit-scrollbar {
    display: block; /* Chrome, Safari, Edge */
}

</style>
