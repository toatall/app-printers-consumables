<script setup>
import axios from 'axios';
import { defineAsyncComponent, inject, onMounted, reactive, ref } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import TableTitle from '@/Shared/TableTitle';
import Badge from 'primevue/badge';
import Button from 'primevue/button';
import { useDialog } from 'primevue/usedialog';
import { useToast } from 'primevue/usetoast';

const props = defineProps({
    auth: Object,
});

const auth = inject('auth');
const urls = inject('urls');
const config = inject('config');
const moment = inject('moment');
const dataTable = ref();
const loading = ref(false);
const dialog = useDialog();
const toast = reactive(useToast());
let cartridgeColors = null;
let consumableTypes = null;

const updateData = () => {
    axios.get(urls.consumables.counts.installed())
        .then((response) => {
            if (Array.isArray(response.data.data)) {
                dataTable.value = response.data.data;
                cartridgeColors = response.data.cartridgeColors;
                consumableTypes = response.data.consumableTypes;              
            }
        })
        .catch((error) => {
            toast.add({
                severity: 'error',
                summary: 'Ошибка',
                detail: error.message,
                life: config.toast.timeLife,
            })
            console.error(error);
        })
        .finally(() => loading.value = false);
}

onMounted(() => {
    loading.value = true;
    updateData();
})

// setInterval(() => {
//     if (!document.hidden) {
//         updateData();
//     }
// }, 60000);

const title = `Установленные расходные материалы`;

const InstalledDialog = defineAsyncComponent(() => import('@/Pages/Consumable/InstalledDialog.vue'));

const btnInstalledDialog = () => {    
    dialog.open(InstalledDialog, {
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
const fieldPrinter = (item) => {
    return `${item.printer_workplace.printer.vendor} ${item.printer_workplace.printer.model}`;
}


</script>
<template>    
    <DataTable :value="dataTable" :loading="loading" paginator :rows="3" dataKey="id" class="w-full rounded shadow h-[30rem] bg-white">
        <template #header>
            <div class="flex justify-between">
                <TableTitle>{{ title }}</TableTitle>
                <Button 
                    v-if="auth.can('admin', 'subtract-consumable')"
                    label="Вычесть расходный материал" 
                    icon="pi pi-minus-circle" 
                    severity="danger" 
                    size="small" 
                    @click="btnInstalledDialog" 
                />
            </div>
        </template>
        <Column header="Расходный материал" sortable field="count">
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
        <Column header="Принтер" :field="fieldPrinter" sortable>
            <template #body="{ data }">
                <div 
                    class="grid grid-rows-2 gap-2" 
                    v-tooltip="`Серийный номер: ${data.printer_workplace.serial_number}, инвентарный номер: ${data.printer_workplace.inventory_number}`"
                    placeholder="Bottom"
                >
                    <div>
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
        <Column header="Дата" field="created_at" sortable>
            <template #body="{ data }">
                <div class="grid gap-y-2">
                    <div>{{ moment(data.created_at).fromNow() }}</div>
                    <div>{{ moment(data.created_at).format('LLLL') }}</div>
                </div>
            </template>
        </Column>
    </DataTable>    
</template>