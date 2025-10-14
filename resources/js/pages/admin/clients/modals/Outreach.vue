<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from 'reka-ui';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog'
import { Mail, Pencil, Plus } from 'lucide-vue-next';

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

const sendMail = () => {
    form.post(route('outreach'), {
        onSuccess: () => {
            isDialogOpen.value = false
            form.reset()
        }
    })
}

watch(() => props.client,
(item) => {
    form.id = item.id;
    form.name = item.name;
    form.company = item.company;
    form.email = item.email;
}, {
    deep: true,
})

</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button class="cursor-pointer">
                <Mail class="cursor-pointer"></Mail>
            </Button>
        </DialogTrigger>
        <DialogContent class="m:max-w-[600px] grid-rows-[auto_minmax(0,1fr)_auto] max-h-[90dvh] bg-light-primary dark:bg-dark-primary">
            <DialogHeader>
                <DialogTitle>Send outreach</DialogTitle>
                <DialogDescription>{{ props.client.company }}</DialogDescription>

                {{ props.client.email }}

            </DialogHeader>
            <DialogFooter>
                    <Button variant="outline" class="cursor-pointer" @click="isDialogOpen = false; form.reset(); form.clearErrors();">Cancel</Button>
                    <Button class="cursor-pointer" @click="sendMail()">Send</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
