<script setup lang="ts">
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select"

const model = defineModel()
const props = defineProps({
    customers: Object,
})

</script>

<template>
    <div>
        <Label for="customer">Customer</Label>
        <Select id="customer" onValueChange="{setSelectedValue}" value="{selectedValue}" v-model="model">
            <SelectTrigger class="w-full cursor-pointer bg-white">
                <SelectValue placeholder="Select customer" />
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectLabel>Customers</SelectLabel>
                    <SelectItem v-for="customer in props.customers" :key="customer.id" :value="customer.id">
                        {{ customer.name }}
                    </SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>
    </div>
</template>
