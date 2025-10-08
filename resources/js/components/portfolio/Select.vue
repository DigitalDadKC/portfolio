<script setup>
import { computed, watch, onMounted, onBeforeUnmount, ref, useId } from 'vue';

const props = defineProps({
    uoms: Object,
    label: String,
    disabled: Boolean,
    width: String,
    modelValue: {
        default: null,
    },
    contentClasses: {
        type: Array,
        default: () => ['py-1', 'bg-white dark:bg-gray-700'],
    },
});
const id = useId()
const dropdown = ref(null)

const emit = defineEmits(['update:modelValue'])

const widthClass = computed(() => {
    return {
        '12': 'w-12',
        '24': 'w-24',
        '36': 'w-36',
        '48': 'w-48',
        '96': 'w-96',
        'full': 'w-full'
    }[props.width.toString()];
});

const selectedOption = ref(null)
const mappedSelectedOption = computed(() => {
    return selectedOption.value?.name  || ''
})

const isDropdownVisible = ref(false)

const closeDropdown = (element) => {
    if(!dropdown.value.contains(element.target)) {
        isDropdownVisible.value = false
    }
}

watch(() => props.modelValue, (value) => {
    selectedOption.value = props.uoms.find(uom => uom.id == value?.id) || null
})

onMounted(() => {
    window.addEventListener('click', closeDropdown)
    selectedOption.value = props.modelValue
})
onBeforeUnmount(() => {
    window.removeEventListener('click', closeDropdown)
})

</script>

<template>
    <div class="relative" ref="dropdown">
        <select
            :id="`${props.label}-${id}`"
            :name="`${props.label}-${id}`"
            class="block px-2.5 pb-1.5 pt-2.5 text-sm text-light-quatrenary dark:text-dark-quatrenary bg-[#FFD966] rounded-lg border-1 border-light-secondary dark:border-dark-secondary appearance-none peer"
            :class="[{'bg-light-quatrenary dark:bg-dark-quatrenary text-light-secondary dark:text-dark-secondary': props.disabled}, widthClass]"
            placeholder=" "
            :disabled="props.disabled"
            :value="mappedSelectedOption"
        >
            <option >{{ mappedSelectedOption }}</option>
             </select>
        <label
            class="absolute text-xs text-light-quatrenary dark:text-dark-quatrenary duration-300 transform -translate-y-4 scale-75 top-3.5 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-3.5 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
            :for="`${props.label}-${id}`"
            :class="{' text-light-secondary dark:text-dark-secondary': props.disabled}"
        >
        {{ props.label }}
        </label>
    </div>
</template>
