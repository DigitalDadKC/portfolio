<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger, DialogDescription } from '@/components/ui/dialog';
import { Pencil, Plus } from 'lucide-vue-next';

const props = defineProps({
    new: Boolean,
    contract: Object,
})

const isDialogOpen = ref(false)
const form = useForm({
    id: props.contract?.id,
    name: props.contract?.name
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
            <DialogHeader class="p-6">
                <DialogTitle>{{ (props.new) ? 'New Contract' : 'Edit Contract' }}</DialogTitle>
                                <DialogDescription></DialogDescription>

            </DialogHeader>

            <div class="flex flex-col gap-4">
                <Label for="name">Name</Label>
                <Input id="name" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50"
                    v-model="form.name" />
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
