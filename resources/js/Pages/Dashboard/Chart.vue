<script setup>
import axios from 'axios';
import { useToast } from 'primevue/usetoast';
import { inject, onMounted, reactive, ref } from 'vue';
import ru from 'apexcharts/dist/locales/ru.json';

const urls = inject('urls')
const moment = inject('moment')
const toast = reactive(useToast())
const config = inject('config')
const loadingChart = ref(false)
const chartOptions = ref({
    chart: {
        locales: [ru],
        defaultLocale: 'ru',
    },
    stroke: {
        curve: "smooth",
    },
    title: {
        text: 'Динамика установки расходных материалов',
    },
    xaxis: {
        type: 'date',
        labels: {
            formatter: function (value, timestamp) {                
                return value != null ? moment(value).format('L') : null
            },
        },        
    },    
})
const chartSeries = ref([])

onMounted(() => {
    loadingChart.value = true
    axios.get(urls.chart.last())
        .then((response) => {                        
            chartSeries.value = []
            chartOptions.value.xaxis.categories = []            
            if (Array.isArray(response.data) && response.data.length > 0) {              
                
                let resultDataAdded = []
                let resultDataInstalled = []
                
                response.data.forEach((item) => {
                    resultDataAdded.push({
                        x: item.date,
                        y: item.count_added,
                    })
                    resultDataInstalled.push({
                        x: item.date,
                        y: item.count_installed,
                    })
                })
                chartSeries.value.push(
                    {
                        name: 'Установлено расходных материалов',
                        data: resultDataInstalled,
                        color: '#ef4444',
                    },
                    {
                        name: 'Добавлено расходных материалов',
                        data: resultDataAdded,
                        color: '#22c55e',
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
})

</script>
<template>
    <div class="h-[30rem] bg-white shadow rounded p-12 grid content-center">
        <div v-show="loadingChart" class="flex align-middle justify-self-center">
            <i class="fa-solid fa-circle-notch fa-spin text-gray-400 text-4xl"></i>
        </div>
        <div v-show="!loadingChart">
            <apexchart type="bar" class="h-full" :options="chartOptions" :series="chartSeries"></apexchart>
        </div>
    </div>
</template>
