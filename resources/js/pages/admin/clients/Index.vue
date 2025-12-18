<script setup lang="ts">
import {  } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import Employees from './drawers/Employees.vue';
import Manage from './modals/Manage.vue';
import Destroy from './modals/Destroy.vue';
import Outreach from './modals/Outreach.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { Button } from '@/components/ui/button';
import { useDateFormat } from '@vueuse/core';
import { Link, MapPin } from 'lucide-vue-next';

const props = defineProps({
    clients: Object,
    states: Object,
    place: Object,
    places: Object,
})

</script>

<template>
    <Head title="Clients" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Clients</h2>
        </template>

        <div class="container mx-auto w-full">
            <Table class="bg-light-primary dark:bg-dark-primary w-full">
                <TableHeader class="bg-light-tertiary dark:bg-dark-tertiary">
                    <TableRow>
                        <TableHead class="text-black p-4">Company</TableHead>
                        <TableHead></TableHead>
                        <TableHead class="text-black">Outreach(es)</TableHead>
                        <TableHead></TableHead>
                        <TableHead class="text-black text-center"><Manage :new="true" :states :place :places></Manage></TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="client in props.clients" :key="client.id">
                        <TableCell>
                            <p class="font-bold">{{ client.company }}</p>
                            <p class="italic text-xs">{{ client.email }}</p>
                            <p class="italic text-xs">Created: {{ useDateFormat(client.created_at, 'MMM D, YYYY') }}</p>
                            <p class="italic text-xs">Updated: {{ useDateFormat(client.updated_at, 'MMM D, YYYY') }}</p>
                        </TableCell>
                        <TableCell>
                            <div class="text-center space-x-4">
                                <Manage :new="false" :client :states :place :places></Manage>
                                <Destroy :client></Destroy>
                                <a target="_blank" :href="`http://maps.google.com/?q=${client.address}, ${client.city}, ${client.state?.state} ${client.zip}`" v-if="client.address">
                                    <Button class="bg-blue-500">
                                        Location
                                        <MapPin />
                                    </Button>
                                </a>
                                <a target="_blank" :href="client.url" v-if="client.url">
                                    <Button class="bg-red-500">
                                        Site
                                        <Link />
                                    </Button>
                                </a>
                            </div>
                        </TableCell>
                        <TableCell>
                            <div v-for="outreach in client.outreaches" :key="outreach.id">
                                {{ useDateFormat(outreach.date_emailed, 'MMM D, YYYY') }}
                            </div>
                        </TableCell>
                        <TableCell>
                            <Outreach :client></Outreach>
                        </TableCell>
                        <TableCell class="text-center">
                            <Employees :client />
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

    </AuthenticatedLayout>
</template>
