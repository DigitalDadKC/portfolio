<script setup lang="ts">
    import { shallowRef, useTemplateRef, nextTick, watch } from 'vue'
    import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
    import { Head, router, Link } from '@inertiajs/vue3';
    import Manage from './modals/Manage.vue';
    import Delete from './modals/Delete.vue';
    import { GripHorizontal } from 'lucide-vue-next';
    import { useDateFormat } from '@vueuse/core';
    import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
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
        active: true,
        skills: [],
    }

    const updateProjectOrder = () => {
        router.post(route('projects.sort'), {
            'projects': list.value
        }, {
            preserveScroll: true,
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

watch(() => (props.projects), (projects) => {
    list.value = projects
}, {
    deep: true,
})

</script>

<template>
    <Head title="Projects" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Projects</h2>
        </template>

        <div class="container mx-auto w-full mt-20">
            <Table class="bg-light-primary dark:bg-dark-primary">
                <TableHeader class="bg-light-tertiary dark:bg-dark-tertiary">
                    <TableRow>
                        <TableHead class="p-6 text-black">Project</TableHead>
                        <TableHead class="text-black">Image</TableHead>
                        <TableHead class="text-black">Description</TableHead>
                        <TableHead class="text-black">Skills</TableHead>
                        <TableHead class="text-black">Image</TableHead>
                        <TableHead class="text-black">URL</TableHead>
                        <TableHead class="text-black">Active</TableHead>
                        <TableHead class="text-black">Created</TableHead>
                        <TableHead class="text-black">Updated</TableHead>
                        <TableHead class="text-black text-center"><Manage :new="true" :project="newProject" :skills></Manage></TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody class="bg-white dark:bg-dark-primary" ref="el">
                    <TableRow v-for="project in list" :key="project.id" class="w-full">
                        <TableCell class="handle bg-light-quatrenary dark:bg-dark-quatrenary border-4 border-black m-1 cursor-grab">
                            <GripHorizontal class="w-full h-full"></GripHorizontal>
                        </TableCell>
                        <TableCell>{{ project.name }}</TableCell>
                        <TableCell>{{ project.description }}</TableCell>
                        <TableCell>
                            <div v-for="skill in project.skills" :key="skill.id">
                                {{ skill.name }}
                            </div>
                        </TableCell>
                        <TableCell><img :src="project.image" /></TableCell>
                        <TableCell>
                            <a target="_blank" :href="project.url">URL</a>
                        </TableCell>
                        <TableCell>{{ !!project.active }}</TableCell>
                        <TableCell>{{ useDateFormat(project.created_at, 'MMM d, YYYY') }}</TableCell>
                        <TableCell>{{ useDateFormat(project.updated_at, 'MMM d, YYYY') }}</TableCell>
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
