<script setup lang="ts">
import { Button } from "@/components/ui/button";
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuGroup, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuCheckboxItem } from "@/components/ui/dropdown-menu"

const props = defineProps({
    states: Object,
    customers: Object,
})

const selectedStates = defineModel('selectedStates', {default: () => ['idiot']})
const selectedCustomers = defineModel('selectedCustomers', {default: () => []})

</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child class="w-full bg-light-quatrenary text-white cursor-pointer">
            <Button class="bg-light-quatrenary">
                Filters ({{ selectedStates.length + selectedCustomers.length }})
            </Button>
        </DropdownMenuTrigger>

        <DropdownMenuContent class="w-120 h-200 overflow-auto flex flex-col items-center gap-1" side="right">
            <DropdownMenuLabel>Filters</DropdownMenuLabel>
            <DropdownMenuGroup class="grid grid-cols-2 overflow-hidden">
                    <div class="overflow-y-auto border border-light-secondary rounded-md bg-light-tertiary shadow-md">
                        <DropdownMenuLabel>Select states</DropdownMenuLabel>
                        <DropdownMenuCheckboxItem
                            v-for="state in props.states"
                            :key="state.id"
                            @select.prevent
                            :class="{'bg-light-quatrenary':selectedStates.includes(state.id)}"
                            @update:model-value="() => {
                                const index = selectedStates?.indexOf(state.id)
                                if (index > -1) {
                                    selectedStates.splice(index, 1)
                                } else {
                                    selectedStates.push(state.id)
                                }
                            }"
                        >
                            {{ state.state }}
                        </DropdownMenuCheckboxItem>
                    </div>
                    <div class="overflow-y-auto border border-light-secondary rounded-md bg-light-tertiary shadow-md">
                        <DropdownMenuLabel>Select Customers</DropdownMenuLabel>
                        <DropdownMenuCheckboxItem
                            v-for="customer in props.customers"
                            :key="customer.id"
                            @select.prevent
                            :class="{'bg-light-quatrenary':selectedCustomers.includes(customer.id)}"
                            @update:model-value="() => {
                                const index = selectedCustomers?.indexOf(customer.id)
                                if (index > -1) {
                                    selectedCustomers.splice(index, 1)
                                } else {
                                    selectedCustomers.push(customer.id)
                                }
                            }"
                        >
                            {{ customer.name }}
                        </DropdownMenuCheckboxItem>
                    </div>
            </DropdownMenuGroup>
            <DropdownMenuSeparator />
        </DropdownMenuContent>
    </DropdownMenu>
</template>