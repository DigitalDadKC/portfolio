<script setup lang="ts">
import { ref, computed, watchEffect } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Label } from 'reka-ui';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import Customer from '../modals/Customer.vue';
import CreatedDate from '../modals/CreatedDate.vue';
import DueDate from '../modals/DueDate.vue';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog'
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import FormattedInput from '@/components/FormattedInput.vue';
import { useFormatCurrency } from '@/composables/useFormatCurrency';
import { useDateFormat } from '@vueuse/core';
import { Pencil, Trash2 } from 'lucide-vue-next';

const { formatWithCommas } = useFormatCurrency();
const props = defineProps({
    new: Boolean,
    invoice: Object,
    customers: Object,
    materials: Object,
})

const isDialogOpen = ref(false)
const form = useForm({
    id: props.invoice.id,
    number: props.invoice.number,
    customer_id: props.invoice.customer?.id,
    date_created: props.invoice.date_created ?? new Date(),
    due_date: props.invoice.due_date,
    reference: props.invoice.reference,
    terms_and_conditions: props.invoice.terms_and_conditions,
    discount: props.invoice.discount,
    invoice_items: props.invoice.invoice_items ?? []
})

const customer = computed(() => {
    return props.customers.find(customer => customer.id == form.customer_id) ?? null
})

const submit = () => {
    form.transform((data) => ({
        ...data,
        date_created: (form.date_created) ? useDateFormat(form.date_created, 'YYYY-MM-DD').value : null,
        due_date: (form.due_date) ? useDateFormat(form.due_date, 'YYYY-MM-DD').value : null,
        invoice_items: form.invoice_items.map(item => ({
            id: item.id,
            invoice_id: form.id,
            material_id: item.material.id,
            quantity: item.quantity,
            unit_price: item.unit_price
        }))
    }))
    if (props.new) {
        return form.post(route('invoices.store'), {
            onSuccess: () => {
                isDialogOpen.value = false
                form.reset()
                form.clearErrors()
            }
        })
    } else {
        form.put(route('invoices.update', form.id), {
            preserveScroll: true,
            onSuccess: () => {
                isDialogOpen.value = false
                form.clearErrors()
            }
        })
    }
}

const update = (data, i) => {
    form.invoice_items[i].material = props.materials.find(material => material.id === data.material.id)
}

const subtotal = computed(() => {
    return form.invoice_items.reduce((acc, item) => acc + (item.unit_price * item.quantity), 0);
});

const addItem = () => {
    form.invoice_items.push({
        material: {
            id: null,
            sku: '',
            name: ''
        },
        unit_price: 0,
        quantity: 1
    });
}

watchEffect(() => {
    Object.assign(form, props.invoice)
})

</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button class="cursor-pointer" v-if="props.new">Add Invoice</Button>
            <Pencil class="cursor-pointer" v-else />
        </DialogTrigger>
        <DialogContent class="sm:max-w-[1200px] h-[70dvh] overflow-auto">
            <DialogHeader>
                <DialogTitle>{{ `${(props.new) ? 'New Invoice' : `Edit Invoice #${props.invoice.number}`}` }}
                </DialogTitle>
                <DialogDescription>
                    <div class="grid grid-cols-4 gap-2">
                        <div>
                            <Label for="customer">Number</Label>
                            <FormattedInput v-model="form.number" width="full" />
                        </div>
                        <div>
                            <Label for="customer">Reference</Label>
                            <FormattedInput v-model="form.reference" width="full" />
                        </div>
                        <div>
                            <Customer :customers v-model="form.customer_id"></Customer>
                            <div v-if="customer">
                                <p>{{ customer?.address }}</p>
                                <p>{{ customer?.city }} {{ customer?.state.abbr }}, {{ customer?.zip }}</p>
                                <p>Email: {{ customer?.email }}</p>
                                <p>Phone: {{ customer?.phone }}</p>
                            </div>
                        </div>
                        <div>
                            <CreatedDate v-model="form.date_created"></CreatedDate>
                            <DueDate v-model="form.due_date"></DueDate>
                        </div>
                    </div>
                    <div>
                        <Table>
                            <TableCaption>A list of your recent invoices.</TableCaption>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>SKU</TableHead>
                                    <TableHead>Material</TableHead>
                                    <TableHead>Price</TableHead>
                                    <TableHead>Quantity</TableHead>
                                    <TableHead>Total</TableHead>
                                    <TableHead>
                                        <Button class="cursor-pointer" @click="addItem()">Add Item</Button>
                                    </TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="(item, index) in form.invoice_items" :key="index">
                                    <TableCell class="font-medium">{{ item.material.sku }}</TableCell>
                                    <TableCell>
                                        <Select id="customer" onValueChange="{setSelectedValue}" value="{selectedValue}"
                                            v-model="item.material.id" @update:model-value="update(item, index)">
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select material" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectGroup>
                                                    <SelectLabel>Materials</SelectLabel>
                                                    <SelectItem v-for="material in props.materials" :key="material.id"
                                                        :value="material.id">
                                                        {{ material.name }}
                                                    </SelectItem>
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>
                                    </TableCell>
                                    <TableCell>
                                        <FormattedInput v-model="item.unit_price" type="currency" />
                                    </TableCell>
                                    <TableCell>
                                        <FormattedInput v-model="item.quantity" type="number" />
                                    </TableCell>
                                    <TableCell class="text-right">
                                        {{ formatWithCommas(item.unit_price * item.quantity, 'currency') }}
                                    </TableCell>
                                    <TableCell class="text-right">
                                        <Trash2 class="cursor-pointer text-red-500" @click="form.invoice_items.splice(index, 1)"></Trash2>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                    <div class="flex flex-col">
                        <Label>Terms & Conditions</Label>
                        <Textarea v-model="form.terms_and_conditions" rows="10" />
                    </div>
                </DialogDescription>
            </DialogHeader>

            <div v-for="(error, index) in form.errors" :key="index">
                <p class="text-red-500">
                    {{ error }}
                </p>
            </div>

            <div class="flex flex-col items-end gap-4">
                Subtotal: {{ formatWithCommas(subtotal, 'currency') }}
                <div class="flex flex-col justify-start items-start">
                    <Label for="discount">Discount</Label>
                    <FormattedInput id="discount" width="full" v-model="form.discount" type="percent" />
                </div>
                Total: {{ formatWithCommas(subtotal * (1 - form.discount / 100), 'currency') }}
            </div>

            <DialogFooter>
                <Button class="cursor-pointer" variant="outline" @click="isDialogOpen = false; form.reset(); form.clearErrors();">Cancel</Button>
                <Button class="cursor-pointer" @click="loading = !loading; submit()">Save</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
