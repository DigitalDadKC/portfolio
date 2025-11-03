<script setup lang="ts">
import { computed } from "vue"
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select"

const model = defineModel()
const props = defineProps({
    unit_of_measurements: Object,
})

const uoms = computed(() => {
    return [{id: null, UOM: 'Select'}, ...props.unit_of_measurements]
})

</script>

<template>
    <Select id="uom" onValueChange="{setSelectedValue}" value="{selectedValue}" v-model="model">
        <SelectTrigger  class="w-36 bg-light-primary hover:bg-light-secondary dark:bg-dark-primary">
            <SelectValue placeholder="Select UOM" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup class="bg-light-tertiary dark:bg-dark-quatrenary ">
                <SelectItem v-for="uom in uoms" :key="uom.id" :value="uom.id" class="dark:text-dark-tertiary">
                    {{ uom.UOM }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
