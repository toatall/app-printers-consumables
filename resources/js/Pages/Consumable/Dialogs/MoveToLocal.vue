<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import { ref, inject, onMounted } from 'vue'
import Label from '@/Shared/Label'
import InlineMessage from 'primevue/inlinemessage'
import Button from 'primevue/button'
import InputNumber from 'primevue/inputnumber'
import Textarea from 'primevue/textarea'
import { Inertia } from '@inertiajs/inertia'
import LoadingButton from '@/Shared/LoadingButton'
import { ConfigUrl } from '@/configUrl'


const dialogRef = inject('dialogRef')
const labelsConsumable = ref()
const printer = ref()
const id = ref()
onMounted(() => {
    labelsConsumable.value = dialogRef.value.data.labelsConsumable
    printer.value = dialogRef.value.data.printer
    id.value = dialogRef.value.data.id
})
const form = useForm({
    count: 1,    
    description: null,
})

const save = () => {    
    const url = ConfigUrl.move.moveToLocal
        .replace('{printer-id}', printer.value.id)
        .replace('{consumable-id}', id.value)

    form.post(url, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            Inertia.reload()
            closeDialog()            
        },
    })
}

console.log(id.value)

const closeDialog = () => {
    dialogRef.value.close()
}

</script>
<template>
    <form @submit.prevent="save">            
        <div class="grid grid-cols-1 gap-6">
            <div>
                <Label for="count">Количество</Label>
                <InputNumber 
                    v-model="form.count" 
                    showButtons 
                    buttonLayout="horizontal" 
                    style="width: 3rem" 
                    :min="0" 
                    :max="999"
                    :invalid="form.errors?.count?.length > 0"
                >
                    <template #incrementbuttonicon>
                        <span class="pi pi-plus" />
                    </template>
                    <template #decrementbuttonicon>
                        <span class="pi pi-minus" />
                    </template>
                </InputNumber>
                <InlineMessage v-if="form.errors?.count" class="mt-2" severity="error">{{ form.errors?.count }}</InlineMessage>
            </div>
            <div>
                <Label for="description">{{ labelsConsumable?.description }}</Label>
                <Textarea v-model="form.description" :invalid="form.errors?.description?.length > 0" rows="5" class="w-full"></Textarea>
                <InlineMessage v-if="form.errors?.description" class="mt-2" severity="error">{{ form.errors?.description }}</InlineMessage>
            </div>
        </div>
    </form>             

    <div class="mt-2 pt-2 border-t border-gray-300 flex justify-between font-bold">
        <Button label="Отмена" severity="secondary" @click="closeDialog" autofocus />
        <loading-button :loading="form.processing" type="button" @click="save">Сохранить</loading-button>
    </div>
    
</template>