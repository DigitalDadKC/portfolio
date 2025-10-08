<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center justify-center px-4 py-2 bg-light-quatrenary dark:bg-dark-quatrenary border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-light-primary dark:text-dark-primary uppercase tracking-widest hover:bg-light-tertiary dark:hover:bg-dark-tertiary focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 shadow-md/50 cursor-pointer"
    >
        <slot />
    </button>
</template>
