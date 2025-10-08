<script setup>
import { router, Link } from '@inertiajs/vue3';
import { ref, reactive } from 'vue';
import DialogModal from '@/components/DialogModal.vue';
import Header from '@/components/portfolio/Header.vue';
import SectionButton from '@/components/SectionButton.vue';
import BidInput from '@/components/estimating/BidInput.vue';
import TextInput from '@/components/TextInput.vue';
import InputLabel from '@/components/InputLabel.vue';
import BidModal from './BidModal.vue';

const props = defineProps({
    jobs: Object,
    job: Object
})

const showModal = ref(false)
const newJob = ref(false)
const data = reactive({
    jobs: props.jobs,
    job: []
})

const addJob = () => {
    newJob.value = true
    showModal.value = true
}

const editJob = (job) => {
    newJob.value = false
    data.job = job
    showModal.value = true
    console.log(job)
}

// const createProposal = () => {
//     form.post('/estimating/proposal', {
//         onSuccess: () => {
//             form.reset()
//             // resetLines()
//         }
//     })
// }

const closeModal = () => {
    showModal.value = false
}

</script>

<template>
    <div class="snap-start bg-light-tail-500 h-screen overflow-hidden" id="vue-estimating-table">
        <Header :title="'Estimating Table'"></Header>
        <div class="container mx-auto border rounded-lg bg-dark-primary">
            <div class="flex justify-between items-end">
                <div>
                    <button class="px-4 py-2 rounded-md bg-light-primary dark:bg-dark-primary" @click="addJob()">Add Job</button>
                </div>
            </div>
            <div class="text-white flex justify-center">
                {{ props.jobs.length }} Jobs
            </div>

            <div class="overflow-auto flex justify-start items-center md:justify-center">
                <table class="table-auto bg-dark-navy-100 text-white uppercase text-left border w-full">
                    <thead class="bg-black flex text-white px-4">
                        <tr class="flex justify-stretch items-center w-full">
                            <th class="w-16">Job #</th>
                            <td class="w-20">Actions</td>
                            <th class="hidden lg:table-cell w-96">Address</th>
                            <th class="hidden lg:table-cell w-48">City</th>
                            <th class="w-48">State</th>
                            <th class="w-24">Total</th>
                        </tr>
                    </thead>
                    <tbody class="flex flex-col overflow-y-scroll w-full px-4 divide-y divide-light-tail-100" style="height: 75vh;">
                        <tr v-for="(job, index) in data.jobs" :key="index" class="flex justify-stretch items-center w-full py-1">
                            <td class="w-16">{{ job.job_number }}</td>
                            <td class="w-20"><button class="px-4 py-2 bg-light-tail-100 dark:bg-dark-navy-500 rounded-md" @click="editJob(job)">EDIT</button></td>
                            <td class="hidden lg:table-cell w-96 whitespace-nowrap">{{ job.address }}</td>
                            <td class="hidden lg:table-cell w-48">{{ job.city }}</td>
                            <td class="w-48">{{ job.state }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <BidModal :show="showModal" :job="data.job" :new="newJob" @close-modal="closeModal()" />
</template>
