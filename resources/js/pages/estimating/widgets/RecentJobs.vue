<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useDateFormat } from '@vueuse/core';
import { NotepadText } from 'lucide-vue-next';

const props = defineProps({
    jobs: Object,
})

const recent_jobs = ref(props.jobs.sort((a, b) => a.created_at - b.created_at))

</script>


<template>
    <div class="relative">
        <div class="p-3 bg-white inline-flex border-2 rounded-lg border-light-quatrenary dark:border-dark-quatrenary absolute -top-10 left-0">
            <NotepadText></NotepadText>
        </div>

        <div class="pl-16">
            <h5 class="my-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Recent Jobs</h5>
        </div>

        <div v-for="(item, index) in recent_jobs.slice(0, 5)" :key="index">
            <div class="flex justify-between text-sm">
                <div>
                    <Link :href="route('estimating.edit', item.id)" class="hover:bg-light-tertiary px-2 py-2 hover:scale-120 transform transition" prefetch>
                        Job #{{item.number}} ({{ item.proposals.length }} proposals)
                    </Link>
                </div>
                <div>
                    {{ useDateFormat(item.created_at, 'M-DD-YYYY hh:mm:ss') }}
                </div>
            </div>
        </div>

    </div>
</template>
