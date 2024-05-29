<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
import { ref, inject } from 'vue'
import Breadcrumbs from '@/Shared/Breadcrumbs'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Button from 'primevue/button'
import { Inertia } from '@inertiajs/inertia'
import TableTitle from '@/Shared/TableTitle'

const props = defineProps({
    organizations: Object,
    labels: Object,
    filters: Object,    
});

defineOptions({
    layout: Layout
});

const title = 'Организации';
const urls = inject('urls');
const moment = inject('moment');

const selectedRow = ref();

const onRowSelect = (event) => {
    Inertia.get(urls.dictionary.organizations.show(event.data.code));
};
</script>
<template>
    
    <Head :title="title" />

    <Breadcrumbs :home="{ label: 'Главная', url: urls.home }" :items="[
        { label: 'Справочники' },
        { label: title },
    ]" />

    <div class="flex justify-stretch bg-white rounded-md shadow overflow-hidden mt-4">
        <DataTable :value="organizations"
            paginator :rows="10" v-model:selection="selectedRow"
            dataKey="code" :metaKeySelection="false"
            class="w-full" tableStyle="min-width: 50rem" selectionMode="single"
            @rowSelect="onRowSelect"
        >
            <template #header>
                <TableTitle class="border-b border-gray-200 pb-2">{{ title }}</TableTitle>
                <div class="flex justify-between mt-5">
                    <Link :href="urls.dictionary.organizations.create()">
                        <Button type="button" severity="info">Добавить организацию</Button>
                    </Link>                   
                </div>                
            </template>
            <Column header="#" headerStyle="width:3rem">
                <template #body="slotProps">
                    {{ slotProps.index + 1 }}
                </template>
            </Column>
            <Column field="code" :header="labels.code" dataType="string" sortable />
            <Column field="name" :header="labels.name" sortable />
            <Column field="created_at" :header="labels.date" sortable>
                <template #body="{ data }">       
                    <div class="grid grid-rows-2 gap-2">
                        <div v-tooltip="`Создано: ${moment(data.created_at).format('LLLL')}`">
                            <i class="far fa-calendar"></i>
                            {{ moment(data.created_at).fromNow() }}
                        </div>                    
                        <div v-if="data.created_at != data.updated_at" v-tooltip="`Изменено: ${moment(data.updated_at).format('LLLL')}`">
                            <i class="far fa-calendar-alt"></i>      
                            {{ moment(data.updated_at).fromNow() }}
                        </div>
                    </div>
                </template>
            </Column>            

            <template #empty> Нет данных </template>

        </DataTable>       
    </div>        
    
</template>