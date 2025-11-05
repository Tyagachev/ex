import axios from "axios";
import {defineStore} from "pinia";
import NProgress from "nprogress";

export const useAnswersStore = defineStore('answers',{
    state: () => ({
        answers:[],
        page: null,
        currentPage: 0,
        lastPage: 0,
        hasMore: true,
        loading: false,
    }),
    getters: {
        allAnswers: (state) => state.answers,
    },
    actions: {
        async getAnswers() {
            if (this.loading || !this.hasMore) return
            NProgress.start()
            this.loading = true;

            try {
                const {data} = await axios.get(`/api/answers?page=${this.page}`);
                this.currentPage = data.meta.current_page
                this.lastPage = data.meta.last_page

                NProgress.done()
                this.answers.push(...data.data);

                if (this.currentPage < this.lastPage) {
                    this.page++
                } else {
                    this.hasMore = false;
                }
            } finally {
                this.loading = false;
                NProgress.done()
            }
        },
        async refresh() {
            this.page = null;
            this.answers = [];
            const {data} = await axios.get(`/api/answers?page=${this.page}`);
            this.answers.push(...data.data);
        },
    }
})
