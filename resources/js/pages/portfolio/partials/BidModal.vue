<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue';
import { useForm} from '@inertiajs/vue3';
import DialogModal from '@/Components/DialogModal.vue';
import Header from '@/Components/Portfolio/Header.vue';
import SectionButton from '@/Components/SectionButton.vue';
import BidInput from '@/Components/Estimating/BidInput.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    show: Boolean,
    job: Object,
    new: Boolean
})

const form = useForm({
    id: 0,
    number: 0,
    state: '',
    city: '',
    address: '',
    sections: [],
    total: 0
})

const showModal = computed({
    get() {
        return props.show
    },
    set(value) {
        emit('closeModal', value)
    }
})

watch(showModal, () => {
    if (props.new) {
        addSection()
    } else {
        form.id = props.job.id
        form.number = props.job.number
        form.state = props.job.state
        form.city = props.job.city
        form.address = props.job.address
        form.sections = props.job.sections
        form.sections.forEach((section, index) => {
            section.lines.forEach((line, i) => {
                line.uom = props.job.sections[index].lines[i].unit_of_measurement?.UOM
                getTotal(index, i)
            })
            if (!section.lines.length) {
                section.lines.push({ ...ref_line })
                section.lines[0].uom = ''
            }
        })
    }
})

const getTotal = (index, i) => {
    let line = form.sections[index].lines[i]
    line.total = line.price*line.quantity
}

const emit = defineEmits(['closeModal'])

const updateJob = () => {
    form.post('/', {
        onSuccess: () => {
            form.reset()
        }
    })
}

const addSection = () => {
    form.sections.push({ ...ref_section })
    form.sections[form.sections.length-1].lines = [{...ref_line}]
}

let ref_line = { 'id': 0, 'description': '', 'uom': '', 'price': 0, 'quantity': 0, 'metadata': {} }
let ref_section = { 'id': 0, 'name': '', 'area': 0, 'lines': [] }

const closeModal = () => {
    showModal.value = false
}

</script>

<template>
    <DialogModal :show="props.show" @close="closeModal()" :maxWidth="'6xl'">
        <template #header>

        </template>

        <template #content>
            <h1>{{job.job_number}}</h1>
            <form class="flex flex-col w-full gap-4" @submit.prevent="updateJob()">
                <div>
                    <h2>Job Site</h2>
                    <div>
                        <TextInput v-model="form.address" />
                    </div>
                    <div>
                        <TextInput v-model="form.city" />
                    </div>
                    <div>
                        <TextInput v-model="form.state" />
                    </div>
                </div>
                {{ props.job }}
                <div v-for="(section, index) in form.sections" :key="index">
                    <TextInput type="hidden" v-model="section.id" />
                    <TextInput v-model="section.name" />
                    <TextInput v-model="section.area" onfocus="this.select()" />
                    <button class="py-2 px-4 rounded-md bg-light-primary dark:bg-dark-primary" @click.prevent="addSection()" v-if="!index">ADD SECTION</button>
                    <button class="py-2 px-4 rounded-md bg-light-primary dark:bg-dark-primary" @click.prevent="form.sections.splice(index, 1)" v-else>REMOVE SECTION</button>
                    <div v-for="(line, i) in section.lines" :key="i" class="flex">
                        <TextInput type="hidden" v-model="line.id" />
                        <TextInput class="grow" v-model="line.description" />
                        <TextInput v-model="line.uom" />
                        <BidInput v-model="line.price" @input="getTotal(index, i)" onfocus="this.select()" />
                        <TextInput v-model="line.quantity" @input="getTotal(index, i)" onfocus="this.select()" />
                        <BidInput :value="$filters.formatCurrency(line.price*line.quantity)" />
                        <button class="py-1 px-4 rounded-md bg-light-secondary dark:bg-dark-secondary" @click.prevent="section.lines.push({...ref_line})" v-if="!i">+</button>
                        <button class="py-1 px-4 rounded-md bg-light-secondary dark:bg-dark-secondary" @click.prevent="section.lines.splice(i, 1)" v-else>-</button>
                    </div>
                </div>
            </form>
        </template>

        <template #footer>

        </template>
    </DialogModal>
</template>
