<script setup lang="ts">
import { ref, watch } from "vue"
import { Button } from "@/components/ui/button"
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuCheckboxItem } from "@/components/ui/dropdown-menu"

const props = defineProps({
    states: Object,
})
const emit = defineEmits(['update:modelValue'])

const selectedStates = ref([])

watch(selectedStates, (val) => {
    emit('update:modelValue', val)
}, {
    deep: true,
})



</script>

<template>
    <DropdownMenu v-model="selectedStates">
        <DropdownMenuTrigger as-child class="w-full bg-white cursor-pointer">
            <Button variant="outline">
                Filter States
                <span v-if="selectedStates.length" class="ml-2 text-xs">
                    ({{ selectedStates.length }})
                </span>
            </Button>
        </DropdownMenuTrigger>

        <DropdownMenuContent class="w-56 h-200 overflow-auto flex flex-col gap-1">
            <DropdownMenuLabel>Select states</DropdownMenuLabel>
            <DropdownMenuSeparator />

            <DropdownMenuCheckboxItem
                v-for="state in props.states"
                :key="state.id"
                @select.prevent
                :class="{'bg-light-quatrenary':selectedStates.includes(state.id)}"
                @update:model-value="() => {
                    const index = selectedStates?.indexOf(state.id)
                    if (index > -1) {
                        selectedStates.splice(index, 1)
                    } else {
                        selectedStates.push(state.id)
                    }
                }"
            >
                {{ state.state }}
            </DropdownMenuCheckboxItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
