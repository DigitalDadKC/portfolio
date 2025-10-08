<script setup>
import Icon from '@/components/portfolio/Icon.vue';

const props = defineProps({
    front: {
        type: String,
    },
    back: {
        type: Array,
    },
    name: {
        type: String,
    },
    iconFront: {
        type: Boolean
    },
    iconBack: {
        type: Boolean
    },
})
</script>

<template>
    <div class="card border-8 rounded-2xl text-lg text-center bg-light-tertiary dark:bg-dark-tertiary font-bold transition-all duration-500 ease-in hover:scale-125 shadow-xl" style="transform-style: preserve-3d;">
        <div class="transition-all duration-500 ease-in" style="transform-style: preserve-3d">
            <div class="flex flex-col justify-around items-center h-60" style="transform-style: preserve-3d">
                <div class="text-accent rounded-sm " style="transform: translateZ(50px)">
                    <div class="flex items-center justify-center rounded-sm w-40 mt-12 mx-auto">
                        <Icon v-if="iconFront" :name="props.name" />
                    </div>
                </div>
                <h2 class="text-light-secondary" style="transform: translateZ(50px)">{{ props.front }}</h2>
            </div>
            <div class="absolute flex flex-col justify-around items-center top-10 right-0 left-0 bottom-0" style="transform: rotateY(0.5turn); transform-style: preserve-3d">
                <p class="text-light-primary dark:text-dark-quatrenary" v-for="(item, index) in props.back" :key="index" style="transform: translateZ(50px)">{{ item }}</p>
                <div class="text-accent rounded-sm " style="transform: translateZ(50px)">
                    <Icon v-if="iconBack" :name="props.name" />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .card:hover {
        transform: rotateY(0.5turn) scale(1.10);
    }
</style>
