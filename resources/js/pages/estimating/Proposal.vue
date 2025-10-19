<script setup>
import { computed, onMounted, watch } from 'vue';
import { Link, Head, useForm, router } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { useProjectMath } from '@/composables/useProjectMath';
import { Label } from 'reka-ui';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import FormattedInput from '@/components/FormattedInput.vue';
import Uom from './partials/Uom.vue';
import Type from './partials/Type.vue';
import draggable from 'vuedraggable';
import { Trash2, Grip, Plus, GripVertical } from 'lucide-vue-next';
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

const form = useForm({
    proposal: props.proposal,
    PDF: true
})

onMounted(() => {
    form.proposal.job.start_date = useDateFormat(form.proposal.job.start_date, 'MMM DD, YYYY')
})

let { fillQuantity, totals } = useProjectMath(form)

const uoms = computed(() => {
    return [{id: null, UOM: 'Select'}, ...props.unit_of_measurements]
})

onMounted(() => {
    totals()
})

const updateProposal = () => {
    router.put(route('proposals.update', form.proposal.id), {
        name: form.proposal.name,
        type: form.proposal.type,
        exclusions: form.proposal.exclusions
    }, {
        preserveScroll: true,
        onSuccess: () => {
            totals()
        }
    })
}

const destroyProposal = () => {
    router.delete(route('proposals.destroy', form.proposal.id))
}

const addScope = () => {
    router.post(route('scopes.create', form.proposal.id), {
    }, {
        preserveScroll: true,
        onSuccess: () => {
            totals()
        }
    })
}

const updateScope = (index) => {
    let scope = form.proposal.scopes[index]
    router.put(route('scopes.update', scope.id), {
        name: scope.name,
        area: scope.area,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            totals()
        }
    })
}

const removeScope = (id) => {
    router.delete(route('scopes.destroy', id), {
        preserveScroll: true,
        onSuccess: () => {
            totals()
        }
    })
}

const addLine = (index) => {
    let id = form.proposal.scopes[index].id
    router.post(route('lines.create', id), {
    }, {
        preserveScroll: true,
        onSuccess: () => {
            totals()
        }
    })
}

const updateLine = (scopeIndex, index) => {
    let line = form.proposal.scopes[scopeIndex].lines[index]
    router.put(route('lines.update', line.id), {
        description: line.description,
        unit_of_measurement_id: line.unit_of_measurement.id,
        price: line.price,
        quantity: line.quantity,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            totals()
        }
    })
}

const removeLine = (index, i) => {
    let id = form.proposal.scopes[index].lines[i].id
    router.delete(route('lines.destroy', id), {
        preserveScroll: true,
        onSuccess: () => {
            totals()
        }
    })
}

const sort = (scope) => {
    router.patch(route('lines.sort', scope.id), {
        lines: scope.lines,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            totals()
        }
    })
}

