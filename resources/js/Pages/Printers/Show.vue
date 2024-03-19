<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
import Consumable from '@/Pages/Consumable/Index'
import { computed, reactive } from 'vue'
import Breadcrumbs from '@/Shared/Breadcrumbs'
import Button from 'primevue/button'
import LoadingButton from '@/Shared/LoadingButton'
import { Inertia } from '@inertiajs/inertia'
import TableTitle from '@/Shared/TableTitle'
import { DateHelper } from '@/Helpers/DateHelper'

defineOptions({
    layout: Layout
})

const props = defineProps({
    printer: Object,
    labelsPrinter: Object,
    labelsConsumable: Object,
    consumables: Array,
    typesConsumables: Object,
    cartridgeColors: Object,
    canGlobal: Object,
})
const printer = reactive(props.printer)
const typesConsumables = reactive(props.typesConsumables)

const printerFullName = computed({
    get() { 
        return props.printer.vendor + ' ' + props.printer.model
    }
})

const editPrinter = () => {
    Inertia.get(`/printers/${printer.id}/edit`)
}
const destroyPrinter = () => {
    if (confirm('Вы уверены, что хотите удалить принтер?')) {
        Inertia.delete(`/printers/${printer.id}`)
    }    
}

</script>

<template>
    <div>
        <Head :title="printerFullName" />        

        <Breadcrumbs :home="{ label: 'Главная', url: '/' }" :items="[
            { label: 'Принтеры и расходные материалы', url: '/printers' },
            { label: printerFullName },
        ]" />

        <div class="rounded-lg bg-white shadow-sm border border-gray-200">                           
            <div class="relative m-4 overflow-x-auto shadow-md sm:rounded-lg w-1/2">
                <TableTitle>Описание принтера</TableTitle>         
                <table class="text-gray-700 w-full mt-2">
                    <tr class="border-b border-gray-200">
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50">{{ labelsPrinter.vendor }}</td>
                        <td scope="row" class="px-6 py-4">{{ printer.vendor }}</td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50">{{ labelsPrinter.model }}</td>
                        <td scope="row" class="px-6 py-4">{{ printer.model }}</td>                        
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50">{{ labelsPrinter.color_print }}</td>
                        <td scope="row" class="px-6 py-4">{{ printer.color_print ? 'Да' : 'Нет' }}</td>                        
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50">{{ labelsPrinter.created_at }}</td>
                        <td scope="row" class="px-6 py-4">                            
                            <span v-tooltip="DateHelper.formatLong(printer.created_at)">
                                {{ DateHelper.relative(printer.created_at) }}
                            </span>
                        </td>                        
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50">{{ labelsPrinter.updated_at }}</td>
                        <td scope="row" class="px-6 py-4">
                            <span v-tooltip="DateHelper.formatLong(printer.updated_at)">
                                {{ DateHelper.relative(printer.updated_at) }}
                            </span>
                        </td>                        
                    </tr>
                </table>
            </div>
            <div v-if="canGlobal.editorStock" class="flex items-center justify-between p-8 py-4 bg-gray-50 border-t border-gray-100 w-full">
                <Button class="font-bold" @click="editPrinter">
                    Редактировать
                </Button>                
                <LoadingButton class="font-bold" severity="danger" @click="destroyPrinter">Удалить</LoadingButton>                
            </div>
        </div>

        <div class="flex justify-stretch bg-white rounded-md shadow overflow-hidden mt-4">
            <consumable 
                :consumables="consumables" 
                :labelsConsumable="labelsConsumable" 
                :printer="printer" 
                :typesConsumables="typesConsumables" 
                :cartridgeColors="cartridgeColors"
                :canGlobal="canGlobal"
            />
        </div>
        
    </div>

</template>

