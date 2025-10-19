<script setup>
import { ref, watchEffect } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Cog } from 'lucide-vue-next';
import Roles from '../partials/Roles.vue';

const props = defineProps({
    user: Object,
    roles: Object
})

const isDialogOpen = ref(false)
const form = useForm({
    user: props.user
})

const submit = () => {
    form.patch(route('users.update', form.user.id), {
        onSuccess: () => {
            isDialogOpen.value = false
        }
    })
}

watchEffect(() => {
    Object.assign(form, props.user)
})

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
                    <Input id="name" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" width="full" v-model="form.name" />
                </div>
                <div class="col-span-4">
                    <div v-if="form.errors.name" class="text-red-500">{{ form.errors.name }}</div>
                </div>
                <div class="col-span-4">
                    <Roles v-model="form.user.roles" :user :roles></Roles>
                </div>
            </div>
            <DialogFooter class="flex gap-2 p-6">
                <Button class="cursor-pointer" variant="outline" @click="isDialogOpen = false; form.reset(); form.clearErrors();">Cancel</Button>
                <Button class="cursor-pointer" type="submit" :disabled="form.processing" @click="submit()">Save</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>


    <!-- <div class="px-2 py-1 text-center">
        <v-dialog v-model="dialog" max-width="800">
            <template v-slot:activator="{ props: activatorProps }">
                <PrimaryButton v-bind="activatorProps" >
                    <v-icon size="x-small">mdi-cog</v-icon>
                </PrimaryButton>
            </template>

            <v-card prepend-icon="mdi-cog" :title="`Edit User: ${form.user.name}`">
                <v-card-text>
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field :disabled="true" density="compact" label="Name" v-model="form.user.name"></v-text-field>
                        </v-col>
                        <v-col cols="12" md="8">
                            <v-select density="compact" v-model="form.user.roles" :items="props.roles" label="Roles" item-title="name" item-value="id" auto-select-first multiple chips clearable return-object></v-select>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions>
                    <v-btn text="Close" variant="plain" @click="dialog = false; form.reset(); form.clearErrors()"></v-btn>
                    <v-btn color="primary" text="Save" @click="loading = !loading; submit()"></v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div> -->
</template>
