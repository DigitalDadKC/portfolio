<script setup lang="ts">
import { Github, Mail } from 'lucide-vue-next';

</script>

<template>
    <section id="home" class="flex items-center bg-white dark:bg-dark-primary overflow-hidden">
        <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12">
            <div class="mr-auto place-self-center lg:col-span-7">
                <p class="text-xl font-bold text-accent mb-[2px] mt-5 lg:mt-0">Hey I'm Raleigh! </p>
                <blockquote class="">(Raw-lee)</blockquote>
                <h1 class="text-4xl leading-[44px] md:text-5xl md:leading-tight lg:text-7xl text-center lg:text-start lg:leading-[1.2] font-bold md:tracking-[-2px]">
                    I make web apps.
                </h1>
                <h6>Transforming your spreadsheets into sophisticated, robust software</h6>
                <div class="py-20">
                    <h5 class="text-lg flex items-center dark:text-white mb-2">Connect with me!</h5>
                    <div class="flex items-center justify-between gap-2 sm:justify-start mb-2">
                        <a target="_blank" href="mailto:raleighgroesbeck@gmail.com" class="bg-light-tertiary dark:bg-dark-tertiary text-light-quatrenary dark:text-dark-quatrenary border-2 border-light-quatrenary dark:border-dark-quatrenary p-3 rounded-full transition ease-in-out hover:scale-110 duration-100">
                            <Mail></Mail>
                        </a>
                        <a target="_blank" href="https://github.com/DigitalDadKC" class="bg-light-tertiary dark:bg-dark-tertiary text-light-quatrenary dark:text-dark-quatrenary border-2 border-light-quatrenary dark:border-dark-quatrenary p-3 rounded-full transition ease-in-out hover:scale-110 duration-100">
                            <Github></Github>
                        </a>
                        <!-- <a target="_blank" href="https://www.linkedin.com/in/digitaldadkc" class="p-1 rounded-full transition ease-in-out hover:scale-110 duration-100">
                            <v-icon size="40" class="text-blue-500 dark:text-white">mdi-linkedin</v-icon>
                        </a> -->
                    </div>
                </div>
            </div>
            <div class="hidden lg:mt-0 lg:col-span-5 lg:flex">
                <img src="img/profile 2.jpg" alt="Hero" class="rounded-lg" /><br>
            </div>
        </div>
    </section>
</template>
