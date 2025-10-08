<script setup>
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';

let form = ref([])
let allclients = ref([])
let client_id = ref([])
let item = ref([])
let listCart = ref([])
const showModal = ref(false)
const hideModal = ref(true)
const listproducts = ref([])

onMounted(async () => {
    indexForm()
    getAllClients()
    getproducts()
})

const indexForm = async () => {
    let response = await axios.get('/api/create_invoice')
    form.value = response.data
}

const getAllClients = async () => {
    let response = await axios.get('/api/clients')
    allclients.value = response.data.clients
}

const addCart = (item) => {
    const itemcart = {
        id: item.id,
        item_code: item.item_code,
        description: item.description,
        unit_price: item.unit_price,
        quantity: item.quantity,
    }

    listCart.value .push(itemcart)
    closeModal()
}

const openModal = () => {
    showModal.value = !showModal.value
}

const closeModal = () => {
    showModal.value = !hideModal.value
}

const getproducts = async() => {
    let response = await axios.get('/api/products')
    listproducts.value = response.data.products
}

const removeItem = (i) => {
    listCart.value.splice(i, 1);
}

const subtotal = () => {
    let total = 0;
    listCart.value.map((data) => {
        total = total + (data.quantity*data.unit_price)
    })
    return total;
}

const total = () => {
    return subtotal()-form.value.discount;
}

const onSave = () => {
    if(listCart.value.length) {
        let sub_total = 0
        sub_total = subtotal()

        let invoice_total = 0
        invoice_total = total()

        const formData = new FormData()
        formData.append('invoice_item', JSON.stringify(listCart.value))
        formData.append('client_id', client_id.value)
        formData.append('date', form.value.date)
        formData.append('due_date', form.value.due_date)
        formData.append('number', form.value.number)
        formData.append('reference', form.value.reference)
        formData.append('discount', form.value.discount)
        formData.append('subtotal', sub_total)
        formData.append('total', invoice_total)
        formData.append('terms_and_conditions', form.value.terms_and_conditions)

        console.log(...formData);
        axios.post('/api/add_invoice', formData)
        listCart.value = []
    }
}

</script>

