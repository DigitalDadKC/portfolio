<script setup lang="ts">
import { ref, computed } from "vue";
import { Check, ChevronsUpDown, X } from "lucide-vue-next";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover";
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from "@/components/ui/command";
import { cn } from "@/lib/utils";

const props = defineProps({
    options: Array,
    placeholder: String,
    property: {
        type: String,
        default: 'name',
        required: false,
    }
})

const modelValue = defineModel<string[]>({ default: [] });

const open = ref(false);

function toggleOption(value: object) {
    console.log(value)
    if (modelValue.value.map((v) => v.id).includes(value.id)) {
        modelValue.value = modelValue.value.filter((v) => v.id !== value.id);
    } else {
        modelValue.value = [...modelValue.value, value];
    }
}

function removeOption(value: string, e: Event) {
    e.stopPropagation();
    modelValue.value = modelValue.value.filter((v) => v.id !== value.id);
}

const selectedLabels = computed(() =>
    props.options.filter((o) => modelValue.value.map((d) => d.id).includes(o.id)),
);
</script>

<template>
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <Button
                variant="outline"
                role="combobox"
                :aria-expanded="open"
                class="w-full justify-between h-auto min-h-10"
            >
                <div class="flex flex-wrap gap-1">
                    <template v-if="selectedLabels.length">
                        <Badge
                            v-for="option in selectedLabels"
                            :key="option"
                            variant="secondary"
                            class="mr-1"
                        >
                            {{ option[props.property] }}
                            <span
                                class="ml-1 rounded-full outline-none hover:bg-muted-foreground/20"
                                @click="(e) => removeOption(option, e)"
                            >
                                <X class="h-3 w-3" />
                            </span>
                        </Badge>
                    </template>
                    <span v-else class="text-muted-foreground font-normal">
                        {{ placeholder ?? "Select options..." }}
                    </span>
                </div>
                <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-[--reka-popover-trigger-width] p-2">
            <Command>
                <CommandInput placeholder="Search..." />
                <CommandList>
                    <CommandEmpty>No results found.</CommandEmpty>
                    <CommandGroup>
                        <CommandItem
                            v-for="option in options"
                            :key="option.id"
                            :value="option.id"
                            @select.prevent="toggleOption(option)"
                        >
                            <Check
                                :class="
                                    cn(
                                        'mr-2 h-4 w-4',
                                        modelValue.map((s) => s.id).includes(option.id)
                                            ? 'opacity-100'
                                            : 'opacity-0',
                                    )
                                "
                            />
                            {{ option[props.property] }}
                        </CommandItem>
                    </CommandGroup>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>
