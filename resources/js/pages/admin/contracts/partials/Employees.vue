<script setup lang="ts">
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select"

const model = defineModel()
const props = defineProps({
    employees: Object,
})

</script>

<template>
    <Select id="client" onValueChange="{setSelectedValue}" value="{selectedValue}" v-model="model">
        <SelectTrigger class="w-full cursor-pointer bg-white">
            <SelectValue placeholder="Select Recipient" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectLabel>Employees</SelectLabel>
                <SelectItem v-for="employee in props.employees" :key="employee.id" :value="employee.id">
                    {{ employee.name }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
