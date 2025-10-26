<script setup lang="ts">
import { ref, watchEffect } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import Input from '@/components/FormattedInput.vue';
import { Label } from 'reka-ui';
import { Button } from '@/components/ui/button';
import Clients from '../partials/Clients.vue';
import Line from '../partials/Line.vue';
import InvoiceDate from '../partials/InvoiceDate.vue';
import { Textarea } from '@/components/ui/textarea';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog'
import { Send } from 'lucide-vue-next';
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import { useFormatCurrency } from '@/composables/useFormatCurrency';

const props = defineProps({
    new: Boolean,
    invoice: Object,
})

const { formatWithCommas } = useFormatCurrency();
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
    router.get(route('admin.invoices.send', props.invoice.id))
}

watchEffect(() => {
    Object.assign(form, props.invoice)
    form.client_id = props.invoice.client.id
    form.line_items = props.invoice.client_invoice_items
})
</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button>
                <Send></Send>
            </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[1000px] grid-rows-[auto_minmax(0,1fr)_auto] max-h-[90dvh] bg-light-primary dark:bg-dark-primary">
            <DialogHeader>
                <DialogTitle>Invoice #{{ props.invoice.number }}</DialogTitle>
                <DialogDescription>
                    <div class="flex justify-between items-center">
                        {{ props.invoice.client.company }}<br />{{ props.invoice.client.email }}
                        <ApplicationLogo :scrollbackground="false" />
                    </div>
                </DialogDescription>

                <div class="flex flex-col gap-20">
                    <div class="grid grid-cols-6 gap-4">
                        <div class="col-span-1">
                            <Label for="number">Number</Label>
                            <Input id="number" width="full" class="bg-white" v-model="form.number" :disabled="true" />
                        </div>
                        <div class="col-span-2">
                        </div>
                        <div class="col-span-1 col-start-5">
                            <Label for="date_created">Created</Label>
                            <InvoiceDate v-model="form.date_created" :disabled="true" />
                        </div>
                        <div class="col-span-1">
                            <Label for="due_date">Due Date</Label>
                            <InvoiceDate v-model="form.due_date" :disabled="true" />
                        </div>
                    </div>
                    <div>
                        <div v-for="(item, index) in form.line_items" :key="index" class="col-span-6">
                            <Line :item :index :disabled="true" />
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-end">
                            {{ form.line_items.length
                                ? `Total: ` + formatWithCommas(form.line_items.reduce((a, b) => a + (b.price*b.quantity), 0), 'currency')
                                : 'No line items added yet.' }}
                        </div>
                    </div>
                    <div>
                        <div class="col-span-6">
                            <Label for="terms_and_conditions">Terms & Conditions</Label>
                            <Textarea id="terms_and_conditions" class="bg-white" v-model="form.terms_and_conditions" :disabled="true" />
                        </div>
                    </div>
                </div>

                <div v-for="error in form.errors" :key="error.id" class="text-red-500">
                    {{ error }}
                </div>

            </DialogHeader>
            <DialogFooter>
                    <Button variant="outline" class="cursor-pointer" @click="isDialogOpen = false; form.reset(); form.clearErrors();">Cancel</Button>
                    <Button class="cursor-pointer" @click="loading = !loading; submit()">Send</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
