<script setup lang="ts">
    import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

defineProps({
    project: Object
})
</script>

<template>
    <a :href="project.url" target="_blank" v-motion :initial="{ opacity: 0, y:100}" :visibleOnce="{opacity: 1, y:0}">
        <Card class="hover:scale-105 transition bg-light-tertiary border-2 border-black shadow-xl">
            <CardHeader>
                <CardTitle>{{ project.name }}</CardTitle>
                <CardDescription class="text-gray-600">
                    {{project.description}}
                </CardDescription>
            </CardHeader>
            <CardContent class="p-2 bg-light-quatrenary rounded-b-xl border-t-4 border-black">
                <img :src="project.image" :alt="project.name" class="rounded-md h-40 lg:h-52" v-motion>
            </CardContent>
        </Card>
    </a>
</template>
