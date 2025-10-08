<script setup>
const props = defineProps({
    // icon: String,
    // title: String,
    // detail: String,
    service_items: Array,
})

</script>

<template>
    <div class="bg-light-tertiary dark:bg-dark-tertiary rounded-lg p-4 shadow-xl/20 ring-2 ring-light-primary transition duration-200 hover:scale-105" v-motion :initial="{ opacity: 0, y:200}" :visibleOnce="{opacity: 1, y:0}" :delay="200" v-for="service in props.service_items" :key="service.title">
        <div class="flex justify-center items-center mb-4 w-10 h-10 rounded-full bg-primary-100 lg:h-12 lg:w-12 dark:bg-primary-900">
            <component :is="service.icon" />
        </div>
        <h3 class="mb-2 text-xl font-bold dark:text-white">{{ service.title }}</h3>
        <p class="text-light-quatrenary dark:text-dark-quatrenary">{{ service.detail }}</p>
    </div>
</template>
