<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    new: Boolean,
    business: Object,
})

const dialog = ref(false)
const form = useForm({
    id: props.business.id,
    name: props.business.name
})

const submit = () => {
    if(props.new) {
        form.post(route('businesses.store'), {
            onSuccess: () => {
                dialog.value = false
                form.reset()
            }
        })
    } else {
        form.patch(route('businesses.update', form.id), {
            onSuccess: () => {
                dialog.value = false
            }
        })
    }
}

</script>

<template>
    <v-dialog v-model="dialog" max-width="800">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn v-bind="activatorProps" v-if="props.new" >New Business</v-btn>
            <v-icon v-bind="activatorProps" v-else>mdi-cog</v-icon>
        </template>

        <v-card prepend-icon="mdi-office-building" :title="(props.new) ? 'New Business' : `Edit Business: ${props.business.name}`">
            <v-card-text>
                <v-row>
                    <v-col cols="12">
                        <v-text-field density="compact" v-model="form.name" hide-details label="Name"></v-text-field>
                    </v-col>
                </v-row>
            </v-card-text>

            <v-card-actions>
                <v-btn text="Close" variant="plain" @click="dialog = false; form.reset(); form.clearErrors()"></v-btn>
                <v-btn color="primary" text="Save" @click="loading = !loading; submit()"></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
