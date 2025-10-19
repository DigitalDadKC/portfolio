<script setup lang="ts">
import {
  Sidebar,
  SidebarContent,
  SidebarGroup,
  SidebarGroupContent,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from "@/components/ui/sidebar"
import SidebarHeader from "./ui/sidebar/SidebarHeader.vue";

const props = defineProps({
    navigations: Object,
})
</script>

<template>
    <Sidebar>
        <SidebarHeader class="pt-20">PROJECTS</SidebarHeader>
        <SidebarContent>
            <SidebarGroup>
                <SidebarGroupContent>
                    <SidebarMenu>
                        <SidebarMenuItem v-for="project in navigations" :key="project.name">
                            <SidebarMenuButton asChild>
                            <Link :href="project.url" prefetch>
                                <component :is="project.icon" />
                                <span>{{project.name}}</span>
                            </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarGroupContent>
            </SidebarGroup>
        </SidebarContent>
    </Sidebar>
</template>
