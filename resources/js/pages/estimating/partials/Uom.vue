<script setup lang="ts">
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select"

const model = defineModel()
const props = defineProps({
    unit_of_measurements: Object,
})

</script>

<template>
    <Select id="uom" onValueChange="{setSelectedValue}" value="{selectedValue}" v-model="model">
        <SelectTrigger  class="w-36 bg-light-primary hover:bg-light-secondary dark:bg-dark-primary">
            <SelectValue placeholder="Select UOM" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup class="bg-light-tertiary dark:bg-dark-quatrenary ">
                <SelectItem v-for="uom in props.unit_of_measurements" :key="uom.id" :value="uom.id" class="dark:text-dark-tertiary">
                    {{ uom.UOM }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
