<script setup lang="ts">
import { computed } from 'vue';
import { Badge } from '@/components/ui/badge'

const props = defineProps({
    filter: Number,
    states: Array,
})

const emits = defineEmits(['remove'])

const state = computed(() => {
    return props.states?.find((state) => state.id == props.filter)
})
</script>

<template>
  <Badge class="flex text-center justify-center flex cursor-pointer" @click="$emit('remove', props.filter)">
        {{ state.state }}
  </Badge>
</template>