//import { computed, onMounted } from 'vue'
//import { useStore } from 'vuex'

export default function useAuth() {
    //const store = useStore()

    // computed для реактивности
    //const user = computed(() => store.state.user)
    //const roles = computed(() => store.state.user?.roles || [])
    //const isAuthenticated = computed(() => !!store.state?.user)

    // Загружаем пользователя при монтировании компонента
   /* const fetchUser = async () => {
        try {
            await store.dispatch('getUser')
        } catch (e) {
            console.error('[useAuth] Failed to fetch user', e)
        }
    }

    onMounted(() => {
        if (!store.state.user) fetchUser()
    })*/

    // методы проверки
    /*const hasRole = (role) => roles.value.includes(role)
    console.log(roles);
    const hasAnyRole = (roleArray) => roleArray.some(r => roles.value.includes(r))*/

    return {
        //user,
        //roles,
        //isAuthenticated,
        //hasRole,
        //hasAnyRole,
        //fetchUser
    }
}
