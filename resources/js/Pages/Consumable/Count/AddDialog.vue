<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import { inject } from 'vue'
import InputNumber from 'primevue/inputnumber'
import InlineMessage from 'primevue/inlinemessage'
import Label from '@/Shared/Label'
import LoadingButton from '@/Shared/LoadingButton'

const props = defineProps({
    auth: Object, 
})

const urls = inject('urls')
const dialogRef = inject('dialogRef')
const consumableCountLabels = dialogRef.value.data.consumableCountLabels

const form = useForm({   
    id_consumable: dialogRef.value.data.idConsumable,
    count: null,
    selectedOrganizations: [dialogRef.value.data.organizations],
})
function save() {        
    form.put(urls.consumables.counts.update(dialogRef.value.data.id), {
        onSuccess: () => {
            dialogRef.value.close()
        },
    })    
}

</script>

<template>
    <form @submit.prevent="save">
        
        <div class="grid gap-x-6 gap-y-8">
            <div>                        
                <Label for="count">{{ consumableCountLabels.count }}</Label>
                <InputNumber 
                    class="w-full"
                    v-model="form.count" 
                    :placeholder="consumableCountLabels.count" 
                    :invalid="form.errors?.count?.length > 0"
                />
                <InlineMessage v-if="form.errors?.count" class="mt-2" severity="error">{{ form.errors?.count }}</InlineMessage>
            </div>
        </div>

        <div class="flex items-center justify-between pt-5 w-full">
            <loading-button :loading="form.processing" class="font-bold" type="submit">Сохранить</loading-button>
        </div>

    </form>
</template>