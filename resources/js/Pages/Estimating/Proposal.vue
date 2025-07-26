<script setup>
import { computed, onMounted, watch } from 'vue';
import { Link, Head, useForm, router } from '@inertiajs/vue3';
import EstimatingLayout from '@/Layouts/EstimatingLayout.vue';
import { useProjectMath } from '@/Composables/useProjectMath';
import {VDateInput} from 'vuetify/labs/VDateInput';
import draggable from 'vuedraggable';

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

let { lineTotal, totals } = useProjectMath(form)

const end_date = computed(() => {
    if(!form.proposal.job.start_date) {return ''}
    let item = new Date(form.proposal.job.start_date);
    item = new Date(item.setDate(item.getDate()+form.proposal.job.days)).toLocaleDateString()
    return item
})

const states = computed(() => {
    return [{id: null, state: 'Select'}, ...props.states]
})
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
    }), {
        onSuccess: () => {
            totals()
        }
    }
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

watch(() => props.proposal, (item) => {
    form.proposal = props.proposal
})

</script>

<template>
<EstimatingLayout>
        <Head title="Estimating Web App" />

        <v-container class="w-100 w-xl-75 bg-grey-lighten-2 rounded-lg">
            <v-row class="pa-2">
                <v-col cols="12" md="6">
                    <v-img :src="'/img/logo.png'" class="rounded-md" aria-label="company logo" />
                    <div density="compact" disabled class="text-h5">ABC Company</div>
                    <div density="compact" disabled class="text-h5">123 Main St</div>
                    <div density="compact" disabled class="text-h5">Scranton, PA 12345</div>
                </v-col>

                <v-col cols="12" md="6" class="d-flex flex-column ga-1">
                    <v-text-field v-model.number="form.proposal.job.number" type="number" :disabled="true" :prefix="`D${new Date(form.proposal.job.created_at).getFullYear()} - `" hide-details density="compact" label="Job Number *" class="bg-grey-lighten-4"></v-text-field>
                    <v-text-field v-model="form.proposal.job.address" hide-details :disabled="true" density="compact" label="Address *" class="bg-grey-lighten-4"></v-text-field>
                    <v-row no-gutters>
                        <v-col cols="12" lg="6">
                            <v-text-field v-model="form.proposal.job.city" hide-details :disabled="true" density="compact" label="City *" class="bg-grey-lighten-4"></v-text-field>
                        </v-col>
                        <v-col cols="12" lg="3">
                            <v-select v-model="form.proposal.job.state.id" hide-details :disabled="true" :items="states" item-title="state" item-value="id" label="State *" density="compact" class="bg-grey-lighten-4"></v-select>
                        </v-col>
                        <v-col cols="12" lg="3">
                            <v-text-field v-model="form.proposal.job.zip" hide-details label="Zip Code *" :disabled="true" density="compact" class="bg-grey-lighten-4"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-date-input v-model="form.proposal.job.start_date" :disabled="true" label="Start Date" density="compact" hide-details class="bg-grey-lighten-4"></v-date-input>
                    <!-- <div class="d-flex ga-1">
                        <v-text-field v-model="end_date" hide-details density="compact" label="Completion Date" class="bg-grey-lighten-4" disabled></v-text-field>
                        <v-text-field v-model="form.proposal.job.days" hide-details density="compact" label="Days *" class="bg-grey-lighten-4" disabled></v-text-field>
                    </div> -->
                    <v-textarea v-model="form.proposal.job.notes" :disabled="true" hide-details label="Notes" class="bg-grey-lighten-4"></v-textarea>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12" md="9">
                    <v-text-field v-model="form.proposal.name" label="Proposal Title" bg-color="white" hide-details @blur="updateProposal()"></v-text-field>
                </v-col>
                <v-col>
                    <v-select v-model="form.proposal.type" :items="props.types" item-title="" item-value="id" bg-color="white" label="Proposal Type" hide-details @update:modelValue="updateProposal()"></v-select>
                </v-col>
            </v-row>

            <v-row>
                <v-col>
                    <form @submit.prevent="submit()" class="pa-4 bg-grey-lighten-1 rounded-md">
                        <div class="flex justify-end">
                            <v-btn prepend-icon="mdi-plus-box" @click.prevent="addScope()">Add Scope</v-btn>
                        </div>
                        <div v-for="(scope, scopeIndex) in form.proposal.scopes" :key="scopeIndex" class="bg-grey-lighten-3 rounded-md p-4 d-flex flex-column mb-2">
                            <div class="flex justify-between py-1">
                                <h1 size="48" class="text-lg">Scope # {{ scopeIndex+1 }}</h1>
                                <v-btn prepend-icon="mdi-minus" @click.prevent="removeScope(scope.id)">Remove Scope</v-btn>
                            </div>
                            <div class="d-flex flex-column flex-md-row gap-1 w-min">
                                <v-text-field v-model="scope.name" label="Scope Name" hide-details density="compact" placeholder="Name" class="bg-grey-darken-1 md:w-96 text-black" @blur="updateScope(scopeIndex)"></v-text-field>
                                <v-text-field v-model.number="scope.area" label="Area" hide-details density="compact" placeholder="Area" class="bg-grey-darken-1 md:w-60 text-black" type="number" @blur="updateScope(scopeIndex)"></v-text-field>
                            </div>
                            <div v-if="form.errors['scopes.' + scopeIndex + '.name']" class="text-red-400">{{ form.errors['scopes.' + scopeIndex + '.name']}}</div>

                            <div class="flex justify-end">
                                <v-btn prepend-icon="mdi-plus-box" class="bg-grey-darken-1 w-36" text="Add Line" @click.prevent="addLine(scopeIndex)">Add Line</v-btn>
                            </div>

                            <div class="bg-grey-darken-1 rounded-md">
                                <draggable :list="scope.lines" item-key="id" handle=".handle">
                                    <template #item="{element, index}">
                                        <div>
                                            <details class="bg-grey open:bg-grey-lighten-2 duration-500 rounded-md d-block d-md-none" @keyup.space="(event) => {event.preventDefault();}">
                                                <summary class="flex items-start p-1 text-lg cursor-pointer">
                                                    <v-icon size="28" class="handle cursor-pointer bg-grey-darken-4 rounded ">mdi mdi-drag-horizontal-variant</v-icon>
                                                    <v-text-field v-model="element.description" hide-details density="compact" label="Description *" class="bg-grey text-grey-darken-4" @blur="updateLine(scopeIndex, index)"></v-text-field>
                                                    <button class="rounded-md bg-grey-darken-4 float-right">
                                                        <v-icon size="28" class="bg-grey-darken-4 " @click.prevent="addLine(scopeIndex)" v-if="!index">mdi-plus-box</v-icon>
                                                        <v-icon size="28" class="bg-grey-darken-4 " @click.prevent="removeLine(scopeIndex, index)" v-else>mdi-minus</v-icon>
                                                    </button>
                                                </summary>
                                                <div>
                                                    <v-select v-model="element.unit_of_measurement.id" hide-details :items="uoms" density="compact" label="UOM *" class="bg-grey text-grey-darken-4" item-title="UOM" item-value="id" @blur="updateLine(scopeIndex, index)"></v-select>
                                                    <v-text-field v-model.number="element.price" hide-details type="number" density="compact" label="Price *" prefix="$" class="bg-grey text-grey-darken-4" @blur="updateLine(scopeIndex, index)" @input="lineTotal(scopeIndex, index)"></v-text-field>
                                                    <v-text-field v-model.number="element.quantity" hide-details type="number" density="compact" label="Quantity *" class="bg-grey text-grey-darken-4" @blur="updateLine(scopeIndex, index)" @input="lineTotal(scopeIndex, index)"></v-text-field>
                                                    <v-text-field v-model="element.total" hide-details density="compact" label="Line Total" disabled class="bg-grey text-grey-darken-4 text-black"></v-text-field>
                                                </div>
                                            </details>

                                            <div class="d-none d-md-flex d-md-flex-column align-center py-2">
                                                <v-icon size="48" class="handle cursor-pointer bg-grey-lighten-2 rounded-md mx-1">mdi mdi-drag-horizontal-variant</v-icon>
                                                <v-text-field v-model="element.description" hide-details density="compact" label="Description *" class="bg-grey-lighten-4 w-1/2" @blur="updateLine(scopeIndex, index)"></v-text-field>
                                                <v-select v-model="element.unit_of_measurement.id" hide-details :items="uoms" density="compact" label="UOM *" class="bg-grey-lighten-3" item-title="UOM" item-value="id" @update:modelValue="updateLine(scopeIndex, index)"></v-select>
                                                <v-text-field v-model.number="element.price" hide-details type="number" density="compact" label="Price *" prefix="$" class="bg-grey-lighten-4" @blur="updateLine(scopeIndex, index)"></v-text-field>
                                                <v-text-field v-model.number="element.quantity" hide-details type="number" density="compact" label="Quantity *" class="bg-grey-lighten-4" @blur="updateLine(scopeIndex, index)"></v-text-field>
                                                <v-text-field v-model="element.total" density="compact" label="Total" prefix="$"  hide-details readonly class="bg-grey-lighten-4 text-grey-darken-4"></v-text-field>
                                                <v-icon size="40" class="bg-grey-darken-4 text-white" @click.prevent="addLine(scopeIndex)" v-if="!index">mdi-plus-box</v-icon>
                                                <v-icon size="40" class="bg-grey-darken-4 text-white" @click.prevent="removeLine(scopeIndex, index)" v-else>mdi-minus</v-icon>
                                            </div>
                                            <div v-if="form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.description']" class="text-red-500">{{ form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.description']}}</div>
                                            <div v-if="form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.price']" class="text-red-500">{{ form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.price']}}</div>
                                            <div v-if="form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.quantity']" class="text-red-500">{{ form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.quantity']}}</div>
                                            <div v-if="form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.unit_of_measurement.id']" class="text-red-500">{{ form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.unit_of_measurement.id']}}</div>
                                        </div>
                                    </template>
                                </draggable>
                            </div>

                            <div class="flex justify-end mt-2">
                                <v-text-field v-model="scope.total" hide-details density="compact" label="Scope Total" prefix="$" disabled class="bg-grey max-w-48 text-black"></v-text-field>
                            </div>
                        </div>
                        <div class="flex justify-end my-2">
                            <v-text-field v-model="form.proposal.total" density="compact" label="Job Total" prefix="$" class="bg-grey text-black max-w-48" hide-details disabled></v-text-field>
                        </div>
                    </form>

                    <div>
                        <v-textarea v-model="form.proposal.exclusions" hide-details label="Exclusions" class="bg-grey-lighten-1" @blur="updateProposal()"></v-textarea>
                    </div>

                    <div class="flex justify-between mt-2">
                        <Link :href="route('estimating.index')">
                            <v-btn type="button">Back</v-btn>
                        </Link>
                        <!-- <v-btn as="submit" type="submit" :disabled="form.processing" @click="form.PDF = false">Save & Return</v-btn> -->
                        <v-btn color="red-accent-2" @click="destroyProposal()">Delete Proposal</v-btn>
                        <!-- <v-btn as="submit" type="submit" :disabled="form.processing">Generate PDF</v-btn> -->
                    </div>
                </v-col>
            </v-row>
        </v-container>

</EstimatingLayout>
</template>
