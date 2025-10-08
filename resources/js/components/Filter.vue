<script setup>
import { ref, watch } from 'vue';
    
const show = ref(false);
const futureCheckedValue = ref(true)

const props = defineProps({
    list: {
        type: Array,
        required: true
    },
    items: {
        type: Array,
        required: true
    },
    name: {
        type: String,
        required: true
    }
})

watch(() => props.items, (test) => {
    return futureCheckedValue.value = (props.list.length === test.length) ? true : false
})

const emit = defineEmits(['filter'])

const filter = ((e) => {
    emit('filter', e.target.value);
    return futureCheckedValue.value = (props.items.length == props.list.length) ? true : false
})

const selectAll = (() => {
    if (props.list.length === props.items.length) {
        futureCheckedValue.value = false
        props.items.splice(0)
    } else {
        props.list.forEach(item => (!props.items.includes(item) ? props.items.push(item) : ''))
    }
})

</script>

<template>
    <div @mouseover="show = true" @mouseleave="show = false" class="relative flex items-start w-full px-4">
        <button class="w-full flex items-center justify-center px-4 uppercase bg-gray-200 rounded dark:hover:bg-gray-100">
            {{ props.name }}
            <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        </button>
        <div v-if="show" class="absolute top-6 left-4 z-10 w-48 p-3 bg-white rounded-lg shadow">
            <h6 class="mb-3 text-sm font-medium text-gray-900">Status</h6>
            <ul class="space-y-1 text-sm">
                <li class="flex items-start justify-start">
                    <input id="select-all" type="checkbox" @click="selectAll" class="bg-gray-100 border-gray-300 rounded text-sm mr-2" :checked="futureCheckedValue" v-model="futureCheckedValue">
                    <label for="select-all" class="text-start">SELECT ALL</label>
                </li>
                <li v-for="(item, index) in props.list" :key="index" class="flex items-start justify-start">
                    <input :id="`${props.name}-filter_option_${index}`" type="checkbox" @change="filter" :value="item" class="bg-gray-100 border-gray-300 rounded text-sm mr-2" :checked="props.items.includes(item)">
                    <label :for="`${props.name}-filter_option_${index}`" class="text-start">{{ item }}</label>
                </li>
            </ul>
        </div>
    </div>
</template>