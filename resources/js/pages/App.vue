<template>
        <div class="example-component">
            <Sidebar>
                <router-view></router-view>
            </Sidebar>
        </div>
</template>

<script setup>
import useAuth from "@/mixins/auth.js";
import {onMounted, ref} from "vue";

import Sidebar from "@/layouts/Sidebar.vue";
import {useUserStore} from "@/stores/users.js";

const {user, roles, hasRole, hasAnyRole, fetchUser} = useAuth();
const message = 'Hello world'
const userStore = useUserStore();

onMounted( () => {
    //userStore.getUser();
    //await getPosts();
});

/*const getPosts = async () => {
    console.log(hasAnyRole);
    const res = await axios.get('/api/posts');

}*/

</script>

<style scoped>

</style>
