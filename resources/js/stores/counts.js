import {defineStore} from "pinia";

export const useCountsStore = defineStore('counts', {
    actions: {
        /**
         * Подсчет всего чего угодно.
         * @param votes
         * @returns {*|string}
         */
        formatCount (votes) {
            if (votes >= 1000) {
                return (votes / 1000).toFixed(1) + 'k';
            }
            return votes;
        }
    }
});
