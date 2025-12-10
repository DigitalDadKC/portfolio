<script setup lang="ts">
import { ref, watchEffect } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog'
import { ReceiptText } from 'lucide-vue-next';

const props = defineProps({
    client: Object,
})

const isDialogOpen = ref(false)
const form = useForm({
    id: props.client.id,
    name: props.client.name,
    company: props.client.company,
    email: props.client.email
})

// const sendMail = () => {
//     form.post(route('outreach'), {
//         onSuccess: () => {
//             isDialogOpen.value = false
//             form.reset()
//         }
//     })
// }

const file = ref(null)
const send = () => {
    router.post(route('clients.send'), {
        document: file.value
    })
}

watchEffect(() => {
    Object.assign(form, props.client)
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
                <DialogTitle>Send outreach</DialogTitle>
                <DialogDescription>{{ props.client.company }}</DialogDescription>

                {{ props.client.email }}


                <button class="py-2 px-4 bg-light-quatrenary rounded-md text-white cursor-pointer" @click="send()">Send</button>
                <input type="file" @change="file = $event.target.files[0]" />

            </DialogHeader>
            <DialogFooter>
                    <Button variant="outline" class="cursor-pointer" @click="isDialogOpen = false; form.reset(); form.clearErrors();">Cancel</Button>
                    <Button class="cursor-pointer" @click="sendMail()">Send</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
