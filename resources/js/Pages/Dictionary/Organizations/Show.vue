<script setup>
import Layout from '@/Shared/Layout';
import { inject } from 'vue';
import { Head } from '@inertiajs/inertia-vue3';
import Breadcrumbs from '@/Shared/Breadcrumbs';
import Card from 'primevue/card';
import { Inertia } from '@inertiajs/inertia';
import { useConfirm } from "primevue/useconfirm";
import Button from 'primevue/button';

defineOptions({
    layout: Layout,
})

const props = defineProps({
    organization: Object,
    labels: Object,
})

const urls = inject('urls')
const moment = inject('moment')
const organization = props.organization
const labels = props.labels
const confirm = useConfirm()

const title = `${organization.name} (${organization.code})`
const goToEdit = () => Inertia.get(urls.dictionary.organizations.edit(organization.code))
const deleteOrganization = () => {
    confirm.require({
        message: 'Вы уверены, что хотите удалить запись?',
        header: 'Удаление записи',
        accept: () => {
            Inertia.delete(urls.dictionary.organizations.delete(organization.code))
        },
    })    
}
</script>

<template>

    <Head :title="title" /> 

    <Breadcrumbs :home="{ label: 'Главная', url: urls.home }" :items="[
        { label: 'Справочники' },
        { label: 'Организации', url: urls.dictionary.organizations.index() },
        { label: title },
    ]" />

    <Card>
        <template #title> {{ title }} </template>
        <template #content>
            <table class="w-1/2 text-left text-gray-700">
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th class="px-6 py-4">{{ labels.code }}</th>
                    <td class="px-6 py-4">
                        {{ organization.code }}
                    </td>
                </tr>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th class="px-6 py-4">{{ labels.name }}</th>
                    <td class="px-6 py-4">
                        <div class="flex">
                            {{ organization.name }}
                        </div>
                    </td>
                </tr>                
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th class="px-6 py-4">
                        {{ labels.created_at }}
                    </th>
                    <td class="px-6 py-4">
                        <div>
                            <i class="far fa-calendar"></i>
                            {{ moment(organization.created_at).fromNow() }}
                            ({{ moment(organization.created_at).format('LLLL') }})
                        </div> 
                    </td>
                </tr>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th class="px-6 py-4">
                        {{ labels.updated_at }}
                    </th>
                    <td class="px-6 py-4">
                        <div>
                            <i class="far fa-calendar-alt"></i>      
                            {{ moment(organization.updated_at).fromNow() }}
                            ({{ moment(organization.updated_at).format('LLLL') }})
                        </div>
                    </td>
                </tr>
            </table>

            <div class="flex justify-between mt-10">
                <Button class="font-bold" @click="goToEdit">Редактировать</Button>
                <Button severity="danger" class="font-bold" @click="deleteOrganization">Удалить</Button>
            </div>
        </template>
    </Card>

</template>