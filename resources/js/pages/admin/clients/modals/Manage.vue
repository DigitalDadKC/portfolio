<script setup lang="ts">
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from 'reka-ui';
import { Button } from '@/components/ui/button';
import State from '@/pages/estimating/jobs/partials/State.vue';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog'
import { Pencil, Plus } from 'lucide-vue-next';
import axios from 'axios';

const props = defineProps({
    new: Boolean,
    client: Object,
    states: Object,
    places: Object,
})

const isDialogOpen = ref(false)
const form = useForm({
    id: props.client.id,
    company: props.client.company,
    email: props.client.email,
    address: props.client.address,
    city: props.client.city,
    state_id: props.client.state?.id,
    placeId: props.client.placeId,
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

const search = ref('')

const searchPlaces = () => {
    router.reload({
        data: {
            search: search.value,
        },
        only: ['places'],
        replace: true,
        preserveState: true,
        onSuccess: () => {
            console.log(props.places)
        }
    })
}

// const handle = (e) => {
//     console.log(e)
//     router.post(route('clients.selectPlace', {
//         data: e.placePrediction.placeId
//     }, {
//         onSuccess: () => {
//             console.log('idiot')
//             // test()
//         }
//     }))
// }


const handle = (e) => {
    console.log(e)
    let response = axios.post(`/api/test`).then(response => {console.log(response.data);}).catch(error => {console.log(error);})
    console.log('response', response)
    // invoices.value = response.data.invoices
}

</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button class="cursor-pointer" v-if="props.new">
                <Plus></Plus>
                New Client
            </Button>
            <Button class="cursor-pointer" v-else>
                <Pencil class="cursor-pointer"></Pencil>
            </Button>
        </DialogTrigger>
        <DialogContent class="m:max-w-[800px] grid-rows-[auto_minmax(0,1fr)_auto] max-h-[90dvh] bg-light-primary dark:bg-dark-primary">
            <DialogHeader>
                <DialogTitle>{{ `${(props.new) ? 'New Client' : `Edit Client #${props.client.name}`}` }}</DialogTitle>
                <DialogDescription></DialogDescription>



                <div class="flex flex-col gap-4">
                    <div>
                        <Label for="company">Company</Label>
                        <Input id="company" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.company" />
                    </div>
                    <div>
                        <Label for="email">Email</Label>
                        <Input id="email" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.email" />
                    </div>
                </div>


                <input v-model="search" @input="searchPlaces()" />

                <div v-for="result in props.places.suggestions">
                    <span @click="handle(result)" class="hover:bg-blue-200 cursor-pointer p-1">
                        {{ result.placePrediction.text.text }}
                    </span>
                </div>

                <div>
                    <Input v-model="form.address" :disabled="true" />
                </div>
                <div>
                    <Input v-model="form.city" :disabled="true" />
                </div>
                <div>
                    <State :states v-model="form.state_id" :disabled="true" />
                </div>

                <div v-for="error in form.errors" :key="error.id" class="text-red-500">
                    {{ error }}
                </div>

            </DialogHeader>
            <DialogFooter>
                    <Button variant="outline" class="cursor-pointer" @click="isDialogOpen = false; form.reset(); form.clearErrors();">Cancel</Button>
                    <Button class="cursor-pointer" @click="loading = !loading; submit()">Save</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
