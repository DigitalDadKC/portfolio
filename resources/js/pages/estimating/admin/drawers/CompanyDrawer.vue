<script setup lang="ts">
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import FormattedInput from "@/components/FormattedInput.vue";
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import State from "../../jobs/partials/State.vue";
import { Cog } from 'lucide-vue-next';
import { Sheet, SheetContent, SheetDescription, SheetHeader, SheetFooter, SheetTitle, SheetTrigger } from '@/components/ui/sheet';

const props = defineProps({
    company: Object,
    states: Object
})

const isOpen = ref(false)
const form = useForm({
    id: props.company.id,
    name: props.company.name,
    address: props.company.address,
    city: props.company.city,
    state_id: props.company.state?.id,
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
    form.post(route('companies.update', props.company.id), {
        _method: 'put',
        onSuccess: () => {
            isOpen.value = false
        }
    })
}
</script>

<template>
    <Sheet v-model:open="isOpen">
        <SheetTrigger>
            <Button class="cursor-pointer">
                <Cog></Cog>
            </Button>
        </SheetTrigger>
        <SheetContent class="min-w-[800px] bg-light-secondary dark:bg-dark-secondary">
            <SheetHeader>
                <SheetTitle>Manage Company Details</SheetTitle>
                <SheetDescription>
                    <div class="flex flex-col gap-12 py-4 px-6">
                        <div class="grid grid-cols-4 gap-2">
                            <div class="col-span-4">
                                <Label for="name">Company Name</Label>
                                <FormattedInput id="name" class="" width="full" v-model.number="form.name" />
                            </div>
                            <div class="col-span-4">
                                <div v-if="form.errors.name" class="text-red-500">{{ form.errors.name }}</div>
                                <div v-if="form.errors.customer_id" class="text-red-500">{{ form.errors.customer_id }}</div>
                                <div v-if="form.errors.start_date" class="text-red-500">{{ form.errors.start_date }}</div>
                            </div>
                            <div class="col-span-4">
                                <Label for="address">Address</Label>
                                <FormattedInput id="address" width="full" class="" v-model="form.address" />
                            </div>
                            <div class="col-span-4">
                                <div v-if="form.errors.address" class="text-red-500">{{ form.errors.address }}</div>
                            </div>
                            <div class="col-span-2">
                                <Label for="city">City</Label>
                                <FormattedInput id="city" width="full" class="" v-model="form.city" />
                            </div>
                            <div class="col-span-1">
                                <Label for="state">State</Label>
                                <State v-model="form.state_id" :states></State>
                            </div>
                            <div class="col-span-1">
                                <Label for="zip">Zip</Label>
                                <FormattedInput id="zip" width="full" class="" v-model="form.zip" />
                            </div>
                            <div class="col-span-4">
                                <div v-if="form.errors.city" class="text-red-500">{{ form.errors.city }}</div>
                                <div v-if="form.errors.state_id" class="text-red-500">{{ form.errors.state_id }}</div>
                                <div v-if="form.errors.zip" class="text-red-500">{{ form.errors.zip }}</div>
                            </div>
                        </div>
                        <div>
                            <div class="col-span-4">
                                <Label for="terms">Terms & Conditions</Label>
                                <Textarea id="terms" class="bg-white" v-model="form.terms" />
                            </div>
                        </div>
                        <div>
                            <div class="col-span-4 mb-2">
                                <Label for="image">Image</Label>
                                <Input id="image" type="file" width="36" class="bg-white w-60" @input="handleImage($event)" />
                            </div>
                            <div class="col-span-4">
                                <img :src="picture" class="w-full" />
                            </div>
                        </div>
                    </div>
                </SheetDescription>
            </SheetHeader>
            <SheetFooter>
                <Button class="cursor-pointer" variant="outline" @click="isOpen = false; form.reset(); form.clearErrors();">Cancel</Button>
                <Button class="cursor-pointer" type="submit" :disabled="form.processing" @click="submit()">Save</Button>
            </SheetFooter>
        </SheetContent>
    </Sheet>
</template>
