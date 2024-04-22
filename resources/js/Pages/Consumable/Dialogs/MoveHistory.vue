<script setup>
import { ref, inject, onMounted } from 'vue'
import axios from 'axios'
import ProgressSpinner from 'primevue/progressspinner'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
// import { ConfigUrl } from '@/config'
import Button from 'primevue/button'
import { Inertia } from '@inertiajs/inertia'
import Message from 'primevue/message'
import { DateHelper } from '@/Helpers/DateHelper'

const loading = ref()
const dialogRef = inject('dialogRef')
const labelsConsumable = ref()
const printer = ref()
const id = ref()
const data = ref()
const isAdmin = ref()
const loadingDel = ref(false)
const errorMessage = ref()
let idDelete = 0

onMounted(() => {
    labelsConsumable.value = dialogRef.value.data.labelsConsumable
    printer.value = dialogRef.value.data.printer
    id.value = dialogRef.value.data.id   
    isAdmin.value = dialogRef.value.data.canGlobal.admin
    update()    
})

const update = () => {
    loading.value = true
    errorMessage.value = null
    axios.get(ConfigUrl.move.history
        .replace('{printer-id}', printer.value.id)
        .replace('{consumable-id}', id.value))
    .then(response => {        
        data.value = response.data
    })
    .catch((error) => {
        console.log(error)
        errorMessage.value = error.message
    })
    .finally(() => {
        loading.value = false
    })
}

const redo = (id) => {    
    if (confirm('Вы уверены, что хотите отменить операцию?')) {
        idDelete = id
        loadingDel.value = true
        Inertia.delete(`/consumable-move/${id}/history`, {
            onSuccess: () => {                 
                update()
                loadingDel.value = false
                idDelete = 0
            }
        })
    }
}

</script>
<template>    
    <div v-if="loading" class="flex justify-center">
        <ProgressSpinner />
    </div>

    <Message v-else-if="errorMessage" severity="error" :closable="false">
        {{ errorMessage }}
    </Message>
    
    <DataTable v-else :value="data" tableStyle="min-width: 50rem" class="m-4" selectionMode="single">  
        <Column field="id" header="ИД" sortable />
        <Column field="user.fio" header="Пользователь" sortable>
            <template #body="{ data }">
                <div v-if="data?.user?.fio" class="mb-2">
                    {{ data.user.fio }}
                </div>
                {{ data?.user?.name ?? 'Неизвестно'  }}
            </template>
        </Column>
        <Column header="Статус">
            <template #body="item">
                <div class="font-bold">
                    <template v-if="item.data.type_move == 'ADD'">
                        <i class="fas fa-arrow-up text-green-600"></i> добавлено<br />({{ item.data.count_local }} шт.)
                    </template>
                    <template v-if="item.data.type_move == 'TAKE_LOCAL'">
                        <i class="fas fa-arrow-down text-red-600"></i> установлено<br />({{ item.data.count_local * -1 }} шт.)
                    </template>
                </div>
            </template>
        </Column>      
        <Column field="description" header="Описание" sortable />
        <Column field="created_at" header="Дата" sortable>
            <template #body="{ data }">
                <span v-tooltip="DateHelper.formatLong(data.created_at)">
                    {{ DateHelper.relative(data.created_at) }}
                </span>
            </template>
        </Column>
        <Column header="" v-if="isAdmin">
            <template #body="{ data }">
                <Button 
                    v-tooltip="`Отменить`" 
                    icon="fas fa-redo-alt fa-flip-horizontal" 
                    @click="redo(data.id)" 
                    :loading="loadingDel && data.id === idDelete" />
            </template>
        </Column>
    </DataTable>    
</template>