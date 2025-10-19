<script setup lang="ts">
import { Label } from "@/components/ui/label"
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select"
import { cn } from "@/lib/utils"
import { Check } from "lucide-vue-next"

const model = defineModel()
const props = defineProps({
    skills: Object,
})

</script>

<template>
    <div>
        <Label for="skill" class="text-right">Skill</Label>
        <Select id="skill" onValueChange="{setSelectedValue}" value="{selectedValue}" multiple v-model="model">
            <SelectTrigger
                class="w-full bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/5 cursor-pointer">
                <!-- <SelectValue placeholder="Select a skill" /> -->
                {{model.map(skill => skill.name).join(', ')}}
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectLabel>Categories</SelectLabel>
                    <SelectItem v-for="skill in props.skills" :key="skill.id" :value="skill.id" :data-selected="true"
                    @select.prevent="(e) => {
                        let skillToAdd = props.skills.find(skill => skill.id === e.detail.value)
                        if(!model.map(skill => skill.id).includes(skillToAdd.id)) {
                            model.push(skillToAdd)
                        } else {
                            model.splice(model.map(skill => skill.id).indexOf(skillToAdd.id), 1)
                        }
                    }"
                    >
                        {{ skill.name }}
                        <span class="absolute right-2 flex size-3.5 items-center justify-center">
                            <Check :class="cn('ml-auto h-20 w-20')" v-if="model.map(item => item.id).includes(skill.id)" />
                        </span>
                    </SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>
    </div>
</template>
