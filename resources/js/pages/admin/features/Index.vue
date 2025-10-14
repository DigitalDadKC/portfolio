<script setup lang="ts">
import { shallowRef, useTemplateRef, nextTick, watch } from 'vue'
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Manage from './modals/Manage.vue';
import Destroy from './modals/Destroy.vue';
import { GripHorizontal } from 'lucide-vue-next';
import { useDateFormat } from '@vueuse/core';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { useSortable } from '@vueuse/integrations/useSortable'

const props = defineProps({
    features: Object,
})

const newFeature = {
    id: null,
    name: ''
}

const el = useTemplateRef<HTMLElement>('el')
const list = shallowRef(props.features)

const { option } = useSortable(el, list, {
    handle: '.handle',
    animation: 200,
    onSort: (e) => {
    nextTick(() => {
        list.value.forEach((item, index) => {
            item.order = index
        })
        updateFeatureOrder()
    })
    },
})

const updateFeatureOrder = () => {
    router.post(route('features.sort'), {
        'features': props.features
    }, {
        preserveScroll: true,
    })
}

watch(() => (props.features), (features) => {
    list.value = features
}, {
    deep: true,
})

</script>

<template>

    <Head title="Features" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Features</h2>
        </template>

        <div class="container mx-auto w-full mt-20">
            <Table class="bg-light-primary dark:bg-dark-primary w-full">
                <TableHeader class="bg-light-tertiary dark:bg-dark-tertiary">
                    <TableRow>
                        <TableHead class="p-6 text-black"></TableHead>
                        <TableHead class="p-6 text-black">Feature</TableHead>
                        <TableHead class="text-black">Created</TableHead>
                        <TableHead class="text-black">Updated</TableHead>
                        <TableHead class="text-black text-end">
                            <Manage :new="true" :feature="newFeature"></Manage>
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody class="bg-white dark:bg-gray-800 dark:border-gray-700" ref="el">
                    <TableRow v-for="feature in list" :key="feature.id" class="w-full">
                        <TableCell class="p-3">
                            <GripHorizontal class="handle cursor-pointer w-8 h-8"></GripHorizontal>
                        </TableCell>
                        <TableCell>{{ feature.name }}</TableCell>
                        <TableCell>{{ useDateFormat(feature.created_at, 'MMM d, YYYY') }}</TableCell>
                        <TableCell>{{ useDateFormat(feature.updated_at, 'MMM d, YYYY') }}</TableCell>
                        <TableCell class="flex justify-end gap-4">
                                <Manage :new="false" :feature></Manage>
                                <Destroy :feature></Destroy>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

</AuthenticatedLayout>
</template>
