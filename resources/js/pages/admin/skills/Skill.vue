<script setup>
    import { computed } from 'vue';
    import { Head, useForm, router } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
    import PrimaryButton from '@/components/PrimaryButton.vue';
    import FormattedInput from '@/components/FormattedInput.vue';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';
    import InputError from '@/components/InputError.vue';

    const props = defineProps({
        new: Boolean,
        skill: Object
    })

    const form = useForm({
        name: props.skill?.name,
        image: props.skill?.image
    });

    console.log(props.skill)
    console.log(form.image)

    const picture = computed(() => {
        if(!form.image) {
            return
        }
        if (typeof form.image == 'object') {
            return URL.createObjectURL(form.image)
        } else {
            return form.image
        }
        return URL.createObjectURL(form.image)
        // return (form.image?.type?.startsWith('image')) ? URL.createObjectURL(form.image) : form.image
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
    <Head title="Skill" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ props.new ? "New" : "Edit" }} Skill</h2>
        </template>

        <div class="py-12">
        {{ form.image }}
            <div class="max-w-md mx-auto sm:px-6 lg:px-8 ">
                <form @submit.prevent="submit" class="p-4">
                    <div>
                        <Label for="name">Skill</Label>
                        <FormattedInput id="name" width="full" class="bg-light-primary dark:bg-dark-primary" v-model="form.name" />
                        <InputError class="mt-2" :message="$page.props.errors.name" />
                    </div>
                    <div class="mt-2">
                        <Label for="image">Image</Label>
                        <Input id="image" type="file" class="bg-light-primary dark:bg-dark-primary" @input="handleImage($event)" />
                        <InputError class="mt-2" :message="$page.props.errors.image" />
                    </div>

                    <div>
                        <img :src="picture" />
                        <img :src="form.image" />
                        <img src="skills/04wVReVUz0XrfGq2TTW0o075ETJfuaoqP0lf1RmX.jpg" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" v-if="props.new">Create</PrimaryButton>
                        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" v-else>Update</PrimaryButton>
                    </div>
                </form>
            </div>
            {{ picture }}
        </div>
    </AuthenticatedLayout>
</template>
