<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
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

        <v-container class="mt-8">
            <v-row no-gutters class="flex items-center bg-light-quatrenary text-light-primary p-4">
                <v-col>Feature</v-col>
                <v-col cols="3"><Manage :new="true" :feature="newfeature"></Manage></v-col>
            </v-row>
                <draggable :list="props.features" item-key="id" handle=".handle" @start="drag-true" @end="drag-false" @change="updateFeatureOrder()">
                    <template #item="{element}">
                        <v-row no-gutters class="bg-light-secondary text-accent border-b-1 border-black p-2">
                            <v-col>{{ element.name }}</v-col>
                            <v-col class=""><Manage :new="false" :feature="element"></Manage></v-col>
                            <v-col class=""><Destroy :new="false" :feature="element"></Destroy></v-col>
                            <v-col><v-icon size="32" class="handle cursor-pointer bg-light-quatrenary text-white rounded">mdi mdi-drag-horizontal-variant</v-icon></v-col>
                            <!-- <v-col class="">{{ element.order }}</v-col> -->
                        </v-row>
                    </template>
                </draggable>
        </v-container>
    </AuthenticatedLayout>
</template>

<style scoped>
    .v-table thead {
        background-color: orange;
    }
</style>
