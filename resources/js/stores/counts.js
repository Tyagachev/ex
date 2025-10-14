import {defineStore} from "pinia";

export const useCountsStore = defineStore('counts', {
    actions: {
        formatCount (votes) {
            if (votes >= 1000) {
                return (votes / 1000).toFixed(1) + 'k';
            }
            return votes;
        }
    }
});
