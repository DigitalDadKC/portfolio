<script setup lang="ts">
import { handleError, ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import Manage from './modals/Manage.vue';
import Destroy from './modals/Destroy.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { useDateFormat } from '@vueuse/core';
import Outreach from './modals/Outreach.vue';

const props = defineProps({
    clients: Object,
    states: Object,
    data: Object,
})

console.log(props.clients)

const newClient = {
    id: null,
    name: ''
}


</script>

<template>
    <Head title="Projects Index" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Clients</h2>
        </template>

        <div class="container mx-auto w-full mt-20">
            <Table class="bg-light-primary dark:bg-dark-primary w-full">
                <TableHeader class="bg-light-tertiary dark:bg-dark-tertiary">
                    <TableRow>
                        <TableHead class="text-black">Company</TableHead>
                        <TableHead class="text-black">Email</TableHead>
                        <TableHead class="text-black">Outreach(es)</TableHead>
                        <TableHead class="text-black">Created</TableHead>
                        <TableHead class="text-black">Updated</TableHead>
                        <TableHead class="text-black text-end"><Manage :new="true" :client="newClient" :states :data></Manage></TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="client in props.clients" :key="client.id">
                        <TableCell>{{ client.company }}</TableCell>
                        <TableCell>{{ client.email }}</TableCell>
                        <TableCell>
                            <div v-for="outreach in client.outreaches" :key="outreach.id">
                                {{ useDateFormat(outreach.date_emailed, 'MMM D, YYYY') }}
                            </div>
                        </TableCell>
                        <TableCell>{{ useDateFormat(client.created_at, 'MMM D, YYYY') }}</TableCell>
                        <TableCell>{{ useDateFormat(client.updated_at, 'MMM D, YYYY') }}</TableCell>
                        <TableCell class="flex justify-end gap-4">
                                <Manage :new="false" :client :states :data></Manage>
                                <Destroy :client></Destroy>
                                <Outreach :client></Outreach>

                                <a target="_blank" :href="`http://maps.google.com/?q=${client.address}, ${client.city}, ${client.state?.id}`" v-if="client.address">
                                    <div class="flex items-center bg-light-tertiary dark:bg-dark-tertiary text-black p-2 rounded-lg">
                                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M4.37 7.657c2.063.528 2.396 2.806 3.202 3.87 1.07 1.413 2.075 1.228 3.192 2.644 1.805 2.289 1.312 5.705 1.312 6.705M20 15h-1a4 4 0 0 0-4 4v1M8.587 3.992c0 .822.112 1.886 1.515 2.58 1.402.693 2.918.351 2.918 2.334 0 .276 0 2.008 1.972 2.008 2.026.031 2.026-1.678 2.026-2.008 0-.65.527-.9 1.177-.9H20M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                    </div>
                                </a>

                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

    </AuthenticatedLayout>
</template>
