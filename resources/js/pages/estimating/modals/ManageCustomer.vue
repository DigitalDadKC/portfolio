<script setup>
import { ref, watch } from "vue";
import { useForm, Link } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    customer: Object,
    states: Object,
    new: Boolean,
});

const dialog = ref(false);

const form = useForm({
    id: props.customer.id,
    name: props.customer.name,
    state_id: props.customer.state.id,
});

const submit = () => {
    if (props.new) {
        form.post(route("customers.store"), {
            onSuccess: () => {
                dialog.value = false;
                form.reset()
            },
        });
    } else {
        form.patch(route("customers.update", { customer: form.id }), {
            onSuccess: () => {
                dialog.value = false;
            },
            preserveState: "errors",
        });
    }
};

watch(
    () => props.customer,
    (customer) => {
        (form.id = customer.id),
            (form.name = customer.name),
            (form.state_id = customer.state.id);
    }
);
</script>

<template>
    <v-dialog v-model="dialog" max-width="600">
        <template v-slot:activator="{ props: activatorProps }">
            <v-icon v-bind="activatorProps" size="52" v-if="props.new">mdi-plus-box</v-icon>
            <v-icon v-bind="activatorProps" size="32" v-else>mdi-pencil</v-icon>
        </template>

        <v-card :title="props.new ? 'New Customer' : `Edit ${props.customer.name ?? ''}`">
            <v-card-text>
                <v-row>
                    <v-col cols="12">
                        <v-text-field density="compact" v-model="form.name" label="Name"></v-text-field>
                        <h1 v-if="form.errors.name" class="text-red-500">{{ form.errors.name }}</h1>
                    </v-col>
                    <v-col cols="12">
                        <v-autocomplete density="compact" v-model="form.state_id" :items="props.states" label="State"item-title="state" item-value="id" clearable></v-autocomplete>
                    <h1 v-if="form.errors.state_id" class="text-red-500">{{ form.errors.state_id }}</h1>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12">
                        <v-card title="Jobs" subtitle="click on a job">
                            <v-card-text v-for="job in props.customer.jobs" :key="job.id">
                                <Link :href="route('estimating.edit', job.id)" prefetch>
                                    {{job.number}} - ({{ job.city }}, {{ job.state.abbr }})
                                </Link>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-card-text>

            <v-card-actions>
                <SecondaryButton text="Close" variant="plain" @click=" dialog = false; form.reset(); form.clearErrors();">Cancel</SecondaryButton>
                <PrimaryButton color="primary" text="Save" @click="submit()">Save</PrimaryButton>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
