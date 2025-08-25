<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, reactive, computed, onMounted} from 'vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import FilterDropdown from '@/Components/FilterDropdown.vue';
import Sidebar from '@/Components/Sidebar.vue';
import SidebarFilter from '@/Components/SidebarFilter.vue';
import Slider from '@/Components/Slider.vue';
import Links from './Partials/Links.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { useDateFormat } from '@vueuse/core'
import { useFormatCurrency } from '@/Composables/useFormatCurrency.js';
import { useOffsetPagination } from '@vueuse/core'

const { formatCurrency } = useFormatCurrency();

const props = defineProps({
    materials: Object,
    contracts: Object,
    data: Object,
    categories: Object,
    book_price_effective_dates: Object,
    statuses: Object,
    discountables: Object
});

const database = ref([])
database.value = props.materials
database.value.forEach(item => item.filters = {})
const count = ref(0)
const pageData = ref([])
const pageSize = ref(10)
const pages = ref(1)
const showSideBar = ref(false)
const discount = ref(0)

const filteredMaterials = computed(() => {
    let items = props.materials

    items = items.filter(item => !Object.values(item.filters).includes(false))
    items = items.filter(item => item[data.effective_date] < data.price)

    if (data.search !== null) {
                items = items.filter(item =>
                    item.Name.toLowerCase().includes(data.search.toLowerCase().trim()) ||
                    item.SKU.toLowerCase().includes(data.search.toLowerCase().trim())
                )
    }

    data.categories.forEach(unit => unit.count = items.filter(item => item.material_category.Name == unit.Name).length)
    data.statuses.forEach(unit => { unit.count = items.filter(item => item[data.status] == unit.Name).length })
    data.discountables.forEach(unit => { unit.count = items.filter(item => item.Discountable == unit.Name).length })

    if (data.sortSKU) {
        if (data.sortMethod == 'asc') { items.sort((a, b) => a.SKU.localeCompare(b.SKU)) } else { items.sort((a, b) => b.SKU.localeCompare(a.SKU)) }
    }

    return items
})

const {currentPage, currentPageSize, isFirstPage, isLastPage} = useOffsetPagination({total: props.materials.length, page: 1, pageSize: 10, onPageChange: fetch, onPageSizeChange: fetch})

const data = reactive({
    search: '',
    contract: 0,
    effective_date_id: props.book_price_effective_dates[0].id,
    effective_date: props.book_price_effective_dates[0].effective_date,
    status: '',
    effective_dates: props.book_price_effective_dates,
    reference_date: [],
    categories: props.categories,
    statuses: props.statuses,
    discountables: props.discountables,
    price_min: 0,
    price: 10000,
    price_max: Math.max(...props.materials.map(item => item[props.book_price_effective_dates[0].effective_date])),
    sortSKU: false,
    sortMethod: ''
})

data.status = data.effective_date + '-status'

function fetch() {
    const start = (currentPage.value - 1) * currentPageSize.value;
    const end = start + currentPageSize.value;
    pageData.value = filteredMaterials.value.slice(start, end)
    pages.value = Math.ceil(filteredMaterials.value.length / currentPageSize.value)
    if (pages.value < currentPage.value) { currentPage.value = pages.value }
    count.value = filteredMaterials.value.length
    return filteredMaterials.value.slice(start, end)
}

fetch()

const sortBySKU = () => {
    data.sortSKU = true;
    data.sortMethod = (data.sortMethod == 'asc') ? 'desc' : 'asc'
    fetch()
}

data.effective_dates.forEach(date => date.display_date = useDateFormat(date.effective_date, 'MMMM D, YYYY').value)

const filteredPrice = (material, date) => {
    const status = date + '-status'
    return (material.Discountable) ? +material[date]*(1 - +discount.value) : ((!material[date] && material[status]) ? 'QUOTE' : +material[date])
}

const filteredStatus = (material, date) => {
    const status = date + '-status'
    return material[status]
}

const changeContract = () => {
    data.effective_dates = (+data.contract) ? props.contracts.filter(contract => contract.id === +data.contract)[0].effective_dates : props.book_price_effective_dates
    data.effective_dates.forEach(date => date.display_date = useDateFormat(date.effective_date, 'MMMM D, YYYY').value)
    data.effective_date_id = +data.effective_dates[0].id
    data.effective_date = data.effective_dates[0].effective_date
    discount.value = (+data.contract) ? +props.contracts.filter(coop => coop.id === +data.contract)[0]?.discount : 0
}

const changeEffectiveDate = () => {
    data.effective_date = props.contracts.filter(contract => contract.id === +data.contract)[0]?.effective_dates.filter(date => date.id === +data.effective_date_id)[0]?.effective_date ?? props.book_price_effective_dates.filter(date => date.id === +data.effective_date_id)[0].effective_date
    data.status = data.effective_date + "-status"
}

onMounted(() => {
    props.materials.forEach(item => item.category = item.material_category.Name)
})

</script>

