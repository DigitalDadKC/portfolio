<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
    modelValue: {
        type: [String, Number]
    },
    label: {
        type: String
    },
    id: {
        type: String
    },
    column: {
        type: String
    },
    options: {
        type: Array
    },
    initial: {
        type: String
    },
    disabled: {
        type: Boolean
    }
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div class="relative z-10">
        <select
        class="peer block appearance-none w-full bg-light-primary text-light-quatrenary border-6 ps-2 pt-3 pb-0 border-rose-500 ring-1 ring-light-quatrenary focus:outline-1 focus:text-accent focus:ring-accent focus:ring-2"
        ref="input"
        @change="$emit('update:modelValue', $event.target.value)">
            <option :disabled="props.disabled" v-if="initial" class="bg-light-secondary text-black" value>{{ initial }}</option>
            <option v-for="(option, index) in props.options" :key="index" :value="option.id" :selected="modelValue === option.id" class="bg-light-secondary text-black font-bold">
                    {{ option[column] }}
            </option>
        </select>
        <label :for="props.id"
        class="absolute opacity-50 peer-focus:opacity-100 text-xs text-light-quatrenary left-1 duration-200 transform -translate-y-3.5 scale-75 top-4 origin-[0] peer-focus:start-0.5 peer-focus:font-bold peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-3 peer-focus:scale-75 peer-focus:-translate-y-4 peer-focus:z-50 peer-focus:text-black rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto"
        >
            {{props.label}}
        </label>
    </div>
</template>
