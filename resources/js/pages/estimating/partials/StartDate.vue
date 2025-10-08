<script setup lang="ts">
import { Label } from "@/components/ui/label"
import { Button } from '@/components/ui/button';
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover"
import { CalendarIcon } from "lucide-vue-next";
import { Calendar } from "@/components/ui/calendar"
import { cn } from "@/lib/utils";
import { useDateFormat } from "@vueuse/core";

const model = defineModel()
const props = defineProps({
    disabled: Boolean,
})

</script>

<template>
    <div>
        <Label for="start_date">Start Date</Label>
        <Popover>
            <PopoverTrigger as-child>
                <Button variant="outline" id="start_date" :class="cn(
                    'w-full justify-start text-left font-normal bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50',
                    !model && 'text-muted-foreground',
                )">
                    <CalendarIcon class="mr-2 h-4 w-4" />
                    {{ model ? useDateFormat(model, 'M.D.YYYY') : "Pick a date" }}
                </Button>
            </PopoverTrigger>
            <PopoverContent class="w-auto p-0">
                <Calendar v-model:selected="model" initial-focus
                    @update:model-value="(v) => model = v?.toString()" />
            </PopoverContent>
        </Popover>
    </div>
</template>