<template>
    <GuestLayout>

    <div class="flex">
        <Head title="Datatable" />

        <Sidebar @showSideBar="(sidebar) => showSideBar = sidebar">
            <template #sidebarContents>
                <div class="px-4 flex flex-col justify-start items-start justify-between">
                    <div>
                        <InputLabel for="contractFilter" value="Contracts" class="text-xl text-light-primary" />
                        <FilterDropdown v-model="data.contract" :options="contracts" :column="'name'" :initial="'Initial'" id="contractFilter" @change="changeContract()"></FilterDropdown>
                    </div>
                    <p class="truncate text-md text-white">Contract Discount: {{ (discount*100).toFixed(2) }}%</p>
                </div>
                <div class="px-4 md:py-2">
                    <InputLabel for="effectiveDateFilter" value="Effective Date" class="text-gray-900 text-xl text-light-primary truncate" />
                    <FilterDropdown v-model.number="data.effective_date_id" :options="data.effective_dates" :column="'display_date'" @change="changeEffectiveDate()"></FilterDropdown>
                </div>
                <div class="px-4 md:py-2">
                    <InputLabel for="price" value="Price" class="text-gray-900 text-xl text-light-primary truncate" />
                    <Slider :min="0" :max="10000" :step="50" v-model="data.price" @input="fetch()" />
                </div>
                <div class="px-4 md:py-2">
                    <SidebarFilter :list="data.statuses" :name="'Status'" :category="data.status" :items="database" @update:list="fetch()" />
                </div>
                <div class="px-4 md:py-2">
                    <SidebarFilter :list="data.discountables" :name="'Discountable'" :category="'Discountable'" :items="database" @update:list="fetch()" />
                </div>
                <div class="px-4 md:py-2">
                    <SidebarFilter :list="data.categories" :name="'Category'" :category="'category'" :items="database" @update:list="fetch()" />
                </div>
                <div class="px-4 md:py-2 mt-4">
                    <a :href="route('export', { data: data })" class="px-4 py-2 bg-accent border border-black rounded-md text-black hover:text-light-primary hover:bg-light-tertiary">EXPORT</a>
                </div>
            </template>
        </Sidebar>

        <div class="flex-1 bg-dark-primary md:px-4 py-16 px-4 h-screen" :class="{'overflow-hidden md:overflow-y-auto md:block': showSideBar, 'overflow-auto': !showSideBar}">
            <div class="container mx-auto">
                <div class="w-full">
                    <InputLabel for="search" value="Search" class="dark:text-gray-900 text-xl text-light-primary" />
                    <TextInput v-model="data.search" type="text" class="rounded-md w-full text-xl" required autofocus placeholder="Search items..." id="search" @input="fetch()" />
                </div>
                <Links :pages="pages" :isFirstPage="isFirstPage" :isLastPage="isLastPage" :currentPage="currentPage" :currentPageSize="currentPageSize" @changePage="(item) => currentPage = item" @changeRecords="(size) => currentPageSize = size" />

                <div class="text-white flex justify-center">
                    Showing {{ (currentPageSize * currentPage)-(currentPageSize - 1)}} - {{ (currentPageSize * currentPage < count) ? (currentPageSize * currentPage) : count }} Items ({{ count }} Total Items)
                </div>
            </div>

            <!-- MOBILE -->
            <div class="sm:hidden text-sm">
                <div v-for="(material, index) in pageData" :key="index" class="flex flex-col border-b">
                    <div class="flex justify-between">
                        <div>
                            {{ material.SKU }} {{ material.Name }} ({{ material.material_unit_size.Unit_Size }})
                        </div>
                        <div>
                            <span>Book: {{formatCurrency(material[data.effective_date])}}</span>
                        </div>
                    </div>
                    <div class="flex justify-between text-nowrap">
                        <div>
                                {{ material.material_category.Name }}
                        </div>
                        <div>
                            <span v-if="material.Discountable && data.contract" class="whitespace-nowrap">Discounted: {{formatCurrency( filteredPrice(material, data.effective_date) )}}</span>
                        </div>
                    </div>
                    <div>{{ filteredStatus(material, data.effective_date) }}</div>
                </div>
            </div>

            <!-- SMALL AND UP SCREENS -->
            <div class="hidden sm:flex text-sm md:text-base bg-light-tertiary">
                <table class="table-auto w-full text-white uppercase text-left">
                    <thead class="bg-black text-white">
                        <tr class="flex md:px-4">
                            <th class="w-1/12" @click="sortBySKU()">
                                <span class="flex-inline">
                                    SKU
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3.5" stroke="currentColor" class="w-4 h-4 inline" v-if="!data.sortSKU">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3.5" stroke="currentColor" class="w-4 h-4 inline" v-if="data.sortSKU && data.sortMethod == 'desc'">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3.5" stroke="currentColor" class="w-4 h-4 inline" v-if="data.sortSKU && data.sortMethod == 'asc'">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" />
                                    </svg>
                                </span>
                            </th>
                            <th class="w-1/3">Description</th>
                            <th class="w-1/12 hidden lg:table-cell">Unit</th>
                            <th class="w-1/6">Price</th>
                            <th class="w-1/6">Status</th>
                            <th class="w-1/12">Disc?</th>
                            <th class="w-1/12">Category</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-light-tail-100">
                        <tr v-for="(material, index) in pageData" :key="index" class="flex md:px-4 truncate">
                            <td class="w-1/12 ">
                                {{ material.SKU }}
                            </td>
                            <td class="w-1/3 text-wrap">
                                {{ material.Name }}
                            </td>
                            <td class="w-1/12 hidden lg:table-cell">
                                {{ material.material_unit_size.Unit_Size }}
                            </td>
                            <td class="w-1/6">
                                <p class="whitespace-nowrap">Book: {{formatCurrency(material[data.effective_date])}}</p>
                                <p v-if="material.Discountable && data.contract" class="whitespace-nowrap">Discounted: {{formatCurrency( filteredPrice(material, data.effective_date) )}}</p>
                            </td>
                            <td class="w-1/6">
                                {{ filteredStatus(material, data.effective_date) }}
                            </td>
                            <td class="w-1/12">
                                {{ (material.Discountable) ? 'YES' : 'NO' }}
                            </td>
                            <td class="w-1/12">
                                {{ material.material_category.Name }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </GuestLayout>

</template>
