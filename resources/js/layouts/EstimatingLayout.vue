<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import GuestLayout from './GuestLayout.vue';
import { Notebook } from 'lucide-vue-next';

const links = [
    { name: "Estimating", url: '/estimating', icon: Notebook },
    { name: "Customers", url: '/customers', icon: Notebook },
    { name: "Admin", url: '/admin', icon: Notebook },
]

</script>

<template>
    <Head title="Estimating" />

    <GuestLayout title="Construction Estimating Software">
        <template #navigation>
            <ul
                class="font-medium flex flex-col p-4 md:p-0 mt-4 rounded-lg md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 dark:border-dark-tertiary">
                <li v-for="(link, index) in links" :key="index" class="py-2">
                    <Link :href="link.url"
                        class="text-light-quatrenary dark:text-dark-quatrenary hover:text-light-quatrenary hover:border-b-2 border-light-quatrenary dark:hover:text-dark-tertiary"
                        :aria-label="link.name" aria-current="page">{{ link.name }}</Link>
                </li>
            </ul>
        </template>

        <slot />
    </GuestLayout>
</template>
