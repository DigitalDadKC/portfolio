<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
    modelValue: String,
    disabled: Boolean,
    label: String,
    id: String,
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
    <div class="relative z-0 w-72">
        <input :id="`${props.id}`"
            class="peer block appearance-none w-full bg-light-primary text-light-quatrenary border-6 ps-2 pt-3 pb-0 border-rose-500 ring-1 ring-light-quatrenary focus:outline-1 focus:text-accent focus:ring-accent focus:ring-2"
            :class="{'bg-light-secondary border-4': props.disabled}"
            placeholder=""
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :disabled="props.disabled"
            ref="input"
        />
        <label :for="props.id"
        class="absolute opacity-50 peer-focus:opacity-100 text-xs text-light-quatrenary left-1 duration-200 transform -translate-y-3.5 scale-75 top-4 origin-[0] peer-focus:start-0.5 peer-focus:font-bold peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-3 peer-focus:scale-75 peer-focus:-translate-y-4 peer-focus:z-50 peer-focus:text-black rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto"
        >
            {{props.label}}
        </label>
    </div>
</template>
