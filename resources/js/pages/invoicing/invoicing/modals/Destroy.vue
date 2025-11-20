<script setup lang="ts">
import { ref, watchEffect } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog'
import { Archive, Trash2 } from 'lucide-vue-next';

const props = defineProps({
    invoice: Object,
})

const isDialogOpen = ref(false)
const form = useForm({
    invoice: props.invoice,
})

const submit = () => {
    form.delete(route('invoices.destroy', props.invoice.id), {
        preserveScroll: true,
        onSuccess: () => {
            isDialogOpen.value = false
            form.clearErrors()
        }
    })
}

watchEffect(() => {
    Object.assign(form, props.invoice)
})

</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Archive class="cursor-pointer" />
        </DialogTrigger>
        <DialogContent class="sm:max-w-[600px] overflow-auto">
            <DialogHeader>
                <DialogTitle>{{ `Destroy Invoice #${props.invoice.number}` }}
                </DialogTitle>
                <DialogDescription>
                    Are you sure you want to archive this invoice? This action cannot be undone.
                </DialogDescription>
            </DialogHeader>

            <div v-for="(error, index) in form.errors" :key="index">
                <p class="text-red-500">
                    {{ error }}
                </p>
            </div>

            <DialogFooter>
                <Button variant="outline" @click="isDialogOpen = false; form.reset(); form.clearErrors();">Cancel</Button>
                <Button @click="loading = !loading; submit()">Archive</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
