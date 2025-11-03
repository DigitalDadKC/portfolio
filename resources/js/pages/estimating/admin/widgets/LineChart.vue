<script setup>
import { useCharts } from '@/composables/useCharts';
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, ArcElement, CategoryScale, LinearScale, PointElement } from 'chart.js';

const { colors, months } = useCharts()
ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement)

const props = defineProps({
    jobs: Object,
})


const lineMetadata = []
const yearly_jobs = props.jobs.filter(job => new Date(job.created_at).getFullYear() == new Date().getFullYear())
months.forEach(month => {
    lineMetadata.push(yearly_jobs.filter(job => new Date(job.created_at).getMonth() == month.id).flatMap(job => job.proposals).length)
})

const lineData = {
    labels: months.map(item => item.month + ' ' + new Date().getFullYear()),
    datasets: [{
        label: 'Proposal Count',
        data: lineMetadata,
        backgroundColor: colors,
    }]
}

const lineChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
        position: 'right',
    },
    title: {
        display: true,
        text: 'Monthly Proposal Count',
        font: {
            familY: 'Graphik',
            size: 20
        },
        fullSize: true
    }
  }
}

</script>

<template>
    <Line id="line-chart" :data="lineData" :options="lineChartOptions" aria-label="Line Chart Data" aria-describedby="line-chart">Chart could not be loaded</Line>
</template>
