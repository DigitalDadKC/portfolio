<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import FormattedInput from '@/components/FormattedInput.vue';
import { Label } from 'reka-ui';
import { Button } from '@/components/ui/button';
import State from '@/pages/estimating/jobs/partials/State.vue';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog'
import { Pencil, Plus, Search } from 'lucide-vue-next';
import { GoogleMap, AdvancedMarker } from 'vue3-google-map'

const props = defineProps({
    new: Boolean,
    client: Object,
    states: Object,
    place: Object,
    places: Object,
})

const isDialogOpen = ref(false)
const form = useForm({
    id: props.client?.id,
    company: props.client?.company,
    email: props.client?.email,
    address: props.client?.address,
    city: props.client?.city,
    state_id: props.client?.state?.id,
    zip: props.client?.zip,
    placeId: props.client?.placeId,
    latitude: props.client?.latitude,
    longitude: props.client?.longitude,
    url: props.client?.url,
})


const center = computed(() => {
    return {
        lat: form.latitude ?? 40.689247,
        lng: form.longitude ?? -74.044502
    }
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
            form.placeId = e.placePrediction.placeId
            form.latitude = props.place.location.latitude,
            form.longitude = props.place.location.longitude
            props.places.suggestions = []
        }
    })
}

</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button v-if="props.new">
                <Plus></Plus>
                New Client
            </Button>
            <Button v-else>
                Edit
                <Pencil class="cursor-pointer"></Pencil>
            </Button>
        </DialogTrigger>
        <DialogContent class="md:max-w-400 grid-rows-[auto_minmax(0,1fr)_auto] max-h-[90dvh] bg-light-primary dark:bg-dark-primary">
            <DialogHeader>
                <DialogTitle>{{ `${(props.new) ? 'New Client' : `Edit ${props.client.company}`}` }}</DialogTitle>
                <DialogDescription></DialogDescription>

                <div class="grid grid-cols-4 gap-4 mb-12">
                    <div class="col-span-1">
                        <Label for="company">Company</Label>
                        <Input id="company"
                            class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50"
                            v-model="form.company" />
                    </div>
                    <div class="col-span-1">
                        <Label for="email">Company Email</Label>
                        <Input id="email" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50"
                            v-model="form.email" />
                    </div>

                    <div class="col-span-2">
                        <Label for="url">URL</Label>
                        <FormattedInput v-model="form.url" id="url" width="full" />
                    </div>

                    <div class="relative w-full items-center col-span-4 mb-12">
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
                </div>
        
                <GoogleMap
                    api-key="AIzaSyCaeMDr_DdMy4FTvhPjQGHnjpSxS2LvQzw"
                    mapId="DEMO_MAP_ID"
                    style="width: 100%; height: 500px"
                    :center="center"
                    :zoom="15"
                >
                    <AdvancedMarker :options="{ position: center }" />
                </GoogleMap>

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
