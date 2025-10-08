<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Input } from '@/Components/ui/input';
import { Label } from 'reka-ui';
import { Button } from '@/Components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog'
import { Pencil } from 'lucide-vue-next';

const props = defineProps({
    new: Boolean,
    client: Object,
})

const isDialogOpen = ref(false)
const form = useForm({
    id: props.client.id,
    name: props.client.name,
    company: props.client.company,
    email: props.client.email
})

const submit = () => {
    if(props.new) {
        form.post(route('clients.store'), {
            onSuccess: () => {
                isDialogOpen.value = false
                form.reset()
            }
        })
    } else {
        form.patch(route('clients.update', form.id), {
            onSuccess: () => {
                isDialogOpen.value = false
            }
        })
    }
}

    const sendMail = () => {
        form.post(route('outreach'), {
            onSuccess: () => {
                isDialogOpen.value = false
                form.reset()
            }
        })
    }

</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button v-if="props.new">New Client</Button>
            <Pencil class="cursor-pointer" v-else />
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>{{ `${(props.new) ? 'New Client' : `Edit Client #${props.client.name}`}` }}</DialogTitle>
                <DialogDescription>
                    <div class="">
                        <div>
                            <Label for="name">Name</Label>
                            <Input id="name" v-model="form.name" />
                        </div>
                        <div>
                            <Label for="company">Company</Label>
                            <Input id="company" v-model="form.company" />
                        </div>
                        <div v-if="$page.props.errors.company" class="text-red-400">{{ $page.props.errors.company }}</div>
                        <div>
                            <Label for="email">Email</Label>
                            <Input id="email" v-model="form.email" />
                        </div>
                        <div v-if="$page.props.errors.email" class="text-red-400">{{ $page.props.errors.email }}</div>
                    </div>
                </DialogDescription>
            </DialogHeader>
            <DialogFooter>
                    <Button @click="sendMail()">Email</Button>
                    <Button variant="secondary" @click="dialog = false; form.reset(); form.clearErrors();">Cancel</Button>
                    <Button @click="loading = !loading; submit()">Save</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
