<script setup>
const props = defineProps({
    label: {
        type: String,
    },
    for: {
        type: String
    }
});
</script>

<template>
    <label :for="props.for" class=" text-black duration-300 transform -translate-y-6 scale-75 origin-[0] peer-focus:start-0 peer-focus:text-accent peer-focus:font-bold peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto"
    >
        {{props.label}}
    </label>
</template>
