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
    <div>
        
        <div class="">
            <Link class="group flex items-center py-3" href="/">
                <div :class="isUrl('') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                    <i class="fas fa-home"></i> Главная
                </div>            
            </Link>
        </div>

        <div class="mt-4">
            <Link class="group flex items-center py-3" href="/chart">
                <div :class="isUrl('chart') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                    <i class="fas fa-chart-pie"></i> График
                </div>
            </Link>
        </div>

        <div class="mt-4">
            <Link class="group flex items-center py-3" href="/consumable-moves">            
                <div :class="isUrl('consumable-moves') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                    <i class="fas fa-scroll"></i> Журнал
                </div>
            </Link>
        </div>  

        <div class="mt-4" v-if="can.editorStock | can.editorLocal">
            <Link class="group flex items-center py-3" href="/printers">            
                <div :class="isUrl('printers') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                    <i class="fas fa-print"></i> Принтеры и расходные материалы (справочник)
                </div>
            </Link>
        </div>   
        
        <div class="mt-4" v-if="can.admin">
            <Link class="group flex items-center py-3" href="/users">            
                <div :class="isUrl('users') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                    <i class="fas fa-user"></i> Пользователи
                </div>
            </Link>
        </div>  

        <!-- <div class="mt-14">
            <hr class="h-px bg-indigo-400 border-0" />
        </div> -->

        <div class="mt-14 flex items-center">
            <hr class="h-px bg-indigo-400 border-0 w-full" />
            <h3 class="text-indigo-200 font-bold py-4">Справочники</h3>
            <hr class="h-px bg-indigo-400 border-0 w-full" />
        </div>
        <div class="mt-4">
            <Link class="group flex items-center py-3" href="/dictionary/printers">
                <div :class="isUrl('dictionary/printers') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                    <i class="fas fa-print"></i>
                    Принтеры (справочник)
                </div>
            </Link>
        </div>

        <div class="mt-4">
            <Link class="group flex items-center py-3" href="/dictionary/consumables">
                <div :class="isUrl('dictionary/consumables') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                    <i class="fas fa-box"></i>
                    Расходные материалы (справочник)
                </div>
            </Link>
        </div>

    </div>
</template>