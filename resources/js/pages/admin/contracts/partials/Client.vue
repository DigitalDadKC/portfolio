<script setup lang="ts">
import { Label } from 'reka-ui';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select"

const model = defineModel()
const props = defineProps({
    clients: Object,
})

</script>

<template>
    <div>
        <Label for="client">client</Label>
        <Select id="client" onValueChange="{setSelectedValue}" value="{selectedValue}" v-model="model">
            <SelectTrigger class="w-full cursor-pointer bg-white">
                <SelectValue placeholder="Select Client" />
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectLabel>Clients</SelectLabel>
                    <SelectItem v-for="client in props.clients" :key="client.id" :value="client.id">
                        {{ client.company }}
                    </SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>
    </div>
</template>
