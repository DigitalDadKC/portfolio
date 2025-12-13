<script setup lang="ts">
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select"

const model = defineModel()
const props = defineProps({
    services: Object,
})

</script>

<template>
    <Select id="client" onValueChange="{setSelectedValue}" value="{selectedValue}" v-model="model" multiple>
        <SelectTrigger class="w-full cursor-pointer bg-white">
            <SelectValue placeholder="Select Services" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectLabel>Services</SelectLabel>
                <SelectItem v-for="service in props.services" :key="service.id" :value="service.id">
                    {{ service.name }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
