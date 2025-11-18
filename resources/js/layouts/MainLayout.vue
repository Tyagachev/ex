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
                        <nav v-if="user.id" class="flex items-center space-x-3">
                            <router-link class='py-2.5 px-4 border border-white text-sm bg-transparent text-white rounded-full cursor-pointer font-semibold text-center transition hover:bg-gray-600 flex items-center'
                                         :to="{name:'posts.create'}">
                                <span class="text-sm">+</span>
                            </router-link>
                            <span class="text-white text-sm hidden md:inline">{{ user.name }}</span>
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
                    :class="['lt hide-scrollbar border-r border-black overflow-y-auto overflow-x-hidden bg-gray-800 transition-transform duration-300 ease-in-out transform fixed lg:static z-30 h-full',
                    isSidebarOpen ? 'translate-x-0 w-52' : '-translate-x-full lg:translate-x-0 lg:w-50']"
                    style="min-width: 0;">
                    <div class="w-full">
                        <div v-for="link in links">
                            <div v-if="!link.items">
                                <div v-if="link.auth && user.id">
                                    <router-link :to="{name: link.route}" class="text-white text-sm">
                                        <button
                                            class="cursor-pointer hover:bg-gray-600 w-full p-2 flex items-center"
                                            :class="{
                                            'bg-gray-600 text-white': $route.name === link.route
                                            }">
                                            <div class="mr-2" v-html="link.img"></div>
                                            <p style="font-size: medium">{{link.title}}</p>
                                        </button>
                                    </router-link>
                                </div>
                                <div v-else-if="!link.auth">
                                    <router-link :to="{name: link.route}" class="text-white text-sm">
                                        <button class="cursor-pointer hover:bg-gray-600 w-full p-2 flex items-center"
                                                :class="{
                                            'bg-gray-600 text-white': $route.name === link.route
                                            }">
                                            <div class="mr-2" v-html="link.img"></div>
                                            <p style="font-size: medium">{{link.title}}</p>
                                        </button>
                                    </router-link>
                                </div>
                            </div>
                            <div v-else-if="link.items" class="text-white text-sm">
                                <button @click.prevent="showItems" class="cursor-pointer hover:bg-gray-600 w-full p-2 flex items-center">
                                    <div class="mr-2" v-html="link.img"></div>
                                    <p style="font-size: medium">{{link.title}}</p>
                                </button>
                            </div>
                            <div v-if="showItemsPanel" v-for="item in link.items">
                                <div class="border-b-1 border-gray-500">
                                    <router-link :to="{name: item.route}" class="text-white text-sm ">
                                        <button class="cursor-pointer hover:bg-gray-600 w-full mt-1 pt-2 pb-2 pl-4 hover:bg-gray-600 flex items-center"
                                                :class="{
                                            'bg-gray-600 text-white': $route.name === link.route
                                            }">
                                            <div class="mr-2" v-html="item.img"></div>
                                            <p style="font-size: medium">{{item.title}}</p>
                                        </button>
                                    </router-link>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>

                <div
                    v-if="isSidebarOpen"
                    class="fixed inset-0 bg-black bg-opacity-50 -z-1 lg:hidden"
                    @click="toggleSidebar">
                </div>

                <section class="lt pt-4 pb-10 pl-4 pr-4 custom-scrollbar flex-1 min-h-0 w-full z-10" ref="scrollContainer">
                    <aside>
                        <router-view></router-view>
                    </aside>
                </section>
            </div>
        </main>
    </div>
</template>

<script setup>
import {useUserStore} from "@/stores/users.js";
import {useRoute} from "vue-router";


import {computed, nextTick, onMounted, ref, watch} from "vue";

import Search from "@/components/Search/Search.vue";

import {useScrollStore} from "@/stores/scroll.js";

defineOptions({
    name: "MainLayout"
})

const route = useRoute()
const scroll = useScrollStore();
const userStore = useUserStore();
const scrollContainer = ref(null);
const isSidebarOpen = ref(false);
const showItemsPanel = ref(false);
const user = computed(() => userStore.user)
let handleScroll;
const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
}

const showItems = () => {
    showItemsPanel.value = !showItemsPanel.value
}

onMounted(() => {
    if (scrollContainer.value) {
        // Восстанавливаем скролл только для разрешенных маршрутов
        if (shouldSaveScroll(route)) {
            const savedPosition = scroll.getScrollPosition(route.path)
            scrollContainer.value.scrollTop = savedPosition || 0
        }

        handleScroll = () => {
            if (shouldSaveScroll(route)) {
                scroll.saveScrollPosition(route.path, scrollContainer.value.scrollTop)
            }
        }
        scrollContainer.value.addEventListener('scroll', handleScroll)
    }
})

