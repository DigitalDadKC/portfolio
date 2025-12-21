<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import EstimatingLayout from '@/layouts/EstimatingLayout.vue';
import Paginator from '@/components/Paginator.vue';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import SearchBox from './partials/SearchBox.vue';
import Pages from './partials/Pages.vue';
import State from './partials/State.vue';
import Customer from './partials/Customer.vue';
import ManageJob from './modals/ManageJob.vue';
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
const pages = ref(props.filters?.pages ?? 25)
const state_id = ref(props.filters.state)
const customer_id = ref(props.filters.customer)

const states = computed(() => {
    return [{ 'id': null, 'state': 'SELECT' }, ...props.states]
})
const customers = computed(() => {
    return [{ 'id': null, 'name': 'SELECT' }, ...props.customers]
})

const getJobs = () => {
    router.reload({
        data: {
            search: search.value,
            pages: pages.value,
            state: state_id.value,
            customer: customer_id.value
        },
        only: ['jobs', 'filters'],
        replace: true,
        preserveState: true
    })
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
            <table class="table table-auto bg-light-primary dark:bg-dark-primary border-2 border-black w-full">
                <thead>
                    <tr class="uppercase border-2 border-black">
                        <th colspan="10">
                            <div class="flex flex-col md:flex-row justify-between px-2 py-4">
                                <ManageJob :new="true" :states :customers></ManageJob>
                                <div>
                                    <Paginator :links="props.jobs.meta.links" @update:model-value="getJobs()" />
                                    <div class="mt-2">
                                        {{ props.jobs.meta.from }} - {{ props.jobs.meta.to }} of {{
                                            props.jobs.meta.total }} jobs
                                    </div>
                                </div>
                                <div>
                                    <Pages v-model="pages" @update:model-value="getJobs()"></Pages>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th class="pl-2">
                            <Label>Job #</Label>
                            <SearchBox v-model="search" @update:model-value="getJobs()"></SearchBox>
                        </th>
                        <th>
                            <Label for="state">State</Label>
                            <State v-model="state_id" :states @update:model-value="getJobs()"></State>
                        </th>
                        <th class="text-center hidden xl:table-cell">
                            <Label for="customer">Customer</Label>
                            <Customer v-model="customer_id" :customers @update:model-value="getJobs()"></Customer>
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
                                        <th class="w-24 text-start">Total</th>
                                        <th class="w-20 text-start">Edit</th>
                                        <th class="text-start">Export</th>
                                    </tr>
                                </tbody>
                            </table>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(job, index) in props.jobs.data" :key="index" class="text-xs border-2 border-black">
                        <td class="p-2">
                            <div class="flex items-center justify-between">
                                <div>
                                    {{ `D${new Date(job.created_at).getFullYear()} - ` + job.number }}
                                    <p class="italic text-xs" v-if="job.prevailing_wage">(Prevailing Wage)</p>
                                </div>
                                <ManageJob :new="false" :job :states :customers></ManageJob>
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
                                    <tr v-for="(proposal, i) in job.proposals" :key="i" class="dark:text-black w-full">
                                        <td class="min-w-52">{{ proposal.name }}</td>
                                        <td class="min-w-24">{{ proposal.type }}</td>
                                        <td class="min-w-24">{{ useDateFormat(proposal.created_at, 'M/D/YYYY') }}</td>
                                        <td CLASS="min-w-28">{{ proposal.estimator.name }}</td>
                                        <td class="min-w-24">
                                            {{formatWithCommas(proposal.scopes.reduce((a, b) => a + b.lines.reduce((c,
                                                d) => c +
                                                ((d.price * d.quantity * 100) / 100), 0), 0), 'currency')}}
                                        </td>
                                        <td class="min-w-24">
                                            <Link :href="route('proposals.edit', { proposal: proposal.id })">
                                            <Button variant="outline">EDIT PROPOSAL</Button>
                                            </Link>
                                        </td>
                                        <td class="w-full">
                                            <div class="flex py-1">
                                                <a :href="route('proposals.downloadPDF', { proposal: proposal.id })">
                                                    <Download class="text-accent"></Download>
                                                </a>
                                                <a target="_blank"
                                                    :href="route('proposals.browserPDF', { proposal: proposal.id })">
                                                    <FileText class="text-accent"></FileText>
                                                </a>
                                            </div>
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
