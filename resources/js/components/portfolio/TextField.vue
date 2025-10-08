<script setup>
import { ref, computed, watch, useId } from 'vue'
import { useFormatCurrency } from '@/Composables/useFormatCurrency';

const { formatWithCommas } = useFormatCurrency();
const props = defineProps({
    type: {
        type: String,
        default: 'text'
    },
    label: {
        type: [String, Number],
        default: ''
    },
    disabled: {
        type: Boolean,
        default: false
    },
    width: {
        type: String,
        default: '36'
    },
    digits: {
        type: Number,
        default: 2,
    }
})

const id = useId();

let modelValue = defineModel();
const isFocused = ref(false)
const rawValue = ref(modelValue)

const emit = defineEmits(['blur'])

const widthClass = computed(() => {
    return {
        '12': 'w-12',
        '16': 'w-16',
        '24': 'w-24',
        '36': 'w-36',
        '48': 'w-48',
        '96': 'w-96',
        'full': 'w-full'
    }[props.width.toString()];
});

watch(() => modelValue, (newVal) => {
  rawValue.value = newVal
})

const displayValue = computed(() => {
  if (isFocused.value) {
    return (props.type === 'percent') ? rawValue.value : rawValue.value?.toString()
  }
  return formatWithCommas(rawValue.value, props.type)
})

function onInput(value) {
    if(props.disabled) {return modelValue = rawValue.value}
    const numeric = parseFloat(value.replace(/,/g, ''))
    rawValue.value = isNaN(numeric) ? '' : numeric
    modelValue = rawValue.value
}

function onFocus(e) {
    isFocused.value = true
}

function onBlur() {
    isFocused.value = false
    emit('blur', rawValue.value)
}
</script>

<template>
    <div class="relative z-0" :class="[widthClass]">
        <input
            :value="displayValue"
            :id="`${props.label}-${id}`"
            :name="`${props.label}-${id}`"
            type="text"
            class="block peer px-2.5 pb-1.5 pt-2.5 w-full text-sm text-light-quatrenary dark:text-dark-quatrenary bg-[#FFD966] rounded-lg border-1 border-light-quatrenary dark:border-dark-quatrenary appearance-none dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600"
            :class="[{'bg-light-quatrenary dark:bg-dark-quatrenary text-light-secondary dark:text-dark-secondary border-light-tertiary dark:border-dark-tertiary': props.disabled}, widthClass]"
            placeholder=" "
            :disabled="props.disabled"
            @input="onInput($event.target.value)"
            @focus="onFocus($event)"
            @blur="onBlur()"
            @click="$event.target.select()"
        />
        <label
            class="absolute text-xs text-light-quatrenary dark:text-dark-quatrenary duration-300 transform -translate-y-4 scale-75 top-3.5 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-3.5 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
            :for="`${props.label}-${id}`"
            :class="{'cursor-default text-light-secondary dark:text-dark-secondary': props.disabled}"
        >
        {{ props.label }}
        </label>
    </div>
</template>
