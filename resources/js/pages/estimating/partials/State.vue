<script setup lang="ts">
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"

const model = defineModel()
const props = defineProps({
    states: Object,
})

</script>

<template>
    <Select id="state" onValueChange="{setSelectedValue}" value="{selectedValue}" v-model="model">
        <SelectTrigger class="w-full bg-white">
            <SelectValue placeholder="Select state" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectItem v-for="state in props.states" :key="state.id" :value="state.id">
                    {{ state.state }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
