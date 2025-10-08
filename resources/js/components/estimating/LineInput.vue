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
    <div class="relative w-72">
        <input :id="`${props.id}`"
            class="peer block pb-2.5 pt-4 px-2.5 w-full text-light-quatrenary bg-light-primary border-1 border-b-2 border-light-quatrenary appearance-none font-bold focus:outline-none focus:text-accent focus:ring-0 focus:border-accent"
            placeholder=""
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :disabled="props.disabled"
            ref="input"
        />
        <label :for="props.id" class="absolute text-sm text-black duration-300 transform -translate-y-4 scale-75 top-3 z-10 origin-[0] cursor-text bg-white py-1 px-3 rounded-md peer-focus:start-0 peer-focus:text-accent peer-focus:font-bold peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto"
        >
            {{props.label}}
        </label>
    </div>
</template>
