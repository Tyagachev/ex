import {defineStore} from "pinia";


export const useScrollStore = defineStore('scroll', {
    state: () => ({
        scrollPositions: {} // Сохраняем позиции для разных маршрутов
    }),
    actions: {
        saveScrollPosition(routePath, position) {
            this.scrollPositions[routePath] = position;
        },
        getScrollPosition(routePath) {
            return this.scrollPositions[routePath] || null;
        }
    }
});
