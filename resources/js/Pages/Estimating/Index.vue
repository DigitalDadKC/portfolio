<script setup>
import { ref, computed } from 'vue';
import { Link, Head, router } from '@inertiajs/vue3';
import EstimatingLayout from '@/Layouts/EstimatingLayout.vue';
import Paginator from '@/Components/Paginator.vue';
import { useDateFormat } from '@vueuse/core';

const props = defineProps({
    jobs: Object,
    states: Object,
    filters: Object
})

const search = ref(props.filters.search)
const pages = ref(props.filters?.pages ?? 25)
const state_id = ref(props.filters.state)
const states = computed(() => {
    return [{'id': null, 'state': 'SELECT'}, ...props.states]
})

const getJobs = () => {
    router.reload({
        data: {
            search: search.value,
            pages: pages.value,
            state: state_id.value,
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
        <div class="d-flex justify-center">
            <v-table density="compact" height="1100px" fixed-header>
                <thead>
                    <tr class="uppercase">
                        <th colspan="10">
                            <div class="d-flex flex-column flex-md-row justify-space-between ga-4 px-2 py-4">
                                <Link :href="route('estimating.create')">
                                    <v-btn color="orange-accent-1" size="large">Add Job</v-btn>
                                </Link>
                                <div class="d-flex align-center ga-2">
                                    <Paginator :links="props.jobs.meta.links" />
                                </div>
                                <v-select density="compact" v-model="pages" variant="underlined" hide-details label="Pages" :items="[{'id': 10}, {'id': 25}, {'id': 50}]" item-title="id" item-value="id" class="d-flex align-center justify-center max-w-20 text-center bg-grey-lighten-2 rounded-md" @update:model-value="getJobs()"></v-select>
                            </div>
                        </th>
                    </tr>
                    <tr class="uppercase font-weight-bold">
                        <th class="font-weight-bold w-96">
                            Job #
                            <div class="flex">
                                <v-text-field density="compact" v-model="search" class="bg-grey-lighten-2 w-full rounded-lg" hide-details label="Search job #, address, or city..." prepend-inner-icon="mdi-magnify" @input="getJobs()"></v-text-field>
                            </div>
                        </th>
                        <th class="font-weight-bold">
                            <div>Address</div>
                            <v-select density="compact" v-model="state_id" class="bg-grey-lighten-2 min-w-40 rounded-lg" :items="states" label="State" hide-details item-title="state" item-value="id" @update:model-value="getJobs()"></v-select>
                        </th>
                        <th class="text-center font-weight-bold hidden xl:table-cell">Job Notes</th>
                        <th class="bg-grey-lighten-1 font-weight-bold border">
                            <div class="text-center text-lg">
                                Proposals
                            </div>
                            <v-table density="compact" class="bg-grey-lighten-1">
                                <tr>
                                    <th class="w-52 text-start">Name</th>
                                    <th class="w-24 text-start">Type</th>
                                    <th class="w-24 text-start">Created</th>
                                    <th class="w-24 text-start">Total</th>
                                    <th class="w-20 text-start">Edit</th>
                                    <th class="text-start">Export</th>
                                </tr>
                            </v-table>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(job, index) in props.jobs.data" :key="index">
                        <td class="py-3">
                            <div class="d-flex align-center justify-between">
                                {{`D${new Date(job.created_at).getFullYear()} - ` + job.number }}
                                <Link :href="route('estimating.edit', job.id)">
                                    <v-btn color="orange-accent-1">Edit</v-btn>
                                </Link>
                            </div>
                        </td>
                        <td>{{ job.address }}<br>{{ job.city }}, {{ job.state.state }} {{ job.zip }}</td>
                        <td class="hidden xl:table-cell max-w-96">{{ job.notes }}</td>
                        <td class="bg-grey-lighten-2 w-1/2">
                            <v-btn size="small" color="orange-accent-2" @click="getProposal(job.id, null)">New Proposal</v-btn>
                            <!-- <Link :href="route('proposals.create', {job: job.id})">
                                <v-btn size="small" color="orange-accent-2">New Proposal</v-btn>
                            </Link> -->
                            <tr v-for="(proposal, i) in job.proposals" :key="i">
                                <td class="min-w-52">
                                    <div class="d-flex flex-column justify-end align-start">
                                        <div>
                                            {{ proposal.name }}
                                        </div>
                                    </div>
                                </td>
                                <td class="min-w-24">{{ proposal.type }}</td>
                                <td class="min-w-24">{{ useDateFormat(proposal.created_at, 'M/D/YYYY') }}</td>
                                <td class="min-w-24">
                                    {{ $filters.formatCurrency(proposal.scopes.reduce((a, b) => a + b.lines.reduce((c, d) => c + ((d.price * d.quantity*100)/100), 0), 0)) }}
                                </td>
                                <td class="min-w-24">
                                    <v-btn size="small" color="orange-accent-2" @click="getProposal(job.id, proposal.id)">Edit</v-btn>
                                    <!-- <Link :href="route('proposals.edit', {proposal: proposal.id})">
                                        <v-btn size="small" color="orange-accent-2">Edit</v-btn>
                                    </Link> -->
                                </td>
                                <td class="text-start">
                                    <div class="d-flex justify-space-between py-1">
                                        <a :href="route('downloadPDF.pdf', { proposal: proposal.id })">
                                            <v-btn icon="mdi-download" size="small" color="orange-accent-4" variant="elevated" class="text-black"></v-btn>
                                        </a>
                                        <a target="_blank" :href="route('browserPDF.pdf', { proposal: proposal.id })">
                                            <v-btn icon="mdi-file-document-arrow-right" size="small" color="orange-accent-4" variant="elevated" class="text-black"></v-btn>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </td>
                    </tr>
                </tbody>
            </v-table>
        </div>
    </EstimatingLayout>
</template>
