<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    customer: Object,
    errors: Object
})

const dialog = ref(false)

const form = useForm({
    id: props.customer.id,
    name: props.customer.name,
})

const submit = () => {
        form.delete(route('customers.destroy', {customer: form.id}), {
            onSuccess: () => {
                dialog.value = false
            }
        })
    }


watch(
    () => props.customer,
    (customer) => {
        (form.id = customer.id),
        (form.name = customer.name);
    }
);


</script>

<template>
    <v-dialog v-model="dialog" max-width="600">
        <template v-slot:activator="{ props: activatorProps }">
            <v-icon v-bind="activatorProps" size="32">mdi-trash-can</v-icon>
        </template>

        <v-card :title="`Delete ${props.customer.name}?`">
            <v-card-actions>
                <SecondaryButton @click=" dialog = false; form.reset(); form.clearErrors();">Cancel</SecondaryButton>
                <DangerButton @click="submit()">Delete</DangerButton>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
