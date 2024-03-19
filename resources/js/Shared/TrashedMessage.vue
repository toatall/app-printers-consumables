<script setup>
import Button from 'primevue/button'

const emits = defineEmits(['restore'])
</script>

<template>
    <div class="flex items-center justify-between p-4 max-w-full bg-yellow-400 rounded">
        <div class="flex items-center">
            <i class="fas fa-trash mr-2 font-medium text-yellow-800"></i>
            <div class="text-yellow-800 font-medium">
                <slot />
            </div>
        </div>       
        <Button severity="warning" @click="$emit('restore')" class="font-bold">Восстановить</Button>
    </div>
</template>