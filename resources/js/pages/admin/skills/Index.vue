<script setup>
    import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import Table from '@/components/Table.vue';
import { Pencil, Trash2 } from 'lucide-vue-next';

    const props = defineProps({
        skills: Object
    })

</script>

<template>
    <Head title="Skills Index" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Skills</h2>
        </template>

        <Table>
            <template v-slot:add>
                <Link :href="route('skills.create')" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-700 hover:dark:bg-indigo-700 text-white rounded-md">New Skill</Link>
            </template>

            <template v-slot:header-cells>
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Name</th>
                    <th scope="col" class="px-6 py-3">Image</th>
                    <th scope="col" class="px-6 py-3"></th>
                </tr>
            </template>

            <template v-slot:data>
                <tr v-for="skill in props.skills" :key="skill.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ skill.id }}</th>
                    <td class="px-6 py-4">{{ skill.name }}</td>
                    <td class="px-6 py-4"><img :src="skill.image" alt="" class="w-12 h-12 rounded-full"></td>
                    <td class="px-6 py-4 flex">
                        <Link :href="route('skills.edit', skill.id)" class="font-medium hover:text-blue-700 mr-2">
                            <Pencil></Pencil>
                        </Link>
                        <Link :href="route('skills.destroy', skill.id)" method="delete" as="button" type="button" class="font-medium text-red-500 hover:text-red-700 mr-2">
                            <Trash2 class="cursor-pointer"></Trash2>
                        </Link>
                    </td>
                </tr>
            </template>
        </Table>

    </AuthenticatedLayout>
</template>
