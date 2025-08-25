<script setup>

</script>

<template>
    <Link :href="route('home')" prefetch>
        <div class="transition duration-200 hover:scale-105 rounded-md px-2 bg-light-secondary hover:bg-light-primary">
            <v-icon class="text-accent">mdi-arrow-left-drop-circle</v-icon>
            <button class="px-2 py-2 rounded-md text-light-quatrenary hover:text-light-tertiary cursor-pointer transition duration-200 hover:scale-105">Back to Digital Dad</button>
        </div>
    </Link>
</template>
