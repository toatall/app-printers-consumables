<script setup>
import Layout from '@/Shared/Layout';
import { Head } from '@inertiajs/inertia-vue3';
import LastOperationsInstalled from './LastOperationsInstalled.vue';
import Chart from './Chart.vue';

defineOptions({
    layout: Layout,    
})

const props = defineProps({
    appName: String,
})

</script>
<template>
    <Head :title="appName" />   

    <div class="grid grid-cols-2 gap-5">
                
        <LastOperationsInstalled />

        <div class="bg-white rounded-lg border border-gray-100 shadow p-4">
            <Chart />
        </div>
        
    </div>
</template>
