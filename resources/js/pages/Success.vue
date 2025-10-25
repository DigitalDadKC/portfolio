<script setup lang="ts">
import {  } from 'vue';
import { Head } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';

interface DataItem {
    division: string
    section: string
}

const props = defineProps({
    divisions: Object,
    sections: Object,
    subsections: Object,
})

</script>

<template>
    <Head title="Success" />

    <GuestLayout title="Success">
        Hoora!
        you paid me some sweet cash!
    </GuestLayout>

</template>
