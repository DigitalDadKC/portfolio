<script setup lang="ts">
import { ref, watchEffect } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import Customer from '../partials/Customer.vue';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import StartDate from '../partials/StartDate.vue';
import State from '../partials/State.vue';

const props = defineProps({
    new: Boolean,
    job: Object,
    states: Object,
    customers: Object,
})

const isDialogOpen = ref(false)
const form = useForm({
    id: props.job?.id,
    number: props.job?.number,
    address: props.job?.address,
    city: props.job?.city,
    state_id: props.job?.state?.id,
    zip: props.job?.zip,
    notes: props.job?.notes,
    customer_id: props.job.customer?.id,
    start_date: props.job?.start_date,
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

watchEffect(() => {
    Object.assign(form, props.job)
    form.state_id = props.job.state?.id
    form.customer_id = props.job.customer?.id
})

</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button class="cursor-pointer" v-if="props.new">New Job</Button>
            <Button class="cursor-pointer" v-else>EDIT</Button>
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
                <div class="col-span-2">
                    <Customer :customers v-model="form.customer_id"></Customer>
                </div>
                <div class="col-span-1">
                    <StartDate v-model="form.start_date" :job></StartDate>
                </div>
                <div class="col-span-4">
                    <div v-if="form.errors.number" class="text-red-500">{{ form.errors.number }}</div>
                    <div v-if="form.errors.customer_id" class="text-red-500">{{ form.errors.customer_id }}</div>
                    <div v-if="form.errors.start_date" class="text-red-500">{{ form.errors.start_date }}</div>
                </div>
                <div class="col-span-4">
                    <Label for="address">Address</Label>
                    <Input id="address" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.address" />
                </div>
                <div class="col-span-4">
                    <div v-if="form.errors.address" class="text-red-500">{{ form.errors.address }}</div>
                </div>
                <div class="col-span-2">
                    <Label for="city">City</Label>
                    <Input id="city" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.city" />
                </div>
                <div class="col-span-1">
                    <State v-model="form.state_id" :states></State>
                </div>
                <div class="col-span-1">
                    <Label for="zip">Zip</Label>
                    <Input id="zip" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.zip" />
                </div>
                <div class="col-span-4">
                    <div v-if="form.errors.city" class="text-red-500">{{ form.errors.city }}</div>
                    <div v-if="form.errors.state_id" class="text-red-500">{{ form.errors.state_id }}</div>
                    <div v-if="form.errors.zip" class="text-red-500">{{ form.errors.zip }}</div>
                </div>
                <div class="col-span-4">
                    <Label for="zip">Notes</Label>
                    <Textarea id="notes" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.notes" />
                </div>
            </div>
            <DialogFooter class="flex gap-2 p-6">
                <Button class="cursor-pointer" variant="outline" @click="isDialogOpen = false; form.reset(); form.clearErrors();">Cancel</Button>
                <Button class="cursor-pointer" type="submit" :disabled="form.processing" @click="submit()">Save</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
