<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Search } from 'lucide-vue-next';

const model = defineModel()

</script>

<template>
    <div class="relative w-full">
        <Input v-model="model" id="search" type="text" placeholder="Search..." class="w-full bg-light-tertiary dark:bg-dark-tertiary dark:text-dark-quatrenary pl-10" />
        <span class="absolute start-0 inset-y-0 flex items-center justify-center px-2">
            <Search class="size-6 " />
        </span>
    </div>
</template>
