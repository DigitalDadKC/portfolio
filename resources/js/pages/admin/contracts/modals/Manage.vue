<script setup lang="ts">
import { computed, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import FormattedInput from '@/components/FormattedInput.vue';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import Client from '../partials/Client.vue';
import Services from '../partials/Services.vue';
import Employees from '../partials/Employees.vue';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger, DialogDescription } from '@/components/ui/dialog';
import { Pencil, Plus } from 'lucide-vue-next';
import { useDateFormat } from '@vueuse/core';

const props = defineProps({
    new: Boolean,
    contract: Object,
    clients: Object,
    services: Object,
})

const isDialogOpen = ref(false)
const form = useForm({
    id: props.contract?.id,
    price: props.contract?.price,
    date: props.contract?.date,
    client_id: props.contract?.client.id,
    employee_id: props.contract?.employee.id,
    services: props.contract?.service_ids,
})

const submit = () => {
    form.transform((data) => ({
        ...data,
        date: (form.date) ? useDateFormat(form.date, 'YYYY-MM-DD').value : null,
    }))
    if (props.new) {
        form.post(route('contracts.store'), {
            onSuccess: () => {
                isDialogOpen.value = false
                form.reset()
            }
        })
    } else {
        form.patch(route('contracts.update', form.id), {
            onSuccess: () => {
                isDialogOpen.value = false
            }
        })
    }
}

const employees = computed(() => {
    return props.clients.find(client => client.id == form.client_id)?.employees
})

</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button v-if="props.new">
                <Plus></Plus>
                New Contract
            </Button>
            <Button v-else>
                Edit
                <Pencil></Pencil>
            </Button>
        </DialogTrigger>

        <DialogContent class="sm:max-w-200 grid-rows-[auto_minmax(0,1fr)_auto] max-h-[90dvh] bg-light-primary dark:bg-dark-primary">
            <DialogHeader class="p-2">
                <DialogTitle>{{ (props.new) ? 'New Contract' : 'Edit Contract' }}</DialogTitle>
                <DialogDescription>
                    <p v-if="!props.new">NOTE: This will delete the old contract</p>
                </DialogDescription>
            </DialogHeader>

            <div class="grid grid-cols-4 gap-4">
                <div class="col-span-2">
                    <Label for="description">Client</Label>
                    <Client :clients @update:model-value="form.employees = []" v-model="form.client_id" />
                </div>
                <div class="col-span-2">
                    <Label>Recipient</Label>
                    <Employees :employees v-model="form.employee_id" />
                </div>
                <div class="col-span-1">
                    <Label for="title">Price</Label>
                    <FormattedInput type="currency" class="w-full" v-model="form.price" />
                </div>
                <div class="col-span-3">
                    <Label>Services</Label>
                    <Services :services v-model="form.services" />
                </div>
            </div>

            <DialogFooter class="p-6 pt-0">
                <div class="flex gap-2">
                    <Button variant="outline" class="cursor-pointer" @click="isDialogOpen = false">Cancel</Button>
                    <Button type="submit" class="cursor-pointer" :disabled="form.processing"
                        @click="submit()">
                            <p v-if="props.new">Create</p>
                            <p v-else>Update</p>
                        </Button>
                </div>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
