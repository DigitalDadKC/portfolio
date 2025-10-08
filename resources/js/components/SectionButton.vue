<script setup>
const props = defineProps({
    title: ''
})
</script>

<template>
    <button class="px-4 py-2 rounded-md font-semibold bg-light-secondary dark:bg-dark-secondary hover:bg-light-tail-100 dark:hover:bg-dark-navy-100 w-36"><slot />
    </button>
</template>