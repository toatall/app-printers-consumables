<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
import { watch, reactive, inject } from 'vue'
import Breadcrumbs from '@/Shared/Breadcrumbs'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Button from 'primevue/button'
import { Inertia } from '@inertiajs/inertia'
import TableTitle from '@/Shared/TableTitle'
import InputText from 'primevue/inputtext'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'

const props = defineProps({
    printers: Object,
    labels: Object,
    filters: Object,    
    canGlobal: Object,  
    consumable: Object,    
    consumableTypeValue: String,
})

defineOptions({
    layout: Layout
})
const consumable = props.consumable
const title = `Привязка принтера`
const urls = inject('urls')

const filters = reactive(props.filters)

const form = reactive({
    search: filters.search,
})
watch(
    () => form,
    throttle(() => {
        Inertia.get(urls.dictionary.consumables.printers.index(consumable.id), pickBy(form), { preserveState: true })
    }, 150),
    { deep: true }
)


const addPrinter = (id) => {
    Inertia.post(urls.dictionary.consumables.printers.add(consumable.id, id), {
        onSuccess: () => Inertia.get(urls.dictionary.components.show(consumable.id)),
    })
}

</script>
<template>
    
    <Head :title="title" />

    <Breadcrumbs :home="{ label: 'Главная', url: '/' }" :items="[
        { label: 'Расходные материалы (справочник)', url: urls.dictionary.consumables.index() },
        { label: `${props.consumableTypeValue} ${consumable.name}`, url: urls.dictionary.consumables.show(consumable.id) },
        { label: title },
    ]" />

    <div class="flex justify-stretch bg-white rounded-md shadow overflow-hidden mt-4">
        <DataTable :value="printers"
            paginator :rows="10"
            dataKey="id" :metaKeySelection="false"
            class="w-full" tableStyle="min-width: 50rem" selectionMode="single"            
        >
            <template #header>
                <TableTitle class="border-b border-gray-200 pb-2">{{ title }}</TableTitle>
                <div class="flex justify-between mt-5">
                    <Link :href="urls.dictionary.consumables.show(consumable.id)" v-if="canGlobal.editorStock">
                        <Button type="button" severity="secondary" outlined>
                            <i class="fas fa-chevron-circle-left me-3"></i>
                            Назад
                        </Button>
                    </Link>
                    <div v-else></div>                    
                    <span class="relative">
                        <i class="fas fa-search absolute top-2/4 -mt-2 left-3 text-surface-400"></i>
                        <InputText v-model="form.search" placeholder="Поиск" class="pl-10 font-normal"/>
                    </span>
                </div>                
            </template>
            <Column header="#" headerStyle="width:3rem">
                <template #body="slotProps">
                    {{ slotProps.index + 1 }}
                </template>
            </Column>
            <Column field="vendor" header="Производитель" sortable />
            <Column field="model" header="Модель" sortable />
            <Column field="is_color_print" header="Цветная печать" sortable>
                <template #body="{ data }">
                    {{ data.is_color_print ? 'Да' : 'Нет' }}
                </template>
            </Column>            
            <Column header="">
                <template #body="{ data }">
                    <Button @click="addPrinter(data.id)">
                        <i class="fas fa-check me-3"></i>
                        Выбрать
                    </Button>
                </template>
            </Column>
            
            <template #empty> Нет данных </template>

        </DataTable>       
    </div>        
    
</template>