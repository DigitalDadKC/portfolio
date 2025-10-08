<script setup lang="ts">
import { Label } from '@/Components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select"

const model = defineModel()
const props = defineProps({
    customers: Object,
})

</script>

<template>
    <Label for="customer">Customers</Label>
    <Select id="customer" onValueChange="{setSelectedValue}" value="{selectedValue}" v-model="model">
        <SelectTrigger class="w-full bg-white dark:bg-dark-tertiary dark:text-dark-quatrenary">
            <SelectValue placeholder="Select customer" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectItem v-for="customer in props.customers" :key="customer.id" :value="customer.id">
                    {{ customer.name }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
