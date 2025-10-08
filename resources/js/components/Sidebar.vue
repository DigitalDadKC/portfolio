<script setup>
import { useStorage } from '@vueuse/core'

const sidebar = useStorage('my-flag', false)

const emit = defineEmits(['showSideBar']);

const showSideBar = () => {
    sidebar.value = !sidebar.value
    emit('showSideBar', sidebar.value)
}

</script>

<template>

    <aside class="absolute lg:static bg-light-quatrenary min-h-screen transition-all duration-500 ease-in-out" :class="{'w-screen sm:w-72 border-r': sidebar, 'w-0': !sidebar}">
        <div class="relative">
            <button class="fixed transition-all duration-500 ease-in-out rounded-l-none rounded-r-md z-10 mt-16" :class="{'rotate-90 bg-dark-quatrenary ml-72 rounded-br-none rounded-tl-md': sidebar, 'bg-dark-tertiary': !sidebar}" @click="showSideBar()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 dark:text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>
        <div class="mt-10 overflow-hidden py-2">
            <slot name="sidebarContents" />
        </div>
        
    </aside>


</template>