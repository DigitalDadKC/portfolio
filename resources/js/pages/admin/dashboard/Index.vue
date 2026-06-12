<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';

const props = defineProps({
    repos: Object,
})

console.log(props.repos)
console.log(usePage().props.auth.user)
console.log(usePage().props.auth.user.socialAccounts)
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="flex flex-col gap-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div v-for="repo in props.repos" :key="repo.id" v-if="props.repos.length">
                        <a :href="repo.html_url">
                            {{ repo.name }} - 
                            {{ repo.description }}
                        </a>
                    </div>
                    <div v-else>
                        <a class="inline-flex justify-center items-center px-4 py-2 bg-light-tertiary dark:bg-dark-tertiary border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-light-quatrenary dark:hover:bg-white focus:bg-light-tertiary dark:focus:bg-white active:bg-light-quatrenary dark:active:bg-dark-quatrenary focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:focus:ring-offset-accent transition ease-in-out duration-150 text-black shadow-md/20 cursor-pointer" href="/admin/auth/github/redirect">
                            Sign in with GitHub
                        </a>
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <a class="inline-flex justify-center items-center px-4 py-2 bg-light-tertiary dark:bg-dark-tertiary border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-light-quatrenary dark:hover:bg-white focus:bg-light-tertiary dark:focus:bg-white active:bg-light-quatrenary dark:active:bg-dark-quatrenary focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:focus:ring-offset-accent transition ease-in-out duration-150 text-black shadow-md/20 cursor-pointer" href="/admin/auth/google/redirect">
                        Sign in with Google
                    </a>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
