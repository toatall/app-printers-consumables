<script setup>
import Layout from '@/Shared/Layout'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { defineAsyncComponent, reactive, ref, watch } from 'vue'
import InputText from 'primevue/inputtext'
import IconField from 'primevue/iconfield'
import InputIcon from 'primevue/inputicon'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'
import { Inertia } from '@inertiajs/inertia'
import Tag from 'primevue/tag'
import Button from 'primevue/button'
import { useDialog } from 'primevue/usedialog'
import { Head } from '@inertiajs/inertia-vue3'


defineOptions({
    layout: Layout
})
const dialog = useDialog()
const AddCount = defineAsyncComponent(() => import('@/Pages/Consumable/Dialogs/AddCount.vue'))
const TakeCount = defineAsyncComponent(() => import('@/Pages/Consumable/Dialogs/TakeCount.vue'))
const MoveHistory = defineAsyncComponent(() => import('@/Pages/Consumable/Dialogs/MoveHistory.vue'))

const props = defineProps({
    consumables: Object,
    filters: Object,
    canGlobal: Object,
    typesConsumables: Object,
    cartridgeColors: Object,
    labelsConsumable: Object,    
})
const loading = ref(false)

const filters = reactive(props.filters)

const form = reactive({
    search: filters.search,
})
const typesConsumables = reactive(props.typesConsumables)
const cartridgeColors = reactive(props.cartridgeColors)
const getTypeConsumableLabel = (key) => typesConsumables[key] ?? key
const getCartridgeColor = (key) => cartridgeColors[key] ?? key

watch(
    () => form,
    throttle(() => {
        Inertia.get('/', pickBy(form), { 
            preserveState: true,
            onFinish: () => {
                loading.value = false
            },
            onProgress: (e) => {
                console.log(e)
            }
        })
    }, 1),
    { deep: true }
)

const addResource = (id, printer, title) => {
    const dialogRef = dialog.open(AddCount, {
        props: {
            header: title,
            style: {
                width: '20rem',
            },
            breakpoints:{
                '960px': '75vw',
                '640px': '90vw'
            },
            modal: true,            
        },
        data: {
            labelsConsumable: props.labelsConsumable,
            id: id,
            printer: printer,
        }
    })
}
const takeResource = (id, printer, title) => {
    const dialogRef = dialog.open(TakeCount, {
        props: {
            header: title,
            style: {
                width: '20rem',
            },
            breakpoints:{
                '960px': '75vw',
                '640px': '90vw'
            },
            modal: true,            
        },
        data: {
            labelsConsumable: props.labelsConsumable,
            id: id,
            printer: printer,
        }
    })
}

const showHistory = (id, printer) => {
    const dialogRef = dialog.open(MoveHistory, {
        props: {
            header: 'История',            
            modal: true,            
        },
        data: {
            canGlobal: props.canGlobal,
            labelsConsumable: props.labelsConsumable,
            id: id,
            printer: printer,
        }
    })
}

</script>

