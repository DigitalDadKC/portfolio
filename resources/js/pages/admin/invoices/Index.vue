<script setup lang="ts">
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import Manage from './modals/Manage.vue';
import Destroy from './modals/Destroy.vue';
import Invoice from './modals/Invoice.vue';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { useDateFormat } from '@vueuse/core';
import { useFormatCurrency } from '@/composables/useFormatCurrency';
import { descriptors } from 'chart.js/dist/core/core.defaults';

const props = defineProps({
    invoices: Object,
    clients: Object,
})

const { formatWithCommas } = useFormatCurrency();
const newInvoice = {
    id: null,
    number: 100+props.invoices.length,
    date_created: new Date(),
    due_date: new Date().setDate(new Date().getDate() + 30),
    line_items: [{
        id: null,
        description: '',
        price: 0,
        quantity: 1,
    }],
    terms_and_conditions: '',
    client_id: null,
}

</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Invoices</h2>
        </template>

        <div class="container mx-auto w-full mt-20">
            <Table class="bg-light-primary dark:bg-dark-primary w-full">
                <TableHeader class="bg-light-tertiary dark:bg-dark-tertiary">
                    <TableRow>
                        <TableHead class="p-6 text-black">Number</TableHead>
                        <TableHead class="p-6 text-black">Company</TableHead>
                        <TableHead class="p-6 text-black">Email</TableHead>
                        <TableHead class="p-6 text-black">Price</TableHead>
                        <TableHead class="p-6 text-black">Date Created</TableHead>
                        <TableHead class="p-6 text-black">Due Date</TableHead>
                        <TableHead class="text-black text-end"><Manage :new="true" :invoice="newInvoice" :clients></Manage></TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="invoice in props.invoices" :key="invoice.id">
                        <TableCell class="p-3">{{ invoice.number }}</TableCell>
                        <TableCell class="p-3">{{ invoice.client.company }}</TableCell>
                        <TableCell class="p-3">{{ invoice.client.email }}</TableCell>
                        <TableCell class="p-3">{{ formatWithCommas(invoice.client_invoice_items.reduce((a, b) => a + (b.price*b.quantity), 0), 'currency') }}</TableCell>
                        <TableCell class="p-3">{{ useDateFormat(invoice.date_created, 'MMM D, YYYY') }}</TableCell>
                        <TableCell class="p-3">{{ useDateFormat(invoice.due_date, 'MMM D, YYYY') }}</TableCell>
                        <TableCell class="flex justify-end gap-4">
                                <Manage :new="false" :invoice :clients></Manage>
                                <Destroy :invoice></Destroy>
                                <Invoice :invoice></Invoice>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

    </AuthenticatedLayout>
</template>
