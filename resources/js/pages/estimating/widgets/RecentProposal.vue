<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useDateFormat } from '@vueuse/core';
import { NotepadText } from 'lucide-vue-next';

const props = defineProps({
    proposals: Object,
})

</script>


<template>
    <div class="relative">
        <div class="p-3 bg-white inline-flex border-2 rounded-lg border-light-quatrenary dark:border-dark-quatrenary absolute -top-10 left-0 z-50">
            <NotepadText></NotepadText>
        </div>

        <div class="pl-16">
            <h5 class="my-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Recent Proposals</h5>
        </div>

        <div v-for="(item, index) in props.proposals.slice(0, 5)" :key="index">
            <div class="flex justify-between text-sm text-nowrap">
                <div>
                    <Link :href="route('proposals.edit', item.id)" class="hover:bg-light-tertiary px-2 py-2 " prefetch>
                        {{item.name}} ({{ item.type }})
                    </Link>
                </div>
                <div>
                    {{ useDateFormat(item.created_at, 'M-DD-YYYY') }}
                </div>
            </div>
        </div>

    </div>
</template>
