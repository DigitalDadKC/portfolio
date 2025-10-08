<script setup>
import { onMounted } from 'vue';

const props = defineProps({
    options: {
        type: Array
    },
    column: {
        type: String,
    },
    initial: {
        type: String
    },
    disabled: {
        type: Boolean
    },
    modelValue: {
        type: [String, Number]
    }
});

const emit = defineEmits(['update:modelValue']);

const toggle = (event) => {
    emit('update:modelValue', event.target.value)
}
</script>

<template>
    <select class="rounded-md border border-black" ref="input" @change="toggle($event)">
        <option :disabled="props.disabled" v-if="initial" value>{{ initial }}</option>
        <option v-for="(option, index) in props.options" :key="index" :value="option.id" :selected="modelValue === option.id">
                {{ option[column] }}
        </option>
    </select>
</template>
