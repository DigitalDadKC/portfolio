<script setup>
import { useFormatCurrency } from "@/composables/useFormatCurrency";
import { CircleDollarSign } from "lucide-vue-next";

const { formatWithCommas } = useFormatCurrency()
const props = defineProps({
    jobs: Object,
})

const current_month_jobs = props.jobs.filter(job => new Date(job.created_at).getMonth() == new Date().getMonth() && new Date(job.created_at).getFullYear() == new Date().getFullYear())
const previous_month_jobs = props.jobs.filter(job => new Date(job.created_at).getMonth() == new Date().getMonth()-1 && new Date(job.created_at).getFullYear() == new Date().getFullYear())
const current_month_revenue = current_month_jobs.flatMap(job => job.proposals.reduce((a, b) => a + b.scopes.reduce((c, d) => c + d.lines.reduce((e, f) => e + (f.price*f.quantity), 0), 0), 0)).reduce((g, h) => h + g, 0)
const previous_month_revenue = previous_month_jobs.flatMap(job => job.proposals.reduce((a, b) => a + b.scopes.reduce((c, d) => c + d.lines.reduce((e, f) => e + (f.price*f.quantity), 0), 0), 0)).reduce((g, h) => h + g, 0)

</script>

<template>
    <div class="relative">
        <div class="p-3 bg-white inline-flex border-2 rounded-lg border-light-quatrenary dark:border-dark-quatrenary absolute -top-10 left-0">
            <CircleDollarSign></CircleDollarSign>
        </div>

        <div class="pl-16">
            <h5 class="my-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Current Monthly Revenue</h5>
            <h5 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white mb-2">{{ formatWithCommas(current_month_revenue, 'currency') }}</h5>
            <h6>Previous Monthly Revenue</h6>
            <h6>{{ formatWithCommas(previous_month_revenue, 'currency') }}</h6>

            <div class="flex items-center">
                <svg class="w-6 h-6 text-green-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" v-if="current_month_revenue-previous_month_revenue>0">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v13m0-13 4 4m-4-4-4 4"/>
                </svg>
                <svg class="w-6 h-6 text-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" v-if="current_month_revenue-previous_month_revenue<0">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V5m0 14-4-4m4 4 4-4"/>
                </svg>
                <h6>
                    {{ formatWithCommas((current_month_revenue - previous_month_revenue)/previous_month_revenue*100, 'percent') }}
                </h6>
            </div>
        </div>

    </div>
</template>
