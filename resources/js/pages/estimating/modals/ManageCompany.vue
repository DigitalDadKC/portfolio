<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Cog } from 'lucide-vue-next';
import State from '../partials/State.vue';

const props = defineProps({
    company: Object,
    states: Object
})

const isDialogOpen = ref(false)
const form = useForm({
    id: props.company.id,
    name: props.company.name,
    address: props.company.address,
    city: props.company.city,
    state_id: props.company.state.id,
    zip: props.company.zip,
    logo: props.company.logo,
    terms: props.company.terms
})

const picture = computed(() => {
    return (form.logo.type?.startsWith('image')) ? URL.createObjectURL(form.logo) : form.logo
})

const handleImage = (e) => {
    form.logo = e.target.files[0]
}

const submit = () => {
    form.post(route('companies.update', form.id), {
        _method: 'put',
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
                <Cog></Cog>
            </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[800px] grid-rows-[auto_minmax(0,1fr)_auto] p-0 max-h-[90dvh] bg-light-primary dark:bg-dark-primary">
            <DialogHeader class="p-6 pb-0">
                <DialogTitle>Manage Company</DialogTitle>
                <DialogDescription>Edit Company Details</DialogDescription>
            </DialogHeader>

            <div class="grid grid-cols-4 gap-4 py-4 overflow-y-auto px-6">
                <div class="col-span-2">
                    <Label for="name">Name</Label>
                    <Input id="name" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" width="full" v-model.number="form.name" />
                </div>
                <div class="col-span-4">
                    <div v-if="form.errors.name" class="text-red-500">{{ form.errors.name }}</div>
                    <div v-if="form.errors.customer_id" class="text-red-500">{{ form.errors.customer_id }}</div>
                    <div v-if="form.errors.start_date" class="text-red-500">{{ form.errors.start_date }}</div>
                </div>
                <div class="col-span-4">
                    <Label for="address">Address</Label>
                    <Input id="address" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.address" />
                </div>
                <div class="col-span-4">
                    <div v-if="form.errors.address" class="text-red-500">{{ form.errors.address }}</div>
                </div>
                <div class="col-span-2">
                    <Label for="city">City</Label>
                    <Input id="city" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.city" />
                </div>
                <div class="col-span-1">
                    <State v-model="form.state_id" :states></State>
                </div>
                <div class="col-span-1">
                    <Label for="zip">Zip</Label>
                    <Input id="zip" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.zip" />
                </div>
                <div class="col-span-4">
                    <div v-if="form.errors.city" class="text-red-500">{{ form.errors.city }}</div>
                    <div v-if="form.errors.state_id" class="text-red-500">{{ form.errors.state_id }}</div>
                    <div v-if="form.errors.zip" class="text-red-500">{{ form.errors.zip }}</div>
                </div>
                <div class="col-span-4">
                    <Label for="terms">Terms & Conditions</Label>
                    <Textarea id="terms" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.terms" />
                </div>
                <div class="col-span-4">
                    <Label for="image">Image</Label>
                    <Input id="image" type="file" class="bg-white dark:bg-dark-tertiary hover:bg-input/50" @input="handleImage($event)" />
                </div>
                <div class="col-span-4">
                    <img :src="picture" class="w-full" />
                </div>
            </div>
            
            <DialogFooter class="flex gap-2 p-6">
                <Button class="cursor-pointer" variant="outline" @click="isDialogOpen = false; form.reset(); form.clearErrors();">Cancel</Button>
                <Button class="cursor-pointer" type="submit" :disabled="form.processing" @click="submit()">Save</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
