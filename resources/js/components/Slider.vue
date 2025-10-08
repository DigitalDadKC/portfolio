<!-- ./src/components/CustomSlider.vue -->
<script setup>
import { ref, watchEffect, onMounted } from "vue";
import { useFormatCurrency } from '@/Composables/useFormatCurrency.js'

const { formatCurrency } = useFormatCurrency();

const { min, max, step, modelValue } = defineProps({
    min: {
        type: Number,
        default: 0,
    },
    max: {
        type: Number,
        default: 100,
    },
    step: {
        type: Number,
        default: 1,
    },
    modelValue: {
        type: Number,
        default: 50,
    },
});

const emit = defineEmits(["update:modelValue"]);

let sliderValue = ref(modelValue);
const slider = ref(null);

const getProgress = (value, min, max) => {
  return ((value - min) / (max - min)) * 100;
};

const setCSSProgress = (progress) => {
  slider.value.style.setProperty("--ProgressPercent", `${progress}%`);
};

watchEffect(() => {
  if (slider.value) {
    emit("update:modelValue", sliderValue.value);

    const progress = getProgress(
      sliderValue.value,
      slider.value.min,
      slider.value.max
    );

    let extraWidth = (100 - progress) / 10;

    setCSSProgress(progress + extraWidth);
  }
});

onMounted(() => {
    sliderValue.value = max
})

</script>

<template>
    <div class="inline-flex w-full text-white justify-between">
        <input
        ref="slider"
        :value="sliderValue"
        @input="({ target }) => (sliderValue = parseFloat(target.value))"
        type="range"
        :min="min"
        :max="max"
        :step="step"
        class="appearance-none bg-light-secondary rounded-md w-48 accent-light-quatrenary"
        />
        <span>{{ formatCurrency(sliderValue) }}</span>
    </div>
</template>