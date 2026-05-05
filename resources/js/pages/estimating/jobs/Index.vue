<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import EstimatingLayout from '@/layouts/EstimatingLayout.vue';
import Paginator from '@/components/Paginator.vue';
import { Button } from '@/components/ui/button';
import SearchBox from './partials/SearchBox.vue';
import Pages from './partials/Pages.vue';
import ManageJob from './modals/ManageJob.vue';
import Filters from './partials/Filters.vue';
import StateTag from './partials/StateTag.vue';
import CustomerTag from './partials/CustomerTag.vue';
import { useDateFormat } from '@vueuse/core';
import { useFormatCurrency } from '@/composables/useFormatCurrency';
import { Download, FileText } from 'lucide-vue-next';

const props = defineProps({
    jobs: Object,
    states: Object,
    customers: Object,
    filters: Object
})

const { formatWithCommas } = useFormatCurrency()
const search = ref(props.filters.search)
const pages = ref(props.filters.pages)
const state_ids = ref(props.filters.states ?? [])
const customer_ids = ref(props.filters.customers ?? [])

const reload = () => {
    router.post(route('estimating.jobs.filter'), {
        search: search.value,
        pages: pages.value,
        states: state_ids.value,
        customers: customer_ids.value,
    }, {
        only: ['jobs', 'filters'],
        replace: true,
    })
}

watch([search, pages, state_ids, customer_ids], () => {
    reload()
}, {
    deep: true,
})

const remove_state_filter = (id) => {
    state_ids.value = state_ids.value.filter(state_id => state_id !== id)
}

const remove_customer_filter = (id) => {
    customer_ids.value = customer_ids.value.filter(customer_id => customer_id !== id)
}

</script>

<template>
    <EstimatingLayout>
        <template #navigation>
            <ul
                class="font-medium flex flex-col p-4 md:p-0 mt-4 rounded-lg md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 dark:border-dark-tertiary">
                <li v-for="(link, index) in links" :key="index" class="py-2">
                    <Link :href="link.url"
                        class="text-light-quatrenary dark:text-dark-quatrenary hover:text-light-quatrenary hover:border-b-2 border-light-quatrenary dark:hover:text-dark-tertiary"
                        :class="{ 'text-base': scrollBackground, 'text-lg': !scrollBackground }"
                        :aria-label="navigation.name" aria-current="page">{{ navigation.name }}</Link>
                </li>
            </ul>
        </template>

        <div class="container mx-auto bg-light-tertiary dark:bg-dark-primary rounded-xl py-8 w-full">

            <div class="flex gap-2 py-1" v-if="state_ids.length">
                States:
                <div v-for="filter in state_ids" :key="filter">
                    <StateTag :filter :states @remove="(id) => remove_state_filter(id)" />
                </div>
            </div>
            <div class="flex gap-2 py-1" v-if="customer_ids.length">
                Customers:
                <div v-for="filter in customer_ids" :key="filter">
                    <CustomerTag :filter :customers @remove="(id) => remove_customer_filter(id)" />
                </div>
            </div>

            <table class="table table-auto bg-light-primary dark:bg-dark-primary border-2 border-black w-full">
                <thead>
                    <tr class="uppercase border-2 border-black">
                        <th colspan="10">
                            <div class="flex flex-col md:flex-row justify-between px-2 py-4">
                                <ManageJob :new="true" :states :customers></ManageJob>
                                <div>
                                    <Paginator :links="props.jobs.meta.links" />
                                    <div class="flex items-center justify-center mt-2 gap-2">
                                        {{ props.jobs.meta.from }} - {{ props.jobs.meta.to }} of {{ props.jobs.meta.total }} jobs
                                        <Pages v-model="pages"></Pages>
                                    </div>
                                </div>
                                <div>
                                    <Filters :states :customers v-model:selectedStates="state_ids" v-model:selectedCustomers="customer_ids" />
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3">
                            <SearchBox v-model="search"></SearchBox>
                        </th>
                        <th
                            class="bg-light-tertiary dark:bg-dark-tertiary border-b-black border-b-4 py-2 text-black rounded-t-sm">
                            <div class="text-center text-lg">
                                Proposals
                            </div>
                            <table class="table table-auto">
                                <tbody>
                                    <tr>
                                        <th class="pl-2 w-52 text-start">Name</th>
                                        <th class="w-24 text-start">Type</th>
                                        <th class="w-24 text-start">Created</th>
                                        <th class="w-28 text-start">Estimator</th>
                                        <th class="w-24 text-center">Total</th>
                                        <th class="col-span-2"></th>
                                    </tr>
                                </tbody>
                            </table>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(job, index) in props.jobs.data" :key="index" class="text-xs border-2 border-black">
                        <td class="p-2">
                            <div>
                                <div class="flex gap-2">
                                    <p class="font-bold text-sm">{{ `D${new Date(job.created_at).getFullYear()} - ` + job.number }}</p>
                                    <ManageJob :new="false" :job :states :customers></ManageJob>
                                </div>
                                <div>
                                    <p>Start Date: {{ useDateFormat(job.start_date, 'M/D/YYYY') }}</p>
                                    <p class="italic text-xs" v-if="job.prevailing_wage">(Prevailing Wage)</p>
                                    <p class="italic text-xs" v-else>(Non-Prevailing Wage)</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ job.address }}<br>{{ job.city }}, {{ job.state.state }} {{ job.zip }}</td>
                        <td class="hidden xl:table-cell max-w-96">{{ job.customer.name }}</td>
                        <td class="bg-light-secondary dark:bg-dark-secondary border-2 border-black px-2 py-1">
                            <table>
                                <tbody>
                                    <tr>
                                        <Link :href="route('proposals.store', { job: job.id })" method="post">
                                            <Button>NEW PROPOSAL</Button>
                                        </Link>
                                    </tr>
                                    <tr v-for="(proposal, i) in job.proposals" :key="i">
                                        <td class="min-w-52">{{ proposal.name }}</td>
                                        <td class="min-w-24">{{ proposal.type }}</td>
                                        <td class="min-w-24">{{ useDateFormat(proposal.created_at, 'M/D/YYYY') }}</td>
                                        <td CLASS="min-w-28">{{ proposal.estimator.name }}</td>
                                        <td class="min-w-24">
                                            {{formatWithCommas(proposal.scopes.reduce((a, b) => a + b.lines.reduce((c,
                                                d) => c +
                                                ((d.price * d.quantity * 100) / 100), 0), 0), 'currency')}}
                                        </td>
                                        <td class="flex gap-2 py-0.5">
                                            <Link :href="route('proposals.edit', { proposal: proposal.id })">
                                                <Button>EDIT</Button>
                                            </Link>
                                            <a :href="route('proposals.downloadPDF', { proposal: proposal.id })">
                                                <Button class="bg-accent">
                                                    <Download class="text-light-primary"></Download>
                                                </Button>
                                            </a>
                                            <a target="_blank" :href="route('proposals.browserPDF', { proposal: proposal.id })">
                                                <Button class="bg-accent">
                                                    <FileText class="text-light-primary"></FileText>
                                                </Button>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </EstimatingLayout>
</template>
