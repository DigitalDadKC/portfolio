<script setup lang="ts">
import { ref, watchEffect } from "vue";
import { useForm } from "@inertiajs/vue3";
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Mail, User, Phone } from 'lucide-vue-next';
import { Sheet, SheetContent, SheetDescription, SheetHeader, SheetFooter, SheetTitle, SheetTrigger } from '@/components/ui/sheet';

const props = defineProps({
    client: Object,
})

const isOpen = ref(false)
const form = useForm({
    id: props.client.id,
    employees: props.client.employees
})

const submit = () => {
    form.post(route('employees.update', props.client.id), {
        onSuccess: () => {
            isOpen.value = false
        }
    })
}

const add = () => {
    form.employees.push({
        id: null,
        name: '',
        email: null,
        phone: null,
    })
}

// watchEffect(() => {
//     Object.assign(form, props.client.employees)
// })
</script>

<template>
    <Sheet v-model:open="isOpen">
        <SheetTrigger>
            <Button class="cursor-pointer">
                <User></User>
            </Button>
        </SheetTrigger>
        <SheetContent class="min-w-[400px] bg-light-secondary dark:bg-dark-secondary">
            <SheetHeader>
                <SheetTitle>Employees</SheetTitle>
                <SheetDescription>
                    <Button @click="add()">Add Employee</Button>
                </SheetDescription>
            </SheetHeader>

            <div class="space-y-8 p-4 overflow-y-auto">
                <div class="col-span-4" v-for="employee in form.employees" :key="employee.id">
                    <div class="flex items-center gap-2">
                        <User></User>
                        <Input id="name" class="bg-white" width="full" v-model="employee.name" />
                    </div>
                    <div class="flex items-center gap-2">
                        <Mail></Mail>
                        <Input id="name" class="bg-white" width="full" v-model="employee.email" />
                    </div>
                    <div class="flex items-center gap-2">
                        <Phone></Phone>
                        <Input id="name" class="bg-white" width="full" v-model="employee.phone" />
                    </div>
                </div>
            </div>

            <SheetFooter>
                <Button class="cursor-pointer" variant="outline" @click="isOpen = false; form.reset(); form.clearErrors();">Cancel</Button>
                <Button class="cursor-pointer" type="submit" :disabled="form.processing" @click="submit()">Save</Button>
            </SheetFooter>
        </SheetContent>
    </Sheet>
</template>