// При смене маршрута
watch(() => route.path, (toPath, fromPath) => {
    // Сохраняем скролл при уходе с разрешенного маршрута
    if (scrollContainer.value && shouldSaveScroll(route)) {
        scroll.saveScrollPosition(fromPath, scrollContainer.value.scrollTop)
    }

    // Восстанавливаем или сбрасываем скролл при входе
    nextTick(() => {
        if (scrollContainer.value) {
            if (shouldSaveScroll(route)) {
                const savedPosition = scroll.getScrollPosition(toPath)
                scrollContainer.value.scrollTop = savedPosition || 0
            } else {
                scrollContainer.value.scrollTop = 0
            }
        }
    })
})

// Функция для определения, нужно ли сохранять скролл для этого маршрута
const shouldSaveScroll = (route) => {
    const saveScrollRoutes = ['posts', 'answers', 'saved'] // базовые имена маршрутов
    const ignoreRoutes = ['posts.create', 'posts.edit'] // маршруты где скролл не нужен

    // Проверяем, содержит ли имя маршрута один из базовых
    const isSaveScrollRoute = saveScrollRoutes.some(baseRoute =>
        route.name && route.name.includes(baseRoute)
    )

    // Проверяем, не входит ли маршрут в игнорируемые (проверяем, что имя маршрута не включает ни один из ignoreRoutes)
    const isIgnoredRoute = ignoreRoutes.some(ignoreRoute =>
        route.name && route.name.includes(ignoreRoute)
    )

    return isSaveScrollRoute && !isIgnoredRoute
}


const links = [
    {
        "title": "Посты",
        "img": '<i class="fa fa-home" aria-hidden="true"></i>',
        "auth": false,
        route: 'posts.page'
    },
    {
        "title": "Мои посты",
        "img": '<i class="fa fa-list-alt" aria-hidden="true"></i>',
        "auth": true,
        route: 'posts.me'
    },
    {
        "title": "Ответы",
        "img": '<i class="fa fa-comment" aria-hidden="true"></i>',
        "auth": true,
        route: 'answers.posts'
    },
    {
        "title": "Сохраненные",
        "img": '<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="16px" height="16px" version="1.1" viewBox="0 0 200 252.391"><g id="Objects">\n' +
            '                <path fill="#fff" stroke="#bebbbb" d="M10 0l180 0c5.508,0 10,4.493 10,10l0 232.354c0,3.7 -1.851,6.876 -5.07,8.7 -3.219,1.824 -6.894,1.78 -10.069,-0.121l-79.88 -47.849c-3.251,-1.948 -7.042,-1.945 -10.29,0.007l-79.54 47.804c-3.173,1.907 -6.852,1.956 -10.075,0.133 -3.223,-1.823 -5.076,-5.001 -5.076,-8.704l0 -232.324c0,-5.507 4.492,-10 10,-10z"/>\n' +
            '            </g>\n' +
            '            </svg>\n',
        "auth": true,
        route: 'saves.posts'
    },
    {
        "title": "Посмотренное",
        "img": '<i class="fa fa-history" aria-hidden="true"></i>',
        "auth": true,
        route: 'views.posts'
    },
    {
        "title": "Для тестов",
        "img": '<i class="fa fa-history" aria-hidden="true"></i>',
        "auth": true,
        route: 'test.page'
    },
    /*{
        "title": "Array",
        "img": '<i class="fa fa-home" aria-hidden="true"></i>',
        'items': [
            {
                "title": "Item",
                "img": '<i class="fa fa-home" aria-hidden="true"></i>',
                route: 'main'
            },
            {
                "title": "Item",
                "img": '<i class="fa fa-home" aria-hidden="true"></i>',
                route: 'main'
            },
            {
                "title": "Item",
                "img": '<i class="fa fa-home" aria-hidden="true"></i>',
                route: 'main'
            },
        ]
    },*/
];



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
    scrollbar-width: none;
}
.lt {
    transition: all 0.3s ease-in-out;
    height: calc(100vh - 50px);
}

/* Стили для кастомного скроллбара */
.custom-scrollbar {
    overflow: scroll;
    scrollbar-color: #2d3748 transparent;
    //scrollbar-width: none;
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

.custom-scrollbar::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Edge */
}

.custom-scrollbar:hover::-webkit-scrollbar {
    display: block; /* Chrome, Safari, Edge */
}

.dot_color {
    fill: #bebbbb;
    stroke: #bebbbb;
}

</style>
