<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Mail, User, Phone, Trash2 } from 'lucide-vue-next';

const props = defineProps({
    employee: Object,
})

const form = useForm({
    name: props.employee.name,
    email: props.employee.email,
    phone: props.employee.phone,
    client_id: props.employee.client_id
})

const update = () => {
    form.patch(route('employees.update', props.employee.id))
}

const destroy = () => {
    router.delete(route('employees.destroy', props.employee.id))
}

</script>

<template>
    <div>
        <div class="flex items-center gap-2">
            <User />
            <Input id="name" class="bg-white" width="full" v-model="form.name" @blur="update()" />
            <Button @click="destroy()">
                <Trash2 />
            </Button>
        </div>
        <div class="flex items-center gap-2">
            <Mail></Mail>
            <Input id="name" class="bg-white" width="full" v-model="form.email" @blur="update()" />
        </div>
        <div class="flex items-center gap-2">
            <Phone></Phone>
            <Input id="name" class="bg-white" width="full" v-model="form.phone" @blur="update()" />
        </div>

        <div v-for="error in form.errors" :key="error.id" class="text-red-500">
            {{ error }}
        </div>
    </div>
</template>
