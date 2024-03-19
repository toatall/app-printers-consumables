<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
import Form from './Form'
import { reactive, ref } from 'vue'
import Breadcrumbs from '@/Shared/Breadcrumbs'

defineOptions({
    layout: Layout
})

const props = defineProps({
    labels: Object,
})
const labels = reactive(props.labels)
const title = 'Добавление принтера'
</script>

<template>
    <Head :title="title" />        

    <Breadcrumbs :home="{ label: 'Главная', url: '/' }" :items="[
        { label: 'Принтеры и расходные материалы', url: '/printers' },
        { label: title },
    ]" />

    <Form :isNew="true" :labels="labels" :printer="{ vendor: null, name: null, color_print: false }"></Form>    

</template>

