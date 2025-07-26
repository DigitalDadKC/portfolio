<script setup>
import { Head } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/Frontend.vue';
import Hero from '@/Components/Frontend/Hero.vue';
import Promote from '@/Components/Frontend/Promote.vue'
import About from '@/Components/Frontend/About.vue'
import Skills from '@/Components/Frontend/Skills.vue'
import Portfolio from '@/Components/Frontend/Portfolio.vue'
import Services from '@/Components/Frontend/Services.vue'
import Contact from '@/Components/Frontend/Contact.vue'
import Features from '@/Components/Frontend/Features.vue';

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
        <Promote />
        <Services />
        <Skills :skills="skills" />
        <Portfolio :skills :projects />
        <Features :features />
        <About />
        <Contact />
    </FrontendLayout>
</template>
