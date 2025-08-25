<script setup>
import { ref, computed, watch } from "vue";
import { Head, router } from "@inertiajs/vue3";
import { useDateFormat } from "@vueuse/core";
import EstimatingLayout from "@/Layouts/EstimatingLayout.vue";
import Manage from "./Modals/ManageCustomer.vue";
import Delete from "./Modals/DeleteCustomer.vue";

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
const filtered_states = computed(() => {
    return [{ id: null, state: "Select" }, ...props.filtered_states];
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
    return order.value == "desc" ? "mdi-arrow-up-box" : "mdi-arrow-down-box";
})
</script>

<template>
    <EstimatingLayout>

        <Head title="Customers" />

        <table class="container mx-auto">
            <thead>
                <tr>
                    <th colspan="6" class="text-2xl text-center py-4">
                        CUSTOMERS<Manage :new="true" :customer="newCustomer" :states></Manage>
                    </th>
                </tr>
                <tr class="uppercase bg-light-primary">
                    <th>
                        <v-text-field v-model="search" density="compact" hide-details label="Search customers..."
                            :prepend-inner-icon="icon" @click:prepend-inner="order = order == 'asc' ? 'desc' : 'asc'"
                            @input="getCustomers()"></v-text-field>
                    </th>
                    <th>
                        <v-select v-model="state" density="compact" hide-details="auto" :items="filtered_states"
                            item-title="state" item-value="id" label="State"
                            @update:model-value="getCustomers()"></v-select>
                    </th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th colspan="2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-light-secondary text-center">
                <tr v-for="(customer, index) in props.customers" :key="index">
                    <td class="py-2 px-4">{{ customer.name }}</td>
                    <td>{{ customer.state.state }}</td>
                    <td>{{ useDateFormat(customer.created_at, "M.D.YYYY") }}</td>
                    <td>{{ useDateFormat(customer.updated_at, "M.D.YYYY") }}</td>
                    <td>
                        <Manage :new="false" :customer :states />
                    </td>
                    <td>
                        <Delete :customer="customer" />
                    </td>
                </tr>
            </tbody>
        </table>
    </EstimatingLayout>
</template>
