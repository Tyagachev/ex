import {defineStore} from "pinia";

export const useAvatarStore = defineStore('avatars', {

    state:() => {

    },
    getters: {

    },
    actions: {

        /**
         * Временная аватарка
         * если нет картинки
         *
         * @param user
         * @returns {string}
         */
        avatarColor(user) {
            const str = (user || "u").toLowerCase();
            let hash = 0;
            for (let i = 0; i < str.length; i++)
                hash = str.charCodeAt(i) + ((hash << 5) - hash);
            const hue = Math.abs(hash) % 360;
            return `hsl(${hue} 70% 60%)`;
        },
    }
})
