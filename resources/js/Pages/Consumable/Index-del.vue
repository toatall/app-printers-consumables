<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import { reactive } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { Inertia } from '@inertiajs/inertia'
import TableTitle from '@/Shared/TableTitle'
import Button from 'primevue/button'
import Tag from 'primevue/tag'

const props = defineProps({
    consumables: Array,
    printer: Object,
    typesConsumables: Object,
    labelsConsumable: Object,
    cartridgeColors: Object,
    canGlobal: Object,
})
const printer = reactive(props.printer)
const typesConsumables = reactive(props.typesConsumables)
const cartridgeColors = reactive(props.cartridgeColors)

const onRowSelect = (event) => {
    Inertia.get(`/printers/${printer.id}/consumables/${event.data.id}/edit`)
}

function getTypeConsumableLabel(key) {
    return typesConsumables[key] ?? key
}
function getColor(key) {
    return cartridgeColors[key]
}

</script>

<template>
    <DataTable :value="consumables" tableStyle="min-width: 50rem" class="m-4" selectionMode="single" @rowSelect="onRowSelect">        
        <template #header>
            <div class="flex flex-wrap items-center justify-between gap-2">                
                <TableTitle>Расходные материалы</TableTitle>
                <Link :href="`/printers/${printer.id}/consumables/create`" v-if="canGlobal.editorStock">
                    <Button>Добавить</Button>
                </Link>
            </div>
        </template>
        <Column field="type_consumable" header="Вид">
            <template #body="slotProps">
                {{ getTypeConsumableLabel(slotProps.data.type_consumable) }}
                <div class="mt-2" v-if="slotProps.data.count_local < 1">
                    <Tag icon="pi pi-exclamation-triangle" severity="warning">Ресурс закончился!</Tag>
                </div>
            </template>
        </Column>
        <Column field="name" header="Наименование (модель)" sortable>
            <template #body="slotProps">
                {{ slotProps.data.name }}
                <small v-if="slotProps.data.type_consumable == 'cartridge'" class="flex mt-2">                      
                    <div class="flex">
                        <div :class="['rounded-full', 'size-4', 'mr-2', getColor(slotProps.data.color).color]"></div>
                        <div>
                            {{ getColor(slotProps.data.color).name }}
                        </div>
                    </div>                    
                </small>
            </template>
        </Column>           
        <Column field="description" header="Описание" sortable></Column>
        
        <template v-if="consumables.length === 0" #footer> Нет данных </template>

    </DataTable>

    
</template>