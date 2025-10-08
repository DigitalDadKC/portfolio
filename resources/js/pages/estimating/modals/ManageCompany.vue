<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    company: Object,
    states: Object
})

const dialog = ref(false)
const form = useForm({
    id: props.company.id,
    name: props.company.name,
    address: props.company.address,
    city: props.company.city,
    state_id: props.company.state.id,
    zip: props.company.zip,
    logo: props.company.logo,
    terms: props.company.terms
})

const picture = computed(() => {
    return (form.logo.type?.startsWith('image')) ? URL.createObjectURL(form.logo) : form.logo
})

const handleImage = (e) => {
    form.logo = e.target.files[0]
}

const submit = () => {
    form.post(route('companies.update', form.id), {
        _method: 'put',
        onSuccess: () => {
            dialog.value = false
        }
    })
}

</script>

<template>
    <div class="px-2 py-1 text-center">
        <v-dialog v-model="dialog" max-width="1000" max-height="1000">
            <template v-slot:activator="{ props: activatorProps }">
                <v-icon size="large" v-bind="activatorProps">mdi-cog-outline</v-icon>
            </template>

            <v-card prepend-icon="mdi-cog" :title="`Edit User: ${props.company.name}`">
                <v-card-text>
                    <v-row no-gutters>
                        <v-col cols="12">
                            <v-text-field density="compact" label="Name" v-model="form.name"></v-text-field>
                            <div v-if="$page.props.errors.name" class="text-red-400">{{ $page.props.errors.name }}</div>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field density="compact" label="Address" v-model="form.address"></v-text-field>
                            <div v-if="$page.props.errors.address" class="text-red-400">{{ $page.props.errors.address }}</div>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field density="compact" label="City" v-model="form.city"></v-text-field>
                            <div v-if="$page.props.errors.city" class="text-red-400">{{ $page.props.errors.city }}</div>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-select density="compact" v-model="form.state_id" :items="props.states" label="State" item-title="state" item-value="id" auto-select-first clearable></v-select>
                            <div v-if="$page.props.errors.state_id" class="text-red-400">{{ $page.props.errors.state_id}}</div>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-text-field density="compact" label="Zip" v-model="form.zip"></v-text-field>
                            <div v-if="$page.props.errors.zip" class="text-red-400">{{ $page.props.errors.zip }}</div>
                        </v-col>
                        <v-col cols="12">
                            <v-img density="compact" label="Logo" height="255" :src="picture"></v-img>
                            <div v-if="$page.props.errors.logo" class="text-red-400">{{ $page.props.errors.logo }}</div>
                            <v-file-input show-size type="file" accept="image/*" label="Company Logo" @input="handleImage($event)"></v-file-input>
                        </v-col>
                        <v-col cols="12" class="overflow-y-auto">
                            <div class="text-xl font-bold text-center uppercase">Terms & Conditions</div>
                            <v-textarea v-model="form.terms" density="compact" rows="12" hide-details class="overflow-y-auto"></v-textarea>
                            <div v-if="$page.props.errors.terms" class="text-red-400">{{ $page.props.errors.terms }}</div>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions>
                    <SecondaryButton @click="dialog = false; form.reset(); form.clearErrors()">Cancel</SecondaryButton>
                    <PrimaryButton @click="loading = !loading; submit()">Save</PrimaryButton>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
