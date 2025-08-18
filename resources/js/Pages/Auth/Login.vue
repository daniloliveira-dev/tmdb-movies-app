<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-900 text-white">

        <Head title="Login" />
        <div class="w-full max-w-md p-8 bg-gray-800 rounded-xl shadow-lg">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-red-600">TMDB Movies</h1>
                <p class="text-gray-400 mt-2">Entre na sua conta</p>
            </div>

            <div v-if="status" class="mb-4 p-2 bg-green-700 text-white rounded">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label for="email" class="block mb-1 font-semibold">Email</label>
                    <input id="email" v-model="form.email" type="email"
                        class="w-full px-4 py-2 rounded-md bg-gray-700 border border-gray-600 focus:border-red-600 focus:outline-none"
                        placeholder="Digite seu email" required autofocus />
                    <p class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label for="password" class="block mb-1 font-semibold">Senha</label>
                    <input id="password" v-model="form.password" type="password"
                        class="w-full px-4 py-2 rounded-md bg-gray-700 border border-gray-600 focus:border-red-600 focus:outline-none"
                        placeholder="Digite sua senha" required />
                    <p class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" v-model="form.remember"
                            class="h-4 w-4 text-red-600 focus:ring-red-500 rounded">
                        <span class="text-gray-300 text-sm">Lembrar-me</span>
                    </label>
                    <Link v-if="canResetPassword" href="/forgot-password"
                        class="text-sm text-gray-400 hover:text-red-600 underline">Esqueci minha senha</Link>
                </div>

                <button type="submit" :disabled="form.processing"
                    class="w-full py-2 bg-red-600 hover:bg-red-700 rounded-md font-semibold transition-opacity duration-200 disabled:opacity-50">
                    Entrar
                </button>
            </form>

            <p class="mt-6 text-center text-gray-400 text-sm">
                Não tem conta?
                <Link href="/register" class="text-red-600 hover:underline">Cadastre-se</Link>
            </p>
        </div>
    </div>
</template>

<style scoped>
body {
    margin: 0;
    font-family: Arial, sans-serif;
}
</style>
