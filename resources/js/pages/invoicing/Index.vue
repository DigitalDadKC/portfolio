<script setup>
import { onMounted, ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Search from './Partials/Search.vue';
import Paginator from './Partials//Paginator.vue';
import FilterCustomer from './Partials/FIlterCustomer.vue';
import FilterPaid from './Partials/FilterPaid.vue';
import Pages from './Partials/Pages.vue';
import { useDateFormat } from '@vueuse/core';
import { useFormatCurrency } from '@/Composables/useFormatCurrency';
import Manage from './Partials/Manage.vue';
// import { initFlowbite } from 'flowbite'
import { Download, File } from 'lucide-vue-next';

// onMounted(() => {
//     initFlowbite();
// })

const { formatWithCommas } = useFormatCurrency();
const props = defineProps({
    invoices: Object,
    customers: Object,
    materials: Object,
    filters: Object,
})

const search = ref(props.filters.search)
const unpaid = ref(props.filters.unpaid)
const pages = ref(props.filters.pages)
const selectedCustomers = ref(props.filters.customers)

const newInvoice = {
    'id': null,
    'number': null,
    'reference': null,
    'date_created': null,
    'due_date': null,
    'terms_and_conditions': null,
    'discount': null,
    'customer': null,
}

const getInvoices = () => {
    router.reload({
        data: {
            search: search.value,
            unpaid: unpaid.value,
            pages: pages.value,
            customers: selectedCustomers.value
        },
        only: ['invoices', 'filters'],
        replace: true,
        preserveState: true,
        onSuccess: () => {
        }
    })
}

const update_customers = (data) => {
    selectedCustomers.value = (data.length) ? [...data] : null
    getInvoices()
}

</script>

<template>
    <Head title="Invoices"></Head>

    <GuestLayout title="Invoicing Software">
        <template #title>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Invoicing Software</h2>
        </template>

        <section class="bg-light-secondary dark:bg-dark-secondary h-full p-3 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12 h-full">
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg">
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <Manage :new="true" :invoice="newInvoice" :customers :materials></Manage>
                        <Search v-model="search" @update:model-value="getInvoices()"></Search>
                        <FilterPaid v-model="unpaid" @update:model-value="getInvoices()"></FilterPaid>
                        <FilterCustomer :customers :selectedCustomers @updateArray="(data) => update_customers(data)"></FilterCustomer>
                        <Pages v-model="pages" @update:model-value="getInvoices()"></Pages>
                        <Link :href="route('invoices.index')">Clear Filters</Link>
                    </div>
                    <div class="overflow-x-auto h-full">
                        <table class="w-full text-sm text-left text-blue-500 dark:text-gray-400 h-full">
                            <thead class="text-xs text-light-primary dark:text-dark-primary uppercase bg-light-quatrenary dark:bg-dark-quatrenary">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Number</th>
                                    <th scope="col" class="px-4 py-3">Customer</th>
                                    <th scope="col" class="px-4 py-3">Created</th>
                                    <th scope="col" class="px-4 py-3">Due Date</th>
                                    <th scope="col" class="px-4 py-3">Description</th>
                                    <th scope="col" class="px-4 py-3">Total</th>
                                    <th scope="col" class="px-4 py-3">Paid</th>
                                    <th scope="col" class="px-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-light-tertiary dark:bg-dark-tertiary h-full">
                                <tr class="border-b dark:border-gray-700" v-for="(invoice, index) in props.invoices.data" :key="index">
                                    <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ invoice.number }}</th>
                                    <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ invoice.customer.name }}</th>
                                    <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ useDateFormat(invoice.date_created, 'MMM-D-YYYY') }}</th>
                                    <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ useDateFormat(invoice.due_date, 'MMM-D-YYYY') }}</th>
                                    <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ invoice.reference }}</th>
                                    <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ formatWithCommas(invoice.invoice_items.reduce((a, b) => a + (b.unit_price*b.quantity*100), 0)*(1-(invoice.discount/100))/100, 'currency') }}</th>
                                    <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ (invoice.paid) ? 'Yes' : 'No' }}</th>
                                    <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white flex items-center">
                                        <Manage :new="false" :invoice :customers :materials></Manage>
                                        <a :href="route('invoices.downloadPDF', {invoice: invoice.id})" class="text-black">
                                            <Download class=""></Download>
                                        </a>
                                        <a target="_blank" :href="route('invoices.browserPDF', { invoice: invoice.id })" class="text-black">
                                            <File></File>
                                        </a>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4" aria-label="Table navigation">
                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                            Showing
                            <span class="font-semibold text-gray-900 dark:text-white">{{props.invoices.meta.from}} - {{ props.invoices.meta.to }}</span>
                            of
                            <span class="font-semibold text-gray-900 dark:text-white">{{ props.invoices.meta.total }}</span>
                        </span>
                        <Paginator :links="props.invoices.meta.links" />
                    </nav>
                </div>
            </div>
        </section>

    </GuestLayout>
</template>