watch(() => props.proposal, (item) => {
    form.proposal = props.proposal
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
                            <FormattedInput id="number" type="number" width="full" :disabled="true" v-model="form.proposal.job.number" />
                        </div>
                        <div class="col-span-4 md:col-span-3">
                            <Label for="address">Address</Label>
                            <FormattedInput id="address" width="full" :disabled="true" v-model="form.proposal.job.address" />
                        </div>
                        <div class="col-span-4 md:col-span-2">
                            <Label for="city">City</Label>
                            <FormattedInput id="city" width="full" :disabled="true" v-model="form.proposal.job.city" />
                        </div>
                        <div class="col-span-4 md:col-span-2">
                            <Label for="state">State</Label>
                            <FormattedInput id="state" width="full" :disabled="true" v-model="form.proposal.job.state.state" />
                        </div>
                        <div class="col-span-4 md:col-span-2">
                            <Label for="zip">Zip</Label>
                            <FormattedInput id="zip" width="full" :disabled="true" v-model="form.proposal.job.zip" />
                        </div>
                        <div class="col-span-4 md:col-span-2">
                            <Label for="start_date">Start Date</Label>
                            <FormattedInput id="start_date" width="full" :disabled="true" v-model="form.proposal.job.start_date" />
                        </div>
                        <div class="col-span-4">
                            <Label for="notes">Job Notes</Label>
                            <Textarea id="notes" v-model="form.proposal.job.notes" class="bg-light-secondary dark:bg-dark-quatrenary dark:text-dark-primary border-light-tertiary dark:border-dark-tertiary" rows="10" :disabled="true" />
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
                    <FormattedInput id="name" width="96" v-model="form.proposal.name" @blur="updateProposal()" />
                </div>
                <div>
                    <Type :types v-model="form.proposal.type" @update:modelValue="updateProposal()"></Type>
                </div>
            </div>

            <form @submit.prevent="submit()" class="flex flex-col rounded-md">
                <div class="flex items-start justify-end mb-4">
                    <Button class="h-12 w-32 cursor-pointer" @click.prevent="addScope()"><Plus></Plus>Add Scope</Button>
                </div>
                <div v-for="(scope, scopeIndex) in form.proposal.scopes" :key="scopeIndex" class="relative bg-light-tertiary rounded-md p-2 my-6" v-motion-slide-right>
                    <div class="absolute flex gap-2 bg-light-quatrenary -top-10 rounded-t-lg p-2">
                        <h1 class="text-light-secondary">Scope # {{ scopeIndex+1 }}</h1>
                        <Trash2 class="cursor-pointer text-red-500" @click="removeScope(scope.id)"></Trash2>
                    </div>
                    <div class="flex justify-between mb-4">
                        <div class="flex flex-col md:flex-row gap-1">
                            <div>
                                <Label :for="`scope-name-${scopeIndex}`">Scope Name</Label>
                                <FormattedInput width="96" :id="`scope-name-${scopeIndex}`" v-model="scope.name" @blur="updateScope(scopeIndex)" />
                            </div>
                            <div>
                                <Label :for="`scope-area-${scopeIndex}`">Scope Area</Label>
                                <FormattedInput width="48" :id="`scope-area-${scopeIndex}`" v-model="scope.area" type="number" @blur="updateScope(scopeIndex)" />
                            </div>
                        </div>
                        <Button class="cursor-pointer" @click.prevent="addLine(scopeIndex)"><Plus></Plus>Add Line</Button>
                    </div>
                    <div v-if="form.errors['scopes.' + scopeIndex + '.name']" class="text-red-400">{{ form.errors['scopes.' + scopeIndex + '.name']}}</div>

                    <div class="rounded-md">
                        <draggable :list="scope.lines" item-key="id" handle=".handle" @change="sort(scope)">
                            <template #item="{element, index}">
                                <div v-motion-slide-right>
                                    <details class="open:bg-light-primary duration-500 rounded-md block md:hidden" @keyup.space="(event) => {event.preventDefault();}">
                                        <summary class="flex items-start p-1 text-lg cursor-pointer">
                                            <Grip class="handle cursor-pointer"></Grip>
                                            <FormattedInput v-model="element.description" />
                                            <button class="rounded-md float-right">
                                                <Plus @click="addLine(scopeIndex)"></Plus>
                                                <Plus @click="addLine(scopeIndex)"></Plus>
                                                <Trash2 class="cursor-pointer text-red-500" @click="removeLine(scopeIndex, index)"></Trash2>
                                            </button>
                                        </summary>
                                        <div>
                                            <Uom v-model="element.unit_of_measurement.id" :unit_of_measurements @update:model-value="updateLine(scopeIndex, index)"></Uom>
                                            <FormattedInput v-model="element.price" type="currency" label="Price" @blur="updateLine(scopeIndex, index)" />
                                            <FormattedInput v-model="element.quantity" type="number" label="Quantity" @blur="updateLine(scopeIndex, index)" />
                                            <FormattedInput v-model="element.total" type="currency" label="Total" :disabled="true" />
                                        </div>
                                    </details>

                                    <div class="hidden md:flex md:flex-row items-end gap-1">
                                        <GripVertical class="handle cursor-pointer w-8 h-10 dark:bg-dark-quatrenary dark:text-dark-primary rounded-lg"></GripVertical>
                                        <div class="grow">
                                            <Label v-if="!index">Description</Label>
                                            <FormattedInput class="w-full" v-model="element.description" label="Description" @blur="updateLine(scopeIndex, index)" />
                                        </div>
                                        <div>
                                            <Label v-if="!index">Unit</Label>
                                            <Uom v-model="element.unit_of_measurement.id" :unit_of_measurements @update:model-value="updateLine(scopeIndex, index)"></Uom>
                                        </div>
                                        <div>
                                            <Label v-if="!index">Price</Label>
                                            <FormattedInput v-model="element.price" type="currency" label="Price" @blur="updateLine(scopeIndex, index)" />
                                        </div>
                                        <div>
                                            <Label v-if="!index">Quantity</Label>
                                            <FormattedInput v-model="element.quantity" type="number" label="Quantity" @blur="updateLine(scopeIndex, index)" />
                                        </div>
                                        <div>
                                            <Label v-if="!index">Total</Label>
                                            <FormattedInput v-model="element.total" type="currency" label="Total" :disabled="true" />
                                        </div>
                                        <Trash2 class="cursor-pointer text-red-500" @click="removeLine(scopeIndex, index)"></Trash2>
                                    </div>
                                    <div v-if="form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.description']" class="text-red-500">{{ form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.description']}}</div>
                                    <div v-if="form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.price']" class="text-red-500">{{ form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.price']}}</div>
                                    <div v-if="form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.quantity']" class="text-red-500">{{ form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.quantity']}}</div>
                                    <div v-if="form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.unit_of_measurement.id']" class="text-red-500">{{ form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.unit_of_measurement.id']}}</div>
                                </div>
                            </template>
                        </draggable>
                    </div>

                    <div class="flex justify-end">
                        <div class="flex flex-col justify-start">
                            <Label>Scope Total</Label>
                            <FormattedInput v-model="scope.total" type="currency" :disabled="true" />
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <div class="flex flex-col">
                        <Label for="total">Total</Label>
                        <FormattedInput id="total" v-model="form.proposal.total" type="currency" :disabled="true" />
                    </div>
                </div>
            </form>

            <div>
                <Label for="exclusions">Exclusions</Label>
                <Textarea id="exclusions" class="bg-light-primary dark:bg-dark-primary dark:text-dark-quatrenary" v-model="form.proposal.exclusions" />
            </div>

            <div class="flex justify-between mt-2">
                <Link :href="route('estimating.index')" as="button" prefetch>
                    <Button class="cursor-pointer">Back</Button>
                </Link>
                <!-- <v-btn as="submit" type="submit" :disabled="form.processing" @click="form.PDF = false">Save & Return</v-btn> -->
                <Button class="cursor-pointer" variant="destructive" @click="destroyProposal()">Delete</Button>
                <!-- <DangerButton color="red-accent-2" @click="destroyProposal()">Delete Proposal</DangerButton> -->
                <!-- <v-btn as="submit" type="submit" :disabled="form.processing">Generate PDF</v-btn> -->
            </div>
        </div>

</GuestLayout>
</template>
