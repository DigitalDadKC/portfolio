<script setup lang="ts">

const props = defineProps({
    scrollBackground: {
        type: Boolean,
        default: false,
    },
})
</script>

<template>
    <img
        src="/img/dad.svg"
        alt="Hero"
        class="rounded-lg h-12"
        :class="{'scale-80 transition': scrollBackground, 'scale-110 transition':!scrollBackground}"
        v-motion
        :initial="{ x: 0, y: 0 }"
        :enter="{ x: Math.floor(Math.random()*1) +1, y: Math.floor(Math.random()*1) +1, transition: { repeat: Infinity, repeatType: 'loop', repeatDelay: 100} }"
    />
</template>
