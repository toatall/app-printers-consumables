<script setup>
import { Head, useForm } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
import Breadcrumbs from '@/Shared/Breadcrumbs'
import { inject, ref, defineAsyncComponent, computed } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Chip from 'primevue/chip'
import DetailViewer from '@/Shared/DetailViewer'
import Button from 'primevue/button'
import { useDialog } from 'primevue/usedialog'
import ShowJournal from './ShowJournal.vue'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import Label from '@/Shared/Label'
import Checkbox from 'primevue/checkbox'
import InlineMessage from 'primevue/inlinemessage'
import Panel from 'primevue/panel'


defineOptions({
    layout: Layout
})
const props = defineProps({
    consumableCount: Object,
    consumable: Object,
    consumableTitle: String,
    consumableCountLabels: Object,
    organizations: Array,
    organizationLabels: Object,
    allOrganizations: Array,
})
const urls = inject('urls')
const title = props.consumableTitle
const consumableCountLabels = props.consumableCountLabels
const dialog = useDialog()
const AddDialog = defineAsyncComponent(() => import('./AddDialog.vue'))
const visible = ref(1)
const items = ref([
    {
        label: 'Главная',
        icon: 'pi pi-home',
        command: () => {
            visible.value = 1
        }
    },
    {
        label: 'Журнал',
        icon: 'pi pi-replay',
        command: () => {
            visible.value = 2
        }
    },
    {
        label: 'Организации',
        icon: 'pi pi-list',
        command: () => {
            visible.value = 3
        }
    },    
])

const actions = {    
    add: () => {        
        const dialogRef = dialog.open(AddDialog, {
            props: {
                header: 'Добавить',
                style: {
                    width: '50vw',
                },
                breakpoints:{
                    '960px': '75vw',
                    '640px': '90vw'
                },
                modal: true,            
            },      
            data: {                
                idConsumable: props.consumable.id,
                id: props.consumableCount.id,
                organizations: Array.from(props.organizations),
                consumableCountLabels: props.consumableCountLabels,
            }
        })
    },
    organizationsEdit: () => {

    },
}

const bgColor = computed(() => props.consumableCount.count <= 1 ? 'bg-red-500' : 
    (props.consumableCount.count < 10 ? 'bg-yellow-500' : 'bg-primary-500') 
)

const visibleOrganizationsEdit = ref(false)
const form = useForm({
    id_consumable: props.consumable.id,
    count: 1,
    selectedOrganizations: Array.from(props.organizations).map((item) => item.code),
})
const saveOrganizations = () => {
    form.put(urls.consumables.counts.updateOrganizations(props.consumableCount.id), {
        onSuccess: () => visibleOrganizationsEdit.value = false
    })
}

</script>
<template>

    <Head :title="title" />    

    <Breadcrumbs :home="{ label: 'Главная', url: urls.home }" :items="[
        { label: 'Количество расходных материалов', url: urls.consumables.counts.index() },
        { label: title },
    ]" />

    <TabView lazy="true">
        <TabPanel>
            <template #header>
                <i class="pi pi-home me-2"></i> Главная
            </template>
            <Chip class="pl-0 pr-3">
                <span 
                    class="font-bold text-2xl text-surface-0 rounded-full w-12 h-12 flex items-center justify-center"
                    :class="bgColor"
                >
                    {{ consumableCount.count }}
                </span>
                <span class="ml-2 font-medium">
                    доступное количество
                </span>
                <Button text rounded icon="pi pi-plus" class="font-bold" @click="actions.add" />
            </Chip>

            <DetailViewer class="mt-8" :items="[                    
                { 
                    label: consumableCountLabels.created_at, 
                    value: props.consumableCount.created_at,
                    is_date: true,
                    icon: 'far fa-calendar',
                },
                { 
                    label: consumableCountLabels.updated_at, 
                    value: props.consumableCount.updated_at,
                    is_date: true,
                    icon: 'far fa-calendar-alt',                        
                },
            ]"></DetailViewer>  
        </TabPanel>

        <TabPanel>
            <template #header>
                <i class="pi pi-replay me-2"></i> Журнал
            </template>
            <ShowJournal :consumable="consumable" :consumableCount="consumableCount" />
        </TabPanel>

        <TabPanel>
            <template #header>
                <i class="pi pi-list me-2"></i> Организации
            </template>
            
            <div v-if="visibleOrganizationsEdit">
                <div>      
                    <form @submit.prevent="saveOrganizations">                        
                        <Panel header="Редактирование списка организаций">
                            <template #footer>
                                <Button severity="secondary" @click="visibleOrganizationsEdit = false" size="small" label="Назад" />
                                <Button type="submit" :loading="form.processing" class="ms-2" icon="pi pi-save" label="Сохранить" size="small" />
                            </template>                        
                            <div class="w-1/3">
                                <div class="w-full" id="organizations">                                                  
                                    <div v-for="organization in allOrganizations" :key="organization.code" class="flex items-center mt-2">
                                        <Checkbox v-model="form.selectedOrganizations" :inputId="organization.code" name="organizations" :value="organization.code" />
                                        <label :for="organization.code" class="ml-2 cursor-pointer">
                                            {{ `${organization.name} (${organization.code})` }}
                                        </label>
                                    </div>
                                </div>
                                <InlineMessage v-if="form.errors?.selectedOrganizations" class="mt-2" severity="error">{{ form.errors?.selectedOrganizations }}</InlineMessage>
                            </div>                                           
                        </Panel>
                    </form>
                </div>
            </div>

            <DataTable :value="organizations"
                dataKey="code"
                class="w-1/3"
                selectionMode="single"
                v-else
            >          
                <template #header>
                    <Button severity="success" @click="visibleOrganizationsEdit = true" size="small" label="Редактировать" />                        
                </template>      

                <Column :header="props.organizationLabels.code" field="code" sortable />
                <Column :header="props.organizationLabels.name" field="name" sortable />

                <template #empty> Нет данных </template>

            </DataTable>

        </TabPanel>

    </TabView>
    

</template>