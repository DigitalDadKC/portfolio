<script setup>
import { ref } from 'vue'
import { useFormatCurrency } from "@/composables/useFormatCurrency";
import { useDateFormat } from '@vueuse/core';
import { BadgeDollarSign } from 'lucide-vue-next';

const { formatWithCommas } = useFormatCurrency()
const props = defineProps({
    jobs: Object,
})

const biggest_job = ref([])
const biggest_proposal = ref([])

biggest_job.value = props.jobs[0]
biggest_proposal.value = biggest_job.value.proposals[0]

props.jobs.forEach(job => {
    job.proposals.forEach(proposal => {
        let proposal_total = proposal.scopes.reduce((a, b) => a + b.lines.reduce((c, d) => c + (d.price*d.quantity), 0), 0)
        if(proposal_total > (biggest_proposal.value?.scopes.reduce((a, b) => a + b.lines.reduce((c, d) => c + (d.price*d.quantity), 0), 0) ?? 0)) {
            biggest_job.value = job
            biggest_proposal.value = proposal
        }
    })
})

</script>

<template>
    <div class="relative">
        <div class="p-3 bg-white inline-flex border-2 rounded-lg border-light-quatrenary dark:border-dark-quatrenary absolute -top-10 left-0">
            <BadgeDollarSign></BadgeDollarSign>
        </div>

        <div class="pl-16">
            <h5 class="my-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Largest Proposal</h5>
        </div>
            <ul class="text-sm">
                <li class="font-normal text-gray-700 dark:text-gray-400 py-0.5">
                    Job #: {{ biggest_job.number }}
                </li>
                <li class="font-normal text-gray-700 dark:text-gray-400 py-0.5">
                    Proposal: {{ biggest_proposal?.name }}
                </li>
                <li class="font-normal text-gray-700 dark:text-gray-400 py-0.5">
                    Scopes: {{ biggest_proposal?.scopes.length }}
                </li>
                <li class="font-normal text-gray-700 dark:text-gray-400 py-0.5">
                    Project Amount: {{ formatWithCommas(biggest_proposal?.scopes.reduce((a, b) => a + b.lines.reduce((c, d) => c + (d.price*d.quantity), 0), 0), 'currency') }}
                </li>
                <li class="font-normal text-gray-700 dark:text-gray-400 py-0.5">
                    Date {{ useDateFormat(biggest_job.created_at, 'MMM DD, YYYY') }}
                </li>
            </ul>
    </div>
</template>
