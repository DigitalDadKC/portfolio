<script setup lang="ts">
import { Label } from 'reka-ui';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select"

const model = defineModel()

</script>

<template>
    <div>
        <Label for="customer">Pages</Label>
        <Select id="customer" onValueChange="{setSelectedValue}" value="{selectedValue}" v-model="model">
            <SelectTrigger>
                <SelectValue placeholder="Select pages" />
            </SelectTrigger>
            <SelectContent>
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
    </div>
</template>