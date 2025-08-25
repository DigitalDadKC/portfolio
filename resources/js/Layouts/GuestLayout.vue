<script setup>
    import { Head } from '@inertiajs/vue3';
    import NavLink from '@/Components/NavLink.vue';
    import HomeButton from '@/Components/HomeButton.vue';
    import { useStorage } from "@vueuse/core";

    const showSidebar = useStorage('my-flag', true)

    defineProps({
        title: String,
    })
</script>

<template>
    <v-app class="flex min-h-screen">
        <v-app-bar class="">
            <slot name="title" />
            <v-app-bar-title>{{ title }}</v-app-bar-title>
            <template v-slot:prepend>
                <v-app-bar-nav-icon @click.stop="showSidebar = !showSidebar" class="cursor-pointer bg-blue text-blue"></v-app-bar-nav-icon>
                <HomeButton></HomeButton>
            </template>

            <slot name="links" />
        </v-app-bar>

        <v-navigation-drawer v-model="showSidebar" temporary class="flex flex-wrap p-4" elevation="2" width="300">
            <v-card>
                <slot name="sidebar"></slot>
            </v-card>
        </v-navigation-drawer>

        <v-main class="text-xs h-full">
            <div class="p-2 h-full">
                <slot />
            </div>
        </v-main>
    </v-app>
</template>
