<script setup lang="ts">
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select"

const model = defineModel()
const props = defineProps({
    clients: Object,
})

</script>

<template>
    <div>
        <Select id="customer" onValueChange="{setSelectedValue}" value="{selectedValue}" v-model="model">
            <SelectTrigger class="w-full cursor-pointer bg-white dark:bg-dark-secondary dark:text-dark-quatrenary">
                <SelectValue placeholder="Select client" />
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectLabel>Customers</SelectLabel>
                    <SelectItem v-for="client in props.clients" :key="client.id" :value="client.id">
                        {{ client.company }}
                    </SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>
    </div>
</template>
