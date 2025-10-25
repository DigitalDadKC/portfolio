<script setup lang="ts">
import ManageCompany from '../modals/ManageCompany.vue'
import { Building } from 'lucide-vue-next'
import CompanyDrawer from './CompanyDrawer.vue';

const props = defineProps({
    company: Object,
    states: Object,
})
</script>

<template>
    <div class="relative">
        <div class="p-3 bg-white inline-flex border-2 rounded-lg border-light-quatrenary dark:border-dark-quatrenary absolute -top-10 left-0">
            <Building></Building>
        </div>

        <div class="flex justify-end">
            <!-- <ManageCompany :company :states></ManageCompany> -->
            <CompanyDrawer :company :states></CompanyDrawer>
        </div>

        <div class="flex justify-between pl-16">
            <div>
                <h5 class="my-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Company</h5>
                <div>
                    {{ props.company.name }}
                </div>
                <div>
                    {{ props.company.address }}
                </div>
                <div>
                    {{ props.company.city }}
                </div>
                    {{ props.company.state.state }}
                <div>
                    {{ props.company.zip }}
                </div>
            </div>

        </div>
    </div>
</template>
