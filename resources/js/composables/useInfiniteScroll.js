import { ref, onMounted, onUnmounted } from 'vue'

export function useInfiniteScroll(loadMoreFn, options = { rootMargin: '200px' }) {
    const target = ref(null)
    const isVisible = ref(false)
    let observer = null

    const createObserver = () => {
        observer = new IntersectionObserver(async entries => {
            const entry = entries[0]
            isVisible.value = entry.isIntersecting

            if (entry.isIntersecting) {
                await loadMoreFn()
            }
        }, options)

        if (target.value) {
            observer.observe(target.value)
        }
    }

    onMounted(() => {
        createObserver()
    })

    onUnmounted(() => {
        if (observer && target.value) observer.unobserve(target.value)
        observer = null
    })

    return { target, isVisible }
}
