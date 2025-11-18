import axios from "axios";
import {defineStore} from "pinia";
import NProgress from "nprogress";

export const useTabbedStore = defineStore('tabbed',{
    state: () => ({
        data: [],
        page: 0,
        currentPage: 0,
        lastPage: 0,
        hasMore: true,
        loading: false,
        isTabbedPage: true,
        path: ''
    }),
    getters: {
        tabbedData: (state) => state.data,
    },
    actions: {
        setRoutePath(route) {
            if (this.path !== route) {
                this.page = 1
            }
            this.path = route
        },

        /**
         *
         * @returns {Promise<void>}
         */
        async getTabbedData() {
            if (this.loading || !this.hasMore) return
            NProgress.start()

            this.loading = true;
            try {
                const {data} = await axios.get(`/api${this.path}?page=${this.page}`);
                this.currentPage = data.meta.current_page
                this.lastPage = data.meta.last_page
                NProgress.done()
                this.data.push(...data.data);

                if (this.currentPage <= this.lastPage) {
                    this.page++
                } else {
                    this.hasMore = false;
                }
            } finally {
                this.loading = false;
                NProgress.done()
            }
        },
        /**
         *
         * @returns {Promise<void>}
         */
        async resetLoadedStatusAndRefresh() {
            this.data = []
            this.loading = false;
            this.hasMore = true;
            await this.getTabbedData();
        }
    }
})
