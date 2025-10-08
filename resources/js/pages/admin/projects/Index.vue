<script setup>
    import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import Table from '@/components/Table.vue';
    import draggable from 'vuedraggable';
import { Grip, Pencil, Trash2 } from 'lucide-vue-next';

    const props = defineProps({
        projects: Object,
    })

const updateProjectOrder = () => {
    props.projects.map((project, index) => project.project_order = index + 1)
    router.post(route('projects.sort'), {
        'projects': props.projects
    })
}

</script>

<template>
    <Head title="Projects Index" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Projects</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-end m-2 p-2">
                    <Link :href="route('projects.create')" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-700 hover:dark:bg-indigo-700 text-white rounded-md">New Project</Link>
                </div>
                <div class="flex flex-col">
                    <div class="flex text-xs text-start text-gray-200 uppercase bg-gray-500 dark:bg-gray-700 dark:text-gray-400">
                        <div scope="col" class="w-20 py-3"></div>
                        <div scope="col" class="w-60 py-3">Name</div>
                        <div scope="col" class="w-72 py-3">Description</div>
                        <div scope="col" class="w-28 py-3">Skills</div>
                        <div scope="col" class="w-28 py-3">Image</div>
                        <div scope="col" class="w-28 py-3 text-center">Order</div>
                        <div scope="col" class="w-60 py-3">Active</div>
                        <div scope="col" class="w-60 py-3">Actions</div>
                    </div>

                    <draggable :list="projects" item-key="index" handle=".handle" @start="drag-true" @end="drag-false" class="flex flex-col" @change="updateProjectOrder()">
                        <template #item="{element}">
                            <div class="flex items-center justify-start bg-white dark:bg-gray-800 dark:border-gray-700 px-4">
                                <div class="w-12 py-3">
                                    <Grip class="handle cursor-pointer"></Grip>
                                </div>
                                <div class="w-60 py-4 ps-4">{{ element.name }}</div>
                                <div class="w-72 py-4">{{ element.description }}</div>
                                <div class="w-28 py-4"><span v-for="skill in element.skills" :key="skill.id">{{skill.name}}<br /></span></div>
                                <div class="w-28 py-4"><img :src="element.image" alt="" class="w-12 h-12 rounded-full"></div>
                                <div class="w-28 py-4 text-center">{{ element.project_order }}</div>
                                <div class="w-28 py-4 text-center">{{ !!element.active }}</div>
                                <div class="w-60 py-4 flex">
                                    <Link :href="route('projects.edit', element.id)" class="font-medium hover:text-blue-700 mr-2">
                                        <Pencil></Pencil>
                                    </Link>
                                    <Link :href="route('projects.destroy', element.id)" method="delete" as="button" type="button" class="font-medium text-red-500 hover:text-red-700 mr-2">
                                        <Trash2 class="cursor-pointer"></Trash2>
                                    </Link>
                                </div>
                            </div>
                        </template>
                    </draggable>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
