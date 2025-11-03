<script setup lang="ts">
import { Check, ChevronsUpDown, Search } from "lucide-vue-next"
import { cn } from "@/lib/utils"
import { Button } from "@/components/ui/button"
import { Combobox, ComboboxAnchor, ComboboxEmpty, ComboboxGroup, ComboboxInput, ComboboxItem, ComboboxItemIndicator, ComboboxList, ComboboxTrigger } from "@/components/ui/combobox"
import { computed } from "vue"
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select"

const model = defineModel()
const props = defineProps({
    states: Object,
})

const state = computed(() => {
    return props.states.find(state => state.id == model.value)
})

</script>

<template>
    <Select v-model="model">
        <SelectTrigger class="w-full bg-white cursor-pointer hover:bg-accent dark:bg-dark-primary">
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
