<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
import { inject } from 'vue'
import Breadcrumbs from '@/Shared/Breadcrumbs'
import Button from 'primevue/button'
import { Inertia } from '@inertiajs/inertia'
import Card from 'primevue/card';
import { useConfirm } from "primevue/useconfirm"
import DetailViewer from '@/Shared/DetailViewer'
import DataTable from 'primevue/datatable'
import Badge from 'primevue/badge'
import Column from 'primevue/column'

defineOptions({
    layout: Layout
});
const urls = inject('urls');
const auth = inject('auth');
const confirm = useConfirm();
const props = defineProps({
    printerWorkplace: Object,
    printerLabels: Object,
    printerWorkplaceLabels: Object,    
    organization: Object,

    consumables: Object,
    consumableLabels: Object,
    consumableCountLabels: Object,
    consumableTypes: Object,
    cartridgeColors: Object,    
});

const printer = props.printerWorkplace.printer;
const printerWorkplace = props.printerWorkplace;
const printerLabels = props.printerLabels;

const actions = {
    edit: () => Inertia.get(urls.printers.edit(printerWorkplace.id)),
    delete: () => confirm.require({
        message: 'Вы уверены, что хотите удалить?',
        header: 'Удаление',
        accept: () => Inertia.delete(urls.printers.delete(printerWorkplace.id)),
    }),
};
const title = `${printer.vendor} ${printer.model} (${printerWorkplace.location})`;
</script>
<template>
    <div>
        <Head :title="title" />        

        <Breadcrumbs :home="{ label: 'Главная', url: urls.home }" :items="[
            { label: 'Принтеры', url: urls.printers.index() },
            { label: title },
        ]" />

        <Card>
            <template #title> {{ title }} </template>
            <template #content>
                <DetailViewer :items="[
                    { 
                        label: printerLabels.vendor, 
                        value: printer.vendor 
                    },
                    { 
                        label: printerLabels.model, 
                        value: printer.model },
                    { 
                        label: printerLabels.is_color_print, 
                        value: printer.is_color_print ? 'Да': 'Нет',
                    },
                    {
                        label: printerWorkplaceLabels.org_code, 
                        value: `${organization.name} (${organization.code})`,
                    },
                    { 
                        label: printerWorkplaceLabels.location, 
                        value: printerWorkplace.location,
                    },
                    { 
                        label: printerWorkplaceLabels.serial_number, 
                        value: printerWorkplace.serial_number,
                    },
                    { 
                        label: printerWorkplaceLabels.inventory_number, 
                        value: printerWorkplace.inventory_number,
                    },
                    { 
                        label: printerWorkplaceLabels.author, 
                        value: printerWorkplace.author.fio ?? printerWorkplace.author.name,
                    },
                    { 
                        label: printerWorkplaceLabels.created_at, 
                        value: printerWorkplace.created_at,
                        is_date: true,
                        icon: 'far fa-calendar',
                    },
                    { 
                        label: printerWorkplaceLabels.updated_at, 
                        value: printerWorkplace.updated_at,
                        is_date: true,
                        icon: 'far fa-calendar-alt',                        
                    },
                ]"></DetailViewer>

                <div v-if="auth.can('admin', 'editor-printer-workplace')" class="flex justify-between mt-10 font-bold">
                    <Button @click="actions.edit">Редактировать</Button>
                    <Button severity="danger" @click="actions.delete">Удалить</Button>             
                </div>
            </template>
        </Card>

        <Card class="mt-4">
            <template #title>Расходные материалы</template>
            <template #content>
                <DataTable :value="consumables"
                    paginator 
                    :rows="10" 
                    dataKey="id" 
                    :metaKeySelection="false"
                    class="w-full" 
                    tableStyle="min-width: 50rem" 
                    selectionMode="single"                        
                >
                    <Column :header="consumableLabels.type" field="type">
                        <template #body="{ data }">
                            {{ consumableTypes[data.type] }}
                        </template>
                    </Column>
                    <Column :header="consumableLabels.name" field="consumable.name">
                        <template #body="{ data }">
                            <div class="grid grid-rows-2 gap-4">
                                <div>
                                    {{ data.name }}
                                </div>
                                <div v-if="data.type === 'cartridge'">
                                    <div class="flex">
                                        <div :class="['rounded-full', 'size-4', 'mr-2', cartridgeColors[data.color]['bg']]"></div>
                                        <div>
                                            {{ cartridgeColors[data.color]['name'] }}
                                        </div>
                                    </div>               
                                </div>
                            </div>
                        </template>
                    </Column>
                    <Column :header="consumableCountLabels.count">
                        <template #body="{ data }">                            
                            <Badge 
                                :value="data.consumable_count?.count ?? 0" 
                                size="large" 
                                :severity="(data.consumable_count?.count ?? 0) <= 1 ? 'danger' 
                                    : ((data.consumable_count?.count ?? 0) < 10 ? 'warning' : 'success')" 
                            />
                        </template>
                    </Column>    
                </DataTable>
            </template>
        </Card>
        
    </div>

</template>

