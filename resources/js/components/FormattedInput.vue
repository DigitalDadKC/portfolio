<script setup>
import { ref, computed, watch, useId } from 'vue'

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
        default: '24'
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
    return (props.type === 'percent') ? rawValue.value : rawValue.value
  }
  return formatWithCommas(rawValue.value)
})

function onInput(value) {
    if(props.disabled) {return modelValue = rawValue.value}
    if(props.type === 'currency') {
        const numeric = parseFloat(value.replace(/,/g, ''))
        rawValue.value = isNaN(numeric) ? '' : numeric
    } else if(props.type === 'number') {
        rawValue.value = parseFloat(value)
    } else {
        rawValue.value = value
    }
    modelValue = rawValue.value
}

function onFocus(e) {
    isFocused.value = true
}

function onBlur() {
    isFocused.value = false
    emit('blur', rawValue.value)
}

function formatWithCommas(value) {
  if (value === null || value === '') return ''
  switch (true) {
    case props.type === 'text':
        return value?.toString()
    case props.type === 'number':
        return Intl.NumberFormat('en-US').format(rawValue.value)
    case props.type === 'currency':
        return Number(value).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
            style: 'currency',
            currency: 'USD'
        })
    case props.type === 'percent':
        return Number(value/100).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 4,
            style: 'percent'
        })
    }
}
</script>

<template>
    <div class="relative z-0" :class="[widthClass]">
        <input
            :value="displayValue"
            :name="`${props.label}-${id}`"
            type="text"
            class="file:text-foreground placeholder:text-muted-foreground selection:bg-light-quatrenary selection:text-primary-foreground border-input flex h-9 w-full min-w-0 rounded-md border bg-white dark:bg-dark-primary px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:cursor-not-allowed disabled:opacity-60 disabled:bg-light-secondary disabled:dark:bg-dark-quatrenary disabled:text-red-500 md:text-sm"
            :class="[widthClass]"
            placeholder=" "
            :disabled="props.disabled"
            @input="onInput($event.target.value)"
            @focus="onFocus($event)"
            @blur="onBlur()"
            @click="$event.target.select()"
        />
    </div>
</template>
