<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Search } from 'lucide-vue-next';

const model = defineModel()

</script>

<template>
    <div class="relative w-full px-6">
        <Input v-model="model" id="search" type="text" placeholder="Search Job #, City or Address..." class="w-full bg-white pl-10" />
        <span class="absolute start-0 inset-y-0 flex items-center justify-center px-8">
            <Search class="size-6" />
        </span>
    </div>
</template>
