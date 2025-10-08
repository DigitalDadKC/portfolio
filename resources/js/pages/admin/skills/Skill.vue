<script setup>
    import { computed } from 'vue';
    import { Head, useForm, router } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
    import PrimaryButton from '@/components/PrimaryButton.vue';
    import InputError from '@/components/InputError.vue';

    const props = defineProps({
        new: Boolean,
        skill: Object
    })

    const form = useForm({
        name: props.skill?.name,
        image: props.skill?.image
    });

    const picture = computed(() => {
        return (form.image.type?.startsWith('image')) ? URL.createObjectURL(form.image) : form.image
    })

    const handleImage = (e) => {
        form.image = e.target.files[0]

    }

    const submit = () => {
        if(props.new) {
            form.post(route('skills.store'));
        } else{
            router.post(route('skills.update', props.skill.id), {
                _method: 'put',
                name: form.name,
                image: form.image
            });
        }
    };
</script>

<template>
    <Head title="Edit Skill" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ props.new ? "New" : "Edit" }} Skill</h2>
        </template>

        <div class="py-12">
            <div class="max-w-md mx-auto sm:px-6 lg:px-8 dark:bg-white">
                <form @submit.prevent="submit" class="p-4">
                    <div>
                        <v-text-field v-model="form.name"></v-text-field>
                        <InputError class="mt-2" :message="$page.props.errors.name" />
                    </div>
                    <div class="mt-2">
                        <v-file-input type="file" accept="image/*" label="Skill Photo" @input="handleImage($event)"></v-file-input>
                        <InputError class="mt-2" :message="$page.props.errors.image" />
                    </div>

                    <div>
                        <v-img height="255" aspect-ratio="16/9" :src="picture"></v-img>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" v-if="props.new">Create</PrimaryButton>
                        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" v-else>Update</PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
