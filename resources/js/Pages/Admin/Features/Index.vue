<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Manage from './Modals/Manage.vue';
import Destroy from './Modals/Destroy.vue';
import draggable from 'vuedraggable';

const props = defineProps({
    features: Object,
})

const newfeature = {
    id: null,
    name: ''
}

const updateFeatureOrder = () => {

}

console.log(props.features)

</script>

<template>
    <Head title="Features" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Features</h2>
        </template>

        <div class="container mx-auto mt-8">
            <v-table color="success" density="compact" striped="even" fixed-header>
                <thead color="danger" class="primary">
                    <tr>
                        <th class="py-4">Feature</th>
                        <th class=""><Manage :new="true" :feature="newfeature"></Manage></th>
                    </tr>
                </thead>
                <tbody class="bg-light-tertiary divide-y">
                    <draggable :list="props.features" item-key="id" handle=".handle" @start="drag-true" @end="drag-false">
                        <template #item="{element}">
                            <tr>
                                <td><v-icon size="32" class="handle cursor-pointer bg-light-quatrenary text-white rounded m-4">mdi mdi-drag-horizontal-variant</v-icon></td>
                                <td>{{ element.name }}</td>
                                <td class=""><Manage :new="false" :feature="element"></Manage></td>
                                <td class=""><Destroy :new="false" :feature="element"></Destroy></td>
                                <td class="">{{ element.order }}</td>
                            </tr>
                        </template>
                    </draggable>
                </tbody>
            </v-table>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
    .v-table thead {
        background-color: orange;
    }
</style>
