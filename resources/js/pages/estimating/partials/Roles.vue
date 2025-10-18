<script setup lang="ts">
import { Label } from "@/components/ui/label"
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select"
import { cn } from "@/lib/utils"
import { Check } from "lucide-vue-next"

const model = defineModel()
const props = defineProps({
    user: Object,
    roles: Object,
})

</script>

<template>
    <div>
        <Label for="role" class="text-right">Role</Label>
        <Select id="role" onValueChange="{setSelectedValue}" value="{selectedValue}" multiple v-model="model">
            <SelectTrigger
                class="w-full bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/5 cursor-pointer">
                {{model.map(role => role.name).join(', ')}}
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectLabel>Categories</SelectLabel>
                    <SelectItem v-for="role in props.roles" :key="role.id" :value="role.id" :data-selected="true"
                        @select.prevent="(e) => {
                        let roleToAdd = props.roles.find(role => role.id === e.detail.value)
                        if(!model.map(role => role.id).includes(roleToAdd.id)) {
                            model.push(roleToAdd)
                        } else {
                            model.splice(model.indexOf(roleToAdd.id, 1))
                        }
                    }"
                    >
                        {{ role.name }} - {{ role.id }}
                        <span class="absolute right-2 flex size-3.5 items-center justify-center">
                            <Check :class="cn('ml-auto h-20 w-20')" v-if="model.map(item => item.id).includes(role.id)" />
                        </span>
                    </SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>
    </div>
</template>
