<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, Head, router } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Paginator from '@/components/Paginator.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SearchBox from './Partials/SearchBox.vue';
import Pages from './Partials/Pages.vue';
import Address from './Partials/State.vue';
import Customer from './Partials/Customer.vue';
import ManageJob from './Modals/ManageJob.vue';
import { useDateFormat } from '@vueuse/core';
import { useFormatCurrency } from '@/composables/useFormatCurrency';
import { Download, FileText, } from 'lucide-vue-next';

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

const newJob = {
    'id': null,
    'number': null,
    'address': '',
    'city': '',
    'state_id': null,
    'zip': null
}

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
    <Head title="Estimating" />

    <GuestLayout title="Construction Estimating Software">

        <table class="table table-auto bg-light-secondary dark:bg-dark-secondary rounded-lg">
            <thead>
                <tr class="uppercase">
                    <th colspan="10">
                        <div class="flex flex-col md:flex-row justify-between px-2 py-4">
                            <!-- <Link :href="route('estimating.create')" as="button" class="" prefetch>
                                <Button>Add Job</Button>
                            </Link> -->
                            <ManageJob :new="true" :job="newJob" :states></ManageJob>
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
                        <SearchBox v-model="search" @update:model-value="getJobs()"></SearchBox>
                    </th>
                    <th>
                        <Address v-model="state_id" :states @update:model-value="getJobs()"></Address>
                    </th>
                    <th class="text-center hidden xl:table-cell">
                        <Customer v-model="customer_id" :customers @update:model-value="getJobs()"></Customer>
                    </th>
                    <th class="bg-light-tertiary text-black rounded-t-sm">
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
                <tr v-for="(job, index) in props.jobs.data" :key="index" class="text-xs">
                    <td class="p-2">
                        <div class="flex items-center justify-between">
                            {{ `D${new Date(job.created_at).getFullYear()} - ` + job.number }}
                            <ManageJob :new="false" :job :states></ManageJob>
                        </div>
                    </td>
                    <td>{{ job.address }}<br>{{ job.city }}, {{ job.state.state }} {{ job.zip }}</td>
                    <td class="hidden xl:table-cell max-w-96">{{ job.customer.name }}</td>
                    <td class="bg-light-tertiary px-2 py-1">
                        <!-- <Link method="post" as="button" :href="route('proposals', { job: job.id })">
                            <PrimaryButton class="h-6">New Proposal</PrimaryButton>
                        </Link> -->
                        <tr v-for="(proposal, i) in job.proposals" :key="i" class="dark:text-black w-full">
                            <td class="min-w-52">
                                {{ proposal.name }}
                            </td>
                            <td class="min-w-24">{{ proposal.type }}</td>
                            <td class="min-w-24">{{ useDateFormat(proposal.created_at, 'M/D/YYYY') }}</td>
                            <td CLASS="min-w-28">{{ proposal.estimator.name }}</td>
                            <td class="min-w-24">
                                {{formatWithCommas(proposal.scopes.reduce((a, b) => a + b.lines.reduce((c, d) => c +
                                    ((d.price * d.quantity * 100) / 100), 0), 0), 'currency')}}
                            </td>
                            <td class="min-w-24">
                                <Link :href="route('proposals.edit', { proposal: proposal.id })">
                                <SecondaryButton class="w-20 h-6 bg-accent text-accent">Edit</SecondaryButton>
                                </Link>
                            </td>
                            <td class="w-full">
                                <div class="flex py-1">
                                    <a :href="route('proposals.downloadPDF', { proposal: proposal.id })">
                                        <Download class="text-accent"></Download>
                                    </a>
                                    <a target="_blank" :href="route('proposals.browserPDF', { proposal: proposal.id })">
                                        <FileText class="text-accent"></FileText>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </td>
                </tr>
            </tbody>
        </table>
    </GuestLayout>
</template>
