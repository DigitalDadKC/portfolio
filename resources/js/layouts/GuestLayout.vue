<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import NavLink from '@/components/NavLink.vue';
import { Button } from '@/components/ui/button';
import { useStorage } from "@vueuse/core";
import { useDark, useToggle } from "@vueuse/core";
import { Lightbulb, Moon, Menu, Notebook, BookMarked, DollarSign } from 'lucide-vue-next';
import { initFlowbite } from 'flowbite'
import { SidebarProvider, SidebarTrigger, SidebarInset } from "@/components/ui/sidebar";
import AppSidebar from "@/components/AppSidebar.vue";

const props = defineProps({
    title: String,
})

const showSidebar = useStorage('my-flag', true)
const isDark = useDark();
const toggleDark = useToggle(isDark);
const showMobileMenu = ref('false')
const scrollBackground = ref(false)

const navigations = [
    { name: "Construction Estimating", url: '/estimating', icon: Notebook },
    { name: "Invoicing", url: '/invoices', icon: DollarSign },
    { name: "Masterformat", url: '/masterformat', icon: BookMarked },
]

const setScrollBackground = (value) => {
    scrollBackground.value = value
}

onMounted(() => {
    initFlowbite();
    window.addEventListener("scroll", () => {
        return window.scrollY > 50 ? setScrollBackground(true) : setScrollBackground(false);
    })
})

</script>

<template>
    <div class="min-h-screen bg-light-secondary dark:bg-dark-secondary">
        <SidebarProvider :defaultOpen="showSidebar" style="--sidebar-width: 20rem; --sidebar-width-mobile: 20rem;" class="justify-center">
            <nav class="fixed z-20 w-full transition duration-500"
                :class="{ 'bg-light-primary dark:bg-dark-secondary -translate-y-1 border-b-4 border-black': scrollBackground, ' dark:bg-dark-primary': !scrollBackground }">
                <div class="flex flex-wrap items-center justify-between mx-auto p-2">
                    <div class="flex items-center gap-8">
                        <SidebarTrigger />
                        <Link href="/" class="flex">

                            <img src="/img/dad.svg" alt="Hero" class="rounded-lg h-12" :initial="{ x: 0, y: 0 }" :enter="{
                                x: Math.floor(Math.random() * 1) + 1, y: Math.floor(Math.random() * 1) + 1,
                                transition: { repeat: Infinity, repeatType: 'loop', repeatDelay: 100 }
                            }" />
                            <div
                                class="self-center text-2xl font-semibold whitespace-nowrap text-black dark:text-white transform duration-500 mt-1 ml-2">
                                igitalDadKC
                                <span v-if="props.title">{{ `- ${props.title}` }}</span>
                            </div>
                        </Link>
                    </div>
                    <div class="flex space-x-3 md:space-x-0">
                        <button data-collapse-toggle="navbar-sticky" type="button"
                            class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                            aria-controls="navbar-sticky" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <Menu class="cursor-pointer"></Menu>
                        </button>
                    </div>
                    <div class="hidden md:flex items-center justify-between gap-4 w-full md:w-auto" id="navbar-sticky">
                        <slot name="navigation" />
                        <Button
                            class="cursor-pointer bg-light-quatrenary text-light-secondary dark:bg-dark-quatrenary dark:text-dark-primary py-2 rounded-lg"
                            @click="toggleDark()">
                            <Lightbulb v-if="!isDark"></Lightbulb>
                            <Moon v-else></Moon>
                        </Button>
                    </div>
                </div>
            </nav>

            <AppSidebar :navigations />

            <main class="flex mt-20">
                <slot />
            </main>
        </SidebarProvider>
    </div>
</template>
