<script setup lang="ts">
import { watchEffect, computed } from 'vue';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import Input from '@/components/FormattedInput.vue';
import { useForm } from '@inertiajs/vue3';
import { Minus } from 'lucide-vue-next';

const props = defineProps({
    item: Object,
    index: Number
})

const emit = defineEmits(['update-item', 'remove-item']);

const form = useForm({
    description: props.item.description,
    price: props.item.price,
    quantity: props.item.quantity,
})

const total = computed(() => {
    return form.price * form.quantity;
})

const update = () => {
    emit('update-item', form)
}

const remove = () => {
    emit('remove-item', props.index);
}

watchEffect(() => {
    Object.assign(form, props.item)
})

</script>

<template>
    <div class="grid grid-cols-12 gap-2 justify-end items-end">
        <div class="col-span-6">
            <Label for="line" v-if="!index">Line Item</Label>
            <Input id="line" class="bg-white" width="full" v-model="form.description" @update:model-value="update()" />
        </div>
        <div class="col-span-2">
            <Label for="price" v-if="!index">Price</Label>
            <Input id="price" class="bg-white" width="full" type="currency" v-model="form.price" @update:model-value="update()" />
        </div>
        <div class="col-span-1">
            <Label for="quantity" v-if="!index">Quantity</Label>
            <Input id="quantity" class="bg-white" width="full" v-model="form.quantity" @update:model-value="update()" />
        </div>
        <div class="col-span-2">
            <Label for="price" v-if="!index">Total</Label>
            <Input id="price" class="bg-white" width="full" type="currency" v-model="total" :disabled="true" />
        </div>
        <div class="col-span-1">
            <Button @click="remove()">
                <Minus />
            </Button>
        </div>
    </div>
</template>
