<script setup lang="ts">
import InvoicingLayout from '@/layouts/InvoicingLayout.vue';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"

const props = defineProps({
    products: Object,
})

</script>

<template>
    <InvoicingLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Products</h2>
        </template>

        <div class="container mx-auto w-full mt-20">
            <Table class="bg-light-primary dark:bg-dark-primary">
                <TableHeader class="bg-light-tertiary dark:bg-dark-tertiary">
                    <TableRow>
                        <TableHead>Product</TableHead>
                        <TableHead>Price</TableHead>
                        <TableHead>Image</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody class="bg-light-primary dark:bg-dark-primary">
                    <TableRow v-for="product in props.products" :key="product.id">
                        <TableCell>{{ product.name }}</TableCell>
                        <TableCell>{{ product.price }}</TableCell>
                        <TableCell><img :src="product.image" /></TableCell>
                    </TableRow>
                    <TableRow>
                        <TableCell>
                            <Link method="post" :href="route('checkout')" >Checkout</Link>
                        </TableCell>
                    </TableRow>
                </TableBody>

            </Table>
        </div>

    </InvoicingLayout>
</template>
