<script setup>
import { ref, watchEffect } from "vue";
import { useForm, Link } from "@inertiajs/vue3";
import { Input } from "@/components/ui/input";
import { Label } from 'reka-ui';
import { Button } from '@/components/ui/button';
import State from "../partials/State.vue";
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog'
import { Pencil } from "lucide-vue-next";
import { useDateFormat } from '@vueuse/core';

const props = defineProps({
    customer: Object,
    states: Object,
    new: Boolean,
});

const isDialogOpen = ref(false)
const form = useForm({
    id: props.customer.id,
    name: props.customer.name,
    state_id: props.customer.state.id,
});

const submit = () => {
    if (props.new) {
        form.post(route("customers.store"), {
            onSuccess: () => {
                dialog.value = false;
                form.reset()
            },
        });
    } else {
        form.patch(route("customers.update", { customer: form.id }), {
            onSuccess: () => {
                dialog.value = false;
            },
            preserveState: "errors",
        });
    }
};

watchEffect(() => {
    Object.assign(form, props.customer)
    form.state_id = props.customer.state.id
})

</script>

<template>
        <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button class="cursor-pointer" v-if="props.new">Add Customer</Button>
            <Pencil class="cursor-pointer" v-else />
        </DialogTrigger>
        <DialogContent class=" overflow-auto">
            <DialogHeader>
                <DialogTitle>{{ `${(props.new) ? 'New Customer' : `Edit ${props.customer.name}`}` }}</DialogTitle>
                <DialogDescription>
                    <div class="grid grid-cols-4 gap-2">
                        <div class="col-span-4">
                            <Label for="customer">Name</Label>
                            <Input v-model="form.name" width="full" />
                        </div>
                        <div class="col-span-4">
                            <State :states v-model="form.state_id"></State>
                        </div>
                        <div class="col-span-4">
                            <h2 class="text-lg text-end">Jobs</h2>
                            <div v-for="job in props.customer.jobs" :key="job.id" class="flex justify-end">
                                <Link :href="route('estimating.edit', job.id)">Job #{{ job.number }} - {{ job.city }}, {{ job.state.abbr }}</Link>
                                <!-- Job #{{ job.number }} - {{ useDateFormat(job.start_date, 'MMM D, YYYY') }} {{ job.city }} - {{ job.state.state }} -->
                            </div>
                        </div>
                    </div>
                </DialogDescription>
            </DialogHeader>

            <div v-for="(error, index) in form.errors" :key="index">
                <p class="text-red-500">
                    {{ error }}
                </p>
            </div>

            <DialogFooter>
                <Button class="cursor-pointer" variant="outline" @click="isDialogOpen = false; form.reset(); form.clearErrors();">Cancel</Button>
                <Button class="cursor-pointer" @click="loading = !loading; submit()">Save</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>


    <!-- <v-dialog v-model="dialog" max-width="600">
        <template v-slot:activator="{ props: activatorProps }">
            <v-icon v-bind="activatorProps" size="52" v-if="props.new">mdi-plus-box</v-icon>
            <v-icon v-bind="activatorProps" size="32" v-else>mdi-pencil</v-icon>
        </template>

        <v-card :title="props.new ? 'New Customer' : `Edit ${props.customer.name ?? ''}`">
            <v-card-text>
                <v-row>
                    <v-col cols="12">
                        <v-text-field density="compact" v-model="form.name" label="Name"></v-text-field>
                        <h1 v-if="form.errors.name" class="text-red-500">{{ form.errors.name }}</h1>
                    </v-col>
                    <v-col cols="12">
                        <v-autocomplete density="compact" v-model="form.state_id" :items="props.states" label="State"item-title="state" item-value="id" clearable></v-autocomplete>
                    <h1 v-if="form.errors.state_id" class="text-red-500">{{ form.errors.state_id }}</h1>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12">
                        <v-card title="Jobs" subtitle="click on a job">
                            <v-card-text v-for="job in props.customer.jobs" :key="job.id">
                                <Link :href="route('estimating.edit', job.id)" prefetch>
                                    {{job.number}} - ({{ job.city }}, {{ job.state.abbr }})
                                </Link>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-card-text>

            <v-card-actions>
                <SecondaryButton text="Close" variant="plain" @click=" dialog = false; form.reset(); form.clearErrors();">Cancel</SecondaryButton>
                <PrimaryButton color="primary" text="Save" @click="submit()">Save</PrimaryButton>
            </v-card-actions>
        </v-card>
    </v-dialog> -->
</template>
