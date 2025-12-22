<script setup lang="ts">
import { ref, watchEffect } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog'
import { Send } from 'lucide-vue-next';
import { useFormatCurrency } from '@/composables/useFormatCurrency';

const props = defineProps({
    contract: Object,
})

const { formatWithCommas } = useFormatCurrency();
const isDialogOpen = ref(false)
const form = useForm({
    id: props.contract?.id,
})

const send = () => {
    form.post(route('contracts.send', props.contract.id), {
        onSuccess: () => {
            isDialogOpen.value = false
        }
    })
}

watchEffect(() => {
    Object.assign(form, props.contract)
})

</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button>Send
                <Send></Send>
            </Button>
        </DialogTrigger>

        <DialogContent class="m:max-w-[600px] grid-rows-[auto_minmax(0,1fr)_auto] max-h-[90dvh] bg-light-primary dark:bg-dark-primary">
            <DialogHeader>
                <DialogTitle>Send Contract</DialogTitle>
                <DialogDescription>Client: {{ props.contract.client.company }}</DialogDescription>
            </DialogHeader>

            <h2 class="text-lg">Send a contract for {{ formatWithCommas(props.contract.price, 'currency') }}?</h2>

            <div>
                <h3 class="text-lg font-bold underline">Recipient</h3>
                <p>{{ props.contract.employee.name }}</p>
            </div>

            <div>
                <h3 class="text-lg font-bold underline">Services Included</h3>
                <div v-for="service in props.contract.services" :key="service.id">
                    <p>{{ service.name }}</p>
                </div>
            </div>

            <DialogFooter>
                    <Button variant="outline" class="cursor-pointer" @click="isDialogOpen = false; form.reset(); form.clearErrors();">Cancel</Button>
                    <Button class="cursor-pointer" @click="send()">Send</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
