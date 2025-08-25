<script setup>
import { onMounted, ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    invoices: Object,
})

let invoices = ref([])
let searchInvoice = ref([])

onMounted(async() => {
    getInvoices()
})

const getInvoices = async() => {
    let response = await axios.get("/api/get_all_invoices")
    invoices.value = response.data.invoices
}

const search = async() => {
    let response = await axios.get("/api/search_invoice?s=" + searchInvoice.value)
    invoices.value = response.data.invoices
}
</script>

<template>
    <Head title="Invoices"></Head>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Invoices</h2>
        </template>

        <div class="container bg-light-primary w-full max-w-4xl">
            <div class="w-full">

                <div class="card__header">
                    <PrimaryButton>New Invoice</PrimaryButton>
                </div>

                <div class=" card__content">
                    <div class="table--filter">
                        <span class="table--filter--collapseBtn ">
                            <i class="fas fa-ellipsis-h"></i>
                        </span>
                        <div class="table--filter--listWrapper">
                            <ul class="table--filter--list">
                                <li>
                                    <p class="table--filter--link table--filter--link--active">
                                        All
                                    </p>
                                </li>
                                <li>
                                    <p class="table--filter--link ">
                                        Paid
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="table--search">
                        <div class="table--search--wrapper">
                            <select class="table--search--select" name="" id="">
                                <option value="">Filter</option>
                            </select>
                        </div>
                        <div class="relative">
                            <i class="table--search--input--icon fas fa-search "></i>
                            <input v-model="searchInvoice" class="table--search--input" type="text" placeholder="Search invoice" @keyup="search()">
                        </div>
                    </div>

                    <div class="table--heading">
                        <p>ID</p>
                        <p>Date</p>
                        <p>Number</p>
                        <p>Client</p>
                        <p>Due Date</p>
                        <p>Total</p>
                    </div>

                    <!-- item 1 -->
                    <div class="table--items" v-for="item in invoices" :key="item.id" v-if="invoices.length">
                        <a href="#" class="table--items--transactionId">#{{ item.id }}</a>
                        <p>{{ item.date }}</p>
                        <p>#{{ item.number }}</p>
                        <p v-if="item.client">{{ item.client.name }}</p>
                        <p v-else></p>
                        <p>{{item.due_date}}</p>
                        <p> $ {{item.total}}</p>
                    </div>
                    <div class="table--items" v-else>

                    </div>
                </div>

            </div>
        </div>

    </AuthenticatedLayout>
</template>

<style>

.container {
    width: 100%;
    max-width: 950px;
	margin-top: 5rem;
    margin-left: auto;
    margin-right: auto;
}

.card__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card__content {
    border: 1px solid #e0e0e0;
    box-shadow: 0 6px 13px -12px rgb(50 50 93 / 20%), 0 3px 7px -3px rgb(110 110 110 / 10%);
    border-radius: 4px;
    background-color:white ;
    color:black;
    margin-top: 30px;
    margin-bottom: 30px;
    padding-bottom:30px ;
}

.card__footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 40px;
}

.card__content--header {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    grid-gap: 20px;
    padding: 30px;
}

.invoice__title {
    margin-top: 10px;
    margin-bottom: 10px;
}

h2 {
    display: block;
    font-size: 1.5em;
    margin-block-start: 0.83em;
    margin-block-end: 0.83em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    font-weight: bold;
}

.btn {
    border: 1px solid #6a727a;
    padding: 9px 15px;
    border-radius: 4px;
    cursor: pointer;
}

.btn-secondary {
    background: #FFF;
    color: black;
}

.btn-secondary:hover {
    background: #f1f1f1;
    color: #6a727a;
}


table {
    background: #f1f1f1;
    margin-top: 20px;
    margin-bottom: 30px;
}
.table--filter {
    border-bottom: 1px solid #e0e0e0;
    padding: 20px 30px;
    margin-bottom: 20px;
}

.table--filter--list {
    display: flex;
    justify-content: flex-start;
    list-style: none;

}

.table--filter--link--active {
    color: #006fbb;
    font-weight: 500;
}

