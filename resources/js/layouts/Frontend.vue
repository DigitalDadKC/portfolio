<script setup>
    import Header from '@/pages/portfolio/frontend/Header.vue';
    import Footer from '@/pages/portfolio/frontend/Footer.vue';
</script>

<template>
    <div >
        <Header />

        <main class="min-h-screen">
            <slot />
        </main>

        <Footer />
    </div>
</template>
