<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Table from '@/Components/Table.vue';

</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Invoices</h2>
        </template>

        <Table>
            <template v-slot:add>
                <Link :href="route('skills.create')" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-700 hover:dark:bg-indigo-700 text-white rounded-md">New Skill</Link>
            </template>

            <template v-slot:header-cells>
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Invoice #</th>
                    <th scope="col" class="px-6 py-3">Client</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </tr>
            </template>
        </Table>

    </AuthenticatedLayout>
</template>
