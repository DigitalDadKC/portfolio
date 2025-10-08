<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="flex items-center text-black">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
