<script setup>
import { watch, ref, onMounted } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    name: {
        type: String,
        required: true
    },
    list: {
        type: Object,
        required: true
    },
    category: {
        type: String
    },
    items: {
        type: Object,
    }
})

const selectAllCB = ref(true)
const items = ref({})
items.value = props.items
const filterName = props.name + '-filter'

const emit = defineEmits(['update:list']);

watch(() => props.list, () => {
    selectAllCB.value = props.list.every(item => item.checked);
    items.value.forEach(item => {
        item.filters[filterName] = props.list.filter(unit => unit.checked).map(unit => unit.Name).includes(item[props.category])
    })
    emit('update:list', items.value)
}, {deep: true})

const filter = ((e) => {
    let category = props.list.find(item => props.name + '-' + item.id == e)
    category.checked = !category.checked
})

const selectAll = (() => {
    (props.list.every(category => category.checked)) ? props.list.map(category => category.checked = false) : props.list.map(category => category.checked = true)
})

onMounted(() => {
    props.list.forEach(list => {
        list.checked = true;
        list.category = props.name;
        list.count = items.value.filter(item => list.Name == item[props.category]).length
    })
    items.value.forEach(item => {
        item.filters[filterName] = true
    })
})

</script>

<template>
    <div class="rounded-md w-full rounded-md truncate">
        <InputLabel :for="props.name" :value="props.name" class="text-gray-900 text-xl text-light-primary" />
        <ul class="text-sm bg-white rounded-md p-2">
            <li class="flex items-start justify-start">
                <input :id="`${props.name}-select-all`" type="checkbox" @click="selectAll(props.name)" class="bg-gray-100 border-gray-300 rounded-md text-sm mr-2" :checked="selectAllCB" v-model="selectAllCB">
                <label :for="`${props.name}-select-all`" class="text-start">SELECT ALL</label>
            </li>
            <li v-for="(item, index) in props.list" :key="index" class="flex items-start justify-start">
                <input :id="`${props.name}-filter_option_${index}`" type="checkbox" :value="props.name + '-' + item.id" v-model="item.checked" :checked="item.checked" class="bg-gray-100 border-gray-300 rounded text-sm mr-2" @input="filter($event.target.value)"  >
                <label :for="`${props.name}-filter_option_${index}`" class="text-start mr-2">{{ item.Name }}</label>
                <label>({{ item.count }})</label>
            </li>
        </ul>
    </div>
</template>