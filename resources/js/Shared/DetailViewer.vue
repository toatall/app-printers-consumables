<script setup>
import { inject } from 'vue';

const props = defineProps({    
    items: Array,
})
const moment = inject('moment')
</script>
<template>
    <table class="w-1/2 text-left text-gray-700" :class="$attrs.classTable">        
        <tr class="bg-white border-b" :class="$attrs.classRow" v-for="item in items" :key="item.value">
            <th class="px-6 py-4" :class="item?.classTh">
                {{ item.label }}                
            </th>
            <td class="px-6 py-4" :class="item?.classTd">
                <template v-if="item?.is_date ?? false">
                    <i :class="item.icon" v-if="item?.icon"></i>
                    {{ moment(item.value).fromNow() }}
                    ({{ moment(item.value).format('LLLL') }})
                </template>
                <template v-else>
                    {{ item.value }}
                </template>
            </td>
        </tr>
    </table>
</template>