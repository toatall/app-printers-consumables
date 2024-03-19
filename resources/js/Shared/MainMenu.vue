<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import { usePage } from '@inertiajs/inertia-vue3'
import { computed } from 'vue'

const page = usePage()
const can = computed(() => page.props.value.canGlobal)

function isUrl(...urls) {
    let currentUrl = page.url.value.substring(1)
    if (urls[0] === '') {
        return currentUrl === ''
    }
    return urls.filter((url) => currentUrl.startsWith(url)).length
}
</script>

<template>
<div class="">
    
    <div class="mb-4">
        <Link class="group flex items-center py-3" href="/">
            <div :class="isUrl('') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                <i class="fas fa-home"></i> Главная
            </div>            
        </Link>
    </div>

    <div class="mb-4">
        <Link class="group flex items-center py-3" href="/chart">
            <div :class="isUrl('chart') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                <i class="fas fa-chart-pie"></i> График
            </div>
        </Link>
    </div>

    <div class="mb-4">
        <Link class="group flex items-center py-3" href="/consumable-moves">            
            <div :class="isUrl('consumable-moves') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                <i class="fas fa-scroll"></i> Журнал
            </div>
        </Link>
    </div>  

    <div class="mb-4" v-if="can.editorStock | can.editorLocal">
        <Link class="group flex items-center py-3" href="/printers">            
            <div :class="isUrl('printers') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                <i class="fas fa-print"></i> Принтеры и расходные материалы (справочник)
            </div>
        </Link>
    </div>   
    
    <div class="mb-4" v-if="can.admin">
        <Link class="group flex items-center py-3" href="/users">            
            <div :class="isUrl('users') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                <i class="fas fa-user"></i> Пользователи
            </div>
        </Link>
    </div>  
   

</div>
</template>