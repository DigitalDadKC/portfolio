<script setup>
import { ref, computed, watch } from "vue";
import {  router } from "@inertiajs/vue3";
import { useDateFormat } from "@vueuse/core";
import EstimatingLayout from "@/layouts/EstimatingLayout.vue";
import { Label } from "@/components/ui/label";
import Manage from "./modals/ManageCustomer.vue";
import Delete from "./modals/DeleteCustomer.vue";
import SearchBox from "./partials/SearchBox.vue";
import State from './partials/State.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { ArrowDown, ArrowUp } from "lucide-vue-next";

const props = defineProps({
    customers: Object,
    states: Object,
    filtered_states: Object,
    filters: Object,
});

const search = ref(props.filters.search);
const order = ref(props.filters.order);
const state = ref(props.filters.state);

const states = computed(() => {
    return [{ id: null, state: "Select" }, ...props.states];
});

const newCustomer = {
    id: null,
    name: "",
    state: {
        id: null,
        abbr: "",
        state: "",
    },
};

watch(
    () => order.value,
    () => {
        getCustomers();
    }
);

const getCustomers = () => {
    router.reload({
        data: {
            search: search.value,
            order: order.value,
            state: state.value,
        },
        only: ["customers", "filters"],
        replace: true,
    });
};

const icon = computed(() => {
    return order.value == "desc" ? ArrowUp : ArrowDown;
})
</script>

<template>
    <EstimatingLayout>

        <div class="container mx-auto bg-light-tertiary dark:bg-dark-primary rounded-xl py-8 w-full">
            <Table class="bg-light-primary dark:bg-dark-primary w-300">
                <TableHeader class="p-4">
                    <TableRow class="uppercase bg-light-tertiary dark:bg-dark-tertiary">
                        <TableHead class="py-4">
                            <Label for="search" class="text-black">Customer</Label>
                            <div class="flex items-center gap-2">
                                <SearchBox id="search" v-model="search" @update:model-value="getCustomers()"></SearchBox>
                                <icon class="cursor-pointer" @click="order = order == 'asc' ? 'desc' : 'asc'" />
                            </div>
                        </TableHead>
                        <TableHead>
                            <Label for="state" class="text-black">State</Label>
                            <State id="state" v-model="state" :states @update:model-value="getCustomers()"></State>
                        </TableHead>
                        <TableHead class="text-black">Created</TableHead>
                        <TableHead class="text-black">Updated</TableHead>
                        <TableHead colspan="2" class="text-end">
                            <Manage :new="true" :customer="newCustomer" :states></Manage>
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody class="divide-y divide-light-secondary">
                    <TableRow v-for="(customer, index) in props.customers" :key="index">
                        <TableCell class="py-2 px-4">{{ customer.name }}</TableCell>
                        <TableCell>{{ customer.state.state }}</TableCell>
                        <TableCell>{{ useDateFormat(customer.created_at, "M.D.YYYY") }}</TableCell>
                        <TableCell>{{ useDateFormat(customer.updated_at, "M.D.YYYY") }}</TableCell>
                        <TableCell>
                            <Manage :new="false" :customer :states />
                        </TableCell>
                        <TableCell>
                            <Delete :customer="customer" />
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

    </EstimatingLayout>
</template>
