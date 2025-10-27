import { ref, onMounted, onUnmounted } from 'vue'

export function useInfiniteScroll(loadFn, options = {}) {
    const {
        rootMargin = '200px',
        immediate = true,
        hasMore = () => true,
        isLoading = () => false
    } = options

    const loadTrigger = ref(null)
    let observer = null

    const observerCallback = async (entries) => {
        const entry = entries[0]
        if (entry.isIntersecting && hasMore() && !isLoading()) {
            await loadFn()
        }
    }

    const initObserver = () => {
        observer = new IntersectionObserver(observerCallback, { rootMargin })
        if (loadTrigger.value) {
            observer.observe(loadTrigger.value)
        }
    }

    const disconnectObserver = () => {
        if (observer) {
            observer.disconnect()
            observer = null
        }
    }

    onMounted(async () => {
        if (immediate) {
            await loadFn()
        }
        initObserver()
    })

    onUnmounted(() => {
        disconnectObserver()
    })

    return {
        loadTrigger
    }
}
