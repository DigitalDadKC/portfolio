<script setup>
import { ref, computed } from 'vue';
import { Link, Head, router } from '@inertiajs/vue3';
import EstimatingLayout from '@/Layouts/EstimatingLayout.vue';
import Paginator from '@/Components/Paginator.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useDateFormat } from '@vueuse/core';

const props = defineProps({
    jobs: Object,
    states: Object,
    customers: Object,
    filters: Object
})

const search = ref(props.filters.search)
const pages = ref(props.filters?.pages ?? 25)
const state_id = ref(props.filters.state)
const customer_id = ref(props.filters.customer)

const states = computed(() => {
    return [{'id': null, 'state': 'SELECT'}, ...props.states]
})
const customers = computed(() => {
    return [{'id': null, 'name': 'SELECT'}, ...props.customers]
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

const getProposal = (job, proposal) => {
    router.post(route('proposals', {
        job: job,
        proposal: proposal
    }))
}

</script>

<template>
    <Head title="Estimating" />

    <EstimatingLayout>
        <div class="flex justify-center">
            <table class="table table-auto bg-light-primary dark:bg-dark-primary rounded-lg">
                <thead>
                    <tr class="uppercase">
                        <th colspan="10">
                            <div class="flex flex-col md:flex-row justify-between px-2 py-4">
                                <Link :href="route('estimating.create')" as="button" class="" prefetch>
                                    <PrimaryButton>Add Job</PrimaryButton>
                                </Link>
                                <div class="d-flex align-center ga-2">
                                    <Paginator :links="props.jobs.meta.links" />
                                </div>
                                <v-select density="compact" v-model="pages" variant="underlined" hide-details label="Pages" :items="[{'id': 10}, {'id': 25}, {'id': 50}]" item-title="id" item-value="id" class="d-flex align-center justify-center max-w-20 text-center bg-grey-lighten-2 rounded-md" @update:model-value="getJobs()"></v-select>
                            </div>
                        </th>
                    </tr>
                    <tr class="uppercase font-weight-bold">
                        <th class="w-96 p-1">
                            Job #
                            <div class="flex">
                                <v-text-field density="compact" v-model="search" class="w-full rounded-lg bg-light-secondary" hide-details label="Search job #, address, or city..." prepend-inner-icon="mdi-magnify" @input="getJobs()"></v-text-field>
                            </div>
                        </th>
                        <th class="p-1">
                            <div>Address</div>
                            <v-select density="compact" v-model="state_id" class="min-w-40 bg-light-secondary" :items="states" item-title="state" item-value="id" label="State" hide-details @update:model-value="getJobs()"></v-select>
                        </th>
                        <th class="text-center hidden xl:table-cell">
                            Customer
                            <v-select density="compact" v-model="customer_id" class="min-w-40 bg-light-secondary" :items="customers" item-title="name" item-value="id" label="Customer" hide-details @update:model-value="getJobs()"></v-select>
                        </th>
                        <th class="bg-light-secondary">
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
                    <tr v-for="(job, index) in props.jobs.data" :key="index">
                        <td class="p-2">
                            <div class="flex items-center justify-between">
                                {{`D${new Date(job.created_at).getFullYear()} - ` + job.number }}
                                <Link :href="route('estimating.edit', job.id)" prefetch>
                                    <SecondaryButton>Edit</SecondaryButton>
                                </Link>
                            </div>
                        </td>
                        <td>{{ job.address }}<br>{{ job.city }}, {{ job.state.state }} {{ job.zip }}</td>
                        <td class="hidden xl:table-cell max-w-96">{{ job.customer.name }}</td>
                        <td class="bg-light-secondary w-1/2 px-2 py-1">
                            <Link method="post" as="button" :href="route('proposals', {job: job.id})">
                                <PrimaryButton class="h-6">New Proposal</PrimaryButton>
                            </Link>
                            <tr v-for="(proposal, i) in job.proposals" :key="i">
                                <td class="min-w-52">
                                    <div class="flex flex-col">
                                        <div>
                                            {{ proposal.name }}
                                        </div>
                                    </div>
                                </td>
                                <td class="min-w-24">{{ proposal.type }}</td>
                                <td class="min-w-24">{{ useDateFormat(proposal.created_at, 'M/D/YYYY') }}</td>
                                <td CLASS="min-w-28">{{ proposal.estimator.name }}</td>
                                <td class="min-w-24">
                                    {{ $filters.formatCurrency(proposal.scopes.reduce((a, b) => a + b.lines.reduce((c, d) => c + ((d.price * d.quantity*100)/100), 0), 0)) }}
                                </td>
                                <td class="min-w-24">
                                    <Link :href="route('proposals.edit', {proposal: proposal.id})">
                                        <SecondaryButton class="w-20 h-6 bg-accent text-accent">Edit</SecondaryButton>
                                    </Link>
                                </td>
                                <td class="text-start">
                                    <div class="flex justify-between py-1">
                                        <a :href="route('downloadPDF.pdf', { proposal: proposal.id })">
                                            <v-icon size="x-large" class="text-accent">mdi-download</v-icon>
                                        </a>
                                        <a target="_blank" :href="route('browserPDF.pdf', { proposal: proposal.id })">
                                            <v-icon size="x-large" class="text-accent">mdi-file-document-arrow-right</v-icon>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </EstimatingLayout>
</template>
