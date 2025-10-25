<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Input from '@/components/FormattedInput.vue';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import Clients from '../partials/Clients.vue';
import InvoiceDate from '../partials/InvoiceDate.vue';
import { Textarea } from '@/components/ui/textarea';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog'
import { Pencil, Plus } from 'lucide-vue-next';
import { useDateFormat } from '@vueuse/core';
import Line from '../partials/Line.vue';

const props = defineProps({
    new: Boolean,
    invoice: Object,
    clients: Object,
})

const isDialogOpen = ref(false)
const form = useForm({
    id: props.invoice.id,
    number: props.invoice.number,
    date_created: props.invoice.date_created ?? new Date(),
    due_date: props.invoice.due_date,
    line_items: props.invoice.client_invoice_items ?? [],
    total_price: props.invoice.total_price ?? 0,
    terms_and_conditions: props.invoice.terms_and_conditions,
    client_id: props.invoice.client_id,
})

const submit = () => {
    if(props.new) {
        form.transform((data) => ({
        ...data,
        date_created: (form.date_created) ? useDateFormat(form.date_created, 'YYYY-MM-DD').value : null,
        due_date: (form.due_date) ? useDateFormat(form.due_date, 'YYYY-MM-DD').value : null,
    })).post(route('admin.invoices.store'), {
            onSuccess: () => {
                isDialogOpen.value = false
                form.reset()
            }
        })
    } else {
        form.transform((data) => ({
        ...data,
        date_created: (form.date_created) ? useDateFormat(form.date_created, 'YYYY-MM-DD').value : null,
        due_date: (form.due_date) ? useDateFormat(form.due_date, 'YYYY-MM-DD').value : null,
    })).patch(route('admin.invoices.update', form.id), {
            onSuccess: () => {
                isDialogOpen.value = false
            }
        })
    }
}

const addItem = () => {
    form.line_items.push({
        description: '',
        price: 0,
        quantity: 1,
    });
}

const updateItem = (i, data) => {
    form.line_items[i] = {
        description: data.description,
        price: data.price,
        quantity: data.quantity,
    };
}

const removeItem = (e) => {
    form.line_items.splice(e, 1);
}

</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button class="cursor-pointer" v-if="props.new">
                <Plus></Plus>
                New invoice
            </Button>
            <Button class="cursor-pointer" v-else>
                <Pencil class="cursor-pointer"></Pencil>
            </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[1000px] grid-rows-[auto_minmax(0,1fr)_auto] h-[60dvh] bg-light-primary dark:bg-dark-primary flex flex-col justify-between overflow-auto">
            <DialogHeader>
                <DialogTitle>{{ `${(props.new) ? 'New Invoice' : `Edit Invoice #${props.invoice.number}`}` }}</DialogTitle>
                <DialogDescription></DialogDescription>

                <div class="flex flex-col gap-20">
                    <div class="grid grid-cols-6 gap-4">
                        <div class="col-span-1">
                            <Label for="number">Number</Label>
                            <Input id="number" width="full" class="bg-white" v-model="form.number" />
                        </div>
                        <div class="col-span-2">
                            <Label for="client">Client</Label>
                            <Clients v-model="form.client_id" :clients />
                        </div>
                        <div class="col-span-1 col-start-5">
                            <Label for="date_created">Created</Label>
                            <InvoiceDate v-model="form.date_created" />
                        </div>
                        <div class="col-span-1">
                            <Label for="due_date">Due Date</Label>
                            <InvoiceDate v-model="form.due_date" />
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-end gap-4">
                            <Label for="addItem">Add Line Item</Label>
                            <Button id="addItem" @click="addItem()">
                                <Plus />
                            </Button>
                        </div>
                        <div v-for="(item, index) in form.line_items" :key="index" class="mb-2">
                            <Line :item :index @update-item="(e) => updateItem(index, e)" @remove-item="removeItem(index)" />
                        </div>
                    </div>
                    <div>
                        <div class="col-span-6">
                            <Label for="terms_and_conditions">Terms & Conditions</Label>
                            <Textarea id="terms_and_conditions" class="bg-white" v-model="form.terms_and_conditions" />
                        </div>
                    </div>
                </div>

                <div v-for="error in form.errors" :key="error.id" class="text-red-500">
                    {{ error }}
                </div>

            </DialogHeader>
            <DialogFooter>
                    <Button variant="outline" class="cursor-pointer" @click="isDialogOpen = false; form.reset(); form.clearErrors();">Cancel</Button>
                    <Button class="cursor-pointer" @click="loading = !loading; submit()">Save</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
