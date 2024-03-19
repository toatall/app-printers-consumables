<script setup>
import InputText from 'primevue/inputtext'
import InlineMessage from 'primevue/inlinemessage'
import InputSwitch from 'primevue/inputswitch'
import Label from '@/Shared/Label'
import { reactive } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import LoadingButton from '@/Shared/LoadingButton'

const props = defineProps({
    isNew: Boolean,
    labels: Object,
    printer: Object,
})
const printer = reactive(props.printer)

const form = useForm({    
    vendor: printer.vendor,
    model: printer.model,
    color_print: printer.color_print,    
})

function save() {    
    if (props.isNew) {
        form.post(`/printers`)
    }
    else {
        form.put(`/printers/${printer.id}`)
    }
}

</script>
<template>
    <form @submit.prevent="save">
        <div class="rounded-lg bg-white shadow-sm border border-gray-200">
            
            <div class="p-10">

                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">                                        
                        <Label for="vendor">{{ labels.vendor }}</Label>
                        <InputText
                            class="w-full"
                            v-model="form.vendor" 
                            :placeholder="labels.vendor" 
                            :invalid="form.errors?.vendor?.length > 0"
                        />
                        <InlineMessage v-if="form.errors?.vendor" class="mt-2" severity="error">{{ form.errors?.vendor }}</InlineMessage>
                    </div>
                </div>

                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">                        
                        <Label for="model">{{ labels.model }}</Label>                        
                        <InputText
                            class="w-full"
                            v-model="form.model" 
                            :placeholder="labels.model" 
                            :invalid="form.errors?.model?.length > 0"
                        />
                        <InlineMessage v-if="form.errors?.model" class="mt-2" severity="error">{{ form.errors?.model }}</InlineMessage>
                    </div>
                </div>
            
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">                        
                        <Label for="color_print">{{ labels.color_print }}</Label>             
                        <InputSwitch v-model="form.color_print" />                        
                    </div>
                </div>
            </div>            

            <div class="flex items-center justify-between p-5 bg-gray-50 border-t border-gray-100 w-full">
                <loading-button :loading="form.processing" class="font-bold" type="submit">Сохранить</loading-button>
            </div>

        </div>

    </form>
</template>