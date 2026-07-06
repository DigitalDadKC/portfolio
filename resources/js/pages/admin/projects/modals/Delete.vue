<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Trash2 } from 'lucide-vue-next';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';

const props = defineProps({
    project: Object,
})

const isDialogOpen = ref(false)
const form = useForm({
    id: props.project.id,
    name: props.project.name,
})

const submit = () => {
    form.delete(route('projects.destroy', props.project.id), {
        preserveScroll: true,
        onSuccess: () => {
            isDialogOpen.value = false
        }
    })
}

</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button class="cursor-pointer">
                <Trash2></Trash2>
            </Button>
        </DialogTrigger>

        <DialogContent class="sm:max-w-[600px] grid-rows-[auto_minmax(0,1fr)_auto] max-h-[90dvh] bg-light-primary dark:bg-dark-primary">
            <DialogHeader class="p-6">
                <DialogTitle>{{ `Delete ${props.project.name} ?` }}</DialogTitle>
                <DialogDescription>
                    Are you sure?
                </DialogDescription>
            </DialogHeader>

            <DialogFooter class="p-6 pt-0">
                <div class="flex gap-2">
                    <Button variant="secondary" class="cursor-pointer" @click="isDialogOpen = false">Cancel</Button>
                    <Button type="submit" class="cursor-pointer" :disabled="form.processing" @click="submit()">Yes</Button>
                </div>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
