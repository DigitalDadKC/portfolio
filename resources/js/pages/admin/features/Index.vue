<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Manage from './Modals/Manage.vue';
import Destroy from './Modals/Destroy.vue';
import draggable from 'vuedraggable';
import { Grip } from 'lucide-vue-next';

const props = defineProps({
    features: Object,
})

const newfeature = {
    id: null,
    name: ''
}

const updateFeatureOrder = () => {
    props.features.map((feature, index) => feature.order = index + 1)
    router.post(route('features.sort'), {
        'features': props.features
    })
}

</script>

<template>
    <Head title="Features" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Features</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-end m-2 p-2">
                    <Link :href="route('projects.create')" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-700 hover:dark:bg-indigo-700 text-white rounded-md">New Feature</Link>
                </div>
                <div class="flex flex-col">
                    <div class="flex justify-between px-8 text-xs text-start text-gray-200 uppercase bg-gray-500 dark:bg-gray-700 dark:text-gray-400">
                        <div scope="col" class="py-3">Name</div>
                        <div scope="col" class="py-3">Actions</div>
                    </div>

                    <draggable :list="features" item-key="index" handle=".handle" @start="drag-true" @end="drag-false" class="flex flex-col" @change="updateFeatureOrder()">
                        <template #item="{element}">
                            <div class="flex items-center justify-between bg-white dark:bg-gray-800 dark:border-gray-700 px-4">
                                <div class="flex">
                                    <div class="w-12 py-3">
                                        <Grip class="handle cursor-pointer"></Grip>
                                        <!-- <v-icon size="48" class="handle cursor-pointer bg-indigo-500 text-white rounded  h-100">mdi mdi-drag-horizontal-variant</v-icon> -->
                                    </div>
                                    <div class="py-4 ps-4">{{ element.name }}</div>
                                </div>
                                <div>
                                    <Link :href="route('projects.edit', element.id)" class="font-medium text-blue-500 hover:text-blue-700 mr-2">Edit</Link>
                                    <Link :href="route('projects.destroy', element.id)" method="delete" as="button" type="button" class="font-medium text-red-500 hover:text-red-700 mr-2">Delete</Link>
                                </div>
                            </div>
                        </template>
                    </draggable>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
    .v-table thead {
        background-color: orange;
    }
</style>
