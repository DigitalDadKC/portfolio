<script setup>
const props = defineProps({
    features: Object,
})
</script>

<template>
    <section id="features" class="section grid place-items-center bg-light-tertiary dark:bg-dark-quatrenary py-20">
        <h2 class="section-title text-center dark:text-dark-secondary">Features</h2>
        <div class="grid items-center xl:gap-x-32 xl:gap-y-8 grid-cols-2 sm:grid-cols-3 md:grid-cols-4 text-accent font-semibold lg:text-xl whitespace-nowrap">
            <div class="flex items-center gap-2" v-for="(feature, index) in props.features" :key="index">
                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <p class="truncate whitespace-nowrap">{{ feature.name }}</p>
            </div>
        </div>
    </section>
</template>
