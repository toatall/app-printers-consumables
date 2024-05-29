<script setup>
import axios from 'axios';
import { useToast } from 'primevue/usetoast';
import { inject, onMounted, reactive, ref } from 'vue';
import ru from 'apexcharts/dist/locales/ru.json';

const props = defineProps({
    url: String,
    header: String,
    chartTitle: String,
    chartBgColor: String,
})

const moment = inject('moment');
const toast = reactive(useToast());
const config = inject('config');
const loadingChart = ref(false);
const chartOptions = ref({
    chart: {
        locales: [ru],
        defaultLocale: 'ru',
    },
    stroke: {
        curve: "smooth",
    },
    title: {
        text: props.header,
    },
    xaxis: {
        type: 'date',
        labels: {
            formatter: function (value, timestamp) {
                return value != null ? moment(value).format('L') : null;
            },
        },        
    },    
});
const chartSeries = ref([]);

const updateChart = () => {
    axios.get(props.url)
        .then((response) => {
            chartSeries.value = []
            chartOptions.value.xaxis.categories = []            
            if (Array.isArray(response.data) && response.data.length > 0) {              
                
                let result = []
                
                response.data.forEach((item) => {
                    result.push({
                        x: item.date,
                        y: item.count,
                    })
                })
                chartSeries.value.push(
                    {
                        name: props.chartTitle,
                        data: result,
                        color: props.chartBgColor,
                    },                    
                )
            }
        })
        .catch((error) => {
            toast.add({
                severity: 'error',
                summary: 'Ошибка',
                detail: error.message,
                life: config.toast.timeLife,
            })
            console.error(error)
        })  
        .finally(() => loadingChart.value = false) 
}

onMounted(() => {
    loadingChart.value = true
    updateChart()
})

setInterval(() => {
    if (!document.hidden) {
        updateChart();
    }
}, 60000);

</script>
<template>
    <div class="h-[30rem] bg-white shadow rounded p-12 grid content-center">
        <div v-show="loadingChart" class="flex align-middle justify-self-center">
            <i class="fa-solid fa-circle-notch fa-spin text-gray-400 text-4xl"></i>
        </div>
        <div v-show="!loadingChart">
            <apexchart type="line" class="h-full" :options="chartOptions" :series="chartSeries"></apexchart>
        </div>
    </div>
</template>