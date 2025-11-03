<script setup lang="ts">
import { ref, watchEffect } from "vue";
import { useForm } from "@inertiajs/vue3";
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import FormattedInput from "@/components/FormattedInput.vue";
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import State from '../jobs/partials/State.vue';
import { Cog } from 'lucide-vue-next';
import Roles from '../partials/Roles.vue';
import { Sheet, SheetContent, SheetDescription, SheetHeader, SheetFooter, SheetTitle, SheetTrigger } from '@/components/ui/sheet';

const props = defineProps({
    user: Object,
    roles: Object
})

const isOpen = ref(false)
const form = useForm({
    user: props.user,
})

const submit = () => {
    form.patch(route('users.update', form.user.id), {
        onSuccess: () => {
            isOpen.value = false
        }
    })
}

watchEffect(() => {
    Object.assign(form, props.user)
})
</script>

<template>
    <Sheet v-model:open="isOpen">
        <SheetTrigger>
            <Button class="cursor-pointer">
                <Cog></Cog>
            </Button>
        </SheetTrigger>
        <SheetContent class="min-w-[400px] bg-light-secondary dark:bg-dark-secondary">
            <SheetHeader>
                <SheetTitle>Manage User Details</SheetTitle>
                <SheetDescription>
                    <div class="flex flex-col gap-12 py-4">
                        <div class="grid grid-cols-4 gap-4 py-4 overflow-y-auto px-6">
                            <div class="col-span-4">
                                <Label for="name">Name</Label>
                                <Input id="name" class="bg-white" width="full" v-model="form.name" />
                            </div>
                            <div class="col-span-4">
                                <div v-if="form.errors.name" class="text-red-500">{{ form.errors.name }}</div>
                            </div>
                            <div class="col-span-4">
                                <Roles v-model="form.user.roles" :user :roles></Roles>
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
