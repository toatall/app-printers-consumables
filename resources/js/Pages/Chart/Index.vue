<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import Breadcrumbs from '@/Shared/Breadcrumbs'
import Layout from '@/Shared/Layout'
import Chart from 'primevue/chart'
import { ref, onMounted } from 'vue';
import moment from 'moment';
import 'chartjs-adapter-moment'


moment.locale('ru')
moment().format('LL')


defineOptions({
    layout: Layout
})

const props = defineProps({
    data: Object,
})

const chartData = ref()
const chartOptions = ref()
onMounted(() => {
    const data = props.data

    let labels = []
    let dataAdded = []
    let dataDeducted = []
    data.added.forEach(val => {
        const d = new Date(val.date)

        dataAdded.push({
            x: d,
            y: val.count,
        })
        if (labels.indexOf(d) >= 0) {
            labels.push(d)
        }        
    })

    data.deducted.forEach(val => {
        const d = new Date(val.date)

        dataDeducted.push({
            x: d,
            y: val.count,
        })
        if (labels.indexOf(d) >= 0) {
            labels.push(d)
        }  
    })

    chartOptions.value = setChartOptions()
    chartData.value = setChartData(dataAdded, dataDeducted, labels)
    
})

const setChartData = (dataAdded, dataDeducted, labels) => {
    const documentStyle = getComputedStyle(document.documentElement);

    return {
        datasets: [
            {
                label: 'Поступило',
                fill: false,
                borderColor: documentStyle.getPropertyValue('--cyan-500'),
                yAxisID: 'y',
                tension: 0.4,
                data: dataAdded,                
            },
            {
                label: 'Установлено',
                fill: false,
                borderColor: documentStyle.getPropertyValue('--red-500'),
                yAxisID: 'y1',
                tension: 0.4,
                data: dataDeducted,
            }
        ]
    };
};
const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
    const surfaceBorder = documentStyle.getPropertyValue('--surface-border');

    return {
        locale: 'ru',
        stacked: true,
        maintainAspectRatio: false,
        aspectRatio: 0.8,
        plugins: {
            legend: {
               labels: {
                    color: textColor
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                },
                type: 'time',
                time: {
                    unit: 'day',
                    displayFormats: {
                        'day': 'LL',
                    },
                    tooltipFormat: 'LL',
                },
            },
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                ticks: {
                    color: textColorSecondary
                },
                grid: {                    
                    color: surfaceBorder
                }
            }
        }
    };
}

</script>
<template>
    <div>

        <Head title="График" />

        <Breadcrumbs :home="{ label: 'Главная', url: '/' }" :items="[
            { label: 'График' },
        ]" />

        <div class="bg-white rounded-md shadow overflow-hidden mt-4 p-8 h-full">
            <Chart type="line" :data="chartData" :options="chartOptions" class="h-96" />
        </div>

    </div>
</template>
