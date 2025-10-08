<script setup>
const props = defineProps({
    title: String
})
</script>

<template>
    <div class="text-4xl text-light-primary flex justify-center my-3">
        {{ title }}
    </div>
</template>