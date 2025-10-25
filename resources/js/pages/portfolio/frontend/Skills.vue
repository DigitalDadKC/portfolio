<script setup>
defineProps({
    skills: Object
})
</script>

<template>
    <section class="bg-light-tertiary dark:bg-dark-quatrenary py-16">
        <div class="container mx-auto">
            <div class="grid grid-cols-8 md:grid-flow-col">
                <div class="flex items-center justify-center p-1" v-for="skill in skills" :key="skill.id">
                    <img :src="skill.image" :alt="skill.name" class="h-12 lg:h-20" />
                </div>
            </div>
        </div>
    </section>
</template>
