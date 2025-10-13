<script setup lang="ts">
    import { shallowRef, useTemplateRef, nextTick } from 'vue'
    import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
    import { Head, router } from '@inertiajs/vue3';
    import Manage from './modals/Manage.vue';
    import Delete from './modals/Delete.vue';
    import draggable from 'vuedraggable';
    import { Grip, GripHorizontal, Pencil, Trash2 } from 'lucide-vue-next';
    import { useDateFormat } from '@vueuse/core';
    import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
    import { useSortable } from '@vueuse/integrations/useSortable'

    const props = defineProps({
        projects: Object,
        skills: Object,
    })

    const newProject = {
        id: null,
        name: '',
        description: '',
        image: null,
        url: '',
        skills: [],
    }

    const updateProjectOrder = () => {
        router.post(route('projects.sort'), {
            'projects': list.value
        })
    }


    const el = useTemplateRef<HTMLElement>('el')
    const list = shallowRef(props.projects)


    const { option } = useSortable(el, list, {
        handle: '.handle',
        animation: 200,
        onSort: (e) => {
        nextTick(() => {
            list.value.forEach((item, index) => {
                item.order = index
            })
            updateProjectOrder()
        })
        },
    })

</script>

<template>
    <Head title="Projects" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Projects</h2>
        </template>

        <div class="container mx-auto">
            <Table class="w-full mt-20 bg-light-primary dark:bg-dark-primary">
                <TableHeader>
                    <TableRow>
                        <TableHead>Project</TableHead>
                        <TableHead>Image</TableHead>
                        <TableHead>Description</TableHead>
                        <TableHead>Skills</TableHead>
                        <TableHead>Image</TableHead>
                        <TableHead>URL</TableHead>
                        <TableHead>Active</TableHead>
                        <TableHead>Created</TableHead>
                        <TableHead>Updated</TableHead>
                        <TableHead class="text-center"><Manage :new="true" :project="newProject" :skills></Manage></TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody class="bg-white dark:bg-gray-800 dark:border-gray-700" ref="el">
                    <TableRow v-for="project in list" :key="project.id" class="w-full">
                        <TableCell class="p-3">
                            <GripHorizontal class="handle cursor-pointer w-8 h-8"></GripHorizontal>
                        </TableCell>
                        <TableCell>{{ project.name }}</TableCell>
                        <TableCell>{{ project.description }}</TableCell>
                        <TableCell>
                            <div v-for="skill in project.skills" :key="skill.id">
                                {{ skill.name }}
                            </div>
                        </TableCell>
                        <TableCell><img :src="project.image" /></TableCell>
                        <TableCell>{{ project.url }}</TableCell>
                        <TableCell>{{ !!project.active }}</TableCell>
                        <TableCell>{{ useDateFormat(project.created_at, 'MM d, YYYY') }}</TableCell>
                        <TableCell>{{ useDateFormat(project.updated_at, 'MM d, YYYY') }}</TableCell>
                        <TableCell>
                            <div class="flex justify-around">
                                <Manage :new="false" :project :skills></Manage>
                                <Delete :project></Delete>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

    </AuthenticatedLayout>
</template>
