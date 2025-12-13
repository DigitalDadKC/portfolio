<script setup lang="ts">
import { ref, watchEffect } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog'
import { ReceiptText } from 'lucide-vue-next';

const props = defineProps({
    contract: Object,
})

const isDialogOpen = ref(false)
const form = useForm({
    id: props.contract?.id,
    client: props.contract?.client.company,
    price: props.contract?.price,
    services: props.contract?.services,
})

const file = ref(null)
const send = () => {
    router.post(route('contracts.send'), {
        document: file.value
    })
}

watchEffect(() => {
    Object.assign(form, props.contract)
})

</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button class="cursor-pointer">
                <ReceiptText class="cursor-pointer"></ReceiptText>
            </Button>
        </DialogTrigger>

        <DialogContent class="m:max-w-[600px] grid-rows-[auto_minmax(0,1fr)_auto] max-h-[90dvh] bg-light-primary dark:bg-dark-primary">
            <DialogHeader>
                <DialogTitle>Send Contract</DialogTitle>
                <DialogDescription>{{ form }}</DialogDescription>

            </DialogHeader>
            <DialogFooter>
                    <Button variant="outline" class="cursor-pointer" @click="isDialogOpen = false; form.reset(); form.clearErrors();">Cancel</Button>
                    <Button class="cursor-pointer" @click="send()">Send</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
