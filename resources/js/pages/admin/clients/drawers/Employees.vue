<script setup lang="ts">
import { ref, watchEffect } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Mail, User, Phone } from 'lucide-vue-next';
import Employee from "../partials/Employee.vue";
import { Sheet, SheetContent, SheetDescription, SheetHeader, SheetFooter, SheetTitle, SheetTrigger } from '@/components/ui/sheet';

const props = defineProps({
    client: Object,
})

const isOpen = ref(false)

const form = useForm({
    id: null,
    name: '',
    email: null,
    phone: null,
    client_id: props.client.id,
})

const add = () => {
    form.post(route('employees.store'))
}

// watchEffect(() => {
//     Object.assign(form, props.client.employees)
// })
</script>

<template>
    <Sheet v-model:open="isOpen">
        <SheetTrigger>
            <Button class="cursor-pointer">
                Employees
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
                <div class="col-span-4" v-for="employee in props.client.employees" :key="employee.id">
                    <Employee :employee />
                </div>
            </div>

            <SheetFooter>
            </SheetFooter>
        </SheetContent>
    </Sheet>
</template>
