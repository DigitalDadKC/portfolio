<script setup>
import { computed } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import ManageCompany from '../modals/ManageCompany.vue'
import SecondaryButton from '@/components/SecondaryButton.vue'

const props = defineProps({
    company: Object,
    states: Object,
})

const form = useForm({
    id: props.company?.id,
    name: props.company?.name,
    address: props.company?.address,
    city: props.company?.city,
    state_id: props.company.state?.id,
    zip: props.company?.zip,
    logo: props.company?.logo
})

const image = computed(() => {
    if (!form.logo) {
        return
    }
    if (typeof form.logo == 'object') {
        return URL.createObjectURL(form.logo)
    } else {
        return form.logo
    }
})

const submit = () => {
    if (props.new) {
        form.post(route('companies.store'), {
            onSuccess: (page) => {
                form.id = page.props.flash.message
                form.name = props.company.name
                form.address = props.company.address
                form.city = props.company.city
                form.state_id = props.company.state.id,
                form.zip = props.company.zip
            }
        })
    } else {
        router.patch(route('companies.update', { company: form.id }), {
            name: form.name,
            address: form.address,
            city: form.city,
            state_id: form.state_id,
            zip: form.zip,
        }, {
            preserveScroll: true,
        })
    }
}
</script>

<template>
    <div class="relative">
        <div class="p-3 bg-white inline-flex border-2 rounded-lg border-light-quatrenary dark:border-dark-quatrenary absolute -top-6 left-0">
            <v-icon size="x-large">mdi-card-account-details</v-icon>
        </div>

        <div class="fixed right-6 flex justify-end">
            <ManageCompany :company :states></ManageCompany>
        </div>

        <div class="flex justify-between pl-16">
            <div>
                <h5 class="my-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Company</h5>
                <div>
                    {{ props.company.name }}
                </div>
                <div>
                    {{ props.company.address }}
                </div>
                <div>
                    {{ props.company.city }}
                </div>
                    {{ props.company.state.state }}
                <div>
                    {{ props.company.zip }}
                </div>
            </div>

        </div>
    </div>
</template>
