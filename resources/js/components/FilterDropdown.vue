<script setup>

const props = defineProps({
    options: {
        type: Array
    },
    column: {
        type: String,
    },
    value: {
        default: 'id',
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
    <select class="rounded-md border border-black w-full" ref="input" @change="toggle($event)">
        <option :disabled="props.disabled" v-if="initial" value>{{ initial }}</option>
        <option v-for="(option, index) in props.options" :key="index" :value="option[props.value]" :selected="modelValue === option.id">
                {{ option[column] }}
        </option>
    </select>
</template>
