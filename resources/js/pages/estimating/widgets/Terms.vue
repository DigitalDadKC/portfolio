<script setup>
const model = defineModel()

</script>

<template>
    <div class="flex flex-col gap-2 bg-light-primary dark:bg-dark-primary h-full p-2">
        <div class="text-xl font-bold text-center uppercase">Terms & Conditions</div>
        <v-textarea v-model="model" density="compact" rows="12" hide-details></v-textarea>
        <div v-if="$page.props.errors.terms" class="text-red-400">{{ $page.props.errors.terms }}</div>
    </div>
</template>