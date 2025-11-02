<script setup lang="ts">
const model = defineModel()

</script>

<template>
    <div>
        <label class="inline-flex flex-col items-center justify-center cursor-pointer">
            <input type="checkbox" value="" class="sr-only peer" v-model="model">
            <span class="mb-1 text-sm font-medium text-gray-900 dark:text-gray-300">{{ (modelValue) ? 'Archived' : 'Open' }}</span>
            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-light-quatrenary dark:peer-focus:ring-dark-quatrenary rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-light-quatrenary dark:peer-checked:bg-dark-quatrenary"></div>
        </label>
    </div>
</template>
