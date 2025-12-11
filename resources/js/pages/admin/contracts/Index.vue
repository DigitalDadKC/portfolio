<script setup lang="ts">
import { ref, shallowRef, useTemplateRef, nextTick, watch } from 'vue'
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Manage from './modals/Manage.vue';
import Destroy from './modals/Destroy.vue';
import { GripHorizontal, ReceiptText, FilePen } from 'lucide-vue-next';
import { useDateFormat } from '@vueuse/core';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { useSortable } from '@vueuse/integrations/useSortable'

const props = defineProps({
    contracts: Object,
})

const el = useTemplateRef<HTMLElement>('el')
const list = shallowRef(props.contracts)

const { option } = useSortable(el, list, {
    handle: '.handle',
    animation: 200,
    onSort: (e) => {
    nextTick(() => {
        list.value.forEach((item, index) => {
            item.order = index
        })
        updateContractOrder()
    })
    },
})

const updateContractOrder = () => {
    router.post(route('contracts.sort'), {
        'contracts': props.contracts
    }, {
        preserveScroll: true,
    })
}

watch(() => (props.contracts), (contracts) => {
    list.value = contracts
}, {
    deep: true,
})

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
                        <TableHead class="p-6 text-black"></TableHead>
                        <TableHead class="p-6 text-black">Title</TableHead>
                        <TableHead class="p-6 text-black">Description</TableHead>
                        <TableHead class="text-black">Created</TableHead>
                        <TableHead class="text-black">Updated</TableHead>
                        <TableHead class="text-black text-end">
                            <Manage :new="true"></Manage>
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody class="bg-white dark:bg-gray-800 dark:border-gray-700" ref="el">
                    <TableRow v-for="contract in list" :key="contract.id" class="w-full">
                        <TableCell class="p-3">
                            <GripHorizontal class="handle cursor-grab w-8 h-8"></GripHorizontal>
                        </TableCell>
                        <TableCell>{{ contract.title }}</TableCell>
                        <TableCell>{{ contract.description }}</TableCell>
                        <TableCell>{{ useDateFormat(contract.created_at, 'MMM d, YYYY') }}</TableCell>
                        <TableCell>{{ useDateFormat(contract.updated_at, 'MMM d, YYYY') }}</TableCell>
                        <TableCell class="flex justify-end gap-4">
                                <Manage :new="false" :contract></Manage>
                                <Destroy :contract></Destroy>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

</AuthenticatedLayout>
</template>
