<script setup lang="ts">
import {  } from 'vue'
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Manage from './modals/Manage.vue';
import Destroy from './modals/Destroy.vue';
import { GripHorizontal } from 'lucide-vue-next';
import { useDateFormat } from '@vueuse/core';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"

const props = defineProps({
    services: Object,
})

// watch(() => (props.features), (features) => {
//     list.value = features
// }, {
//     deep: true,
// })

</script>

<template>
    <Head title="Services" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Services</h2>
        </template>

        <div class="container mx-auto w-full mt-20">
            <Table class="bg-light-primary dark:bg-dark-primary w-full">
                <TableHeader class="bg-light-tertiary dark:bg-dark-tertiary">
                    <TableRow>
                        <TableHead class="p-6 text-black">Service</TableHead>
                        <TableHead class="text-black">Created</TableHead>
                        <TableHead class="text-black">Updated</TableHead>
                        <TableHead class="text-black text-end">
                            <Manage :new="true"></Manage>
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody class="bg-white dark:bg-gray-800 dark:border-gray-700">
                    <TableRow v-for="service in props.services" :key="service.id" class="w-full">
                        <TableCell>{{ service.name }}</TableCell>
                        <TableCell>{{ useDateFormat(service.created_at, 'MMM d, YYYY') }}</TableCell>
                        <TableCell>{{ useDateFormat(service.updated_at, 'MMM d, YYYY') }}</TableCell>
                        <TableCell class="flex justify-end gap-4">
                                <Manage :new="false" :service></Manage>
                                <Destroy :service></Destroy>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

</AuthenticatedLayout>
</template>
