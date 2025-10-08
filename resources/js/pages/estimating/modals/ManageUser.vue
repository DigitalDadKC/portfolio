<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/components/PrimaryButton.vue';

const props = defineProps({
    user: Object,
    roles: Object
})

const dialog = ref(false)
const form = useForm({
    user: props.user
})

const submit = () => {
    form.patch(route('users.update', form.user.id), {
        onSuccess: () => {
            dialog.value = false
        }
    })
}

</script>

<template>
    <div class="px-2 py-1 text-center">
        <v-dialog v-model="dialog" max-width="800">
            <template v-slot:activator="{ props: activatorProps }">
                <PrimaryButton v-bind="activatorProps" >
                    <v-icon size="x-small">mdi-cog</v-icon>
                </PrimaryButton>
            </template>

            <v-card prepend-icon="mdi-cog" :title="`Edit User: ${form.user.name}`">
                <v-card-text>
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field :disabled="true" density="compact" label="Name" v-model="form.user.name"></v-text-field>
                        </v-col>
                        <v-col cols="12" md="8">
                            <v-select density="compact" v-model="form.user.roles" :items="props.roles" label="Roles" item-title="name" item-value="id" auto-select-first multiple chips clearable return-object></v-select>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions>
                    <v-btn text="Close" variant="plain" @click="dialog = false; form.reset(); form.clearErrors()"></v-btn>
                    <v-btn color="primary" text="Save" @click="loading = !loading; submit()"></v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