<template>
    <Head title="Система учета картриджей" />    
    <div class="flex justify-stretch bg-white rounded-md shadow overflow-hidden mt-4">
        <DataTable 
            :value="consumables"
            paginator
            :rows="20"
            dataKey="id" :metaKeySelection="false"
            class="w-full" tableStyle="min-width: 50rem" 
            selectionMode="single"
            :loading="loading"
        >
            <template #header>                
                <div class="flex justify-end">
                    <IconField iconPosition="left">
                        <InputIcon><i class="pi pi-search"></i></InputIcon>
                        <InputText v-model="form.search" placeholder="Поиск"></InputText>
                    </IconField>
                </div>                
            </template>

            <template #empty> Нет данных. </template>
            <template #loading> Загрузка. </template>

            <Column field="id" header="ИД"></Column>
            <Column field="name_consumable" header="Наименование">
                <template #body="item">
                    <div class="flex align-items-center gap-2">
                        <div>
                            {{ getTypeConsumableLabel(item.data.type_consumable) }}
                        </div>                        
                        <div v-if="item.data.type_consumable == 'cartridge'">
                            <div                                        
                                :class="`ml-2 rounded-full h-4 w-4 ${getCartridgeColor(item.data.color).color}`"
                                v-tooltip="`${getCartridgeColor(item.data.color).name}`"
                            ></div>
                        </div>
                        <div>
                            {{ item.data.name }}
                        </div>
                    </div>
                    <div class="mt-2">
                        <Tag severity="danger" v-if="item.data.count_local <= 0">
                            Нет в наличии
                        </Tag>
                    </div>
                </template>
            </Column>

            <Column field="full_name" header="Принтер" style="min-width: 200px">
                <template #body="data">
                    <div class="flex align-items-center gap-2">                       
                        <span>{{ data.data.printer.full_name }}</span>         
                        <div v-if="data.data.printer.color_print" v-tooltip="'Цветная печать'">        
                            <svg                                 
                                version="1.1" xmlns="http://www.w3.org/2000/svg" 
                                xmlns:xlink="http://www.w3.org/1999/xlink" 
                                viewBox="0 0 512 512" xml:space="preserve" fill="#000000"
                                class="h-4 w-4"
                                >                               
                                <g> 
                                    <circle style="fill:#EA348B;" cx="156.775" cy="341.5" r="156.775"></circle>
                                    <circle style="fill:#FFDA44;" cx="355.225" cy="341.5" r="156.775"></circle>
                                    <circle style="fill:#99EFF2;" cx="255.53" cy="170.5" r="156.775"></circle>
                                        <path style="fill:#FFFFFF;" d="M399.023,199.001c0,0-100.395-13.715-143.023,21.116c-42.88-35.039-142.023-21.116-142.023-21.116 s34.389,98.73,86.343,118.271c-8.463,54.14,55.184,124.018,55.681,124.018c0.499,0,64.229-70.123,55.621-124.374 C363.214,297.131,399.023,199.001,399.023,199.001z"></path> 
                                        <path style="fill:#91DC5A;" d="M256,220.117c14.291,11.678,26.754,26.087,36.53,43.019c9.888,17.128,16.168,35.347,19.092,53.78 c51.591-19.786,90.066-66.045,98.784-122.155C357.575,174.829,298.627,185.286,256,220.117z"></path> 
                                        <path style="fill:#006DF0;" d="M200.32,317.272c2.9-18.554,9.198-36.898,19.152-54.136c9.774-16.932,22.238-31.339,36.529-43.019 c-42.88-35.039-102.273-45.415-155.344-25.003C109.535,251.405,148.367,297.731,200.32,317.272z"></path> 
                                        <path d="M256,220.117c-14.291,11.678-26.753,26.087-36.529,43.019c-9.952,17.24-16.251,35.584-19.152,54.136 c17.167,6.458,35.759,9.999,55.182,9.999c19.778,0,38.695-3.671,56.121-10.356c-2.926-18.434-9.204-36.653-19.092-53.78 C282.756,246.204,270.291,231.797,256,220.117z"></path> 
                                        <path style="fill:#D80027;" d="M311.622,316.916c-17.426,6.683-36.343,10.356-56.121,10.356c-19.423,0-38.015-3.54-55.182-9.999 c-8.463,54.14,12.08,110.071,55.681,145.633C299.699,427.266,320.232,371.167,311.622,316.916z"></path> 
                                </g>
                            </svg>
                        </div>       
                    </div>
                </template>
            </Column>

            <Column field="count_local" header="Количество"></Column>            
            <Column :exportable="false" style="min-width:8rem">
                <template #body="{ data }">
                    
                    <Button
                        @click="addResource(data.id, data.printer, `${getTypeConsumableLabel(data.type_consumable)} ${data.name}`)"
                        v-if="canGlobal.editorStock"
                        class="p-0"                        
                    ><i class="fas fa-plus"></i>
                    </Button>

                    <Button                            
                        v-if="data.count_local > 0 && (canGlobal.editorStock || canGlobal.editorLocal)"
                        @click="takeResource(data.id, data.printer, `${getTypeConsumableLabel(data.type_consumable)} ${data.name}`)"                                                
                        severity="danger"
                        class="p-2 ml-2"
                    ><i class="fas fa-minus"></i></Button> 

                    <Button
                        v-if="canGlobal.reader"   
                        severity="info"
                        class="ml-2"
                        @click="showHistory(data.id, data.printer)"
                        v-tooltip="`История`"
                    ><i class="fas fa-history"></i></Button>

                </template>
            </Column>
            
        </DataTable>
    </div>

</template>
