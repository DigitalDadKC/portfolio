<script setup>
    import Header from '@/Pages/Portfolio/Frontend/Header.vue';
    import Footer from '@/Pages/Portfolio/Frontend/Footer.vue';
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
