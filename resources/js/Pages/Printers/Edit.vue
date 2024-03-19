<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
import { ref, computed, reactive } from 'vue'
import Breadcrumbs from '@/Shared/Breadcrumbs'
import Form from './Form'

defineOptions({
    layout: Layout
})

const props = defineProps({
    printer: Object,
    labels: Object,
    consumables: Array,
})
const labels = ref(props.labels)
const printer = reactive(props.printer)
const title = computed({
    get() { 
        return props.printer.vendor + ' ' + props.printer.model
    }
})

</script>
<template>
    
    <Head :title="title" />        

    <Breadcrumbs :home="{ label: 'Главная', url: '/' }" :items="[
        { label: 'Принтеры и расходные материалы', url: '/printers' },
        { label: title, url: `/printers/${printer.id}/show` },
        { label: 'Редактирование' },
    ]" />

    <Form :isNew="false" :labels="labels" :printer="printer"></Form>        
    
</template>

