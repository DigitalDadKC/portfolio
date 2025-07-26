<script setup>
import { computed } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import EstimatingLayout from '@/Layouts/EstimatingLayout.vue';
import UserModal from './Modals/UserModal.vue';

const props = defineProps({
    new: Boolean,
    company: Object,
    states: Object,
    users: Object,
    roles: Object,
    skills: Object,
})

const form = useForm({
    id: props.company?.id,
    name: props.company?.name,
    address: props.company?.address,
    city: props.company?.city,
    state_id: props.company.state?.id,
    zip: props.company?.zip,
    terms: props.company?.terms,
    logo: props.company?.logo
})

const image = computed(() => {
    if(!form.logo) {
        return
    }
    if(typeof form.logo == 'object') {
        return URL.createObjectURL(form.logo)
    } else {
        return form.logo
    }
})

const submit = () => {
    if(props.new) {
        form.post(route('company.store'), {
            onSuccess: (page) => {
                form.id = page.props.flash.message
                form.name = props.company.name
                form.address = props.company.address
                form.city = props.company.city
                form.state_id = props.company.state.id,
                form.zip = props.company.zip,
                form.terms = props.company.terms
            }
        })
    } else {
        router.post(route('company.update', {company: form.id}), {
            _method: 'put',
            name: form.name,
            address: form.address,
            city: form.city,
            state_id: form.state_id,
            zip: form.zip,
            terms: form.terms,
            logo: form.logo
        })
    }
}


</script>

<template>
    <EstimatingLayout>
        <Head title="Company" />

        <v-container>
            <v-row class="bg-grey-lighten-2">

                <v-col cols="12" lg="6" class="border-lg elevation-4">
                    <div class="text-h4 text-center py-2">USERS</div>
                    <v-table class="m-auto bg-grey-lighten-2 rounded-md">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in props.users" :key="index">
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>
                                    <div v-for="(role, i) in user.roles" :key="i">
                                        {{ role.name }}
                                    </div>
                                </td>
                                <td><UserModal :user :roles></UserModal></td>
                            </tr>
                        </tbody>
                    </v-table>
                </v-col>

                <v-col cols="12" lg="6" class="border-lg elevation-4">
                    <div class="text-h4 text-center py-2 text-high-emphasis">COMPANY INFO</div>
                    <v-row>
                        <v-col cols="12" lg="6">
                            <v-text-field v-model="form.name" hide-details density="compact" label="Company Name *" class="bg-grey-lighten-4"></v-text-field>
                            <div v-if="$page.props.errors.name" class="text-red-400">{{ $page.props.errors.name }}</div>
                        </v-col>
                        <v-col cols="12" lg="6">
                            <v-text-field v-model="form.address" hide-details density="compact" label="Address *" class="bg-grey-lighten-4 my-1"></v-text-field>
                            <div v-if="$page.props.errors.address" class="text-red-400">{{ $page.props.errors.address }}</div>
                        </v-col>
                        <v-col cols="12" lg="6">
                            <v-text-field v-model="form.city" hide-details density="compact" label="City *" class="bg-grey-lighten-4 my-1"></v-text-field>
                            <div v-if="$page.props.errors.city" class="text-red-400">{{ $page.props.errors.city }}</div>
                        </v-col>
                        <v-col cols="12" md="6" lg="3">
                            <v-select v-model="form.state_id" hide-details :items="props.states" item-title="state" item-value="id" label="State *" density="compact" class="bg-grey-lighten-4 my-1"></v-select>
                            <div v-if="$page.props.errors.state_id" class="text-red-400">{{ $page.props.errors.state_id }}</div>
                        </v-col>
                        <v-col cols="12" md="6" lg="3">
                            <v-text-field v-model.number="form.zip" hide-details density="compact" label="Zip *" class="bg-grey-lighten-4"></v-text-field>
                            <div v-if="$page.props.errors.zip" class="text-red-400">{{ $page.props.errors.zip }}</div>
                        </v-col>
                        <v-col cols="12">
                            <v-textarea v-model="form.terms" density="compact" label="Terms & Conditions" class="text-xs bg-grey-lighten-4" rows="12" hide-details auto-grow></v-textarea>
                            <div v-if="$page.props.errors.terms" class="text-red-400">{{ $page.props.errors.terms }}</div>
                        </v-col>
                        <v-col cols="12">
                            <v-file-input v-model="form.logo" show-size type="file" accept="image/*" label="Company Logo" @input="form.logo = $event.target.files[0]"></v-file-input>
                            <div v-if="form.errors.logo" class="text-red-400">{{ form.errors.logo }}</div>
                            <v-img height="255" aspect-ratio="16/9" :src="image"></v-img>
                        </v-col>
                        <v-col cols="12">
                            <v-btn color="orange-accent-1" type="submit" text="Create" class="float-right" @click="submit()" v-if="props.new"></v-btn>
                            <v-btn color="orange-accent-1" type="submit" text="Update" class="float-right" @click="submit()" v-else></v-btn>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </v-container>

    </EstimatingLayout>
</template>
