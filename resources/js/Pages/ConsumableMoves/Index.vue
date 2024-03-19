<script setup>
import Layout from '@/Shared/Layout'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import { reactive, watch } from 'vue';
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';
import { Inertia } from '@inertiajs/inertia';
import TableTitle from '@/Shared/TableTitle';
import { Head } from '@inertiajs/inertia-vue3';
import { DateHelper } from '@/Helpers/DateHelper';

defineOptions({
    layout: Layout,    
})

const props = defineProps({
    filters: Object,
    moves: Object,
    canGlobal: Object,
    cartridgeColors: Object,
    typesConsumables: Object,
})
const cartridgeColors = reactive(props.cartridgeColors)
const typesConsumables = reactive(props.typesConsumables)
const filters = reactive(props.filters)
const form = reactive({
    search: filters.search,
})

function getColor(key) {
    return cartridgeColors[key]
}
function getTypeConsumableLabel(key) {
    return typesConsumables[key] ?? key
}
watch(
    () => form,
    throttle(() => {
        Inertia.get('/consumable-moves', pickBy(form), { preserveState: true })
    }, 150),
    { deep: true }
)

</script>
<template>

    <Head title="Журнал" />    

    <div class="flex justify-stretch bg-white rounded-md shadow overflow-hidden mt-4">
        <DataTable 
            :value="moves" paginator :rows="10" 
            :sortOrder="-1" sortField="created_at" 
            dataKey="id" 
            tableStyle="min-width: 50rem" 
            class="w-full" selectionMode="single"
        >
            <template #header>
                <TableTitle class="border-b border-gray-200 pb-2">                    
                    Журнал
                </TableTitle>
                <div class="flex justify-end mt-5">                                 
                    <span class="relative">
                        <i class="fas fa-search absolute top-2/4 -mt-2 left-3 text-surface-400"></i>
                        <InputText v-model="form.search" placeholder="Поиск" class="pl-10 font-normal"/>
                    </span>
                </div>                
            </template>

            <Column field="id" header="ИД" sortable />    
            <Column field="type_move" header="Статус" sortable>
                <template #body="{ data }">
                    <div class="font-bold">
                        <template v-if="data.type_move == 'ADD'">
                            <i class="fas fa-arrow-up text-green-600"></i> добавлено<br />({{ data.count_local }} шт.)
                        </template>
                        <template v-if="data.type_move == 'TAKE_LOCAL'">
                            <i class="fas fa-arrow-down text-red-600"></i> установлено<br />({{ Math.abs(data.count_local) }} шт.)
                        </template>
                    </div>
                </template>
            </Column>
            <Column field="consumable.name" header="Расходный материал">
                <template #body="{ data }">
                    {{ getTypeConsumableLabel(data.consumable.type_consumable) }}
                    {{ data.consumable.name }}
                    <small v-if="data.consumable.type_consumable == 'cartridge'" class="flex mt-2">
                        <div class="flex">
                            <div :class="['rounded-full', 'size-4', 'mr-2', getColor(data.consumable.color).color]"></div>
                            <div>
                                {{ getColor(data.consumable.color).name }}
                            </div>
                        </div>                    
                    </small>
                </template>
            </Column>
            <Column field="consumable?.printer?.vendor" header="Принтер">
                <template #body="{ data }">
                    {{ data.consumable?.printer?.vendor }}
                    {{ data.consumable?.printer?.model }}
                </template>        
            </Column>
            <Column field="user.fio" header="Сотрудник" sortable>
                <template #body="{ data }">
                    <div v-if="data?.user?.fio" class="mb-2">
                        {{ data.user.fio }}
                    </div>
                    {{ data?.user?.name ?? 'Неизвестно'  }}
                </template>
            </Column>
            <Column field="description" header="Описание" sortable />
            <Column field="created_at" header="Дата" :sortable="true">
                <template #body="{ data }">
                    <span v-tooltip="DateHelper.formatLong(data.created_at)">
                    {{ DateHelper.relative(data.created_at) }}
                    </span>
                </template>
            </Column>
        </DataTable>
    </div>
</template>