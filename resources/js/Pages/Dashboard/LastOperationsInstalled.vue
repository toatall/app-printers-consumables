<script setup>
import axios from 'axios';
import { defineAsyncComponent, inject, onMounted, ref } from 'vue';
import ProgressSpinner from 'primevue/progressspinner';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import TableTitle from '@/Shared/TableTitle';
import Badge from 'primevue/badge';
import Button from 'primevue/button';
import { useDialog } from 'primevue/usedialog';

const urls = inject('urls')
const moment = inject('moment')
const dataTable = ref()
const loading = ref(false)
const dialog = useDialog()
let cartridgeColors = null
let consumableTypes = null

onMounted(() => {
    loading.value = true
    axios.get(urls.consumables.counts.installed())
        .then((response) => {
            if (Array.isArray(response.data.data)) {
                dataTable.value = response.data.data
                cartridgeColors = response.data.cartridgeColors
                consumableTypes = response.data.consumableTypes                
            }
        })
        .catch((error) => {

        })
        .finally(() => loading.value = false)

})
const title = `Установленные расходные материалы`

const MasterDialog = defineAsyncComponent(() => import('@/Pages/Consumable/MasterDialog.vue'))

const btnMasterDialog = () => {    
    dialog.open(MasterDialog, {
        props: {
            header: 'Взять расходный материал',
            style: {
                width: '50vw',
            },
            breakpoints:{
                '960px': '75vw',
                '640px': '90vw'
            },
            modal: true,
        },
    })
}


</script>
<template>
    <div class="flex content-center bg-white" v-if="loading">
        <ProgressSpinner />
    </div>
    <div v-else>
        <DataTable :value="dataTable" paginator :rows="10" dataKey="id" class="w-full rounded shadow">
            <template #header>
                <div class="flex justify-between">
                    <TableTitle>{{ title }}</TableTitle>
                    <Button label="Взять расходный материал" severity="info" size="small" @click="btnMasterDialog" />
                </div>
            </template>

            <Column header="Расходный материал">
                <template #body="{ data }">
                    <div class="grid grid-rows-2 gap-2">
                        <div class="text-nowrap">
                            <Badge :value="data.count" severity="success" v-tooltip="`Количество`"></Badge>
                            {{ consumableTypes[data.consumable_count.consumable.type] }}
                            {{ data.consumable_count.consumable.name }}
                        </div>
                        <div v-if="data.consumable_count.consumable.type == 'cartridge'">
                            <div class="flex">
                                <div :class="['rounded-full', 'size-4', 'mr-2', cartridgeColors[data.consumable_count.consumable.color]['bg']]"></div>
                                <div>
                                    {{ cartridgeColors[data.consumable_count.consumable.color]['name'] }}
                                </div>
                            </div>   
                        </div>                        
                    </div>
                </template>
            </Column>
            <Column header="Принтер">
                <template #body="{ data }">
                    <div 
                        class="grid grid-rows-2 gap-2" 
                        v-tooltip="`Серийный номер: ${data.printer_workplace.serial_number}, инвентарный номер: ${data.printer_workplace.inventory_number}`"
                        placeholder="Bottom"
                    >
                        <div class="text-nowrap">
                            <i class="pi pi-print"></i>
                            {{ data.printer_workplace.printer.vendor }}
                            {{ data.printer_workplace.printer.model }}
                        </div>
                        <div>
                            {{ data.printer_workplace.location }} кабинет
                        </div>
                    </div>
                </template>
            </Column>
            <Column header="Исполнитель">
                <template #body="{ data }">
                    {{ data.author?.fio ?? data.author.name }}
                </template>
            </Column>
            <Column header="Дата">
                <template #body="{ data }">
                    <div class="grid">
                        <div>{{ moment(data.created_at).fromNow() }}</div>
                        <div>{{ moment(data.created_at).format('LLLL') }}</div>
                    </div>
                </template>
            </Column>

        </DataTable>
    </div>
</template>