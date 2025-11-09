<script setup lang="ts">
import {  } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import Manage from './modals/Manage.vue';
import Destroy from './modals/Destroy.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { Button } from '@/components/ui/button';
import { useDateFormat } from '@vueuse/core';
import Outreach from './modals/Outreach.vue';
import { Earth, Link, Map } from 'lucide-vue-next';

const props = defineProps({
    clients: Object,
    states: Object,
    place: Object,
    places: Object,
})

const newClient = {
    id: null,
    name: ''
}
</script>

<template>
    <Head title="Projects Index" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Clients</h2>
        </template>

        <div class="container mx-auto w-full mt-20">
            <Table class="bg-light-primary dark:bg-dark-primary w-full">
                <TableHeader class="bg-light-tertiary dark:bg-dark-tertiary">
                    <TableRow>
                        <TableHead class="text-black p-4">Company</TableHead>
                        <TableHead class="text-black">Email</TableHead>
                        <TableHead class="text-black">Outreach(es)</TableHead>
                        <TableHead class="text-black">Created</TableHead>
                        <TableHead class="text-black">Updated</TableHead>
                        <TableHead class="text-black text-center"><Manage :new="true" :client="newClient" :states :place :places></Manage></TableHead>
                        <TableHead class="text-black text-center">Location</TableHead>
                        <TableHead class="text-black text-center">URL</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="client in props.clients" :key="client.id">
                        <TableCell>{{ client.company }}</TableCell>
                        <TableCell>{{ client.email }}</TableCell>
                        <TableCell>
                            <div v-for="outreach in client.outreaches" :key="outreach.id">
                                {{ useDateFormat(outreach.date_emailed, 'MMM D, YYYY') }}
                            </div>
                        </TableCell>
                        <TableCell>{{ useDateFormat(client.created_at, 'MMM D, YYYY') }}</TableCell>
                        <TableCell>{{ useDateFormat(client.updated_at, 'MMM D, YYYY') }}</TableCell>
                        <TableCell class="flex justify-around">
                            <Manage :new="false" :client :states :place :places></Manage>
                            <Destroy :client></Destroy>
                            <Outreach :client></Outreach>
                        </TableCell>
                        <TableCell class="text-center">
                            <a target="_blank" :href="`http://maps.google.com/?q=${client.address}, ${client.city}, ${client.state?.state} ${client.zip}`" v-if="client.address">
                                <Button class="bg-blue-500">
                                    <Map />
                                </Button>
                            </a>
                        </TableCell>
                        <TableCell class="text-center">
                            <a target="_blank" :href="client.url" v-if="client.url">
                                <Button class="bg-red-500">
                                    <Link />
                                </Button>
                            </a>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

    </AuthenticatedLayout>
</template>
