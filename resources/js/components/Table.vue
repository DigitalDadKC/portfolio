<script setup>

</script>

<template>
    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end m-2 p-2">
                <slot name="add"></slot>
            </div>
            <div class="relative overflow-x-auto">
                <table class="table-auto w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-200 uppercase bg-gray-500 dark:bg-gray-700 dark:text-gray-400">
                        <slot name="header-cells"></slot>
                    </thead>
                    <tbody>
                        <slot name="data"></slot>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
