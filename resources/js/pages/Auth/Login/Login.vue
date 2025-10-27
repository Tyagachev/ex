
<template>
    <title>Вход в систему</title>
        <div class="max-w-md mx-auto py-8 px-4">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-white">Вход в аккаунт</h1>
                <p class="text-gray-400 mt-2">Введите свои учетные данные для доступа</p>
            </div>

            <div v-if="status" class="mb-4 text-sm font-medium text-green-400 bg-green-900/20 py-2 px-4 rounded-md">
                {{ status }}
            </div>
            <div v-for="error in userStore.errors">
                {{error.email[0]}}
            </div>
            <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
                <form  class="space-y-6" @submit.prevent="submit">
                    <div>
                        <InputLabel for="email" value="Email" class="text-gray-300" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full bg-gray-700 text-white border-gray-600 focus:border-indigo-500 focus:ring-indigo-500"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <!--<InputError class="mt-2" :message="form.errors.email" />-->
                    </div>

                    <div>
                        <InputLabel for="password" value="Пароль" class="text-gray-300" />
                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full bg-gray-700 text-white border-gray-600 focus:border-indigo-500 focus:ring-indigo-500"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                        />
                        <!--<InputError class="mt-2" :message="form.errors.password" />-->
                    </div>

                    <div class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ms-2 text-sm text-gray-400">Запомнить меня</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <!--<a
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors"
                        >
                            Забыли пароль?
                        </a>-->

                        <PrimaryButton
                            class="ms-4 rounded-full bg-indigo-600 cursor-pointer rounded-full hover:bg-indigo-700"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Войти
                        </PrimaryButton>
                    </div>
                </form>

                <div class="mt-6 pt-6 border-t border-gray-700">
                    <div class="text-center">
                        <p class="text-sm text-gray-400">Нет аккаунта?</p>
                        <router-link :to="{name: 'register.page'}">Зарегистрироваться</router-link>
                    </div>
                </div>
            </div>
        </div>
</template>

<script setup>
import {useUserStore} from "@/stores/users.js";
import { useRouter } from 'vue-router'
import Checkbox from '../../../components/Inertia/Checkbox.vue';
import InputLabel from '../../../components/Inertia/InputLabel.vue';
import PrimaryButton from '../../../components/Inertia/PrimaryButton.vue';
import TextInput from '../../../components/Inertia/TextInput.vue';
import {reactive} from "vue";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const userStore = useUserStore();
const router = useRouter()
const form = reactive({
    email: '',
    password: '',
    remember: false,
});

const submit = async () => {
    const login = await userStore.login(form.email, form.password);
    if (login.status === 200) {
        router.back();
    }
}


</script>
