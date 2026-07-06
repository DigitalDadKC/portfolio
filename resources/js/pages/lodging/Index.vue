<script setup lang="ts">
import { ref } from 'vue'
import { router, Head } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import Label from '@/components/ui/label/Label.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import FilterDropdown from '@/components/FilterDropdown.vue';

const props = defineProps({
    states: Object,
    filters: Object,
    results: Object,
})

const year = ref(props.filters?.year)
const state = ref(props.filters?.state)

const reload = useDebounceFn(() => {
    router.post(route('lodging.filter'), {
        year: year.value,
        state: state.value,
    }, {
        only: ['results', 'filters'],
        preserveState: true,
        preserveScroll: true,
        replace: true,
    })
}, 300)

const months = [
    'Jan', 'Feb', 'Mar', 'Apr',
    'May', 'Jun', 'Jul', 'Aug',
    'Sep', 'Oct', 'Nov', 'Dec'
];

const currentYear = new Date().getFullYear();

const years = Array.from({ length: 11 }, (_, index) => ({
    id: index + 1,
    year: currentYear - index,
}));

</script>

<template>
    <Head title="GSA Lodging Example" />

    <GuestLayout title="GSA Lodging Example">
        <main class="flex justify-center p-4 h-auto py-20 md:px-10">
            <div class="grid auto-rows-min gap-4 md:grid-cols-2">
                <div class="flex flex-col">
                    <div class="p-2">
                        <Label>Year</Label>
                        <FilterDropdown v-model="year" :options="years" column="year" value="year" @update:model-value="reload()" />
                    </div>
                    <div v-for="(error, i) in $page.props.errors" :key="i">
                        <p class="text-red-500">{{ error }}</p>
                    </div>
                    <div class="p-2">
                        <Label>State</Label>
                        <FilterDropdown v-model="state" :options="props.states" column="state" @update:model-value="reload()" />
                    </div>
                </div>
                <div v-if="props.results?.length">
                    <div v-for="state in props.results" :key="state.City">
                        <div class="flex flex-col py-4">
                            {{ state.City }}<br />
                            County: {{ state.County ?? 'Standard' }}<br />
                            Meals: {{ state.Meals }}<br />
                            <div v-for="month in months" :key="month">
                                {{ `Lodging (${month}) - ${state[month]}` }}
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    No data
                </div>
            </div>
        </main>
    </GuestLayout>
</template>