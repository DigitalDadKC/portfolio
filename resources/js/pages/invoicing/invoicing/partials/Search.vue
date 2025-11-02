<script setup lang="ts">
import { Search } from 'lucide-vue-next';
import { Input } from '@/components/ui/input';
const model = defineModel()
</script>


<template>
    <div class="w-full md:w-1/2">
        <form class="flex items-center">
            <label for="search" class="sr-only">Search</label>
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <Search />
                </div>
                <Input id="search" v-model="model" class="bg-white dark:bg-dark-primary pl-10" />
                <!-- <input type="text" id="simple-search" class="bg-white dark:bg-dark-primary border border-light-quatrenary text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:border-dark-quatrenary dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Search Invoice # or reference" required="" v-model="model"> -->
            </div>
        </form>
    </div>
</template>
