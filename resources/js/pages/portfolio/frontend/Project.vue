<script setup>
defineProps({
    project: Object
})
</script>

<template>
    <a :href="project.url" target="_blank" class="group flex flex-col items-center text-center cursor-pointer">
        <div class="mb-6" v-motion :initial="{ opacity: 0, y:100}" :visibleOnce="{opacity: 1, y:0}">
            <img :src="project.image" :alt="project.name" class="rounded-2xl h-40 lg:h-52" v-motion>
        </div>
        <span class="group-hover:text-light-quatrenary text-2xl font-semibold capitalize mb-3">
            {{ project.name }}
        </span>
        <div>
            <p class="text-sm">{{ project.description }}</p>
        </div>
    </a>
</template>