<template>

    <Head title="Create Invoice"></Head>

    <div class="container mx-auto bg-light-primary w-full max-w-4xl">
        <div class="invoices">

            <div class="card__header">
                <div>
                    <h2 class="invoice__title">New Invoice</h2>
                </div>
                <div>

                </div>
            </div>

            <div class="card__content">
                <div class="card__content--header">
                    <div>
                        <p class="my-1">Client</p>
                        <select name="" id="" class="input" v-model="client_id">
                            <option disabled value="">Select Client</option>
                            <option :value="client.id" v-for="client in allclients" :key="client.id">{{ client.name }}</option>
                        </select>
                    </div>
                    <div>
                        <p class="my-1">Date</p>
                        <input id="date" placeholder="dd-mm-yyyy" type="date" class="input" v-model="form.date"> <!---->
                        <p class="my-1">Due Date</p>
                        <input id="due_date" type="date" class="input" v-model="form.due_date">
                    </div>
                    <div>
                        <p class="my-1">Numero</p>
                        <input type="text" class="input" v-model="form.number">
                        <p class="my-1">Reference(Optional)</p>
                        <input type="text" class="input" v-model="form.reference">
                    </div>
                </div>
                <br><br>
                <div class="table">

                    <div class="table--heading2">
                        <p>Item Description</p>
                        <p>Unit Price</p>
                        <p>Qty</p>
                        <p>Total</p>
                        <p></p>
                    </div>

                    <!-- item 1 -->
                    <div class="table--items2" v-for="(itemcart, i) in listCart" :key="i">
                        <p>{{ itemcart.item_code }} {{ itemcart.description }} </p>
                        <p>
                            <input type="text" class="input" v-model="itemcart.unit_price">
                        </p>
                        <p>
                            <input type="text" class="input" v-model="itemcart.quantity">
                        </p>
                        <p v-if="item.quantity">
                            $ {{ (itemcart.quantity)*(itemcart.unit_price) }}
                        </p>
                        <p v-else></p>
                        <p style="color: red; font-size: 24px;cursor: pointer;" @click="removeItem(i)">
                            &times;
                        </p>
                    </div>
                    <div style="padding: 10px 30px !important;">
                        <button class="btn btn-sm btn__open--modal" @click="openModal()">Add New Line</button>
                    </div>
                </div>

                <div class="table__footer">
                    <div class="document-footer" >
                        <p>Terms and Conditions</p>
                        <textarea cols="50" rows="7" class="textarea" v-model="form.terms_and_conditions"></textarea>
                    </div>
                    <div>
                        <div class="table__footer--subtotal">
                            <p>Sub Total</p>
                            <span>$ {{ subtotal() }}</span>
                        </div>
                        <div class="table__footer--discount">
                            <p>Discount</p>
                            <input type="text" class="input" v-model="form.discount">
                        </div>
                        <div class="table__footer--total">
                            <p>Grand Total</p>
                            <span>$ {{ total() }}</span>
                        </div>
                    </div>
                </div>


            </div>
            <div class="card__header" style="margin-top: 40px;">
                <div>

                </div>
                <div>
                    <a class="btn btn-secondary" @click="onSave()">
                        Save
                    </a>
                </div>
            </div>

        </div>
    <!--==================== add modal items ====================-->
        <div class="modal main__modal " :class="{show: showModal}">
            <div class="modal__content">
                <span class="modal__close btn__close--modal" @click="closeModal()">×</span>
                <h3 class="modal__title">Add Item</h3>
                <hr><br>
                <div class="modal__items">
                    <ul style="list-style:none">
                        <li v-for="(item, i) in listproducts" :key="item.id" style="display:grid;grid-template-columns:30px 350px 15px; align-items:center; padding-bottom:5px">
                            <p>{{ i+1 }}</p>
                            <a href="#">{{ item.item_code }} {{ item.description }}</a>
                            <button @click="addCart(item)" style="border:1px solid #e0e0e0;width:35px;height:35px;cursor:pointer">
                                +
                            </button>
                        </li>
                    </ul>
                    <select class="input my-1">
                        <option value="None">None</option>
                        <option value="None">LBC Padala</option>
                    </select>
                </div>
                <br><hr>
                <div class="model__footer">
                    <button class="btn btn-light mr-2 btn__close--modal" @click="closeModal()">
                        Cancel
                    </button>
                    <button class="btn btn-light btn__close--modal ">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    padding-top: 20%;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
    color: #454f5b;
}

.show {
    display: block !important;
}

.modal__content {
    background-color: white;
    margin: auto;
    padding: 20px;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 2px 15px rgb(35 40 47 / 25%);
    position: relative;
    border: none;
    border-radius: 7px;
}

.modal__close {
    color: #aaaaaa;
    font-size: 28px;
    font-weight: bold;
    top: 5px;
    right: 15px;
    position: absolute;
    cursor: pointer;
}

.modal__title {
    margin-bottom: 20px;
}

.modal__items {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.inputAdd {
    padding: 9px 15px;
    border-top: 1px solid #e0e0e0;
    border-left: 1px solid #e0e0e0;
    border-bottom: 1px solid #e0e0e0;
    border-right: none;
    border-top-left-radius: 4px;
    border-bottom-left-radius: 4px;
    width: 100%;
}

.inputAdd__Btn {
    background: #5463c1;
    color: white;
    border-top: 1px solid #5463c1;
    border-right: 1px solid #5463c1;
    border-bottom: 1px solid #5463c1;
    border-left: none;
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
    padding: 9px 15px;
    cursor: pointer;
}

.model__footer {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    margin-top: 20px;

}

.btn {
    border: 1px solid #6a727a;
    padding: 9px 15px;
    border-radius: 4px;
    cursor: pointer;
}
.btn-light {
    border: 1px solid #808891;
    background: white;
    color: #454f5b;
}
.mr-2 {
    margin-right: 20px;
}
</style>
