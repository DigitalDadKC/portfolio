<script setup>

const props = defineProps({
    links: Object,
})

</script>

<template>
    <Component
        :is="link.url ? 'Link' : 'span'"
        v-for="link in props.links"
        :href="link.url"
        v-html="link.label"
        class="bg-grey-lighten-2 rounded-md py-2 px-4"
        :class="{'text-light-tertiary dark:text-dark-quatrenary': !link.url, 'font-bold text-light-secondary bg-light-quatrenary dark:bg-dark-quatrenary dark:text-dark-primary': link.active}"
        prefetch
    />
</template>
