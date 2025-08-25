<script setup>
    import NavLink from '@/Components/NavLink.vue';
    import HomeButton from '@/Components/HomeButton.vue';
    import { Head } from '@inertiajs/vue3';
    import { useStorage } from "@vueuse/core";
    import GuestLayout from './GuestLayout.vue';

    // const showSidebar = useStorage('my-flag', true)
</script>

<template>
    <Head title="Estimating" />

    <GuestLayout title="Construction Estimating Software">
        <template v-slot:links>
            <div v-if="$vuetify.display.mobile">
                <v-menu>
                    <template v-slot:activator="{props}">
                        <v-btn icon="mdi-dots-vertical cursor-pointer" v-bind="props"></v-btn>
                    </template>

                    <v-list>
                        <v-list-item color="primary" rounded="shaped">
                            <template v-slot:prepend>
                                <v-icon>mdi-home</v-icon>
                            </template>
                            <v-list-item-title>
                                <Link :href="route('estimating.index')" prefetch>
                                    Estimating
                                </Link>
                            </v-list-item-title>
                        </v-list-item>
                        <v-list-item color="primary" rounded="shaped">
                            <template v-slot:prepend>
                                <v-icon>mdi-chart-line</v-icon>
                            </template>
                            <v-list-item-title>
                                <Link :href="route('estimating.report')" prefetch>
                                    Reports
                                </Link>
                            </v-list-item-title>
                        </v-list-item>
                        <v-list-item color="primary" rounded="shaped">
                            <template v-slot:prepend>
                                <v-icon>mdi-chart-line</v-icon>
                            </template>
                            <v-list-item-title>
                                <Link :href="route('customers.index')" prefetch>
                                    Customers
                                </Link>
                            </v-list-item-title>
                        </v-list-item>
                        <v-list-item color="primary" rounded="shaped">
                            <template v-slot:prepend>
                                <v-icon>mdi-domain</v-icon>
                            </template>
                            <v-list-item-title>
                                <Link :href="route('admin.index')" prefetch>
                                    Admin
                                </Link>
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </div>

            <div v-else>
                <v-list class="flex">
                    <v-list-item rounded="shaped">
                        <v-list-item-title>
                            <v-icon>mdi-home</v-icon>
                            <NavLink :href="route('estimating.index')" :active="route().current('estimating.index')" prefetch>
                                Estimating
                            </NavLink>
                        </v-list-item-title>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-title>
                            <v-icon>mdi-chart-line</v-icon>
                            <NavLink :href="route('estimating.report')" :active="route().current('estimating.report')" prefetch>
                                Reports
                            </NavLink>
                        </v-list-item-title>
                    </v-list-item>
                    <v-list-item color="primary" rounded="shaped">
                        <v-list-item-title>
                            <v-icon>mdi-domain</v-icon>
                            <NavLink :href="route('customers.index')" :active="route().current('customers.index')" prefetch>
                                Customers
                            </NavLink>
                        </v-list-item-title>
                    </v-list-item>
                    <v-list-item color="primary" rounded="shaped">
                        <v-list-item-title>
                            <v-icon>mdi-account-lock</v-icon>
                            <NavLink :href="route('admin.index')" :active="route().current('admin.index')" prefetch>
                                Admin
                            </NavLink>
                        </v-list-item-title>
                    </v-list-item>
                </v-list>
            </div>
        </template>

        <!-- <v-navigation-drawer v-model="showSidebar" temporary class="flex flex-wrap p-4 bg-blue-500 cursor-pointer" elevation="2" width="600">
            <v-card>
                <slot name="sidebar" />
            </v-card>
        </v-navigation-drawer> -->

        <slot />
    </GuestLayout>
</template>
