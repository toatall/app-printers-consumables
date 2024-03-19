<script setup>
import { Head, useForm } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
import Dropdown from 'primevue/dropdown'
import InputText from 'primevue/inputtext'
import InputNumber from 'primevue/inputnumber'
import LoadingButton from '@/Shared/LoadingButton'
import { reactive, ref, computed } from 'vue'
import Breadcrumbs from '@/Shared/Breadcrumbs'
import Label from '@/Shared/Label'
import Textarea from 'primevue/textarea'
import InlineMessage from 'primevue/inlinemessage'

defineOptions({
    layout: Layout
})

const props = defineProps({
    isNew: Boolean,
    consumable: Object,
    types: Array,
    printer: Object,
    labels: Object,
})

const consumable = reactive(props.consumable)
const printer = reactive(props.printer)

const form = useForm({    
    type_consumable: consumable.type_consumable,
    name: consumable.name,
    color: consumable.color,
    description: consumable.description,
    count_local: consumable.count_local,
    count_stock: consumable.count_stock,
})
const colors = ref([
    { name: 'Черный (black)', code: 'black', color: 'black' },
    { name: 'Голубой (blue)', code: 'blue', color: 'blue' },
    { name: 'Желтый (yellow)', code: 'yellow', color: 'yellow' },
    { name: 'Пурпурный (magenta)', code: 'magenta', color: 'purple' },
])

const printerFullName = computed({
    get() { 
        return props.printer.vendor + ' ' + props.printer.model
    }
})

const save = () => {    
    if (props.isNew) {
        form.post(`/printers/${printer.id}/consumables`)
    }
    else {
        form.put(`/printers/${printer.id}/consumables/${consumable.id}`)
    }
}

const destroy = () => {
    if (confirm('Вы уверены, что хотите удалить запись?')) {
        form.delete(`/printers/${printer.id}/consumables/${consumable.id}`)
    }
}

</script>

<template>
    <Head :title="printerFullName" />
    
    <Breadcrumbs :home="{ label: 'Главная', url: '/' }" :items="[
        { label: 'Принтеры и расходные материалы', url: '/printers' },
        { label: printerFullName, url: `/printers/${printer.id}/show` },
        { label: 'Добавление ресурса' },
    ]" />

    <form @submit.prevent="save">
        <div class="rounded-lg bg-white shadow-sm border border-gray-200">
            
            <div class="p-10">

                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <Label for="type_consumable">{{ labels.type_consumable }}</Label>
                        <Dropdown
                            class="w-full"
                            v-model="form.type_consumable"
                            id="type_consumable"
                            :options="types"
                            optionValue="code"
                            optionLabel="name"
                            :placeholder="labels.type_consumable"
                            :invalid="form.errors?.type_consumable?.length > 0"
                        />
                        <InlineMessage v-if="form.errors?.type_consumable" class="mt-2" severity="error">{{ form.errors?.type_consumable }}</InlineMessage>
                    </div>
                </div>

                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <Label for="name">{{ labels.name }}</Label>
                        <InputText 
                            class="w-full"
                            v-model="form.name" 
                            :placeholder="labels.name" 
                            :invalid="form.errors?.name?.length > 0" 
                        />
                        <InlineMessage v-if="form.errors?.name" class="mt-2" severity="error">{{ form.errors?.name }}</InlineMessage>
                    </div>
                </div>
                            
                <div v-if="form.type_consumable === 'cartridge'" class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <Label for="color">{{ labels.color }}</Label>
                        <Dropdown 
                            v-model="form.color" 
                            :options="colors" 
                            :invalid="form.errors?.color?.length > 0" 
                            optionValue="code" 
                            optionLabel="name" 
                            :placeholder="labels.color" 
                            class="w-full"
                        >                            
                        </Dropdown>
                        <InlineMessage v-if="form.errors?.color" class="mt-2" severity="error">{{ form.errors?.color }}</InlineMessage>
                    </div>
                </div>

                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <Label for="name">{{ labels.description }}</Label>
                        <Textarea 
                            class="w-full"
                            v-model="form.description"
                            rows="5"
                            :placeholder="labels.description" 
                            :invalid="form.errors?.description?.length > 0" 
                        />
                        <InlineMessage v-if="form.errors?.description" class="mt-2" severity="error">{{ form.errors?.description }}</InlineMessage>
                    </div>
                </div>

                <div v-if="isNew" class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <Label for="count_local">{{ labels.count_local }}</Label>
                        <InputNumber
                            class="w-full"
                            v-model="form.count_local" 
                            :placeholder="labels.count_local" 
                            :invalid="form.errors?.count_local?.length > 0" 
                        />
                        <InlineMessage v-if="form.errors?.count_local" class="mt-2" severity="error">{{ form.errors?.count_local }}</InlineMessage>
                    </div>
                </div>

                <div v-if="isNew" class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <Label for="count_stock">{{ labels.count_stock }}</Label>
                        <InputNumber
                            class="w-full"
                            v-model="form.count_stock" 
                            :placeholder="labels.count_stock" 
                            :invalid="form.errors?.count_stock?.length > 0" 
                        />
                        <InlineMessage v-if="form.errors?.count_stock" class="mt-2" severity="error">{{ form.errors?.count_stock }}</InlineMessage>
                    </div>                    
                </div>

            </div>           
            
            <div class="flex items-center justify-between p-5 py-4 bg-gray-50 border-t border-gray-100 w-full">
                <loading-button :loading="form.processing" class="font-bold" type="submit">Сохранить</loading-button>
                <loading-button :loading="form.processing" severity="danger" class="font-bold" @click="destroy" v-if="!isNew">Удалить</loading-button>
            </div>

        </div>
    </form>

</template>