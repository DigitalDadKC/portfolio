<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    new: Boolean,
    feature: Object,
})

const dialog = ref(false)
const form = useForm({
    id: props.feature?.id,
    name: props.feature?.name
})

const submit = () => {
    if(props.new) {
        form.post(route('features.store'), {
            onSuccess: () => {
                dialog.value = false
                form.reset()
            }
        })
    } else {
        form.patch(route('features.update', form.id), {
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
            <v-btn v-bind="activatorProps" class="cursor-pointer" v-if="props.new" >New Feature</v-btn>
            <v-btn variant="elevated" class="cursor-pointer" v-bind="activatorProps" v-else>
                <v-icon>mdi-cog</v-icon>
            </v-btn>
        </template>

        <v-card prepend-icon="mdi-office-building" :title="(props.new) ? 'New Feature' : `Edit Feature: ${props.feature.name}`">
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
