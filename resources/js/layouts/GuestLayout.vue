<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import { Button } from '@/components/ui/button';
import { useStorage } from "@vueuse/core";
import { useDark, useToggle } from "@vueuse/core";
import { Lightbulb, Moon, Menu } from 'lucide-vue-next';
import { initFlowbite } from 'flowbite'
import { SidebarProvider, SidebarTrigger } from "@/components/ui/sidebar";
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
    { name: "Home", href: '/' },
    { name: "Estimating", href: '/estimating' },
    { name: "Invoicing", href: '/invoices' },
    { name: "Masterformat", href: '/masterformat' },
    { name: "Operator Logbook", href: 'https://github.com/DigitalDadKC/VBA-OperatorLogbook' },
    { name: "Production Efficiency", href: 'https://github.com/DigitalDadKC/VBA-ProductionLineEfficiencyLog' },
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
    <div class="flex justify-center w-full bg-light-primary dark:bg-dark-primary">

        <nav class="fixed z-20 w-full transition duration-500 bg-light-primary border-gray-200 dark:bg-dark-primary"
            :class="{ 'bg-light-primary dark:bg-dark-secondary border-b-2 -translate-y-3': scrollBackground, ' dark:bg-dark-primary': !scrollBackground }">
            <div class="flex flex-wrap items-center justify-between mx-auto p-2">
                <Link href="/" class="flex">
                <img src="img/dad.png" alt="Hero" class="rounded-lg h-12" :initial="{ x: 0, y: 0 }"
                    :enter="{ x: Math.floor(Math.random() * 1) + 1, y: Math.floor(Math.random() * 1) + 1, transition: { repeat: Infinity, repeatType: 'loop', repeatDelay: 100 } }" />
                <span
                    class="self-center text-2xl font-semibold whitespace-nowrap text-black dark:text-white transform duration-500 mt-1 ml-2">igitalDadKC
                    - {{ props.title }}</span>
                </Link>
                <div class="flex space-x-3 md:space-x-0">
                    <button data-collapse-toggle="navbar-sticky" type="button"
                        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                        aria-controls="navbar-sticky" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <Menu class="cursor-pointer"></Menu>
                    </button>
                </div>
                <div class="hidden md:flex items-center justify-between gap-4 w-full md:w-auto" id="navbar-sticky">
                    <ul
                        class="font-medium flex flex-col p-4 md:p-0 mt-4 rounded-lg md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 dark:border-dark-tertiary">
                        <li v-for="(navigation, index) in navigations" :key="index" class="py-2">
                            <Link :href="navigation.href"
                                class="text-light-quatrenary dark:text-dark-quatrenary hover:text-light-quatrenary hover:border-b-2 border-light-quatrenary dark:hover:text-dark-tertiary"
                                :class="{ 'text-base': scrollBackground, 'text-lg': !scrollBackground }"
                                :aria-label="navigation.name" aria-current="page">{{ navigation.name }}</Link>
                        </li>
                    </ul>
                    <Button
                        class="cursor-pointer bg-light-quatrenary text-light-secondary dark:bg-dark-quatrenary dark:text-dark-primary py-2 rounded-lg"
                        @click="toggleDark()">
                        <Lightbulb v-if="!isDark"></Lightbulb>
                        <Moon v-else></Moon>
                    </Button>
                </div>
            </div>
        </nav>

        <SidebarProvider class="grid justify-center">
            <AppSidebar />
            <SidebarTrigger class="mt-20" />
            <main>
                <slot />
            </main>
        </SidebarProvider>


        <!--
    <div class="flex justify-center min-h-screen bg-light-primary dark:bg-dark-primary">
        <nav
            class="fixed z-20 w-full transition duration-500 bg-light-secondary border-gray-200 dark:bg-dark-secondary">
            <div class="flex flex-wrap items-center justify-between mx-auto p-4">
                <Link href="/" class="flex">
                    <img src="img/dad.png" alt="Hero" class="rounded-lg h-12" :initial="{ x: 0, y: 0 }" :enter="{ x: Math.floor(Math.random() * 1) + 1, y: Math.floor(Math.random() * 1) + 1, transition: { repeat: Infinity, repeatType: 'loop', repeatDelay: 100 } }" />
                    <span class="self-center text-2xl font-semibold whitespace-nowrap text-black dark:text-white transform duration-500 mt-1 ml-2">igitalDadKC</span>
                </Link>

                {{ props.title }}

                <div class="flex items-center" :class="{ hidden: !showMobileMenu }">
                    <Button class="cursor-pointer bg-light-quatrenary text-light-secondary dark:bg-dark-quatrenary dark:text-dark-primary px-4 py-2 rounded-lg" @click="toggleDark()">
                        <Lightbulb v-if="!isDark"></Lightbulb>
                        <Moon v-else></Moon>
                    </Button>
                    <button @click='showMobileMenu = !showMobileMenu' data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false" aria-label="switch dark/light mode">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                        </svg>
                    </button>
                    <div class="w-full md:block md:w-auto" id="navbar-default">
                        <ul
                            class="font-medium flex flex-col p-4 md:p-0 mt-4 rounded-lg md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 dark:border-dark-tertiary">
                            <li v-for="(navigation, index) in navigations" :key="index" class="py-2">
                                <a :href="navigation.href"
                                    class="text-light-quatrenary dark:text-dark-quatrenary hover:text-light-quatrenary hover:border-b-2 border-light-quatrenary dark:hover:text-dark-tertiary"
                                    :class="{ 'text-base': scrollBackground, 'text-lg': !scrollBackground }"
                                    :aria-label="navigation.name" aria-current="page">{{ navigation.name }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div  :class="{ hidden: showMobileMenu }">
                    <div class="w-full md:block md:w-auto" id="navbar-default" :class="{ hidden: showMobileMenu }">
                        <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 rounded-lg md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 dark:border-dark-tertiary">
                            <li v-for="(navigation, index) in navigations" :key="index" class="py-2">
                                <a :href="navigation.href"
                                    class="text-light-quatrenary dark:text-dark-quatrenary hover:text-light-quatrenary hover:border-b-2 border-light-quatrenary dark:hover:text-dark-tertiary"
                                    :class="{ 'text-base': scrollBackground, 'text-lg': !scrollBackground }"
                                    :aria-label="navigation.name" aria-current="page">{{ navigation.name }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav> -->

        <main class="pt-24">
        </main>
    </div>
</template>
