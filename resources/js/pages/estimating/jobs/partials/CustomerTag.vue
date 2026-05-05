<script setup lang="ts">
import { computed } from 'vue';
import { Badge } from '@/components/ui/badge'

const props = defineProps({
    filter: Number,
    customers: Array,
})

const emits = defineEmits(['remove'])

const customer = computed(() => {
    return props.customers?.find((customer) => customer.id == props.filter)
})
</script>

<template>
  <Badge class="flex text-center justify-center flex cursor-pointer" @click="$emit('remove', props.filter)">
        {{ customer.name }}
  </Badge>
</template>