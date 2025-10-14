<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Switch } from '@/components/ui/switch';
import InputError from '@/components/InputError.vue';
import Skills from '../partials/Skills.vue';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Pencil, Plus } from 'lucide-vue-next';

const props = defineProps({
    new: Boolean,
    project: Object,
    skills: Array,
})

const isDialogOpen = ref(false)
const form = useForm({
    id: props.project.id,
    name: props.project.name,
    description: props.project.description,
    image: props.project.image,
    url: props.project.url,
    active: !!props.project.active,
    skills: props.project.skills,
})

const picture = computed(() => {
    return (form.image?.type?.startsWith('image')) ? URL.createObjectURL(form.image) : form.image
})

const handleImage = (e) => {
    form.image = e.target.files[0]
}

const submit = () => {
    if(props.new) {
        form.post(route('projects.store'), {
            onSuccess: () => {
                isDialogOpen.value = false
            }
        });
    } else {
        router.post(route('projects.update', props.project.id), {
            _method: 'put',
            name: form.name,
            description: form.description,
            url: form.url,
            image: form.image,
            active: form.active,
            skills: form.skills,
        }, {
            preserveScroll: true,
            onSuccess: () => {
                isDialogOpen.value = false;
            }
        });
    }
};
</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button class="cursor-pointer" v-if="props.new">
                <Plus></Plus>
                New Project
            </Button>
            <Button class="cursor-pointer" v-else>
                <Pencil class="cursor-pointer"></Pencil>
            </Button>
        </DialogTrigger>

        <DialogContent class="sm:max-w-[600px] grid-rows-[auto_minmax(0,1fr)_auto] max-h-[90dvh] bg-light-primary dark:bg-dark-primary">
            <DialogHeader class="p-6">
                <DialogTitle>{{(props.new) ? 'New Project' : 'Edit Project' }}</DialogTitle>
            </DialogHeader>

            <div class="flex flex-col gap-4">
                <div>
                    <Label for="name">Name</Label>
                    <Input id="name" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.name" />
                </div>
                <div>
                    <Label for="description">Description</Label>
                    <Input id="description" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.description" />
                </div>
                <div>
                    <Label for="url">Project URL</Label>
                    <Input id="url" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.url" />
                </div>
                <div>
                    <Label for="active">{{ (form.active) ? `Active` : `Inactive` }}</Label>
                    <Switch id="active" v-model="form.active" />
                </div>
                <div>
                    <Skills v-model="form.skills" :project :skills></Skills>
                </div>
                <div class="mt-2">
                    <Label for="image">Image</Label>
                    <Input id="image" type="file" class="bg-white dark:bg-dark-tertiary hover:bg-input/50" @input="handleImage($event)" />
                    <InputError class="mt-2" :message="$page.props.errors.image" />
                </div>
                <div class="flex justify-center">
                    <img :src="picture" class="w-60 h-60" />
                </div>
            </div>

            <DialogFooter class="p-6 pt-0">
                <div class="flex gap-2">
                    <Button variant="secondary" class="cursor-pointer" @click="isDialogOpen = false">Cancel</Button>
                    <Button type="submit" class="cursor-pointer" :disabled="form.processing" @click="submit()">Save</Button>
                </div>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
