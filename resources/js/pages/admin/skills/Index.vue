<script setup>
    import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import Manage from './modals/Manage.vue';
    import Delete from './modals/Delete.vue';
    import { useDateFormat } from '@vueuse/core';
    import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"

    const props = defineProps({
        skills: Object
    })

    const newSkill = {
        id: null,
        name: '',
        image: null,
    }

</script>

<template>
    <Head title="Skills" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Skills</h2>
        </template>

        <div class="container mx-auto w-full mt-20">
            <Table class="bg-light-primary dark:bg-dark-primary">
                <TableHeader class="bg-light-tertiary dark:bg-dark-tertiary">
                    <TableRow>
                        <TableHead class="p-6 text-black">Skill</TableHead>
                        <TableHead class="text-black">Image</TableHead>
                        <TableHead class="text-black">Created</TableHead>
                        <TableHead class="text-black">Updated</TableHead>
                        <TableHead class="text-center text-black"><Manage :new="true" :skill="newSkill"></Manage></TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="skill in props.skills" :key="skill.id">
                        <TableCell class="p-4">{{ skill.name }}</TableCell>
                        <TableCell><img :src="skill.image" class="w-12 h-12 rounded-full" /></TableCell>
                        <TableCell>{{ useDateFormat(skill.created_at, 'MM d, YYYY') }}</TableCell>
                        <TableCell>{{ useDateFormat(skill.updated_at, 'MM d, YYYY') }}</TableCell>
                        <TableCell>
                            <div class="flex justify-around">
                                <Manage :new="false" :skill></Manage>
                                <Delete :skill></Delete>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

    </AuthenticatedLayout>
</template>
