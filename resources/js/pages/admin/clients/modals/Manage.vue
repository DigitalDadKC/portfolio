<script setup lang="ts">
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import FormattedInput from '@/components/FormattedInput.vue';
import { Label } from 'reka-ui';
import { Button } from '@/components/ui/button';
import State from '@/pages/estimating/jobs/partials/State.vue';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog'
import { Pencil, Plus, Search } from 'lucide-vue-next';

const props = defineProps({
    new: Boolean,
    client: Object,
    states: Object,
    place: Object,
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
    zip: props.client.zip,
    url: props.client.url,
})

const submit = () => {
    if (props.new) {
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
    })
}

const handle = (e) => {
            search.value = ''
    router.reload({
        data: {
            placeId: e.placePrediction.placeId,
        },
        only: ['place'],
        replace: true,
        onSuccess: () => {
            search.value = ''
            form.address = props.place.shortFormattedAddress ?? props.place.displayName.text
            form.city = props.place.shortFormattedAddress.split(',')[1]
            form.state_id = props.states.find(state => state.abbr == props.place.addressComponents.find(item => item.types.includes('administrative_area_level_1')).shortText).id
            form.zip = props.place.addressComponents.find(item => item.types.includes('postal_code'))?.shortText
            props.places.suggestions = []
        }
    })
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
        <DialogContent class="md:max-w-[800px] grid-rows-[auto_minmax(0,1fr)_auto] max-h-[90dvh] bg-light-primary dark:bg-dark-primary">
            <DialogHeader>
                <DialogTitle>{{ `${(props.new) ? 'New Client' : `Edit Client #${props.client.name}`}` }}</DialogTitle>
                <DialogDescription></DialogDescription>

                <div class="grid grid-cols-4 gap-4">
                    <div class="col-span-2">
                        <Label for="company">Company</Label>
                        <Input id="company"
                            class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50"
                            v-model="form.company" />
                    </div>
                    <div class="col-span-2">
                        <Label for="email">Email</Label>
                        <Input id="email" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50"
                            v-model="form.email" />
                    </div>

                    <div class="relative w-full items-center col-span-4">
                        <Label for="address">Search</Label>
                        <Input v-model="search" id="address" class="bg-white pl-10" placeholder="Search places..." @input="searchPlaces()" />
                        <span class="absolute start-0 inset-y-0 flex items-end mb-2 justify-center px-2">
                            <Search class="size-6 text-muted-foreground" />
                        </span>

                        <div class="bg-white space-y-2 absolute w-full z-10">
                            <div v-for="result in props.places?.suggestions" class="hover:bg-light-tertiary cursor-pointer" @click="handle(result)">
                                <span class="p-2 w-full">
                                    {{ result.placePrediction.text.text }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-4">
                        <Label for="address">Address</Label>
                        <FormattedInput v-model="form.address" id="address" width="full" :disabled="true" />
                    </div>
                    <div class="col-span-2">
                        <Label for="city">City</Label>
                        <FormattedInput v-model="form.city" id="city" width="full" :disabled="true" />
                    </div>
                    <div class="col-span-1">
                        <Label for="state">State</Label>
                        <State :states v-model="form.state_id" id="state" :disabled="true" />
                    </div>
                    <div class="col-span-1">
                        <Label for="zip">Zip</Label>
                        <FormattedInput v-model="form.zip" id="zip" width="full" :disabled="true" />
                    </div>

                    <div class="col-span-4">
                        <Label for="url">URL</Label>
                        <FormattedInput v-model="form.url" id="url" width="full" />
                    </div>
                </div>

                <div v-for="error in form.errors" :key="error.id" class="text-red-500">
                    {{ error }}
                </div>

            </DialogHeader>
            <DialogFooter>
                <Button variant="outline" class="cursor-pointer"
                    @click="isDialogOpen = false; form.reset(); form.clearErrors();">Cancel</Button>
                <Button class="cursor-pointer" @click="loading = !loading; submit()">Save</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
