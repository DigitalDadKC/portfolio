<script setup>
    import { Head, Link, router } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import BusinessModal from './Modals/BusinessModal.vue';
    import draggable from 'vuedraggable';

    const props = defineProps({
        businesses: Object,
    })

    const newBusiness = {
        id: null,
        name: ''
    }
</script>

<template>
    <Head title="Projects Index" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Businesses</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="text-end py-4">
                    <BusinessModal :new="true" :business="newBusiness"></BusinessModal>
                </div>
                <div class="flex flex-col overflow-x-auto">
                    <div class="flex text-xs text-start text-gray-200 uppercase bg-gray-500 dark:bg-gray-700 dark:text-gray-400">
                        <div scope="col" class="w-20 py-3 text-center">ID</div>
                        <div scope="col" class="w-48 py-3">Name</div>
                    </div>

                    <div class="flex items-center justify-between" v-for="(business, index) in props.businesses" :key="index">
                        <div class="flex">
                            <div scope="row" class="w-20 py-4 text-center">{{ business.id }}</div>
                            <div class="w-48 py-4">{{ business.name }}</div>
                        </div>
                        <div>
                            <div class="w-28 py-4"><BusinessModal :new="false" :business></BusinessModal></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
