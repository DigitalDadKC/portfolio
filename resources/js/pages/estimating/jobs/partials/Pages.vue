<script setup lang="ts">
import { Label } from 'reka-ui';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select"

const model = defineModel()

</script>

<template>
    <Select id="pages" v-model.number="model">
        <SelectTrigger class="w-full max-w-[80px] bg-white dark:bg-dark-tertiary dark:text-dark-quatrenary">
            {{ model }}
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
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
