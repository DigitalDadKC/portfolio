<script setup lang="ts">
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select"

const model = defineModel()
const props = defineProps({
    states: Object,
})

</script>

<template>
    <Label for="state">State</Label>
    <Select id="state" onValueChange="{setSelectedValue}" value="{selectedValue}" v-model="model">
        <SelectTrigger class="w-full bg-white dark:bg-dark-tertiary dark:text-dark-quatrenary">
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
