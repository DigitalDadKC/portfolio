<script setup>
import { watch } from 'vue'
import { useCurrencyInput } from 'vue-currency-input'

const props = defineProps({
    modelValue: [String, Number],
    label: String,
    id: String,
    options: {
      type: Object,
      default: {
        currency: 'USD'
      }
    },
});

watch(() => props.modelValue, (value) => {
    setValue(value)
})

const { inputRef, setValue } = useCurrencyInput(props.options)
</script>

<template>
    <div class="relative z-0 w-72">
        <input
            class="peer block appearance-none w-full text-light-quatrenary border-6 ps-2 pt-3 pb-0 border-rose-500 ring-1 ring-light-quatrenary focus:outline-1 focus:text-accent focus:ring-accent focus:ring-2 bg-light-secondary border-4"
            ref="inputRef"
        />
        <label :for="props.id"
        class="absolute opacity-50 peer-focus:opacity-100 text-xs text-light-quatrenary left-1 duration-200 transform -translate-y-3.5 scale-75 top-4 origin-[0] peer-focus:start-0.5 peer-focus:font-bold peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-3 peer-focus:scale-75 peer-focus:-translate-y-4 peer-focus:z-50 peer-focus:text-black rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto"
        >
            {{props.label}}
        </label>
    </div>
</template>
