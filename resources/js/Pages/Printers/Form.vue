<script setup>
import InputText from 'primevue/inputtext'
import InlineMessage from 'primevue/inlinemessage'
import Label from '@/Shared/Label'
import { inject, reactive } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import Button from 'primevue/button'
import Dropdown from 'primevue/dropdown'

const props = defineProps({    
    labels: Object,
    printers: Object,
    printerWorkplace: Object,
});
const urls = inject('urls');
const printerWorkplace = reactive(props.printerWorkplace);

const form = useForm({
    id: printerWorkplace.id,
    id_printer: printerWorkplace.id_printer,        
    location: printerWorkplace.location,
    serial_number: printerWorkplace.serial_number,
    inventory_number: printerWorkplace.inventory_number,    
});
const isNew = printerWorkplace.id === null;
const save = () => {    
    if (isNew) {
        form.post(urls.printers.store())
    }
    else {
        form.put(urls.printers.update(printerWorkplace.id))
    }
};

</script>
<template>
    <form @submit.prevent="save">
        <div class="rounded-lg bg-white shadow-sm border border-gray-200">
            
            <div class="p-10">

                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">                        
                        <Label for="id_printer">{{ labels.id_printer }}</Label>                 
                        <Dropdown v-model="form.id_printer" filter :options="printers" optionLabel="name" optionValue="id" placeholder="Выберите принтер" class="w-full" />
                        <InlineMessage v-if="form.errors?.id_printer" class="mt-2" severity="error">{{ form.errors?.id_printer }}</InlineMessage>
                    </div>
                </div>
                
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">                        
                        <Label for="location">{{ labels.location }}</Label>                        
                        <InputText
                            class="w-full"
                            v-model="form.location" 
                            :placeholder="labels.location" 
                            :invalid="form.errors?.location?.length > 0"
                        />
                        <InlineMessage v-if="form.errors?.location" class="mt-2" severity="error">{{ form.errors?.location }}</InlineMessage>
                    </div>
                </div>


                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">                        
                        <Label for="serial_number">{{ labels.serial_number }}</Label>                        
                        <InputText
                            class="w-full"
                            v-model="form.serial_number" 
                            :placeholder="labels.serial_number" 
                            :invalid="form.errors?.serial_number?.length > 0"
                        />
                        <InlineMessage v-if="form.errors?.serial_number" class="mt-2" severity="error">{{ form.errors?.serial_number }}</InlineMessage>
                    </div>
                </div>

                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">                        
                        <Label for="inventory_number">{{ labels.inventory_number }}</Label>                        
                        <InputText
                            class="w-full"
                            v-model="form.inventory_number" 
                            :placeholder="labels.inventory_number" 
                            :invalid="form.errors?.inventory_number?.length > 0"
                        />
                        <InlineMessage v-if="form.errors?.inventory_number" class="mt-2" severity="error">{{ form.errors?.inventory_number }}</InlineMessage>
                    </div>
                </div>

            </div>            

            <div class="flex items-center justify-between p-5 bg-gray-50 border-t border-gray-100 w-full">
                <Button :loading="form.processing" class="font-bold" type="submit" label="Сохранить" />
            </div>

        </div>

    </form>
</template>