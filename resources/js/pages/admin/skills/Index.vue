<script setup>
    import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import Manage from './modals/Manage.vue';
    import Delete from './modals/Delete.vue';
    import { useDateFormat } from '@vueuse/core';
    import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"

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

        <div class="container mx-auto w-full p-4 mt-20 bg-light-primary dark:bg-dark-primary rounded-lg">
            <Table>
                <TableHeader class="bg-light-quatrenary">
                    <TableRow>
                        <TableHead>Skill</TableHead>
                        <TableHead>Image</TableHead>
                        <TableHead>Created</TableHead>
                        <TableHead>Updated</TableHead>
                        <TableHead class="text-center"><Manage :new="true" :skill="newSkill"></Manage></TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="skill in props.skills" :key="skill.id">
                        <TableCell>{{ skill.name }}</TableCell>
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
