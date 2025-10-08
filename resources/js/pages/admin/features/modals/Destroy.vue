<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    feature: Object
})

const dialog = ref(false)

const form = useForm({
    id: props.feature.id,
    name: props.feature.name,
})

const destroy = () => {
    form.delete(route('features.destroy', form.id), {
        onSuccess: () => {
            dialog.value = false
        }
    })
}

watch(() => props.feature,
(item) => {
    form.id = item.name;
    form.name = props.name;
})

</script>

<template>
    <div class="pa-4 text-center">
        <v-dialog v-model="dialog" max-width="600">
            <template v-slot:activator="{ props: activatorProps }">
                <v-btn class="cursor-pointer" variant="elevated" v-bind="activatorProps" >
                    <v-icon>mdi-trash-can</v-icon>
                </v-btn>
            </template>

            <v-card prepend-icon="mdi-trash-can" title="DELETE">
                <v-card-text>
                    <v-row dense>
                        <v-col cols="12">
                            <v-text-field v-model="form.name" label="Feature" disabled></v-text-field>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions>
                    <v-btn text="Close" variant="plain" @click="dialog = false; form.reset()"></v-btn>
                    <v-btn color="red" text="Delete" @click="destroy()"></v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
