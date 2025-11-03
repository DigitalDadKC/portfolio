<script setup>
import { ref, watchEffect } from "vue";
import { useForm, Link } from "@inertiajs/vue3";
import { Input } from "@/components/ui/input";
import { Label } from 'reka-ui';
import { Button } from '@/components/ui/button';
import State from "../../jobs/partials/State.vue";
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog'
import { Pencil } from "lucide-vue-next";

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
                isDialogOpen.value = false;
                form.reset()
            },
        });
    } else {
        form.patch(route("customers.update", { customer: form.id }), {
            onSuccess: () => {
                isDialogOpen.value = false;
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
            <Button class="cursor-pointer" v-else>
                <Pencil />
            </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[800px] grid-rows-[auto_minmax(0,1fr)_auto] max-h-[90dvh] bg-light-primary dark:bg-dark-primary overflow-auto">
            <DialogHeader>
                <DialogTitle>{{ `${(props.new) ? 'New Customer' : `Edit ${props.customer.name}`}` }}</DialogTitle>
                <DialogDescription>
                    <div class="grid grid-cols-4 gap-2">
                        <div class="col-span-3">
                            <Label for="customer">Name</Label>
                            <Input v-model="form.name" width="full" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" />
                        </div>
                        <div class="col-span-1">
                            <Label for="state">State</Label>
                            <State id="state" :states v-model="form.state_id"></State>
                        </div>
                        <div class="col-span-4" v-if="!props.new">
                            <h2 class="text-lg text-end">Jobs</h2>
                            <div v-for="job in props.customer.jobs" :key="job.id" class="flex justify-end">
                                <Link :href="('/estimating?customer=&pages=25&search=' + job.number)" class="hover:bg-light-tertiary hover:text-light-primary px-2 rounded-xs py-1">Job #{{ job.number }} - {{ job.city }}, {{ job.state.abbr }}</Link>
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
</template>
