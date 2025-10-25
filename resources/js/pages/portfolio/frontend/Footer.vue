<script setup lang="ts">
import ApplicationLogo from '@/components/ApplicationLogo.vue';
</script>

<template>
    <footer class="bg-light-tertiary dark:bg-dark-quatrenary py-12">
        <div class="container mx-auto" v-motion :initial="{opacity: 0, y:100}" :visibleOnce="{opacity: 1, y: 0}">
            <div class="flex flex-col md:flex-row space-y-6 lg:space-y-0 items-center md:justify-end">
                <div class="flex items-center justify-center md:mr-10">
                <ApplicationLogo :scrollbackground="true" />

                </div>
                <p class="text-accent font-bold text-[15px]">
                    &copy; 2025 digitaldad, LLC<br /> All rights reserved
                </p>
            </div>
        </div>
    </footer>
</template>v
