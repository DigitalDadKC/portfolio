<script setup lang="ts">
import { computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3'
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Trash2, GripVertical, Grip, Plus} from 'lucide-vue-next'
import Line from './Line.vue';
import FormattedInput from '@/components/FormattedInput.vue';
import draggable from 'vuedraggable';

const props = defineProps({
    index: Number,
    scope: Object,
    unit_of_measurements: Object,
})

const updateScope = () => {
    router.put(route('scopes.update', props.scope.id), {
        name: props.scope.name,
        area: props.scope.area,
    }, {
        preserveScroll: true,
    })
}

const removeScope = () => {
    router.delete(route('scopes.destroy', props.scope.id), {
        preserveScroll: true,
    })
}

const addLine = () => {
    router.post(route('lines.create', props.scope.id), {
    }, {
        preserveScroll: true,
    })
}

const sort = (scope) => {
    router.patch(route('lines.sort', scope.id), {
        lines: scope.lines,
    }, {
        preserveScroll: true,
    })
}

const total = computed(() => {
    return props.scope.lines.reduce((a, b) => a + (b.price*b.quantity), 0)
})

</script>

<template>
    <div class="relative bg-light-tertiary rounded-md p-2 my-6" v-motion-slide-right>
        <div class="absolute flex gap-2 bg-light-quatrenary -top-10 rounded-t-lg p-2">
            <h1 class="text-light-secondary">Scope # {{ props.index+1 }}</h1>
            <Trash2 class="cursor-pointer text-red-500" @click="removeScope()"></Trash2>
        </div>
        <div class="flex justify-between mb-4">
            <div class="flex flex-col md:flex-row gap-1">
                <div>
                    <Label :for="`scope-name-${props.index}`">Scope Name</Label>
                    <FormattedInput width="96" :id="`scope-name-${index}`" v-model="scope.name" @blur="updateScope()" />
                </div>
                <div>
                    <Label :for="`scope-area-${props.index}`">Scope Area</Label>
                    <FormattedInput width="48" :id="`scope-area-${props.index}`" v-model="scope.area" type="number" @blur="updateScope()" />
                </div>
            </div>
            <Button @click.prevent="addLine()"><Plus></Plus>Add Line</Button>
        </div>
        <!-- <div v-if="form.errors['scopes.' + props.index + '.name']" class="text-red-400">{{ form.errors['scopes.' + props.index + '.name']}}</div> -->

        <div class="rounded-md">
            <draggable :list="scope.lines" item-key="id" handle=".handle" @change="sort(scope)">
                <template #item="{element, index}">
                    <Line :element :index :unit_of_measurements />
                </template>
            </draggable>
        </div>

        <div class="flex justify-end">
            <div class="flex flex-col justify-start">
                <Label>Scope Total</Label>
                <FormattedInput v-model="total" width="36" type="currency" :disabled="true" class="font-bold" />
            </div>
        </div>
    </div>
</template>
