<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Grip, GripVertical, Trash2, Plus } from 'lucide-vue-next';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import FormattedInput from '@/components/FormattedInput.vue';
import Uom from './partials/Uom.vue';
import { computed } from 'vue';

const props = defineProps({
    index: Number,
    element: Object,
    unit_of_measurements: Object,
})

const updateLine = () => {
    router.put(route('lines.update', props.element.id), {
        description: props.element.description,
        unit_of_measurement_id: props.element.unit_of_measurement.id,
        price: props.element.price,
        quantity: props.element.quantity,
    }, {
        preserveScroll: true,
    })
}

const removeLine = () => {
    router.delete(route('lines.destroy', props.element.id), {
        preserveScroll: true,
    })
}

const total = computed(() => {
    return props.element.price * props.element.quantity
})

</script>


<template>
    <div v-motion-slide-right>
        <details class="open:bg-light-primary duration-500 rounded-md block md:hidden" @keyup.space="(event) => {event.preventDefault();}">
            <summary class="flex items-start p-1 text-lg cursor-pointer">
                <Grip class="handle cursor-pointer"></Grip>
                <FormattedInput v-model="element.description" />
                <button class="rounded-md float-right">
                    <Plus @click="addLine(props.index)"></Plus>
                    <Plus @click="addLine(props.index)"></Plus>
                    <Trash2 class="cursor-pointer text-red-500" @click="removeLine(props.index, index)"></Trash2>
                </button>
            </summary>
            <div>
                <Uom v-model="element.unit_of_measurement.id" :unit_of_measurements @update:model-value="updateLine(props.index, index)"></Uom>
                <FormattedInput v-model="element.price" type="currency" label="Price" @blur="updateLine(props.index, index)" />
                <FormattedInput v-model="element.quantity" type="number" label="Quantity" @blur="updateLine(props.index, index)" />
                <FormattedInput v-model="element.total" type="currency" label="Total" :disabled="true" />
            </div>
        </details>

        <div class="hidden md:flex md:flex-row items-end gap-1">
            <GripVertical class="handle cursor-grab w-8 h-10 dark:bg-dark-quatrenary dark:text-dark-primary rounded-lg"></GripVertical>
            <div class="grow">
                <Label v-if="!index">Description</Label>
                <FormattedInput class="w-full" v-model="element.description" label="Description" @blur="updateLine()" />
            </div>
            <div>
                <Label v-if="!index">Unit</Label>
                <Uom v-model="element.unit_of_measurement.id" :unit_of_measurements @update:model-value="updateLine()"></Uom>
            </div>
            <div>
                <Label v-if="!index">Price</Label>
                <FormattedInput v-model="element.price" type="currency" label="Price" @blur="updateLine()" />
            </div>
            <div>
                <Label v-if="!index">Quantity</Label>
                <FormattedInput v-model="element.quantity" type="number" label="Quantity" @blur="updateLine()" />
            </div>
            <div>
                <Label v-if="!index">Total</Label>
                <FormattedInput v-model="total" type="currency" label="Total" width="36" :disabled="true" />
            </div>
            <Trash2 class="cursor-pointer text-red-500" @click="removeLine()"></Trash2>
        </div>
        <!-- <div v-if="form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.description']" class="text-red-500">{{ form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.description']}}</div>
        <div v-if="form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.price']" class="text-red-500">{{ form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.price']}}</div>
        <div v-if="form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.quantity']" class="text-red-500">{{ form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.quantity']}}</div>
        <div v-if="form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.unit_of_measurement.id']" class="text-red-500">{{ form.errors['proposal.scopes.' + scopeIndex + '.lines.' + index + '.unit_of_measurement.id']}}</div> -->
    </div>

</template>
