<script setup lang="ts">
import { Label } from "@/components/ui/label"
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select"

const model = defineModel()
const props = defineProps({
    skills: Object,
})

</script>

<template>
    <div>
        <Label for="skill" class="text-right">Skill</Label>
        {{ model }}
        <Select id="skill" onValueChange="{setSelectedValue}" value="{selectedValue}" multiple v-model="model">
            <SelectTrigger class="w-full bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/5 cursor-pointer">
                <!-- <SelectValue placeholder="Select a skill" /> -->
                {{ model.map(skill => skill.name).toString() }}
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectLabel>Categories</SelectLabel>
                    <SelectItem v-for="skill in props.skills" :key="skill.id" :value="skill.id" :data-selected="true">
                        {{ skill.name }}
                    </SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>
        {{ props.skills }}
    </div>
</template>
