<script setup lang="ts">
import { Label } from 'reka-ui';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select"

const model = defineModel()
const props = defineProps({
    types: Object,
})

</script>

<template>
    <Select id="type" onValueChange="{setSelectedValue}" value="{selectedValue}" v-model="model">
        <SelectTrigger class="w-36 cursor-pointer bg-white hover:bg-accent dark:bg-dark-primary">
            <SelectValue placeholder="Select Type" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup class=" dark:bg-dark-quatrenary ">
                <SelectItem v-for="type in props.types" :key="type" :value="type" class="dark:text-dark-tertiary">
                    {{ type }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
