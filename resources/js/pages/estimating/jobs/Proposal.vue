<script setup>
import { computed } from 'vue';
import { Link, Head, router } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Label } from 'reka-ui';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import Scope from './Scope.vue';
import FormattedInput from '@/components/FormattedInput.vue';
import Type from './partials/Type.vue';
import { Plus } from 'lucide-vue-next';
import { useDateFormat } from '@vueuse/core';

const props = defineProps({
    new: Boolean,
    proposal: Object,
    company: Object,
    states: Object,
    types: Object,
    unit_of_measurements: Object,
    errors: Object
})

const updateProposal = () => {
    router.put(route('proposals.update', props.proposal.id), {
        name: props.proposal.name,
        type: props.proposal.type,
        exclusions: props.proposal.exclusions
    }, {
        preserveScroll: true,
    })
}

const destroyProposal = () => {
    router.delete(route('proposals.destroy', props.proposal.id))
}

const addScope = () => {
    router.post(route('scopes.create', props.proposal.id), {
    }, {
        preserveScroll: true,
    })
}

const state_date = computed(() => {
    return useDateFormat(props.proposal.job.start_date, 'MMM DD, YYYY').value
})

const total = computed(() => {
    return props.proposal.scopes.map(scope => scope.lines.map(line => ((line.price * line.quantity*100)/100)).reduce((a, b) => a + b, 0)).reduce((a, b) => a + b, 0)
})

</script>

<template>
    <Head title="Proposal" />

    <GuestLayout title="Construction Estimating Software">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Proposal</h2>
        </template>

        <div class="max-w-6xl mx-auto bg-light-primary dark:bg-dark-tertiary rounded-lg p-4 mt-8">
            <div class="flex justify-between gap-4 mb-4">
                <div class="flex w-1/2 flex-col">
                    <div class="grid grid-cols-4">
                        <div class="col-span-4 md:col-span-1">
                            <Label for="number">Number</Label>
                            <FormattedInput id="number" type="number" width="full" :disabled="true" v-model="props.proposal.job.number" />
                        </div>
                        <div class="col-span-4 md:col-span-3">
                            <Label for="address">Address</Label>
                            <FormattedInput id="address" width="full" :disabled="true" v-model="props.proposal.job.address" />
                        </div>
                        <div class="col-span-4 md:col-span-2">
                            <Label for="city">City</Label>
                            <FormattedInput id="city" width="full" :disabled="true" v-model="props.proposal.job.city" />
                        </div>
                        <div class="col-span-4 md:col-span-2">
                            <Label for="state">State</Label>
                            <FormattedInput id="state" width="full" :disabled="true" v-model="props.proposal.job.state.state" />
                        </div>
                        <div class="col-span-4 md:col-span-2">
                            <Label for="zip">Zip</Label>
                            <FormattedInput id="zip" width="full" :disabled="true" v-model="props.proposal.job.zip" />
                        </div>
                        <div class="col-span-4 md:col-span-2">
                            <Label for="start_date">Start Date</Label>
                            <FormattedInput id="start_date" width="full" :disabled="true" v-model="state_date" />
                        </div>
                        <div class="col-span-4">
                            <Label for="notes">Job Notes</Label>
                            <Textarea id="notes" v-model="props.proposal.job.notes" class="bg-white dark:bg-dark-quatrenary dark:text-dark-primary border-light-tertiary dark:border-dark-tertiary" rows="10" :disabled="true" />
                        </div>
                    </div>
                </div>
                <div class="flex flex-col">
                    <img :src="'/img/logo.png'" class="rounded-lg mb-2" aria-label="company logo" />
                    <div class="flex justify-end flex-col items-end">
                        <p>{{ props.company.name }}</p>
                        <p>{{ props.company.address }}</p>
                        <p>{{ props.company.city }} {{ props.company.state.abbr }}, {{ props.company.zip }}</p>
                    </div>
                </div>
            </div>

            <div class="flex">
                <div cols="12" md="9">
                    <Label for="name">Proposal Name</Label>
                    <FormattedInput id="name" width="96" v-model="props.proposal.name" @blur="updateProposal()" />
                </div>
                <div>
                    <Label for="type">Type</Label>
                    <Type :types v-model="props.proposal.type" @update:modelValue="updateProposal()"></Type>
                </div>
            </div>

            <form @submit.prevent="submit()" class="flex flex-col rounded-md">
                <div class="flex items-start justify-end mb-4">
                    <Button class="h-12 w-32 cursor-pointer" @click.prevent="addScope()"><Plus></Plus>Add Scope</Button>
                </div>
                <div v-for="(scope, index) in props.proposal.scopes" :key="index">
                    <Scope :index :scope :unit_of_measurements />
                </div>
                <div class="flex justify-end">
                    <div class="flex flex-col">
                        <Label for="total">Total</Label>
                        <FormattedInput id="total" v-model="total" type="currency" :disabled="true" width="36" class="bg-light-tertiary rounded-lg font-bold" />
                    </div>
                </div>
            </form>

            <div>
                <Label for="exclusions">Exclusions</Label>
                <Textarea id="exclusions" class="bg-white dark:bg-dark-primary dark:text-dark-quatrenary" v-model="props.proposal.exclusions" @blur="updateProposal()" />
            </div>

            <div class="flex justify-between mt-2">
                <Link :href="route('estimating.jobs.index')" as="button" prefetch>
                    <Button class="cursor-pointer">Back</Button>
                </Link>
                <Button class="cursor-pointer" variant="destructive" @click="destroyProposal()">Delete</Button>
            </div>
        </div>

</GuestLayout>
</template>
