<script setup lang="ts">
import {  } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import FrontendLayout from '@/layouts/Frontend.vue';
import Button from '@/components/ui/button/Button.vue';

const props = defineProps({
    divisions: Object,
    sections: Object,
    subsections: Object,
})

</script>

<template>
    <Head title="Success" />

    <FrontendLayout>
        <div class="flex flex-col items-center gap-8 pt-48">
            Thank you for your business!
            <Button>
                <Link :href="route('home')">Return  Home</Link>
            </Button>
        </div>
    </FrontendLayout>
</template>
