<script setup>
const props = defineProps({
    features: Object,
})
</script>

<template>
    <section id="features" class="section grid place-items-center px-4 bg-light-tertiary dark:bg-dark-quatrenary">
        <h2 class="section-title text-center dark:text-dark-secondary">Features</h2>
        <div class="grid items-center xl:gap-x-32 grid-cols-2 sm:grid-cols-3 md:grid-cols-4 text-accent lg:text-xl whitespace-nowrap">
            <div class="flex items-center" v-for="(feature, index) in props.features" :key="index">
                <v-icon size="48">mdi-check</v-icon>
                <p class="truncate whitespace-nowrap">{{ feature.name }}</p>
            </div>
        </div>
    </section>
</template>
