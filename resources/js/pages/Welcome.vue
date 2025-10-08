<script setup>
import { Head } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/Frontend.vue';
import Hero from './Portfolio/Frontend/Hero.vue';
import Promote from './Portfolio/Frontend/Promote.vue';
import About from './Portfolio/Frontend/About.vue';
import Skills from './Portfolio/Frontend/Skills.vue';
import Portfolio from './Portfolio/Frontend/Portfolio.vue';
import Services from './Portfolio/Frontend/Services.vue';
import Contact from './Portfolio/Frontend/Contact.vue';
import Features from './Portfolio/Frontend/Features.vue';
import Calendar from './Portfolio/Frontend/Calendar.vue';

defineProps({
    skills: Object,
    projects: Object,
    features: Object,
})

</script>

<template>
    <Head title="DigitalDadKC" />

    <FrontendLayout>
        <Hero />
        <Calendar />
        <Promote />
        <Services />
        <Skills :skills="skills" />
        <Portfolio :skills :projects />
        <Features :features />
        <About />
        <Contact />
    </FrontendLayout>

</template>
