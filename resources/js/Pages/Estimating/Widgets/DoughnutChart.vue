<script setup>
import { useCharts } from '@/Composables/useCharts';
import { Doughnut } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, ArcElement, CategoryScale, LinearScale, PointElement } from 'chart.js';

const { colors, months } = useCharts()
ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale, LinearScale, PointElement)

const props = defineProps({
    users: Object,
    jobs: Object,
})

const doughnutMetadata = []
props.users.forEach(user => {
    doughnutMetadata.push(props.jobs.flatMap(job => job.proposals).filter(proposal => proposal.estimator.id == user.id).length)
})

const doughnutData = {
  labels: props.users.map(user => user.name),
  datasets: [{
    label: 'proposals',
    data: doughnutMetadata,
    backgroundColor: colors,
    hoverOffset: 8
  }]
}

const doughnutChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
        position: 'right',
    },
    title: {
        display: true,
        text: 'Estimator Proposal Count',
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
    <div>
        <Doughnut id="doughnut-chart" :data="doughnutData" :options="doughnutChartOptions" aria-label="Doughnut Chart Data" aria-describedby="doughnut-chart">Chart could not be loaded</Doughnut>
    </div>
</template>
