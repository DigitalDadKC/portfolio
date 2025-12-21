<script setup lang="ts">
import {  } from 'vue'
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Manage from './modals/Manage.vue';
import Destroy from './modals/Destroy.vue';
import { Button } from '@/components/ui/button';
import { FilePen, Eye } from 'lucide-vue-next';
import { useDateFormat } from '@vueuse/core';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import Send from './modals/Send.vue';
import { useFormatCurrency } from '@/composables/useFormatCurrency';

const props = defineProps({
    contracts: Object,
    clients: Object,
    services: Object,
})

const { formatWithCommas } = useFormatCurrency();
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
                        <TableHead class="text-black text-end">
                            <Manage :new="true" :clients :services></Manage>
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody class="bg-white dark:bg-gray-800 dark:border-gray-700" ref="el">
                    <TableRow v-for="contract in props.contracts" :key="contract.id" class="w-full">
                        <TableCell>
                            <p>{{ contract.client.company }}</p>
                            <p class="text-xs italic" v-if="contract.sent">Sent</p>
                            <p class="text-xs italic" v-else>Unsent</p>
                        </TableCell>
                        <TableCell>{{ formatWithCommas(contract.price, 'currency') }}</TableCell>
                        <TableCell>{{ useDateFormat(contract.created_at, 'MMM D, YYYY HH:MM:ss') }}</TableCell>
                        <TableCell>{{ useDateFormat(contract.updated_at, 'MMM D, YYYY HH:MM:ss') }}</TableCell>
                        <TableCell>
                            <div class="flex justify-end items-center gap-2" v-if="!contract.sent">
                                <Send :contract />
                                <Destroy :contract></Destroy>
                            </div>
                            <div class="flex justify-end items-center gap-2" v-else>
                                <Manage :new="false" :contract :clients :services></Manage>
                                <a target="_blank" :href="'https://www.signwell.com/app/builder/' + contract.signwell_id">
                                    <Button>
                                        View
                                        <Eye></Eye>
                                    </Button>
                                </a>
                                <Link :href="route('contracts.viewDocument', contract.id)">
                                    View also
                                </Link>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

</AuthenticatedLayout>
</template>
