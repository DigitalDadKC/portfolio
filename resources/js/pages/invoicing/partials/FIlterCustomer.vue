<script setup>
import { onMounted, ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover"
import { Link } from '@inertiajs/vue3';
import { ChevronDown } from 'lucide-vue-next';

const props = defineProps({
    customers: Object,
    selectedCustomers: Array
})

const emit = defineEmits(['updateArray'])

const selectAll = ref(true)
const customer_array = ref(props.selectedCustomers)

const update = () => {
    emit('updateArray', customer_array.value)
}

const selectAllCustomers = () => {
    customer_array.value = (selectAll.value) ? props.customers.map(customer => customer.id) : []
    update()
}

onMounted(() => {
    customer_array.value = props.customers.map(customer => customer.id)
})

</script>

<template>

    <Popover>
        <PopoverTrigger as-child>
            <Button variant="outline" class="cursor-pointer bg-light-primary">
                Filter Customer
                <ChevronDown></ChevronDown>
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-80 h-96 overflow-auto" align="start">
            <ul class="p-3 space-y-3 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownCheckboxButton">
                <li>
                    <div class="flex items-center">
                        <input id="select-all" type="checkbox"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                            v-model="selectAll" :checked="props.customers.length === customer_array.length"
                            @update:model-value="selectAllCustomers()">
                        <label for="select-all" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Select
                            All</label>
                    </div>
                </li>
                <li v-for="(customer, index) in props.customers" :key="index">
                    <div class="flex items-center">
                        <input :id="`customer-${index}`" type="checkbox" :value="customer.id"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                            v-model="customer_array" @update:model-value="update()">
                        <label :for="`customer-${index}`"
                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ customer.name
                            }}</label>
                    </div>
                </li>
            </ul>
        </PopoverContent>
    </Popover>
</template>
