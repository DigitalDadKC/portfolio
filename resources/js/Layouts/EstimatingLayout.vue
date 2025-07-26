<script setup>
    import NavLink from '@/Components/NavLink.vue';
    import HomeButton from '@/Components/HomeButton.vue';
    import { Head } from '@inertiajs/vue3';
    import { useStorage } from "@vueuse/core";
    import GuestLayout from './GuestLayout.vue';

    const showSidebar = useStorage('my-flag', true)
</script>

<template>
    <Head title="Estimating" />

    <GuestLayout title="Construction Estimating Software">
        <template v-slot:links>
            <div v-if="$vuetify.display.mobile">
                <v-menu>
                    <template v-slot:activator="{props}">
                        <v-btn icon="mdi-dots-vertical" v-bind="props"></v-btn>
                    </template>

                    <v-list>
                        <v-list-item color="primary" rounded="shaped">
                            <template v-slot:prepend>
                                <v-icon>mdi-home</v-icon>
                            </template>
                            <v-list-item-title>
                                <Link :href="route('estimating.index')">
                                    Estimating
                                </Link>
                            </v-list-item-title>
                        </v-list-item>
                        <v-list-item color="primary" rounded="shaped">
                            <template v-slot:prepend>
                                <v-icon>mdi-chart-line</v-icon>
                            </template>
                            <v-list-item-title>
                                <Link :href="route('estimating.report')">
                                    Reports
                                </Link>
                            </v-list-item-title>
                        </v-list-item>
                        <v-list-item color="primary" rounded="shaped">
                            <template v-slot:prepend>
                                <v-icon>mdi-domain</v-icon>
                            </template>
                            <v-list-item-title>
                                <Link :href="route('estimating.index')">
                                    Company Page
                                </Link>
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </div>

            <div v-else>
                <v-list class="d-flex align-center">
                    <v-list-item color="primary" rounded="shaped">
                        <v-list-item-title>
                            <v-icon>mdi-home</v-icon>
                            <NavLink :href="route('estimating.index')" :active="route().current('estimating.index')">
                                Estimating
                            </NavLink>
                        </v-list-item-title>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-title>
                            <v-icon>mdi-chart-line</v-icon>
                            <NavLink :href="route('estimating.report')" :active="route().current('estimating.report')">
                                Reports
                            </NavLink>
                        </v-list-item-title>
                    </v-list-item>
                    <v-list-item color="primary" rounded="shaped">
                        <v-list-item-title>
                            <v-icon>mdi-domain</v-icon>
                            <NavLink :href="route('company.index')" :active="route().current('company.index')">
                                Company Page
                            </NavLink>
                        </v-list-item-title>
                    </v-list-item>
                </v-list>
            </div>
        </template>

        <v-navigation-drawer v-model="showSidebar" temporary class="d-flex flex-wrap p-4 bg-grey" elevation="2" width="400">
            <v-card>
                <slot name="sidebar" />
            </v-card>
        </v-navigation-drawer>

        <slot />
    </GuestLayout>
</template>
