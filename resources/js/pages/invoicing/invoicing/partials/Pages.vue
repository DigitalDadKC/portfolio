<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover"
import { ChevronDown } from 'lucide-vue-next';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select"

const model = defineModel()

</script>

<template>
    <Select id="customer" onValueChange="{setSelectedValue}" value="{selectedValue}" v-model="model" class="w-full">
        <SelectTrigger class="bg-white cursor-pointer hover:bg-accent">
                <SelectValue placeholder="Select pages" />
        </SelectTrigger>
        <SelectContent class="bg-light-secondary border-2 border-light-quatrenary">
            <SelectGroup>
                <SelectLabel></SelectLabel>
                <SelectItem value="10">
                    10
                </SelectItem>
                <SelectItem value="25">
                    25
                </SelectItem>
                <SelectItem value="50">
                    50
                </SelectItem>
                <SelectItem value="100">
                    100
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
