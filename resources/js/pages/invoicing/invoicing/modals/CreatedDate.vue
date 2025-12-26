<script setup lang="ts">
import { Label } from 'reka-ui';
import { Button } from '@/components/ui/button';
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover"
import { Calendar } from "@/components/ui/calendar"
import { useDateFormat } from '@vueuse/core';
import { cn } from "@/lib/utils";
import { CalendarIcon } from 'lucide-vue-next';

const model = defineModel()
const props = defineProps({
    customers: Object,
})

</script>

<template>
    <div>
        <Label for="rate">Date Created</Label>
        <Popover>
            <PopoverTrigger as-child>
                <Button variant="outline" class="cursor-pointer" :class="cn(
                    'w-[280px] justify-start text-left font-normal',
                    !model && 'text-muted-foreground',
                )">
                    <CalendarIcon class="mr-2 h-4 w-4" />
                    {{ model ? useDateFormat(model, 'MMM DD, YYYY') :
                        "Pick a date" }}
                </Button>
            </PopoverTrigger>
            <PopoverContent class="w-auto p-0">
                <Calendar v-model:selected="model" initial-focus
                    @update:model-value="(v) => model = v?.toString()" />
            </PopoverContent>
        </Popover>
    </div>
</template>
