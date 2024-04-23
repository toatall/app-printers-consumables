<script setup>
import axios from 'axios';
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import { inject, ref, onMounted, reactive } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Button from 'primevue/button'
import { Inertia } from '@inertiajs/inertia'
import { useToast } from 'primevue/usetoast'
import { useConfirm } from "primevue/useconfirm"


const props = defineProps({
    consumable: Object,
    consumableCount: Object,    
    isOpen: Boolean,
})
const urls = inject('urls')
const moment = inject('moment')
const toast = reactive(useToast())
const confirm = useConfirm()

const idConsumable = props.consumableCount.id_consumable
const idConsumableCount = props.consumableCount.id

const dataItemsAdded = ref()
const dataItemsInstalled = ref()


const updateJournalAdded = () => {    
    axios.get(urls.consumables.counts.journal.added.index(idConsumable, idConsumableCount))
        .then((response) => {
            dataItemsAdded.value = response.data
        })
        .catch((error) => {
            toast.add({
                severity: 'error',
                summary: 'Ошибка загрузки журнала добавления расходных материалов',
                detail: error.message,
                life: 5000,
            })
            console.error(error)
        })
}
const updateJournalInstalled = () => {    
    axios.get(urls.consumables.counts.journal.added.index(idConsumable, idConsumableCount))
        .then((response) => {
            dataItemsAdded.value = response.data
        })
        .catch((error) => {
            toast.add({
                severity: 'error',
                summary: 'Ошибка загрузки журнала добавления расходных материалов',
                detail: error.message,
                life: 5000,
            })
            console.error(error)
        })
}

onMounted(() => {   
    updateJournalAdded()
})

const journalAdded = {
    loadingDelete: ref(false),
    idDelete: ref(0),
}

const redoJournalAdded = (id) => {        
    confirm.require({
        message: 'Вы уверены, что хотите отменить операцию?',
        header: 'Отмена операции',
        accept: () => {
            journalAdded.idDelete.value = id
            journalAdded.loadingDelete.value = true

            Inertia.delete(urls.consumables.counts.journal.added.redo(idConsumable, idConsumableCount, id), {
                onSuccess: () => {
                    updateJournalAdded()
                    
                    journalAdded.idDelete.value = 0
                    journalAdded.loadingDelete.value = false
                },
            })
        },
    })
}


</script>
<template>    
    <TabView>
        <TabPanel>
            <template #header>         
                <div class="text-green-600">
                    <i class="fa-solid fa-arrow-up fa-rotate-by text-green-600" style="--fa-rotate-angle: 45deg;"></i>
                    Добавлены
                </div>
            </template>
            <DataTable :value="dataItemsAdded"
                paginator 
                :rows="10" 
                dataKey="id" 
                :metaKeySelection="false"
                class="w-full" 
                tableStyle="min-width: 50rem" 
                selectionMode="single"                        
            >
                <Column header="#" headerStyle="width:3rem" sortable>
                    <template #body="slotProps">
                        {{ slotProps.index + 1 }}
                    </template>                        
                </Column>
                <Column header="Количество" field="count" sortable />
                <Column header="Автор" field="author.name" sortable>>
                    <template #body="{ data }">
                        <div v-if="data?.author?.fio" class="mb-2">
                            {{ data.author.fio }}
                        </div>
                        {{ data?.author?.name ?? 'Неизвестно' }}
                    </template>
                </Column>
                <Column field="created_at" header="Дата" sortable>
                    <template #body="{ data }">
                        <div>
                            <div v-tooltip="moment(data.created_at).format('LLLL')">
                                <i class="far fa-calendar"></i>
                                {{ moment(data.created_at).fromNow() }}
                            </div>                                                    
                        </div>
                    </template>
                </Column>
                <Column header="">
                    <template #body="{ data }">
                        <Button 
                            v-tooltip="`Отменить`" 
                            icon="fas fa-redo-alt fa-flip-horizontal" 
                            @click="redoJournalAdded(data.id)" 
                            :loading="journalAdded.loadingDelete && data.id === journalAdded.idDelete.value" />
                    </template>
                </Column>

                <template #empty> Нет данных </template>

            </DataTable>            
        </TabPanel>
        <TabPanel>
            <template #header>
                <div class="text-red-600">
                    <i class="fa-solid fa-arrow-down fa-rotate-by text-red-600" style="--fa-rotate-angle: -45deg;"></i>
                    Установлены
                </div>
            </template>
        </TabPanel>

    </TabView>


</template>