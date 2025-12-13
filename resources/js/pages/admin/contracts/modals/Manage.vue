<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import FormattedInput from '@/components/FormattedInput.vue';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import Client from '../partials/Client.vue';
import Services from '../partials/Services.vue';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger, DialogDescription } from '@/components/ui/dialog';
import { Pencil, Plus } from 'lucide-vue-next';

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
    client_id: props.contract?.client_id,
})

const submit = () => {
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

</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button class="cursor-pointer" v-if="props.new">
                <Plus></Plus>
                New Contract
            </Button>
            <Button class="cursor-pointer" v-else>
                <Pencil class="cursor-pointer"></Pencil>
            </Button>
        </DialogTrigger>

        <DialogContent
            class="sm:max-w-[600px] grid-rows-[auto_minmax(0,1fr)_auto] max-h-[90dvh] bg-light-primary dark:bg-dark-primary">
            <DialogHeader class="p-2">
                <DialogTitle>{{ (props.new) ? 'New Contract' : 'Edit Contract' }}</DialogTitle>
                <DialogDescription></DialogDescription>
            </DialogHeader>

            <div class="flex flex-col gap-4">
                <Label for="title">Price</Label>
                <FormattedInput type="currency" class="w-full" v-model="form.price" />
            </div>

            <div class="flex flex-col gap-4">
                <Label for="description">Client</Label>
                <Client :clients v-model="form.client_id" />
            </div>

            <div class="flex flex-col gap-4">
                <Label for="description">Services</Label>
                <Services :clients v-model="form.client_id" />
            </div>

            <DialogFooter class="p-6 pt-0">
                <div class="flex gap-2">
                    <Button variant="outline" class="cursor-pointer" @click="isDialogOpen = false">Cancel</Button>
                    <Button type="submit" class="cursor-pointer" :disabled="form.processing"
                        @click="submit()">Save</Button>
                </div>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
