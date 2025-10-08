<script setup>
    import { Head, Link, router, useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import Table from '@/Components/Table.vue';
    import ClientModal from './Modals/ClientModal.vue';
    import { useDateFormat } from '@vueuse/core';

    const props = defineProps({
        clients: Object,
    })

    const form = useForm({
        name: '',
        email: '',
    })

    const newClient = {
        id: null,
        name: ''
    }
</script>

<template>
    <Head title="Projects Index" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Clients</h2>
        </template>

        <Table>
            <template v-slot:add>
                <ClientModal :new="true" :client="newClient"></ClientModal>
            </template>

            <template v-slot:header-cells>
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Company</th>
                    <th scope="col">Email</th>
                    <th scope="col">Outreach</th>
                    <th scope="col">Action</th>
                </tr>
            </template>

            <template v-slot:data>
                <tr v-for="(client, index) in props.clients" :key="index" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td class="px-2 py-1">{{ client.id }}</td>
                    <td>{{ client.name }}</td>
                    <td>{{ client.company }}</td>
                    <td>{{ client.email }}</td>
                    <td>
                        <div v-for="outreach in client.outreaches" :key="outreach.id" v-if="client.email">
                            {{ useDateFormat(outreach.date_emailed, 'MMM D, YYYY') }}
                        </div>
                    </td>
                    <td>
                        <ClientModal :new="false" :client></ClientModal>
                    </td>
                </tr>
            </template>
        </Table>

    </AuthenticatedLayout>
</template>