.table--filter--link {
    margin-right: 1rem;
}

.table--filter--collapseBtn {
    display: none;
}

ul {
    list-style: none;
}

.table--search {
    padding: 0 30px;
    display: grid;
    -ms-grid-columns: minmax(150px, auto) minmax(180px, 1fr);
    grid-template-columns: minmax(150px, auto) minmax(180px, 1fr);
    margin-top: 30px;
    margin-bottom: 30px;
}

.table--search--wrapper {
    position: relative;
}

.table--search--select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background: #eeeeee;
    color: #6a727a;
    width: 100%;
    border: none;
    border-top: 1px solid #e0e0e0;
    border-left: 1px solid #e0e0e0;
    border-bottom: 1px solid #e0e0e0;
    border-top-left-radius: 3px;
    border-bottom-left-radius: 3px;
    padding-top: 10px;
    padding-bottom: 10px;
    padding-left: 20px;
    padding-right: 20px;

}

.relative {
    position: relative;
}

.table--search--input--icon {
    top: 12px;
    left: 12px;
    color: #d4d4d4;
    position: absolute;
}

.table--search--input {
    width: 100%;
    border: none;
    color: #454f5b;
    border-top: 1px solid #e0e0e0;
    border-right: 1px solid #e0e0e0;
    border-bottom: 1px solid #e0e0e0;
    border-top-right-radius: 3px;
    border-bottom-right-radius: 3px;
    padding-top: 10px;
    padding-bottom: 10px;
    padding-right: 20px;
    padding-left: 40px;

}

.table--heading {
    padding: 0 30px;
    gap: 10px;
    display: -ms-grid;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    color: #808891;
    font-size: 14px;
    font-weight: 500;
    border-bottom: 1px solid #e0e0e0;
    padding-bottom: 1.2rem;
    margin-top: 20px;
}

.table--items {
    padding: 10px 30px !important;
    gap: 10px;
    display: -ms-grid;
    display: grid;
    -ms-grid-columns: (minmax(100px, 1fr))[auto-fit];
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    border-bottom: 1px solid #e0e0e0;
    padding: 0.6rem 0;
}

.table--items--transactionId {
    color: #006fbb;
    font-weight: 500;
}

.input {
    padding: 9px 15px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    width: 100%;
}

.my-1 {
    margin-top: 10px;
    margin-bottom: 10px;
}

.selectBtnFlat {
    border: none;
    padding: 9px 15px;
    border-radius: 4px;
    cursor: pointer;
    position: relative;
    color: #FFF;
    background: none;
}

.table--heading2 {
    display: grid;
    grid-template-columns: 550px 1fr 1fr 1fr 25px;
    grid-gap: 10px;
    padding: 10px 30px !important;
    font-weight: 900;
    border-bottom: 1px solid #e0e0e0;
    background: #DDDDDD;
}

.table--items2 {
    display: grid;
    grid-template-columns: 550px 1fr 1fr 1fr 25px;
    grid-gap: 10px;
    align-items: center;
    border-bottom: 1px solid #e0e0e0;
    padding: 10px 30px !important;
}

.table__footer {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-gap: 20px;
    padding: 10px 30px !important;

}

.table__footer--subtotal {
    display: grid;
    grid-template-columns: 1fr 1fr;
    font-weight: 500;
    margin-top: 30px;
    margin-bottom: 20px;
}

.table__footer--subtotal p {
    font-size: 20px;
}
.table__footer--subtotal span {
    font-size: 20px;
}

.table__footer--discount {
    display: grid;
    grid-template-columns: 1fr 1fr;
    font-weight: 500;
    margin-bottom: 20px;
}

.table__footer--discount p {
    font-size: 20px;
}

.table__footer--total {
    display: grid;
    grid-template-columns: 1fr 1fr;
    font-weight: 500;
    margin-bottom: 20px;
}

.table__footer--total p {
    font-size: 26px;
}

.table__footer--total span {
    font-size: 26px;
}

.textarea {
    padding: 9px 15px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    width: 100%;
    font-size: 1.1rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
}
</style>
