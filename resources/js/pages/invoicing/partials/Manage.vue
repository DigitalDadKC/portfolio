<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { cn } from "@/lib/utils";
import { Label } from 'reka-ui';
import { Button } from '@/Components/ui/button';
import { Textarea } from '@/Components/ui/textarea';
import { Calendar } from "@/components/ui/calendar"
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover"
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog'
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import FormattedInput from '@/Components/FormattedInput.vue';
import { useFormatCurrency } from '@/Composables/useFormatCurrency';
import { useDateFormat } from '@vueuse/core';
import { Pencil, CalendarIcon, Trash2 } from 'lucide-vue-next';

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

</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button v-if="props.new">Add Invoice</Button>
            <Pencil class="cursor-pointer" v-else />
        </DialogTrigger>
        <DialogContent class="sm:max-w-[1200px] h-[70dvh] overflow-auto">
            <DialogHeader>
                <DialogTitle>{{ `${(props.new) ? 'New Invoice' : `Edit Invoice #${props.invoice.number}`}` }}
                </DialogTitle>
                <DialogDescription>
                    <div class="flex justify-between items-start">
                        <div>
                            <Label for="customer">Number</Label>
                            <FormattedInput v-model="form.number" />
                        </div>
                        <div>
                            <Label for="customer">Reference</Label>
                            <FormattedInput v-model="form.reference" />
                        </div>
                        <div>
                            <Label for="customer">Customer</Label>
                            <Select id="customer" onValueChange="{setSelectedValue}" value="{selectedValue}"
                                v-model="form.customer_id">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select customer" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectLabel>Materials</SelectLabel>
                                        <SelectItem v-for="customer in props.customers" :key="customer.id"
                                            :value="customer.id">
                                            {{ customer.name }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <div v-if="customer">
                                <p>{{ customer?.address }}</p>
                                <p>{{ customer?.city }} {{ customer?.state.abbr }}, {{ customer?.zip }}</p>
                                <p>Email: {{ customer?.email }}</p>
                                <p>Phone: {{ customer?.phone }}</p>
                            </div>
                        </div>
                        <div>
                            <div class="flex flex-col">
                                <Label for="rate">Date Created</Label>
                                <Popover>
                                    <PopoverTrigger as-child>
                                        <Button variant="outline" class="cursor-pointer" :class="cn(
                                            'w-[280px] justify-start text-left font-normal',
                                            !form.date_created && 'text-muted-foreground',
                                        )">
                                            <CalendarIcon class="mr-2 h-4 w-4" />
                                            {{ form.date_created ? useDateFormat(form.date_created, 'MMM DD, YYYY') :
                                                "Pick a date" }}
                                        </Button>
                                    </PopoverTrigger>
                                    <PopoverContent class="w-auto p-0">
                                        <Calendar v-model:selected="form.date_created" initial-focus
                                            @update:model-value="(v) => form.date_created = v?.toString()" />
                                    </PopoverContent>
                                </Popover>
                            </div>
                            <div class="flex flex-col">
                                <Label for="rate">Due Date</Label>
                                <Popover>
                                    <PopoverTrigger as-child>
                                        <Button variant="outline" class="cursor-pointer" :class="cn(
                                            'w-[280px] justify-start text-left font-normal',
                                            !form.due_date && 'text-muted-foreground',
                                        )">
                                            <CalendarIcon class="mr-2 h-4 w-4" />
                                            {{ form.due_date ? useDateFormat(form.due_date, 'MMM DD, YYYY') : "Pick a date" }}
                                        </Button>
                                    </PopoverTrigger>
                                    <PopoverContent class="w-auto p-0">
                                        <Calendar v-model:selected="form.due_date" initial-focus
                                            @update:model-value="(v) => form.due_date = v?.toString()" />
                                    </PopoverContent>
                                </Popover>
                            </div>
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
                                        <Button @click="addItem()">Add Item</Button>
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
                    <FormattedInput id="discount" v-model="form.discount" type="percent" />
                </div>
                Total: {{ formatWithCommas(subtotal * (1 - form.discount / 100), 'currency') }}
            </div>

            <DialogFooter>
                <Button variant="secondary" @click="dialog = false; form.reset(); form.clearErrors();">Cancel</Button>
                <Button @click="loading = !loading; submit()">Save</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
