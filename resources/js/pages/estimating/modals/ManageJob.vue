<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Button } from '@/Components/ui/button';
import { Textarea } from '@/Components/ui/textarea';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog'
import StartDate from '../Partials/StartDate.vue';
import State from '../Partials/State.vue';

const props = defineProps({
    new: Boolean,
    job: Object,
    states: Object,
})

const isDialogOpen = ref(false)
const form = useForm({
    id: props.job?.id,
    number: props.job?.number,
    address: props.job?.address,
    city: props.job?.city,
    state_id: props.job?.state?.id,
    zip: props.job?.zip,
    start_date: props.job?.start_date,
    notes: props.job?.notes,
    created_at: props.job?.created_at,
})

const submit = () => {
    if (props.new) {
        form.post(route('estimating.store'), {
            onSuccess: () => {
                form.reset()
                isDialogOpen.value = false
            }
        })
    }
    else {
        form.patch(route('estimating.update', { job: form.id }), {
            onSuccess: () => {
                isDialogOpen.value = false
            }
        })
    }
}
</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button v-if="props.new">New Job</Button>
            <Button v-else>EDIT</Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[800px] grid-rows-[auto_minmax(0,1fr)_auto] p-0 max-h-[90dvh] bg-light-primary dark:bg-dark-primary">
            <DialogHeader class="p-6 pb-0">
                <DialogTitle>{{(props.new) ? 'New Job' : 'Edit Job' }}</DialogTitle>
                <DialogDescription>
                    {{ (props.new) ? '' : `Job #${props.job.number}` }}
                </DialogDescription>
            </DialogHeader>
            <div class="grid grid-cols-4 gap-4 py-4 overflow-y-auto px-6">
                <div class="col-span-1">
                    <Label for="number">Job Number</Label>
                    <Input id="number" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model.number="form.number" />
                </div>
                <div v-if="form.errors.number" class="text-red-500">{{ form.errors.number }}</div>
                <div class="col-span-1 col-start-4">
                    <StartDate v-model="form.start_date" :job></StartDate>
                </div>
                <h1 v-if="form.errors.start_date" class="text-red-500">{{ form.errors.start_date }}</h1>
                <div class="col-span-4">
                    <Label for="address">Address</Label>
                    <Input id="address" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.address" />
                </div>
                <div v-if="form.errors.address" class="text-red-500">{{ form.errors.address }}</div>
                <div class="col-span-2">
                    <Label for="city">City</Label>
                    <Input id="city" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.city" />
                </div>
                <div v-if="form.errors.city" class="text-red-500">{{ form.errors.city }}</div>
                <div class="col-span-1">
                    <State v-model="form.state_id" :states></State>
                </div>
                <div v-if="form.errors.state_id" class="text-red-500">{{ form.errors.state_id }}</div>
                <div class="col-span-1">
                    <Label for="zip">Zip</Label>
                    <Input id="zip" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.zip" />
                </div>
                <div v-if="form.errors.zip" class="text-red-500">{{ form.errors.zip }}</div>
                <div class="col-span-4">
                    <Label for="zip">Notes</Label>
                    <Textarea id="notes" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.notes" />
                </div>
                <div v-if="form.errors.notes" class="text-red-500">{{ form.errors.notes }}</div>
            </div>
            <DialogFooter class="p-6 pt-0">
                <div class="flex gap-2">
                    <Link :href="route('estimating.index')" prefetch>
                        <Button variant="secondary">Cancel</Button>
                    </Link>
                    <Button type="submit" :disabled="form.processing" @click="submit()">Save</Button>
                </div>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
