<script setup>
    import { computed, onMounted } from 'vue';
    import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
    import { Head, useForm, router } from '@inertiajs/vue3';
    import PrimaryButton from '@/components/PrimaryButton.vue';
    import InputError from '@/components/InputError.vue';
    import InputLabel from '@/components/InputLabel.vue';

    const props = defineProps({
        new: Boolean,
        skills: Array,
        project: Object,
        errors: Object,
        options: Array,
    })

    const form = useForm({
        name: props.project?.name,
        description: props.project?.description,
        image: props.project?.image,
        skills: props.project?.skills,
        project_url: props.project?.project_url,
        active: !!props.project?.active
    });

    const picture = computed(() => {
        return (form.image?.type?.startsWith('image')) ? URL.createObjectURL(form.image) : form.image
    })

    const handleImage = (e) => {
        form.image = e.target.files[0]
    }

    const submit = () => {
        if(props.new) {
            router.post(route('projects.store'), {
                name: form.name,
                description: form.description,
                image: form.image,
                skills: form.skills,
                project_url: form.project_url,
                active: form.active
            });
        } else {
            router.post(route('projects.update', props.project.id), {
                _method: 'put',
                name: form.name,
                description: form.description,
                image: form.image,
                skills: form.skills,
                project_url: form.project_url,
                active: form.active
            });
        }
    };

    onMounted(() => {
        if(props.project?.skills.length) {
            form.skills = form.skills.map(skill => skill.id)
        }
    })
</script>

<template>
    <Head :title="(props.new) ? `New Project` : `Edit Project`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">{{ (props.new) ? `New` : `Edit` }} Project</h2>
        </template>

        <div class="py-12">
            <div class="max-w-md mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="flex flex-col gap-4 p-4">
                    <div>
                        <InputLabel for="skills" class="typo__label">Select Skill</InputLabel>
                        <div>
                            <v-select density="compact" v-model="form.skills" :items="props.skills" item-title="name" item-value="id" multiple chips></v-select>
                            <InputError class="mt-2" :message="$page.props.errors.skills" />
                        </div>
                    </div>
                    <div>
                        <v-text-field density="compact" v-model="form.name" label="Name"></v-text-field>
                        <InputError class="mt-2" :message="$page.props.errors.name" />
                    </div>
                    <div>
                        <v-text-field density="compact" v-model="form.description" label="Description"></v-text-field>
                        <InputError class="mt-2" :message="$page.props.errors.description" />
                    </div>
                    <div>
                        <v-text-field density="compact" v-model="form.project_url" label="URL"></v-text-field>
                        <InputError class="mt-2" :message="$page.props.errors.project_url" />
                    </div>
                    <div>
                        <v-img density="compact" label="Logo" height="255" :src="picture"></v-img>
                    </div>
                    <div>
                        <v-checkbox density="compact" :label="(form.active) ? 'Active' : 'Inactive'" v-model="form.active" :selected="form.active" hide-details></v-checkbox>
                    </div>
                    <div>
                        <v-file-input type="file" accept="image/*" label="Project Photo" @input="handleImage($event)"></v-file-input>
                        <InputError class="mt-2" :message="$page.props.errors.image" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <Link :href="route('projects.index')">
                            <PrimaryButton type="button">Cancel</PrimaryButton>
                        </Link>
                        <PrimaryButton class="ms-4" v-if="props.new">Create</PrimaryButton>
                        <PrimaryButton class="ms-4" v-else>Update</PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
