<script setup>
import { ref, computed } from 'vue';

const model = defineModel()

// const props = defineProps({
//     logo: Object,
// })

// const logo = ref(props.logo)

// const image = computed(() => {
//     if (!props.logo) {
//         return
//     }
//     if (typeof props.logo == 'object') {
//         return URL.createObjectURL(props.logo)
//     } else {
//         return props.logo
//     }
// })

</script>

<template>
    <div class="flex flex-col gap-2 bg-light-primary dark:bg-dark-primary h-full p-2">
        <!-- <div v-if="form.errors.logo" class="text-red-400">{{ form.errors.logo }}</div> -->
        <v-img height="255" aspect-ratio="16/9" :src="model"></v-img>
        <v-file-input v-model="model" show-size type="file" accept="image/*" label="Company Logo" @input="form.logo = $event.target.files[0]"></v-file-input>
    </div>
</template>