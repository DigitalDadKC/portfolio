<script setup>
import { ref, onMounted } from 'vue';
import { useDark, useToggle } from "@vueuse/core";
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/components/ApplicationLogo.vue';

const isDark = useDark();
const toggleDark = useToggle(isDark);

const showMobileMenu = ref('false')
const scrollBackground = ref(false)

const navigations = [
  {name: "Home", href: "#home"},
  {name: "Services", href: "#services"},
  {name: "Portfolio", href: "#portfolio"},
  {name: "About", href: "#about"},
  {name: "Contact", href: "#contact"},
  {name: "Login", href: "dashboard"}
]
const setScrollBackground = (value) => {
  scrollBackground.value = value
}

onMounted(() => {
  window.addEventListener("scroll", () => {
    return window.scrollY > 50 ? setScrollBackground(true) : setScrollBackground(false);
  })
})
</script>

<template>
    <nav class="fixed z-20 w-full transition bg-light-primary dark:bg-dark-primary" :class="{'bg-light-primary dark:bg-dark-secondary border-b-2 border-light-quatrenary -translate-y-3': scrollBackground, 'bg-white dark:bg-dark-primary': !scrollBackground}">
      <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <Link href="/" class="flex items-center">
            <ApplicationLogo :scrollBackground />
            <span class="self-center text-2xl font-semibold whitespace-nowrap text-black dark:text-white transform mt-1 ml-2" :class="{'text-base': scrollBackground, 'text-lg':!scrollBackground}">igitalDadKC</span>
        </Link>
        <button class="py-2 px-4 rounded-lg bg-light-quatrenary dark:bg-dark-quatrenary text-light-secondary dark:text-dark-secondary transform duration-500 cursor-pointer" :class="{'scale-100': scrollBackground, 'scale-110':!scrollBackground}" @click="toggleDark()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" /></svg>
        </button>
        <button @click='showMobileMenu = !showMobileMenu' data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false" aria-label="switch dark/light mode">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
            </svg>
        </button>
        <div class="w-full md:block md:w-auto" id="navbar-default" :class="{hidden : showMobileMenu}">
          <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 rounded-lg md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 dark:border-dark-tertiary">
            <li v-for="(navigation, index) in navigations" :key="index" class="py-2">
              <a :href="navigation.href" class="text-light-quatrenary dark:text-dark-quatrenary hover:text-light-quatrenary hover:border-b-2 border-light-quatrenary dark:hover:text-dark-tertiary" :class="{'text-base': scrollBackground, 'text-lg':!scrollBackground}" :aria-label="navigation.name" aria-current="page">{{ navigation.name }}</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
</template>
