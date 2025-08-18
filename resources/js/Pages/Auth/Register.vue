<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    account_id: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation', 'account_id'),
    });
};
</script>

<template>

    <Head title="Register" />
    <div class="min-h-screen flex items-center justify-center bg-gray-900 text-white">
        <div class="w-full max-w-md p-8 bg-gray-800 rounded-xl shadow-lg">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-red-600">TMDB Movies</h1>
                <p class="text-gray-400 mt-2">Crie sua conta para continuar</p>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <!-- Nome -->
                <div>
                    <label for="name" class="block mb-1 font-semibold">Nome</label>
                    <input id="name" v-model="form.name" type="text" required autofocus autocomplete="name"
                        class="w-full px-4 py-2 rounded-md bg-gray-700 border border-gray-600 focus:border-red-600 focus:outline-none" />
                    <p class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block mb-1 font-semibold">Email</label>
                    <input id="email" v-model="form.email" type="email" required autocomplete="username"
                        class="w-full px-4 py-2 rounded-md bg-gray-700 border border-gray-600 focus:border-red-600 focus:outline-none" />
                    <p class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
                </div>

                <!-- Senha -->
                <div>
                    <label for="password" class="block mb-1 font-semibold">Senha</label>
                    <input id="password" v-model="form.password" type="password" required autocomplete="new-password"
                        class="w-full px-4 py-2 rounded-md bg-gray-700 border border-gray-600 focus:border-red-600 focus:outline-none" />
                    <p class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
                </div>

                <!-- Confirmação de senha -->
                <div>
                    <label for="password_confirmation" class="block mb-1 font-semibold">Confirme a Senha</label>
                    <input id="password_confirmation" v-model="form.password_confirmation" type="password" required
                        autocomplete="new-password"
                        class="w-full px-4 py-2 rounded-md bg-gray-700 border border-gray-600 focus:border-red-600 focus:outline-none" />
                    <p class="text-red-500 text-sm mt-1">{{ form.errors.password_confirmation }}</p>
                </div>

                <!-- Account ID -->
                <div>
                    <label for="account_id" class="block mb-1 font-semibold">Account ID</label>
                    <input id="account_id" v-model="form.account_id" type="text" required
                        class="w-full px-4 py-2 rounded-md bg-gray-700 border border-gray-600 focus:border-red-600 focus:outline-none" />
                    <p class="text-red-500 text-sm mt-1">{{ form.errors.account_id }}</p>
                </div>

                <!-- Terms -->
                <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="flex items-center space-x-2">
                    <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />
                    <label for="terms" class="text-gray-300 text-sm">
                        Concordo com os <a :href="route('terms.show')" target="_blank"
                            class="underline text-red-600">Termos de Serviço</a> e a <a :href="route('policy.show')"
                            target="_blank" class="underline text-red-600">Política de Privacidade</a>
                    </label>
                    <p class="text-red-500 text-sm mt-1">{{ form.errors.terms }}</p>
                </div>

                <!-- Ações -->
                <div class="flex items-center justify-between mt-4">
                    <Link href="/login" class="text-gray-400 hover:text-red-600 underline text-sm">Já possui conta?
                    </Link>
                    <button type="submit" :disabled="form.processing"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-md font-semibold transition-opacity duration-200 disabled:opacity-50">
                        Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
}
</style>
