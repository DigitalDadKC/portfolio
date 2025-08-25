<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import EstimatingLayout from '@/Layouts/EstimatingLayout.vue';
import { Bar, Line } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, PointElement, LineElement } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, PointElement, LineElement)

const props = defineProps({
    'jobs': Object
})

const years = [...new Set(props.jobs.map(job => new Date(job.created_at).getUTCFullYear()))].sort()
const months = [
    {id: 1, month: 'January'},
    {id: 2, month: 'February'},
    {id: 3, month: 'March'},
    {id: 4, month: 'April'},
    {id: 5, month: 'May'},
    {id: 6, month: 'June'},
    {id: 7, month: 'July'},
    {id: 8, month: 'August'},
    {id: 9, month: 'September'},
    {id: 10, month: 'October'},
    {id: 11, month: 'November'},
    {id: 12, month: 'December'}
]
const colors = [
    '#29435C',
    '#925a2b',
    '#556E53',
]

let barMetadata = []
let lineMetadata = []
let yearlyProposalCount = []

years.forEach((year, index) => {
    let barYearlyData = []
    let lineYearlyData = []
    let yearlyProposalCountData = []
    let yearly_jobs = props.jobs.filter(job => new Date(job.created_at).getUTCFullYear() == year)
    months.forEach((month, i) => {
        barYearlyData.push(yearly_jobs.filter(job => new Date(job.created_at).getMonth() == i).length)
        lineYearlyData.push(yearly_jobs.filter(job => new Date(job.created_at).getMonth() == i).map(job => job.proposals.reduce((a, b) => a + b.scopes.reduce((c, d) => c + d.lines.reduce((e, f) => e + ((f.price/100) * (f.quantity/100)), 0), 0), 0)))
        yearlyProposalCountData.push(yearly_jobs.filter(job => new Date(job.created_at).getMonth() == i).map(job => job.proposals).map(proposal => proposal.length).reduce((a, b) => a + b, 0))
    })
    barMetadata.push({
        label: year,
        backgroundColor: colors[index],
        borderColor: colors[index],
        data: barYearlyData
    })
    lineMetadata.push({
        label: year,
        backgroundColor: colors[index],
        borderColor: colors[index],
        data: lineYearlyData.map(data => data.reduce((a, b) => a + b, 0))
    })
    yearlyProposalCount.push(yearlyProposalCountData)
})

const barData = {
    labels: months.map(item => item.month),
    datasets: barMetadata,
}

const lineData = {
    labels: months.map(item => item.month),
    datasets: lineMetadata
}

const year = ref(Math.max(...years))

const jobCountData = computed(() => {
    return barMetadata.find(data => data.label == year.value)
})

const proposalCountData = computed(() => {
    return yearlyProposalCount[years.indexOf(year.value)]
})

const proposalVolumeData = computed(() => {
    return lineMetadata.find(data => data.label == year.value).data
})

const barChartOptions = {
  responsive: true,
  plugins: {
    title: {
        display: true,
        text: 'Annual Job Count'
    }
  }
}

const lineChartOptions = {
  responsive: true,
  plugins: {
    title: {
        display: true,
        text: 'Annual Proposal $'
    }
  }
}

</script>

<template>
    <EstimatingLayout>
        <Head title="Reporting" />

        <v-container class="w-100 w-xl-75 bg-grey-lighten-2 rounded-lg">
            <v-row>
                <v-col cols="12" lg="6">
                    <div class="d-flex justify-center">
                        <Bar id="bar-chart" :options="barChartOptions" :data="barData" aria-label="Bar Chart Data" aria-describedby="bar-chart" class="bg-light-secondary rounded-md w-full h-full">Chart could not be loaded</Bar>
                    </div>
                </v-col>
                <v-col cols="12" lg="6">
                    <div class="d-flex justify-center">
                        <Line id="line-chart" :options="lineChartOptions" :data="lineData" aria-label="Line Chart Data" aria-describedby="line-chart" class="bg-light-tertiary w-full h-full">Chart could not be loaded</Line>
                    </div>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12" lg="6">
                    <v-card>
                        <div class="p-4">
                            <v-card-title class="text-center">Job Count</v-card-title>
                            <v-select v-model="year" :items="years" hide-details class="bg-grey-lighten-2"></v-select>
                        </div>
                        <v-card-item v-for="(month, index) in months" :key="index">
                            <div class="d-flex justify-space-between">
                                <div>
                                    {{month.month}}
                                </div>
                                <div>
                                    {{jobCountData.data[index]}}
                                </div>
                            </div>
                        </v-card-item>
                    </v-card>
                </v-col>
                <v-col cols="12" lg="3">
                    <v-card>
                        <div class="p-4">
                            <v-card-title class="text-center">Proposal Count</v-card-title>
                            <v-select v-model="year" :items="years" hide-details class="bg-grey-lighten-2"></v-select>
                        </div>
                        <v-card-item v-for="(month, index) in months" :key="index">
                            <div class="d-flex justify-space-between">
                                <div>
                                    {{month.month}}
                                </div>
                                <div>
                                    {{proposalCountData[index] }}
                                </div>
                            </div>
                        </v-card-item>
                    </v-card>
                </v-col>
                <v-col cols="12" lg="3">
                    <v-card>
                        <div class="p-4">
                            <v-card-title class="text-center">Proposal Volume</v-card-title>
                            <v-select v-model="year" :items="years" hide-details class="bg-grey-lighten-2"></v-select>
                        </div>
                        <v-card-item v-for="(month, index) in months" :key="index">
                            <div class="d-flex justify-space-between">
                                <div>
                                    {{month.month}}
                                </div>
                                <div>
                                    {{$filters.formatCurrency(proposalVolumeData[index]) }}
                                </div>
                            </div>
                        </v-card-item>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </EstimatingLayout>
</template>
