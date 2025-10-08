<script setup lang="ts">
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';
import { Search } from 'lucide-vue-next';

const model = defineModel()

</script>

<template>
    <Label>Job #</Label>
    <div class="flex">
        <div class="relative w-full max-w-sm items-center">
            <Input v-model="model" id="search" type="text" placeholder="Search..." class="bg-white dark:bg-dark-tertiary dark:text-dark-quatrenary pl-10" />
            <span class="absolute start-0 inset-y-0 flex items-center justify-center px-2">
                <Search class="size-6 " />
            </span>
        </div>
    </div>
</template>
