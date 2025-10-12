<script setup lang="ts">
    import { shallowRef, useTemplateRef } from 'vue'
    import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
    import { Head, router } from '@inertiajs/vue3';
    import Manage from './modals/Manage.vue';
    import Delete from './modals/Delete.vue';
    import draggable from 'vuedraggable';
    import { Grip, Pencil, Trash2 } from 'lucide-vue-next';
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
        props.projects.map((project, index) => project.project_order = index + 1)
        router.post(route('projects.sort'), {
            'projects': props.projects
        })
    }

</script>

<template>
    <Head title="Projects" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Projects</h2>
        </template>

        <div class="container mx-auto w-full p-4 mt-20 bg-light-primary dark:bg-dark-primary rounded-lg">
            <div class="flex justify-end m-2 p-2">
                <div>Project</div>
                <div>Image</div>
                <div>Skills</div>
                <div>Created</div>
                <div>Updated</div>
                <div class="text-center"><Manage :new="divue" :project="newProject" :skills></Manage></div>
            </div>
            <draggable :list="projects" item-key="index" handle=".handle" @start="drag-true" @end="drag-false" class="flex flex-col" @change="updateProjectOrder()">
                <template #item="{element}">
                    <div class="flex items-center justify-between bg-white dark:bg-gray-800 dark:border-gray-700 px-4">
                        <div>
                            <Grip class="handle cursor-pointer"></Grip>
                        </div>
                        <div>{{element.name}}</div>
                        <div>{{element.description}}</div>
                        <div>
                            <div v-for="skill in element.skills" :key="skill.id">
                                {{ skill.name }}
                            </div>
                        </div>
                        <div><img :src="element.image" /></div>
                        <div>{{ element.order }}</div>
                        <div>{{ !!element.active }}</div>
                        <div>
                            <div class="flex justify-around">
                                <Manage :new="false" :project="element" :skills></Manage>
                                <Delete :project="element"></Delete>
                            </div>
                        </div>
                    </div>
                </template>
            </draggable>
        </div>

    </AuthenticatedLayout>
</template>
