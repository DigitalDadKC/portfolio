<script setup>
    import {  } from 'vue';
    import { Link, Head } from '@inertiajs/vue3';
import PrimaryButton from '@/components/PrimaryButton.vue';

</script>

<template>
    <div class="fixed flex p-2 bg-light-secondary w-full items-center">
        <v-icon>mdi-arrow-left-thick</v-icon>
        <Link :href="route('home')">
            <v-btn>Back to Digital Dad</v-btn>
        </Link>
    </div>
</template>
