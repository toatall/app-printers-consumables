<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
import Breadcrumbs from '@/Shared/Breadcrumbs'
import { inject, ref, defineAsyncComponent, computed } from 'vue'
import Card from 'primevue/card'
import TabMenu from 'primevue/tabmenu'
import Chip from 'primevue/chip'
import DetailViewer from '@/Shared/DetailViewer'
import Button from 'primevue/button'
import { useDialog } from 'primevue/usedialog'
import ShowJournal from './ShowJournal.vue'

defineOptions({
    layout: Layout
})
const props = defineProps({
    consumableCount: Object,
    consumable: Object,
    consumableTitle: String,
    consumableCountLabels: Object,
    organizations: Array,
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
}

const bgColor = computed(() => props.consumableCount.count <= 1 ? 'bg-red-500' : 
    (props.consumableCount.count < 10 ? 'bg-yellow-500' : 'bg-primary-500') 
)

</script>
<template>

    <Head :title="title" />    

    <Breadcrumbs :home="{ label: 'Главная', url: urls.home }" :items="[
        { label: 'Количество расходных материалов', url: urls.consumables.counts.index() },
        { label: title },
    ]" />

        
    <TabMenu :model="items" />
    <Card>
        <template #content>
            <p class="m-0" v-if="visible == 1">
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

            </p>
            <p class="m-0" v-else-if="visible == 2">
                <ShowJournal :consumable="consumable" :consumableCount="consumableCount" />
            </p>
            <p class="m-0" v-else-if="visible == 3">
                3333
            </p>                    
        </template>                
    </Card>



</template>