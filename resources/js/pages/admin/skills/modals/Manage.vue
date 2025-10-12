<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Pencil, Plus } from 'lucide-vue-next';

const props = defineProps({
    new: Boolean,
    skill: Object,
})

const isDialogOpen = ref(false)
const form = useForm({
    id: props.skill.id,
    name: props.skill.name,
    image: props.skill.image,
})

const picture = computed(() => {
    return (form.image?.type?.startsWith('image')) ? URL.createObjectURL(form.image) : form.image
})

const handleImage = (e) => {
    form.image = e.target.files[0]
}

const submit = () => {
    if(props.new) {
        form.post(route('skills.store'), {
            onSuccess: () => {
                isDialogOpen.value = false
            }
        });
    } else {
        router.post(route('skills.update', props.skill.id), {
            _method: 'put',
            name: form.name,
            image: form.image
        }, {
            onSuccess: () => {
                isDialogOpen.value = false
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
                New Skill
            </Button>
            <Button class="cursor-pointer" v-else>
                <Pencil class="cursor-pointer"></Pencil>
            </Button>
        </DialogTrigger>

        <DialogContent class="sm:max-w-[600px] grid-rows-[auto_minmax(0,1fr)_auto] max-h-[90dvh] bg-light-primary dark:bg-dark-primary">
            <DialogHeader class="p-6">
                <DialogTitle>{{(props.new) ? 'New Skill' : 'Edit Skill' }}</DialogTitle>
                <DialogDescription>
                    {{ (props.new) ? 'New Skill' : `Edit ${props.skill.name}` }}
                </DialogDescription>
            </DialogHeader>

            <div>
                <div>
                    <Label for="name">Name</Label>
                    <Input id="name" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.name" />
                </div>
            </div>
            <div class="mt-2">
                <Label for="image">Image</Label>
                <Input id="image" type="file" class="bg-white dark:bg-dark-tertiary hover:bg-input/50" @input="handleImage($event)" />
                <InputError class="mt-2" :message="$page.props.errors.image" />
            </div>
            <div class="flex justify-center">
                <img :src="picture" class="w-60 h-60" />
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
