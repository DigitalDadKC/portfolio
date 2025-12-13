<script setup lang="ts">
import { ref, shallowRef, useTemplateRef, nextTick, watch } from 'vue'
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Manage from './modals/Manage.vue';
import Destroy from './modals/Destroy.vue';
import { FilePen } from 'lucide-vue-next';
import { useDateFormat } from '@vueuse/core';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { useSortable } from '@vueuse/integrations/useSortable'
import Send from './modals/Send.vue';

const props = defineProps({
    contracts: Object,
    clients: Object,
    services: Object,
})

console.log(props.contracts)

</script>


<template>
    <Head title="Contract" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Contract</h2>
        </template>

        <div class="container mx-auto w-full">
            <div class="my-10 float-right flex gap-2">
                View Template
                <a target="_blank" :href="route('contracts.browser')" class="text-black">
                    <FilePen></FilePen>
                </a>
            </div>
            <Table class="bg-light-primary dark:bg-dark-primary w-full">
                <TableHeader class="bg-light-tertiary dark:bg-dark-tertiary">
                    <TableRow>
                        <TableHead class="p-6 text-black">Client</TableHead>
                        <TableHead class="text-black">Price</TableHead>
                        <TableHead class="text-black">Created</TableHead>
                        <TableHead class="text-black">Updated</TableHead>
                        <TableHead></TableHead>
                        <TableHead class="text-black text-center">
                            <Manage :new="true" :clients :services></Manage>
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody class="bg-white dark:bg-gray-800 dark:border-gray-700" ref="el">
                    <TableRow v-for="contract in props.contracts" :key="contract.id" class="w-full">
                        <TableCell>{{ contract.client.company }}</TableCell>
                        <TableCell>{{ contract.price }}</TableCell>
                        <TableCell>{{ useDateFormat(contract.created_at, 'MMM d, YYYY') }}</TableCell>
                        <TableCell>{{ useDateFormat(contract.updated_at, 'MMM d, YYYY') }}</TableCell>
                        <TableCell>
                            <Send :contract />
                        </TableCell>
                        <TableCell class="space-x-4 text-center">
                                <Manage :new="false" :contract :clients :services></Manage>
                                <Destroy :contract></Destroy>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

</AuthenticatedLayout>
</template>
