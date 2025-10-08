<script setup>
import { computed, onMounted } from 'vue';
import { Link, Head, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { useProjectMath } from '@/Composables/useProjectMath';
import { Button } from '@/Components/ui/button';
import { Textarea } from '@/Components/ui/textarea';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    new: Boolean,
    job: Object,
    company: Object,
    states: Object,
})

const form = useForm({
    id: props.job.id,
    number: props.job.number,
    address: props.job.address,
    city: props.job.city,
    state_id: props.job.state.id,
    zip: props.job.zip,
    start_date: props.job.start_date,
    notes: props.job.notes,
    created_at: props.job.created_at,
})

const states = computed(() => {
    return [{ id: null, state: 'Select' }, ...props.states]
})

const submit = () => {
    if (props.new) {
        form.post(route('estimating.store'), {
            onSuccess: () => {
                form.reset()
            }
        })
    }
    else {
        form.patch(route('estimating.update', { job: form.id }), {
            onSuccess: () => {
            }
        })
    }
}

onMounted(() => {
    if (form.start_date) {
        form.start_date = new Date(props.job.start_date)
    }
    if (props.new) {
        form.created_at = new Date()
    }
})

</script>

<template>
    <GuestLayout>

        <Head title="Job" />

        <div class="w-100 w-xl-75 rounded-lg">
            <v-form>
                <v-row class="pa-2">
                    <v-col cols="12" md="6">
                        <v-img :src="'/img/logo.png'" class="rounded-md" aria-label="company logo" />
                        <div density="compact" disabled class="text-h5">ABC Company</div>
                        <div density="compact" disabled class="text-h5">123 Main St</div>
                        <div density="compact" disabled class="text-h5">Scranton, PA 12345</div>
                    </v-col>
                    <template>
                        <v-card class="mx-auto" color="surface-variant"
                            image="https://cdn.vuetifyjs.com/docs/images/cards/dark-beach.jpg" max-width="340"
                            subtitle="Take a walk down the beach" title="Evening sunset">
                            <template v-slot:actions>
                                <v-btn append-icon="mdi-chevron-right" color="red-lighten-2" text="Book Activity" variant="outlined" block></v-btn>
                            </template>
                        </v-card>
                    </template>
                    <v-col cols="12" md="6" class="flex flex-col bg-light-secondary dark:bg-dark-secondary rounded-lg p-2">
                        <v-text-field v-model.number="form.number" type="number" :prefix="`D${new Date(form.created_at).getFullYear()} - `" hide-details density="compact" label="Job Number *" class="bg-grey-lighten-4"></v-text-field>
                        <div v-if="form.errors.number" class="text-red-400">{{ form.errors.number }}</div>
                        <v-text-field v-model="form.address" hide-details density="compact" label="Address *" class="bg-grey-lighten-4"></v-text-field>
                        <div v-if="form.errors.address" class="text-red-400">{{ form.errors.address }}</div>
                        <v-row no-gutters>
                            <v-col cols="12" lg="6">
                                <v-text-field v-model="form.city" hide-details density="compact" label="City *" class="bg-grey-lighten-4"></v-text-field>
                                <div v-if="form.errors.city" class="text-red-400">{{ form.errors.city }}</div>
                            </v-col>
                            <v-col cols="12" lg="3">
                                <v-select v-model="form.state_id" hide-details :items="states" item-title="state" item-value="id" label="State *" density="compact" class="bg-grey-lighten-4"></v-select>
                                <div v-if="form.errors.state_id" class="text-red-400">{{ form.errors.state_id }}</div>
                            </v-col>
                            <v-col cols="12" lg="3">
                                <v-text-field v-model="form.zip" hide-details label="Zip Code *" density="compact" class="bg-grey-lighten-4"></v-text-field>
                                <div v-if="form.errors.zip" class="text-red-400">{{ form.errors.zip }}</div>
                            </v-col>
                        </v-row>
                        <v-date-input v-model="form.start_date" label="Start Date" density="compact" hide-details class="bg-grey-lighten-4 "></v-date-input>
                        <!-- <v-textarea v-model="form.notes" hide-details label="Notes" class="bg-grey-lighten-4 my-1"></v-textarea> -->
                        <Textarea v-model="form.notes" />
                    </v-col>
                </v-row>
                <div class="flex justify-end">
                    <div class="flex gap-2">

                        <Link :href="route('estimating.index')" prefetch>
                            <Button variant="secondary">Cancel</Button>
                        </Link>
                        <Button :disabled="form.processing" @click="submit()">Save</Button>
                    </div>
                </div>
            </v-form>
        </div>

    </GuestLayout>
</template>
