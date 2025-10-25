<script setup lang="ts">
import {  } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import EstimatingLayout from '@/layouts/EstimatingLayout.vue';
import Users from './widgets/Users.vue';
import Company from './widgets/Company.vue';
import LargestProposal from "./widgets/LargestProposal.vue";
import RecentProposal from "./widgets/RecentProposal.vue";
import DoughnutChart from "./widgets/DoughnutChart.vue";
import CurrentMonthlyRevenue from "./widgets/CurrentMonthlyRevenue.vue";
import LineChart from "./widgets/LineChart.vue";

const props = defineProps({
    new: Boolean,
    company: Object,
    jobs: Object,
    proposals: Object,
    states: Object,
    users: Object,
    roles: Object,
    skills: Object,
})

</script>

<template>
    <EstimatingLayout>

        <Head title="Admin" />

        <main class="p-4 h-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-4 mb-4">
                <div class="block p-2 border-2 rounded-lg h-60 shadow-lg bg-light-primary dark:bg-light-primary border-gray-300 dark:border-gray-600 hover:bg-light-tertiary dark:hover:bg-dark-secondary">
                    <LargestProposal :jobs></LargestProposal>
                </div>
                <div class="block p-2 border-2 rounded-lg h-60 shadow-lg bg-light-primary dark:bg-light-primary border-gray-300 dark:border-gray-600 hover:bg-light-tertiary dark:hover:bg-dark-secondary">
                    <RecentProposal :proposals></RecentProposal>
                </div>
                <div class="block p-2 border-2 rounded-lg h-60 shadow-lg bg-light-primary dark:bg-light-primary border-gray-300 dark:border-gray-600 hover:bg-light-tertiary dark:hover:bg-dark-secondary">
                    <CurrentMonthlyRevenue :jobs></CurrentMonthlyRevenue>
                </div>
                <div class="block p-2 border-2 rounded-lg h-60 shadow-lg bg-light-primary dark:bg-light-primary border-gray-300 dark:border-gray-600 hover:bg-light-tertiary dark:hover:bg-dark-secondary">
                    <Company :company :states></Company>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-4">
                <div class="block p-6 border-2 rounded-lg h-96 shadow-lg bg-light-primary dark:bg-light-primary border-gray-300 dark:border-gray-600 hover:bg-light-tertiary dark:hover:bg-dark-secondary">
                    <Users :users :roles></Users>
                </div>
                <div class="block p-6 border-2 rounded-lg h-96 shadow-lg bg-light-primary dark:bg-light-primary border-gray-300 dark:border-gray-600 hover:bg-light-tertiary dark:hover:bg-dark-secondary">
                    <DoughnutChart :users :jobs></DoughnutChart>
                </div>
                <div class="flex justify-center p-6 border-2 rounded-lg h-96 shadow-lg bg-light-primary dark:bg-light-primary border-gray-300 dark:border-gray-600 hover:bg-light-tertiary dark:hover:bg-dark-secondary">
                    <LineChart :jobs></LineChart>
                </div>
            </div>
        </main>

    </EstimatingLayout>
</template>
