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

defineOptions({
    layout: Layout
})
const urls = inject('urls')
const confirm = useConfirm()
const props = defineProps({
    printerWorkplace: Object,
    printerLabels: Object,
    printerWorkplaceLabels: Object,    
    canGlobal: Object,
})

const printer = props.printerWorkplace.printer
const printerWorkplace = props.printerWorkplace
const printerLabels = props.printerLabels

const actions = {
    edit: () => Inertia.get(urls.printers.edit(printerWorkplace.id)),
    delete: () => confirm.require({
        message: 'Вы уверены, что хотите удалить?',
        header: 'Удаление',
        accept: () => Inertia.delete(urls.printers.delete(printerWorkplace.id)),
    }),
}
const title = `${printer.vendor} ${printer.model} (${printerWorkplace.location})`
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

                <div class="flex justify-between mt-10 font-bold">
                    <Button @click="actions.edit">Редактировать</Button>
                    <Button severity="danger" @click="actions.delete">Удалить</Button>             
                </div>
            </template>
        </Card>
        
    </div>

</template>

