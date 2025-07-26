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
    <v-app class="d-flex min-h-screen">
        <v-app-bar class="bg-grey-lighten-2">
            <slot name="title" />
            <v-app-bar-title>{{ title }}</v-app-bar-title>
            <template v-slot:prepend>
                <v-app-bar-nav-icon @click.stop="showSidebar = !showSidebar"></v-app-bar-nav-icon>
                <HomeButton></HomeButton>
            </template>

            <slot name="links" />
        </v-app-bar>

        <v-navigation-drawer v-model="showSidebar" temporary class="d-flex flex-wrap p-4 bg-grey" elevation="2" width="400">
            <v-card>
                <slot name="sidebar" />
            </v-card>
        </v-navigation-drawer>

        <v-main class="bg-grey-lighten-1 text-xs h-full">
            <div class="p-2 h-full">
                <slot />
            </div>
        </v-main>
    </v-app>
</template>
